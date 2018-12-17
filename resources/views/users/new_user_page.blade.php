@extends('layouts.general_layout')


@section('page_title', "Crean un usuario")

@section('content')

    <?php
    // Se conforma el Inicio y fin del código html de la caja de mostrar errores (debajo de cada imput)
    $notice_begin = "<br><div class='alert alert-danger' role='alert'>";
    $notice_end = "</div>";
    ?>

    <div class="card">
        <div class="card-header">
            <h2>Crear un usuario</h2>
        </div>

        <div class="card-body">

            <!-- Login Form -->
            <div class="container">
                <form role="form" class="form-horizontal texto_destacado" method="POST" name="formRegistration"
                      action="{{route('users.create_user_script')}}">

                    {{--Creamos un token para evitar ataques de tipo Cross-Site Request Forgery --}}
                    {{-- Realmente crea un campo input oculto de tipo token que es su campo "value" guarda un string larguísimo --}}
                    {{-- ** Esta protección se desactiva comentando la clase VerifyCsrfToken (línea 36 en app/Http/Kernel.php) --}}
                    {{--{!! csrf_field() !!}--}}
                    {{ csrf_field() }}


                    <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('firstname')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-address-card"></i>
                            </div>
                            <input type="text" id="txt_user_firstname" class="form-control col-md-8" placeholder="Nombre" name="firstname" value="{{ old('firstname') }}" @if(!$errors->any()) autofocus @elseif($errors->any() && $errors->first() == $errors->first('firstname')) autofocus style="border-top-color: red; border-right-color: red; border-bottom-color: red;" @endif>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('firstname'))
                                <?PHP echo $notice_begin ?>{{$errors->first('firstname')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('secondname') ? ' has-error' : '' }}">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('secondname')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-address-card"></i>
                            </div>
                            <input type="text" id="txt_user_secondname" class="form-control col-md-8" placeholder="Segundo nombre" name="secondname" value="{{ old('secondname') }}" @if($errors->any() && $errors->first() == $errors->first('secondname')) autofocus style="border-top-color: red; border-right-color: red; border-bottom-color: red;" @endif>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('secondname'))
                                <?PHP echo $notice_begin ?>{{$errors->first('secondname')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('lastname')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-address-card"></i>
                            </div>
                            <input type="text" id="txt_user_lastname" class="form-control col-md-8" placeholder="Apellidos" name="lastname" value="{{ old('lastname') }}" @if($errors->any() && $errors->first() == $errors->first('lastname')) autofocus style="border-top-color: red; border-right-color: red; border-bottom-color: red;" @endif>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('lastname'))
                                <?PHP echo $notice_begin ?>{{$errors->first('lastname')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>

                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('email')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-envelope"></i>
                            </div>
                            <input type="email" id="txt_user_email" class="form-control col-md-8" placeholder="Email" name="email" value="{{ old('email') }}" @if($errors->any() && $errors->first() == $errors->first('email')) autofocus style="border-top-color: red; border-right-color: red; border-bottom-color: red;" @endif>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('email'))
                                <?PHP echo $notice_begin ?>{{$errors->first('email')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>

                    </div>


                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('password')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-key"></i>
                            </div>
                            <input type="password" id="txt_user_password1" class="form-control col-md-8" placeholder="Password" name="password" value="{{old('password')}}" @if($errors->any() && $errors->first() == $errors->first('password')) autofocus style="border-top-color: red; border-right-color: red; border-bottom-color: red;" @endif>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('password'))
                                <?PHP echo $notice_begin ?>{{$errors->first('password')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('password_confirmation')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-key"></i>
                            </div>
                            <input type="password" id="txt_user_password_confirmation" class="form-control col-md-8" placeholder="Repita su password" name="password_confirmation" value="{{old('password_confirmation')}}" <?php  echo($errors->any() && $errors->first() == $errors->first('password_confirmation') ? 'autofocus  style="border-top-color: red; border-right-color: red; border-bottom-color: red;"' : '');  ?>>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('password_confirmation'))
                                <?PHP echo $notice_begin ?>{{$errors->first('password_confirmation')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                        <div class="col-sm-offset-2 col-md-8 input-group">
                            <div class="input-group-addon col-md-1" @if($errors->any() && $errors->first() == $errors->first('phone')) style="border-top-color: red; border-left-color: red; border-bottom-color: red;" @endif>
                                <i class="fa fa-phone"></i>
                            </div>
                            <input type="text" id="txt_user_phone" class="form-control col-md-8" placeholder="Teléfono" name="phone" maxlength="10" value="{{ old('phone') }}" @if($errors->any() && $errors->first() == $errors->first('phone')) autofocus style="border-top-color: red; border-right-color: red; border-bottom-color: red;" @endif>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('phone'))
                                <?PHP echo $notice_begin ?>{{$errors->first('phone')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                        <!--<label id="lbl_user_gender" class="control-label col-sm-2" for="user_gender">Género:</label>-->
                        <div class="col-md-8">
                            <select id="slct_user_gender selectpicker" class="form-control col-md-9" name="gender" title="Escoja el sexo..." @if($errors->any() && $errors->first() == $errors->first('gender')) autofocus style="border-color: red;" @endif>>

                                <option value="" selected disabled hidden>Escoja el sexo</option>

                                <option value="male" @if(old('gender') == 'male') selected @endif>&male; Masculino
                                </option>

                                <option value="female" @if(old('gender') == 'female') selected @endif>&female; Femenino
                                </option>

                            </select>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('gender'))
                                <?PHP echo $notice_begin ?>{{$errors->first('gender')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <input type="hidden" name="is_active" value="1">


                    <div class="form-group {{ $errors->has('kind') ? ' has-error' : '' }}">
                        <!--<label id="lbl_user_kind" class="control-label col-sm-2" for="user_kind">Tipo:</label>-->
                        <div class="col-md-8">
                            <select id="slct_user_kind" class="form-control col-md-9" name="kind" @if($errors->any() && $errors->first() == $errors->first('kind')) autofocus style="border-color: red;" @endif>>

                                <option value="" selected disabled hidden>Escoja los privilegios</option>

                                <option value="administrator" @if(old('kind') == 'administrator') selected @endif>
                                    &#x265B;
                                    Administrador
                                </option>

                                <option value="operator" @if(old('kind') == 'operator') selected @endif>&#x265D;
                                    Operador
                                </option>

                                <option value="guest" @if(old('kind') == 'guest') selected @endif>&#x265F; Invitado
                                </option>

                            </select>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('kind'))
                                <?PHP echo $notice_begin ?>{{$errors->first('kind')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('profession_id') ? ' has-error' : '' }}">
                        <!--<label id="lbl_user_kind" class="control-label col-sm-2" for="user_kind">Tipo:</label>-->
                        <div class="col-md-8">
                            <select id="slct_user_kind" class="form-control col-md-9" name="profession_id" value="{{ old('profession_id') }}" @if($errors->any() && $errors->first() == $errors->first('profession_id')) autofocus style="border-color: red" @endif>>
                                <option value="" selected disabled hidden>Escoja la profesión</option>
                                @foreach( $professions as $profession)
                                    <option value="{{$profession->id}}"
                                            @if(old('profession_id') == $profession->id) selected @endif>
                                        {{$profession->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            @if($errors->has('profession_id'))
                                <?PHP echo $notice_begin ?>{{$errors->first('profession_id')}}<?PHP echo $notice_end ?>
                            @endif
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                    {{--<div class="col-sm-offset-2 col-sm-10">--}}
                    {{--<div class="checkbox">--}}
                    {{--<label><input type="checkbox" name="remember"> Remember me</label>--}}
                    {{--<label id="lbl_destroy_session" for=""><input type="checkbox" id="chbx_session" name="session"--}}
                    {{--value="1">Recordar mis datos (alargar--}}
                    {{--sesión)</label>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">

                            <button type="submit" id="btn_register" class="btn btn-default btn-success faa-parent animated-hover" name="register">
                                <i class="fa fa-sign-in faa-horizontal fa-fast"></i>
                                Registrarse
                            </button>

                            <button type="reset" id="btn_eraser" class="btn btn-default btn-secondary faa-parent animated-hover">
                                <i class="fa fa-eraser faa-ring fa-fast"></i>
                                Limpiar
                            </button>

                            <a href="{{ route('users.users_page') }}" id="a_cancel" class="btn btn-default btn-danger faa-parent animated-hover">
                                <i class="fa fa-times faa-ring fa-fast"></i>
                                Cancelar
                            </a>

                        </div>
                    </div>


                </form>
            </div>
            <!-- / Login Form -->
        </div>
    </div>
    <br>

    {{--    <p><a href="{{url()->previous()}}">URL Anterior</a></p>--}}
@endsection