@extends('layouts.general_layout')


@section('page_title', "{$title}")

@section('content')




    <div class="card">

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-end">
                <h2 class="pb-1">{{ $title  }}</h2>
                <button style="margin-bottom: 10px; cursor: pointer;" class="btn btn-primary delete_all faa-parent animated-hover" name="delete_users" data-url="{{ url('myproductsDeleteAll') }}">
                    <i class="fa fa-trash faa-ring faa-slow"></i>
                    Borrar los seleccionados
                </button>
            </div>
        </div>

        <div class="card-body">

            @if(!$users->isEmpty())

                <!-- División de Tabla -->
                <div id="div_tabla_id" class="div_tabla">

                    <table id="tbl_id" cellspacing="0" cellpadding="0" class="standard-light-blue" style="width: 100%;">
                    {{--<table id="tbl_id" cellspacing="0" cellpadding="0" class="new-style">--}}


                    <!-- Cabecera de la Tabla -->
                        <thead>
                        <tr style="text-align: left;">
                            <!--hola-->
                        </tr>
                        </thead>
                        <!-- Fin de Cabecera de la Tabla -->

                        <!-- Cuerpo de la Tabla -->
                        <tbody>
                        <tr>
                            <th width="50px">
                                {{--<input type="checkbox" id="master">--}}

                                <div class="pretty p-svg p-curve">
                                    <input type="checkbox" id="master"/>
                                    <div class="state p-success" style="margin-left: -12px; width: 1px;">
                                        <!-- svg path -->
                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                        </svg>
                                        <label></label>
                                    </div>
                                </div>

                            </th>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Sexo</th>
                            <th>Privilegios</th>
                            <th colspan="2">
                            </th>

                        </tr>

                        @forelse($users as $user)

                            <tr>
                                {{-- Borrado múltiple --}}
                                <td>
                                    {{--<input type="checkbox" class="sub_chk" data-id="{{$user->id}}">--}}

                                    <div class="pretty p-svg p-curve">
                                        <input type="checkbox" class="sub_chk" data-id="{{$user->id}}" name="chkOrgRow"/>
                                        <div class="state p-success" style="margin-left: -12px; width: 1px;">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label></label>
                                        </div>
                                    </div>

                                </td>

                                <!-- Usando route (EL MEJOR) -->
                                <td style="text-align: center;">
                                    <a href="{{ route('users.user_details_page', ['id' => $user->id]) }}">{{ $user->id }}</a>
                                </td>

                                <!-- Usando route (EL MEJOR) -->
                                <td style="text-align: left;">
                                    <a href="{{ route('users.user_details_page', ['id' => $user->id]) }}">{{ $user->firstname }}</a>
                                </td>

                                <!-- Usando url -->
                                <td style="text-align: left;">
                                    <a href="{{ url('/user_details_page/'.$user->id) }}">{{ $user->lastname }}</a>
                                </td>
                                <td style="text-align: left;">
                                    <a href="{{url("/user_details_page/$user->id")}}">{{ $user->email }}</a>
                                </td>

                                {{-- Usando action --}}
                                <td style="text-align: left;">
                                    <a href="{{action('UserController@show_user_details_page', [$user->id])}}">{{ strlen($user->phone) == 10 ? '(' . substr($user->phone, 0, 3) . ') ' . substr($user->phone, 3, 3) . '-' . substr($user->phone, 6, 4) : $user->phone  }}</a>
                                </td>
                                <td style="text-align: left;">
                                    <a href="{{action('UserController@show_user_details_page', ['id' => $user->id])}}">{{ strtolower($user->gender) == 'female' ? 'Femenino' : 'Masculino' }}</a>
                                </td>

                                <?php
                                $tdColor = ($user->kind == 'administrator') ? 'red' : ($user->kind == 'operator' ? 'orange' : ($user->kind == 'guest' ? 'green' : 'pink'))
                                ?>

                                <td style="text-align: left; color: {{ $tdColor}}">
                                    <a href="{{ route('users.user_details_page', ['id' => $user->id]) }}">{{ ($user->kind == 'administrator') ? 'Administrador' : (($user->kind == 'operator') ? 'Operador' : 'Invitado') }}</a>
                                </td>


                                <td style="text-align: left">
                                    {{--<a href="{{ route('users.edit_user_page', ['id' => $user->id]) }}" class="btn btn-default btn-sm btn-success faa-parent animated-hover" name="edit_user">--}}
                                    <a href="{{ route('users.edit_user_page', $user) }}" class="btn btn-default btn-sm btn-success faa-parent animated-hover" name="edit_user">
                                        <i class="fa fa-edit faa-ring faa-slow"></i>
                                    </a>
                                    {{--<button id="btn_edited_post" type="button" class="btn btn-default btn-sm btn-success" name="edit_post">--}}
                                    {{--<i class="fa fa-edit"></i>--}}
                                    {{--</button>--}}
                                </td>

                                <td style="text-align: left">
                                    <form action="{{ route('users.delete_user_script', $user) }}" method="POST" style="">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button id="btn_delete_post" type="submit" class="btn btn-default btn-sm btn-danger faa-parent animated-hover" name="delete_user" style="cursor: pointer;">
                                            <i class="fa fa-trash faa-ring faa-slow"></i>
                                        </button>
                                    </form>

                                    {{--<a href="{{ route('users.delete_user_script', ['user' => $user->id]) }}" class="btn btn-default btn-sm btn-danger faa-parent animated-hover" name="delete_user">--}}
                                    {{--<i class="fa fa-trash faa-ring faa-slow"></i>--}}
                                    {{--</a>--}}
                                    {{--<button id="btn_delete_post" type="button" class="btn btn-default btn-sm btn-danger faa-parent animated-hover" name="delete_post">--}}
                                    {{--<i class="fa fa-trash faa-ring faa-slow"></i>--}}
                                    {{--</button>--}}
                                </td>

                            </tr>

                        @empty
                            <p>No hay usuarios registrados</p>
                        @endforelse

                        </tbody>
                        <!-- Fin de Cuerpo de la Tabla  -->

                        <!-- Pie de Tabla -->
                        <tfoot>
                        <tr>
                            <!--<td> (Pie de la tabla) </td>-->
                        </tr>
                        </tfoot>
                        <!-- Fin de Pie de Tabla -->

                    </table>
                </div>
                <!-- Fin de la División de Tabla -->

            @else
                {{-- users Is Empty--}}

                <div class='alert alert-danger' role='alert'>
                    <p class="text-danger">No hay usuarios registrados !!</p>
                </div>
            @endif

        </div>
        {{-- /Card Body --}}


    </div>
    {{-- /Card --}}

@endsection