@extends('layouts.general_layout')


@section('page_title', "Datos del usuario")

@section('content')



    <div class="card">
        <div class="card-header">
            <h2>Datos del usuario</h2>
        </div>

        <div class="card-body">
            <!-- División de Tabla -->
            <div id="div_tabla_id" class="div_tabla">

                <table id="tbl_id" cellspacing="0" cellpadding="0" class="standard-light-blue"
                       style="margin-right:auto;margin-left:0px">
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
                        <th>Campo</th>
                        <th>Valor</th>
                        <th>Campo</th>
                        <th>Valor</th>
                    </tr>

                    <tr>
                        <td style="text-align: left; font-weight: bold;">id</td>
                        <td style="text-align: left;">{{ $user->id }}</td>
                        <td style="text-align: left; font-weight: bold;">Teléfono</td>
                        <td style="text-align: left;">{{ strlen($user->phone) == 10 ? '(' . substr($user->phone, 0, 3) . ') ' . substr($user->phone, 3, 3) . '-' . substr($user->phone, 6, 4) : $user->phone  }}</td>

                    </tr>

                    <tr>
                        <td style="text-align: left; font-weight: bold;">Profesión</td>
                        {{--                <td style="text-align: left;">{{ \Cinema\Profession::find($user->profession_id)->name  }}</td>--}}
                        <td style="text-align: left;">{{ $user->profession->name }}</td>
                        <td style="text-align: left; font-weight: bold;">Sexo</td>
                        <td style="text-align: left;">{{ strtolower($user->gender) == 'female' ? 'Femenino' : (strtolower($user->gender) == 'male' ? 'Masculino' : 'Desconocido') }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; font-weight: bold;">Nombre</td>
                        <td style="text-align: left;">{{ $user->firstname }}</td>
                        <td style="text-align: left; font-weight: bold;">Estado</td>
                        <td style="text-align: left;">{{ $user->is_active == 1 ? 'Activo' : 'Inactivo' }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; font-weight: bold;">2do Nombre</td>
                        <td style="text-align: left;">{{ $user->secondname }}</td>
                        <td style="text-align: left; font-weight: bold;">Privilegios</td>
                        <td style="text-align: left;">{{ ($user->kind == 'administrator') ? 'Administrador' : (($user->kind == 'operator') ? 'Operador' : 'Invitado') }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; font-weight: bold;">Apellidos</td>
                        <td style="text-align: left;">{{ $user->lastname }}</td>
                        <td style="text-align: left; font-weight: bold;">Creado:</td>
                        <td style="text-align: left;">{{ $user->created_at }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; font-weight: bold;">Email</td>
                        <td style="text-align: left;">{{ $user->email }}</td>
                        <td style="text-align: left; font-weight: bold;">Actualizado:</td>
                        <td style="text-align: left;">{{ $user->updated_at }}</td>
                    </tr>

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

            <br>

            {{--    <p><a href="{{url()->previous()}}">URL Anterior</a></p>--}}
            <p>
                <a href="{{ route('users.edit_user_page', $user) }}" class="btn btn-default btn-success faa-parent animated-hover" style="text-decoration: none;" name="edit_user">
                    <i class="fa fa-edit faa-ring faa-slow"></i>
                    Editar
                </a>

                <a class="btn btn-default btn-danger faa-parent animated-hover" style="text-decoration: none;" href="{{route('users.users_page')}}">
                    <i class="fa fa-arrow-left faa-horizontal fa-fast"></i>
                    Regresar
                </a>
            </p>

        </div>
        {{-- /Card-Body --}}

    </div>
    {{-- /Card --}}



@endsection