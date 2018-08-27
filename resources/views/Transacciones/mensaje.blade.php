@extends('Transacciones.layout')

@section('contenido')
    <div class="row content">
        <div class="col-sm-6 col-sm-offset-3">
            <h3 class="center">{{ $estado }}</h3>

            <h5 class="center">{{ $mensaje }}</h3>
        </div>
    </div>
@endsection
