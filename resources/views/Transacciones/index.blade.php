@extends('Transacciones.layout')

@section('contenido')
    <div class="row content">
        <div class="col-sm-6 col-sm-offset-3">
            <h5>Realice su pago por PSE.</h5>
            {!! Form::open(['route'=>$ruta, 'method'=>'POST', 'data-toggle'=>'validator', 'role'=>'form']) !!}
                @foreach ($form as $item)
                    <div class="form-group">
                        {{ Form::label($item['name'], $item['label']) }}
                        @if ($payer)
                            {{ Form::text('payer', $payer, ['hidden' => 'true']) }}
                        @endif
                        <div class="cols-sm-10 input-group">
                            <span class="input-group-addon"><i class="fa {{ $item['icon'] }} fa" aria-hidden="true"></i></span>
                            @if ($item['type'] == 'select')
                                {{ Form::select($item['name'], $item['data'], null, ['placeholder' => $item['place'], 'class'=> 'form-control']) }}
                            @elseif ($item['type'] == 'text')
                                {{ Form::text($item['name'], null, ['placeholder' => $item['place'], 'class'=> 'form-control', 'required']) }}
                            @elseif ($item['type'] == 'number')
                                {{ Form::text($item['name'], null, ['placeholder' => $item['place'], 'class'=> 'form-control', 'required']) }}
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="form-group ">
                    {{ Form::submit('Continuar', ['class'=> "btn btn-primary btn-lg btn-block login-button"]) }}
                </div>
                
            {!! Form::close() !!}
        </div>
    </div>
@endsection
