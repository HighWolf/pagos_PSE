<?php

namespace App\Http\Controllers\API;

use App\models\Transacciones;
use App\models\TipoDocumentos;
use App\models\Departamentos;
use App\models\Municipios;
use App\models\Entidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransaccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docs= TipoDocumentos::all();
        $deps= Departamentos::all();
        $muns= Municipios::all();
        return view('Transacciones.index', [
            'docs'=> $docs,
            'deps'=> $deps,
            'muns'=> $muns,
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
        dd($request);
        $entidad= new Entidades($request);
        // $seed= date('c');
        // $tranKey= '024h1IlD';
        // $hashString = sha1( $seed . $tranKey , false );
        // dd($hashString);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Transacciones  $transacciones
     * @return \Illuminate\Http\Response
     */
    public function show(Transacciones $transacciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Transacciones  $transacciones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transacciones $transacciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Transacciones  $transacciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transacciones $transacciones)
    {
        //
    }
}
