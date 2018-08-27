<?php

namespace App\Http\Controllers\API;

use App\models\Transacciones;
use App\models\InterfazBancos;
use App\models\TipoDocumentos;
use App\models\Departamentos;
use App\models\Municipios;
use App\models\Entidades;
use App\models\Bancos;
use App\models\TransactionTraz;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use SoapClient;

class TransaccionesController extends Controller {

    private $URL= 'https://api.placetopay.com/soap/pse/?wsdl';
    private $LOGIN= "6dd490faf9cb87a9862245da41170ff2";
    private $TRANSKEY= "024h1IlD";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $docs= TipoDocumentos::pluck('desc', 'id')->all();
        $deps= Departamentos::all()
            ->orderBy('nombre', 'asc')
            ->pluck('nombre', 'codigo_dane');
        $muns= Municipios::all()
            ->orderBy('nombre', 'asc')
            ->pluck('nombre', 'codigo_dane');
        $form= array(
            ['type'=> 'select', 'name'=> 'documentType', 'label'=> 'Tipo de documento', 'place'=> 'Ingresa tu tipo de documento', 'icon'=> 'fa-id-card', 'data'=> $docs, 'required'=> true],
            ['type'=> 'text', 'name'=> 'document', 'label'=> 'Numero de documento', 'place'=> 'Ingresa tu numero de documento', 'icon'=> 'fa-id-card', 'required'=> true],
            ['type'=> 'text', 'name'=> 'firstName', 'label'=> 'Nombres', 'place'=> 'Ingresa tus nombres', 'icon'=> 'fa-user', 'required'=> true],
            ['type'=> 'text', 'name'=> 'lastName', 'label'=> 'Apellidos', 'place'=> 'Ingresa tus apellidos', 'icon'=> 'fa-user'],
            ['type'=> 'email', 'name'=> 'emailAddress', 'label'=> 'Email', 'place'=> 'Ingresa tu email', 'icon'=> 'fa-envelope', 'required'=> true],
            ['type'=> 'text', 'name'=> 'address', 'label'=> 'Dirección', 'place'=> 'Ingresa tu dirección', 'icon'=> 'fa-list-alt', 'required'=> true],
            ['type'=> 'select', 'name'=> 'departamento', 'label'=> 'Departamento', 'place'=> 'Ingresa tu departamento', 'icon'=> 'fa-lock', 'data'=> $deps],
            ['type'=> 'select', 'name'=> 'city', 'label'=> 'Ciudad', 'place'=> 'Ingresa tu ciudad', 'icon'=> 'fa-lock', 'data'=> $muns, 'required'=> true],
            ['type'=> 'number', 'name'=> 'phone', 'label'=> 'Telefono', 'place'=> 'Ingresa tu telefono', 'icon'=> 'fa-phone'],
            ['type'=> 'number', 'name'=> 'mobile', 'label'=> 'Celular', 'place'=> 'Ingresa tu celular', 'icon'=> 'fa-mobile-phone']
        );

