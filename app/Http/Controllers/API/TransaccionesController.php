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

class TransaccionesController extends Controller
{

    private $URL= 'https://api.placetopay.com/soap/pse/?wsdl';
    private $LOGIN= "6dd490faf9cb87a9862245da41170ff2";
    private $TRANSKEY= "024h1IlD";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docs= TipoDocumentos::pluck('desc', 'id')->all();
        $deps= Departamentos::all()->pluck('nombre', 'codigo_dane');
        $muns= Municipios::all()->pluck('nombre', 'codigo_dane');
        $form= array(
            ['type'=> 'select', 'name'=> 'tipo_doc', 'label'=> 'Tipo de documento', 'place'=> 'Ingresa tu tipo de documento', 'icon'=> 'fa-id-card', 'data'=> $docs],
            ['type'=> 'number', 'name'=> 'documento', 'label'=> 'Numero de documento', 'place'=> 'Ingresa tu numero de documento', 'icon'=> 'fa-id-card'],
            ['type'=> 'text', 'name'=> 'nombres', 'label'=> 'Nombres', 'place'=> 'Ingresa tus nombres', 'icon'=> 'fa-user'],
            ['type'=> 'text', 'name'=> 'apellidos', 'label'=> 'Apellidos', 'place'=> 'Ingresa tus apellidos', 'icon'=> 'fa-user'],
            ['type'=> 'text', 'name'=> 'email', 'label'=> 'Email', 'place'=> 'Ingresa tu email', 'icon'=> 'fa-envelope'],
            ['type'=> 'text', 'name'=> 'direccion', 'label'=> 'Dirección', 'place'=> 'Ingresa tu dirección', 'icon'=> 'fa-list-alt'],
            ['type'=> 'select', 'name'=> 'departamento', 'label'=> 'Departamento', 'place'=> 'Ingresa tu departamento', 'icon'=> 'fa-lock', 'data'=> $deps],
            ['type'=> 'select', 'name'=> 'ciudad', 'label'=> 'Ciudad', 'place'=> 'Ingresa tu ciudad', 'icon'=> 'fa-lock', 'data'=> $muns],
            ['type'=> 'number', 'name'=> 'telefono', 'label'=> 'Telefono', 'place'=> 'Ingresa tu telefono', 'icon'=> 'fa-phone'],
            ['type'=> 'number', 'name'=> 'celular', 'label'=> 'Celular', 'place'=> 'Ingresa tu celular', 'icon'=> 'fa-mobile-phone']
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
    public function informacionPago(Request $request)
    {
        $data= $request->except(['_token', 'departamento']);
        $entidad= new Entidades($data);
        $find= Entidades::where('documento', $request->documento)->first();
        if ($find){
            $entidad= $find;
            $entidad->fill($data);
        }
        $entidad->save();
        
        $banks= $this->getBankList();
        $bankinterfaz= InterfazBancos::all()->pluck('nombre', 'id');

        $form= array(
            ['type'=> 'select', 'name'=> 'bankCode', 'label'=> 'Banco', 'icon'=> 'fa-id-card', 'place'=> '', 'data'=> $banks],
            ['type'=> 'select', 'name'=> 'bankInterface', 'label'=> 'Interfaz', 'place'=> 'seleccione la interfaz', 'icon'=> 'fa-id-card', 'data'=> $bankinterfaz],
            ['type'=> 'number', 'name'=> 'totalAmount', 'label'=> 'Valor a pagar', 'place'=> 'Ingresa el valor a pagar', 'icon'=> 'fa-list-alt'],
        );

        return view('Transacciones.index', [
            'form'=> $form,
            'payer'=> $entidad->id,
            'ruta'=> 'transacciones.getTransactionRequest',
        ]);
    }

    private function auth(){
        $seed= date('c');
        return array(
            'login'=> $this->LOGIN,
            'seed'=> $seed,
            'tranKey'=> sha1( $seed . $this->TRANSKEY , false ),
            'additional'=> array()
        );
    }

    public function soapPSE(String $funcion, Array $data= null)
    {
        try {
            $auth = $this->auth();
            $soapClient = new SoapClient($this->URL, array('encoding' => 'UTF-8'));
            $result = $soapClient->$funcion(array_merge(array('auth'=> $auth), $data));
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getBankList() {
        $banks = null;
        $lista = array();
        $cache_id = 'bankCache';
        $minCache = 1440;
        
        Cache::put($cache_id, null , $minCache);
        $banks= Cache::get($cache_id);
        if ($banks === null) {
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

    public function getTransactionRequest(Request $request) 
    {
        $data= $request->except(['_token']);
        $transaccion= new Transacciones($data);
        $transaccion->reference = bin2hex(random_bytes(10));
        $transaccion->shipping= $transaccion->payer;
        $transaccion->ipAddress= $request->ip();
        $transaccion->userAgent= $request->header('User-Agent');
        $transaccion->save();

        $data= Transacciones::find($transaccion->id)->toArray();
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);
        $data['payer']= Entidades::find($data['payer'])->toArray();
        $data['payer']['tipo_doc']= TipoDocumentos::find($data['payer']['tipo_doc'])->tipo;
        unset($data['payer']['id']);
        unset($data['payer']['created_at']);
        unset($data['payer']['updated_at']);
        $data['shipping']= Entidades::find($data['shipping'])->toArray();
        $data['shipping']['tipo_doc']= TipoDocumentos::find($data['shipping']['tipo_doc'])->tipo;
        unset($data['shipping']['id']);
        unset($data['shipping']['created_at']);
        unset($data['shipping']['updated_at']);
        if ($data['buyer']){
            $data['buyer']= Entidades::find($data['buyer'])->toArray();
            $data['buyer']['tipo_doc']= TipoDocumentos::find($data['buyer']['tipo_doc'])->tipo;
            unset($data['buyer']['id']);
            unset($data['buyer']['created_at']);
            unset($data['buyer']['updated_at']);
        }
        $data['additionalData']= array();

        if ($result= $this->soapPSE('createTransaction', ['transaction'=> $data])){
            $traz= new TransactionTraz();
            $traz->request= $data->toJson();
            $traz->response= $result->PSETransactionResponse->toJson();
            $traz->save();
        }
        return redirect($result->PSETransactionResponse->bankURL);
    }
}
