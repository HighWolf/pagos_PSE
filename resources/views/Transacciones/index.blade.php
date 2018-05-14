@extends('Transacciones.layout')

@section('contenido')
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <h5>Realice su pago por PSE.</h5>
            <form method="post" action="http://www.pagos-pse.com/informacionPago">
                <div class="form-group">
                    <label for="tipo_doc" class="cols-sm-2 control-label">Tipo de documento</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-id-card fa" aria-hidden="true"></i></span>
                            <select name="tipo_doc" required="true" id="tipo_doc" class="form-control" placeholder="Ingresa tu tipo de documento.">
                                @foreach ($docs as $doc)
                                    <option value="{{ $doc->tipo }}">{{ $doc->desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="documento" class="cols-sm-2 control-label">Numero de documento</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-id-card fa" aria-hidden="true"></i></span>
                            <input type="text" required="true" class="form-control" name="documento" id="documento"  placeholder="Ingresa tu numero de documento."/>
                        </div>
                    </div>
                </div>
               
                <div class="form-group">
                    <label for="nombres" class="cols-sm-2 control-label">Nombres</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="text" required="true" class="form-control" name="nombres" id="nombres"  placeholder="Ingresa tus nombres."/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="apellidos" class="cols-sm-2 control-label">Apellidos</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="text" required="true" class="form-control" name="apellidos" id="apellidos"  placeholder="Ingresa tus apellidos."/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="compania" class="cols-sm-2 control-label">Compañia</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-briefcase fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="compania" id="compania"  placeholder="Ingresa tu compañia."/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="cols-sm-2 control-label">Email</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                            <input type="text" required="true" class="form-control" name="email" id="email"  placeholder="Ingresa tu email"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion" class="cols-sm-2 control-label">Dirección</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list-alt fa" aria-hidden="true"></i></span>
                            <input type="text" required="true" class="form-control" name="direccion" id="direccion"  placeholder="Ingresa tu dirección."/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="departamento" class="cols-sm-2 control-label">Departamento</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <select required="true" id="departamento" class="form-control" placeholder="Ingresa tu departamento">
                                @foreach ($deps as $deps)
                                    <option value="{{ $deps->codigo_dane }}">{{ $deps->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ciudad" class="cols-sm-2 control-label">Ciudad</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <select name="ciudad" required="true" id="ciudad" class="form-control" placeholder="Ingresa tu ciudad">
                                @foreach ($muns as $mun)
                                    <option value="{{ $mun->codigo_dane }}">{{ $mun->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="telefono" class="cols-sm-2 control-label">Telefono</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="telefono" id="telefono"  placeholder="Ingresa tu telefono"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="celular" class="cols-sm-2 control-label">Celular</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-mobile-phone fa" aria-hidden="true"></i></span>
                            <input type="text" required="true" class="form-control" name="celular" id="celular"  placeholder="Ingresa tu celular"/>
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <a href="#" type="submit" id="button" class="btn btn-primary btn-lg btn-block login-button">Continuar</a>
                </div>
                
            </form>
        </div>
    </div>
@endsection