        return view('Transacciones.index', [
            'form'=> $form,
            'ruta'=> 'transacciones.informacionpago',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function informacionPago(Request $request) {
        $data= $request->except(['_token', 'departamento']);
        $entidad= new Entidades($data);
        $find= Entidades::where('document', $request->document)->first();
        if ($find){
            $entidad= $find;
            $entidad->fill($data);
        }
        $entidad->save();
        
        $banks= $this->getBankList();
        $bankinterfaz= InterfazBancos::all()->pluck('nombre', 'id');

        $form= array(
            ['type'=> 'select', 'name'=> 'bankCode', 'label'=> 'Banco', 'icon'=> 'fa-id-card', 'place'=> '', 'data'=> $banks, 'required'=> true],
            ['type'=> 'select', 'name'=> 'bankInterface', 'label'=> 'Interfaz', 'place'=> 'seleccione la interfaz', 'icon'=> 'fa-id-card', 'data'=> $bankinterfaz, 'required'=> true],
            ['type'=> 'number', 'name'=> 'totalAmount', 'label'=> 'Valor a pagar', 'place'=> 'Ingresa el valor a pagar', 'icon'=> 'fa-list-alt', 'required'=> true],
        );

        return view('Transacciones.index', [
            'form'=> $form,
            'payer'=> $entidad->id,
            'ruta'=> 'transacciones.getTransactionRequest',
        ]);
    }

    private function auth() {
        $seed= date('c');
        return array(
            'login'=> $this->LOGIN,
            'seed'=> $seed,
            'tranKey'=> sha1( $seed . $this->TRANSKEY , false ),
            'additional'=> array()
        );
    }

    public function soapPSE(String $funcion, Array $data= null, int $transaccionid= null) {
        $result= false;

        $auth = $this->auth();
        $body = array( 'auth'=> $auth );
        if ( $data ) $body= array_merge( $body, $data );

        try {
            $soapClient = new SoapClient($this->URL, array( 'encoding' => 'UTF-8' ));
            $result = $soapClient->$funcion( $body );
        } catch ( Exception $e ) { }

        $traz= new TransactionTraz();
        $traz->request= json_encode( $data );
        $traz->response= json_encode( $result );
        $traz->save();

        if ( !empty( $transaccionid ) && preg_match( '/[t|T]ransaction/', $funcion ) ) {
            $transaccion= Transacciones::find($transaccionid);
            $funcion.= 'Result';
            foreach ($result->$funcion as $key => $value) {
                $transaccion[ $key ]= $value;
            }
            $transaccion->save();
        }

        return $result;
    }

    public function getBankList() {
        $banks = null;
        $lista = array();
        $cache_id = 'bankCache';
        $minCache = 1440;
        
        Cache::put($cache_id, null , $minCache);
        $banks= Cache::get($cache_id);
        if ( empty ($banks) ) {
            if ($result= $this->soapPSE('getBankList')){
                $banks = $result->getBankListResult->item;
                DB::statement('SET FOREIGN_KEY_CHECKS= 0');
                Bancos::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS= 1');
                foreach ($banks as $bank) {
                    $bancos= new Bancos((array)$bank);
                    $bancos->save();
                }
                $banks = array_column($banks, 'bankName', 'bankCode');
                Cache::put($cache_id, true , $minCache);
            }
        }

        $banks = (is_array($banks) ? $banks : Bancos::all()->pluck('bankName', 'bankCode'));
        
        return $banks;
    }

    public function getTransactionRequest(Request $request) {
        $data= $request->except(['_token']);
        $transaccion= new Transacciones($data);
        $transaccion->reference = bin2hex(random_bytes(10));
        $transaccion->returnURL = "http://pagospse.com/transacciones/retorno/".$transaccion->reference;
        $transaccion->shipping= $transaccion->payer;
        $transaccion->ipAddress= $request->ip();
        $transaccion->userAgent= $request->header('User-Agent');
        $transaccion->save();

        $data= Transacciones::find($transaccion->id)->toArray();
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);
        $data['payer']= Entidades::find($data['payer'])->toArray();
        $data['payer']['documentType']= TipoDocumentos::find($data['payer']['documentType'])->tipo;
        unset($data['payer']['id']);
        unset($data['payer']['created_at']);
        unset($data['payer']['updated_at']);
        $data['shipping']= Entidades::find($data['shipping'])->toArray();
        $data['shipping']['documentType']= TipoDocumentos::find($data['shipping']['documentType'])->tipo;
        unset($data['shipping']['id']);
        unset($data['shipping']['created_at']);
        unset($data['shipping']['updated_at']);
        if (isset($data['buyer'])){
            $data['buyer']= Entidades::find($data['buyer'])->toArray();
            $data['buyer']['documentType']= TipoDocumentos::find($data['buyer']['documentType'])->tipo;
            unset($data['buyer']['id']);
            unset($data['buyer']['created_at']);
            unset($data['buyer']['updated_at']);
        } else {
            $data['buyer']= $data['payer'];
        }
        $data['additionalData']= array();

        $result= $this->soapPSE('createTransaction', ['transaction'=> $data], $transaccion->id);
        
        return redirect($result->createTransactionResult->bankURL);
    }

    public function getTransactionResult(String $reference, int $auto= null) {
        $transaccion= Transacciones::where( 'reference', $reference )->get( [ 'id', 'transactionID'] )->first();
        $mensaje= "Error al consultar el estado de la transaccion.";
        $estado= "Transaccion pendiente";
        if ( $result= $this->soapPSE( 'getTransactionInformation', [ 'transactionID'=> $transaccion->transactionID ], $transaccion->id ) ) {
            $result= $result->getTransactionInformationResult;
            if ( $result->transactionState == "OK" )
                $estado= "Transaccion exitosa";
            else if ( $result->transactionState != "PENDING" )
                $estado= "Transaccion fallida";
            $mensaje= $result->responseReasonText;
        }

        if ( !empty( $auto ) )
            return true;

        return view('Transacciones.mensaje', [
            'estado'=> $estado,
            'mensaje'=> $mensaje
        ]);
    }
}
