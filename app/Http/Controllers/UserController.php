<?php
    
    namespace Cinema\Http\Controllers;
    
    use Cinema\Profession;
    use Cinema\User;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Redirect;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    
    class UserController extends Controller
    {
        
        // Muestra la vista users_page y envía un listado de users y un title
        public function show_users_page()
        {
            // Obtengo todos los usuarios de la tabla users
            // $users = DB::table('users')->get();
            
            // Usando Eloquent en lugar del constructor de consultas. Obtengo objetos que son instancias de Eloquent
            $users = User::all();
            
            // Defino el título de la página
            $title = 'Listado de usuarios';
            
            // return view('users.users_page')
            //     // ->with('users', User::all())
            //     ->with('users', $users)
            //     ->with('title', $title);
            
            // Usando el método compact que va a convertir el nombre de las variables locales en un array asociativo
            return view('users.users_page', compact('users', 'title'));
            
        }
        
        // Muestra la vista user_details_page y envía un User y un title o sino muestra la vista 404
        // public function show_user_details_page($id)
        public function show_user_details_page(User $user)
        {
            // Obtengo el user a partir del $id recibido
            // $user = User::find($id);
            
            // El método va a arrojar una excepción de ModelNotFound, que va a finalizar la ejecución de esta acción
            // Pero esta excepción de ModelNotFound va a ser capturada por Laravel y la va a transformar en una respuesta de tipo 404
            // Tras lo cual se mostrará la vista 404 en la carpeta errors (laravel lo detecta y decide por nosotros)
            // $user = User::findOrFail($id);
            
            // if ($user == null) {
            //     return response()->view('errors.404', [], 404);
            // }
            
            return view('users.user_details_page', compact('user'));
            
        }
        
        // Muestra la página con el formulario del nuevo User
        public function show_new_user_page()
        {
            // Obtengo todas las profesiones en la BD
            $professions = Profession::all();
            
            // Muestro la página con el formulario del nuevo usuario, enviándole el listado de profesiones
            return view('users.new_user_page', compact('professions'));
        }
        
        // Crea un nuevo User
        public function goto_create_user_script()
        {
            // Recibo por POST todos los datos del user a la vez y los guardo en un array
            // $data = request()->all();
            
            // *** Otras formas de recibir lo datos ***
            // $email = request('email');
            // $email = request()->email;
            // $email = request()->get('email');
            
            // De esta especificamos únicamente los campos que se desean recibir
            // $data = request()->only(['firstname', 'lastname', 'email']);
            
            // Incluimos una validación rudimentaria en el caso de que se reciban campos vacíos o nulos
            // Redirigimos de nuevo hacia la página de creación del user, e incorporamos un error personificando el mismo
            // if (empty($data['firstname'])) {
            //     return redirect(route('users.new_user_page'))->withErrors([
            //         'firstname' => 'El campo nombre es obligatorio'
            //     ]);
            //
            // }
            
            // Laravel trae un componente de validación. A la vez que recibo los datos, especifico las reglas de validación
            // $data = request()->validate([
            //     'firstname' => 'required',
            //     'secondname' => '',
            //     'lastname' => 'required',
            //     'email' => ['required', 'email', 'unique:users,email'],
            //     // 'password' => ['required', 'confirmed'],
            //     'password' => ['required'],
            //     // 'password_confirmation' => ['required'],
            //     'password_confirmation' => ['required', 'same:password'],
            //     'phone' => 'required',
            //     'gender' => 'required',
            //     'is_active' => 'required',
            //     'kind' => 'required',
            //     'profession_id' => 'required'
            //
            // ], [
            //     // Debo concatenar con un punto el campo con el nombre de la regla en cuestión
            //     'firstname.required' => 'El campo Nombre es obligatorio',
            //     'lastname.required' => 'El campo Apellidos es obligatorio',
            //
            //     'email.required' => 'El campo Email es obligatorio',
            //     'email.email' => 'El email debe poseer un formato adecuado. Del tipo: email_example@domain.com',
            //     'email.unique' => 'El email que ha tecleado ya está en uso. Utilize otro',
            //
            //     'password.required' => 'El campo Contraseña es obligatorio',
            //     // 'password.confirmed' => 'Las contraseñas deben coincidir',
            //     'password_confirmation.required' => 'Debe confirmar la contraseña',
            //     'password_confirmation.same' => 'Las contraseñas deben coincidir',
            //
            //     'phone.required' => 'El campo Teléfono es obligatorio',
            //     'gender.required' => 'El campo Sexo (o Género) es obligatorio',
            //     'kind.required' => 'El campo Privilegios es obligatorio',
            //     'profession_id.required' => 'El campo Profesión es obligatorio',
            // ]);
            //
            // // if ($data->fails()) {
            // //     return redirect(route('users.new_user_page'), $data)
            // //     // ->withErrors($validator)
            // //     ->withInput($data);
            // // }
            //
            // // Introduzco los datos recibidos en la BD
            // User::create([
            //     'profession_id' => $data['profession_id'],
            //     'firstname' => $data['firstname'],
            //     'secondname' => $data['secondname'],
            //     'lastname' => $data['lastname'],
            //     'email' => $data['email'],
            //     'password' => bcrypt($data['password']),
            //     'phone' => $data['phone'],
            //     'gender' => $data['gender'],
            //     'is_active' => $data['is_active'],
            //     'kind' => $data['kind']
            // ]);
            //
            // // Redirecciono hacia la página de usuarios (users_page)
            // return redirect()->route('users.users_page');
            //
            //
            #
            
            
            #
            
            
            #
            
            
            // Recibo por POST todos los datos del user a la vez y los guardo en un array
            // $postData = Input::all();
            $postData = request()->all();
            
            // Especifico algunas variables para valores mínimos y máximos de los campos
            $firstnameMaxLenght = 48;
            $secondnameMaxLenght = 48;
            $lastnameMaxLenght = 64;
            $emailMaxLenght = 48;
            
            $passwordMinLenght = 6;
            $passwordMaxLenght = 255;
            
            $phoneMinLenght = 6;
            $phoneMaxLenght = 21;
            
            // Especifico las expresiones regulares:
            // $letters_with_accents_and_spaces = "/^[\pL\s]+$/u";
            $letters_with_accents_dashes_and_spaces = "/^[\s-'\pL]+$/u";
            
            // Especifico las reglas de validación
            $rules = array(
                'firstname' => ['required', 'regex:' . $letters_with_accents_dashes_and_spaces, "max:$firstnameMaxLenght"],
                'secondname' => ['nullable', 'regex:' . $letters_with_accents_dashes_and_spaces, "max:$secondnameMaxLenght"],
                'lastname' => ['required', 'regex:' . $letters_with_accents_dashes_and_spaces, "max:$lastnameMaxLenght"],
                'email' => ['required', 'email', 'unique:users,email', "max:$emailMaxLenght"],
                'password' => ['required', "min:$passwordMinLenght", "max:$passwordMaxLenght"],
                'password_confirmation' => ['required', 'same:password'],
                'phone' => ['required', 'numeric', "digits_between:$phoneMinLenght,$phoneMaxLenght"],
                'gender' => ['required'],
                'is_active' => [''],
                'kind' => ['required'],
                'profession_id' => ['required']
            );
            
            // Especifico mensajes de error a la medida, para los campos de validación
            $errors = array(
                // Debo concatenar con un punto el campo con el nombre de la regla en cuestión
                'firstname.required' => 'Debe teclear su nombre',
                'firstname.regex' => 'El nombre solo puede estar conformado por letras, tildes, espacios y guiones',
                'firstname.max' => 'El nombre solo puede tener hasta ' . $firstnameMaxLenght . ' caracteres',
                
                'secondname.regex' => 'El segundo nombre solo puede estar conformado por letras, tildes, espacios y guiones',
                'secondname.max' => 'El segundo nombre solo puede tener hasta ' . $secondnameMaxLenght . ' caracteres',
                
                'lastname.required' => 'Debe teclear sus apellidos',
                'lastname.regex' => 'Los apellidos solo pueden estar conformados por letras, tildes, espacios y guiones',
                'lastname.max' => 'Los apellidos solo pueden tener hasta ' . $lastnameMaxLenght . ' caracteres',
                
                'email.required' => 'Debe teclear su email',
                'email.email' => 'El email debe poseer un formato adecuado. Del tipo: email_example@domain.com',
                'email.unique' => 'El email tecleado ya está en uso. Use otro por favor',
                'email.max' => 'El email solo puede tener hasta ' . $emailMaxLenght . ' caracteres',
                
                'password.required' => 'Debe teclear su contraseña',
                'password.min' => 'El password debe poseer un mínimo de ' . $passwordMinLenght . ' caracteres',
                'password.max' => 'El password solo puede tener hasta ' . $passwordMaxLenght . ' caracteres',
                
                'password_confirmation.required' => 'Debe confirmar su contraseña',
                'password_confirmation.same' => 'Las contraseñas deben coincidir',
                
                'phone.required' => 'Debe teclear su teléfono',
                'phone.numeric' => 'El teléfono solo puede estar formado por números',
                'phone.digits_between' => 'El teléfono debe poseer de ' . $phoneMinLenght . ' a ' . $phoneMaxLenght . ' dígitos',
                
                'gender.required' => 'Debe escoger su sexo (género)',
                
                'kind.required' => 'Debe escoger sus privilegios en el sistema',
                
                'profession_id.required' => 'Debe escoger su profesión',
            );
            
            // Realizo la validación, pasando los datos recibidos por post ($postData), las reglas ($rules) y los mensajes de error ($errors)
            $validator = Validator::make($postData, $rules, $errors);
            
            // Ahora verifico el éxito o no de la validación
            if ($validator->fails()) // Si la validación falla...
            {
                // send back to the page with the input data and errors
                // GlobalHelper::setMessage('Fix the errors.', 'warning'); // setting the error message
                
                // Redirecciono nuevamente hacia la página con el formulario de nuevo usuario (la ruta: users.new_user_page) enviando los datos recibidos y los mensajes de error ($errors)
                return redirect::to(route('users.new_user_page'))->withInput()->withErrors($validator);
                
            } else // En cambio, si la validación es exitosa...
            {
                // Introduzco los datos recibidos en la BD
                User::create([
                    'profession_id' => $postData['profession_id'],
                    'firstname' => $postData['firstname'],
                    'secondname' => $postData['secondname'],
                    'lastname' => $postData['lastname'],
                    'email' => $postData['email'],
                    'password' => bcrypt($postData['password']),
                    'phone' => $postData['phone'],
                    'gender' => $postData['gender'],
                    'is_active' => $postData['is_active'],
                    'kind' => $postData['kind']
                ]);
                
                // send back to the page with success message
                // GlobalHelper::setMessage('Registration data saved.', 'success');
                // return Redirect::to('register');
                
                // Redirecciono hacia la página con el listado de usuarios (users_page)
                return redirect()->route('users.users_page');
            }
            
            
        }
        
        // Muestra la página con el formulario para actualizar un User
        public function show_edit_user_page(User $user)
        {
            // Obtengo todas las profesiones en la BD
            $professions = Profession::all();
            
            // Muestro la página con el formulario para actualizar un user, enviándole el listado de profesiones y el user
            return view('users.edit_user_page', ['user' => $user, 'professions' => $professions]);
            
        }
        
        // Actualiza un User
        // public function goto_update_user_script()
        public function goto_update_user_script(User $user)
        {
            // Recibo por POST todos los datos del user a la vez y los guardo en un array
            $postData = request()->all();
            
            // Especifico algunas variables para valores mínimos y máximos de los campos
            $firstnameMaxLenght = 48;
            $secondnameMaxLenght = 48;
            $lastnameMaxLenght = 64;
            $emailMaxLenght = 48;
            
            $passwordMinLenght = 6;
            $passwordMaxLenght = 255;
            
            $phoneMinLenght = 6;
            $phoneMaxLenght = 21;
            
            // Especifico las expresiones regulares:
            // $letters_with_accents_and_spaces = "/^[\pL\s]+$/u";
            $letters_with_accents_dashes_and_spaces = "/^[\s-'\pL]+$/u";
            
            // Especifico las reglas de validación
            $rules = array(
                'firstname' => ['required', 'regex:' . $letters_with_accents_dashes_and_spaces, "max:$firstnameMaxLenght"],
                'secondname' => ['nullable', 'regex:' . $letters_with_accents_dashes_and_spaces, "max:$secondnameMaxLenght"],
                'lastname' => ['required', 'regex:' . $letters_with_accents_dashes_and_spaces, "max:$lastnameMaxLenght"],
                
                // Valida para que no sea un email ya usado, excepto para el email del propio user, tomando en cuenta su id
                // 'email' => ['required', 'email', 'unique:users,email,' . $postData['id'], "max:$emailMaxLenght"],
                'email' => [
                    'required',
                    'email',
                    "unique:users,email,{$postData['id']}",
                    // Llamo al método unique de la clase Rule
                    // Rule::unique('users')->ignore($postData['id']),
                    // Rule::unique('users', 'email')->ignore($postData['id']),
                    "max:$emailMaxLenght"
                ],
                
                // 'password' => ['required', "min:$passwordMinLenght", "max:$passwordMaxLenght"],
                'password' => ['nullable', "min:$passwordMinLenght", "max:$passwordMaxLenght"],
                // 'password_confirmation' => ['required', 'same:password'],
                'password_confirmation' => ['nullable', 'same:password'],
                
                'phone' => ['required', 'numeric', "digits_between:$phoneMinLenght,$phoneMaxLenght"],
                'gender' => ['required'],
                'is_active' => [''],
                'kind' => ['required'],
                'profession_id' => ['required']
            );
            
            // Especifico mensajes de error a la medida, para los campos de validación
            $errors = array(
                // Debo concatenar con un punto el campo con el nombre de la regla en cuestión
                'firstname.required' => 'Debe teclear su nombre',
                'firstname.regex' => 'El nombre solo puede estar conformado por letras, tildes, espacios y guiones',
                'firstname.max' => 'El nombre solo puede tener hasta ' . $firstnameMaxLenght . ' caracteres',
                
                'secondname.regex' => 'El segundo nombre solo puede estar conformado por letras, tildes, espacios y guiones',
                'secondname.max' => 'El segundo nombre solo puede tener hasta ' . $secondnameMaxLenght . ' caracteres',
                
                'lastname.required' => 'Debe teclear sus apellidos',
                'lastname.regex' => 'Los apellidos solo pueden estar conformados por letras, tildes, espacios y guiones',
                'lastname.max' => 'Los apellidos solo pueden tener hasta ' . $lastnameMaxLenght . ' caracteres',
                
                'email.required' => 'Debe teclear su email',
                'email.email' => 'El email debe poseer un formato adecuado. Del tipo: email_example@domain.com',
                'email.unique' => 'El email tecleado ya está en uso. Use otro por favor',
                'email.max' => 'El email solo puede tener hasta ' . $emailMaxLenght . ' caracteres',
                
                // 'password.required' => 'Debe teclear su contraseña',
                'password.min' => 'El password debe poseer un mínimo de ' . $passwordMinLenght . ' caracteres',
                'password.max' => 'El password solo puede tener hasta ' . $passwordMaxLenght . ' caracteres',
                
                // 'password_confirmation.required' => 'Debe confirmar su contraseña',
                'password_confirmation.same' => 'Las contraseñas deben coincidir',
                
                'phone.required' => 'Debe teclear su teléfono',
                'phone.numeric' => 'El teléfono solo puede estar formado por números',
                'phone.digits_between' => 'El teléfono debe poseer de ' . $phoneMinLenght . ' a ' . $phoneMaxLenght . ' dígitos',
                
                'gender.required' => 'Debe escoger su sexo (género)',
                
                'kind.required' => 'Debe escoger sus privilegios en el sistema',
                
                'profession_id.required' => 'Debe escoger su profesión',
            );
            
            // Realizo la validación, pasando los datos recibidos por post ($postData), las reglas ($rules) y los mensajes de error ($errors)
            $validator = Validator::make($postData, $rules, $errors);
            
            // dd($user);
            
            // Ahora verifico el éxito o no de la validación
            if ($validator->fails()) // Si la validación falla...
            {
                // dd($validator->messages()->toJson());
                // dd($validator->messages());
                
                // Muestra el 1er mensaje de error
                // dd($validator->messages()->first());
                
                // Muestra todos los mensajes de error
                // dd($validator->messages()->all());
                
                // Muestra solo los mensajes de error relacionados con el campo phone
                // dd($validator->messages()->get('phone'));
                
                // Redirecciono nuevamente hacia la página con el formulario de actualizar usuario (la ruta: users.edit_user_page) enviando los datos recibidos y los mensajes de error ($errors)
                return redirect::to(route('users.edit_user_page', $postData['id']))->withInput()->withErrors($validator);
                
            } else // En cambio, si la validación es exitosa...
            {
                
                // dd($postData);
                
                // Busco el user que ya está en la BD con los datos viejos (cuando uso POST) NO se usa si utilizo PUT
                // $user = User::where('id', '=', $postData['id']);
                // $user = User::find($postData['id']);
                
                // Antes de actualizar el User verificamos si se deseaba actualizar también la contraseña
                if ($postData['password'] != null) {
                    
                    $postData['password'] = bcrypt($postData['password']);
                    
                } else {
                    // Quitamos el índice password del array asociativo de la variable $postData
                    unset($postData['password']);
                }
                
                
                // Reescribo el user en la BD con los datos nuevos
                // $user->update([
                //     // Innecesario cuando se usa PUT
                //     // 'id' => $postData['id'],
                //     'profession_id' => $postData['profession_id'],
                //     'firstname' => $postData['firstname'],
                //     'secondname' => $postData['secondname'],
                //     'lastname' => $postData['lastname'],
                //     'email' => $postData['email'],
                //     'password' => bcrypt($postData['password']),
                //     'phone' => $postData['phone'],
                //     'gender' => $postData['gender'],
                //     'is_active' => $postData['is_active'],
                //     'kind' => $postData['kind']
                // ]);
                
                // Actualizo con los nuevos datos del User en la BD
                $user->update($postData);
                
                // Redirecciono hacia la página con el listado de usuarios (users_page)
                return redirect()->route('users.users_page');
            }
        }
        
        // Borra un User a partir de su id
        public function goto_delete_user_script(User $user)
        {
            // Borramos el User
            try {
                $user->delete();
            } catch (Exception $e) {
            }
            
            // Redireccionamos hacia la página con el listado de usuarios
            return redirect(route('users.users_page'));
        }
        
        
        // -------------------------------------------------------------------------------------------------------------
        
        // Muestra la vista examples_page y envía el arreglo $usersArray
        public function show_examples_page()
        {
            $usersArray = request()->has('empty') ? [] : ['Reinier', 'Mayelin', 'Mayra', 'Pedro', '<script>alert("Clicker")</script>'];
            // $usersArray = ['Reinier', 'Mayelin', 'Mayra', 'Pedro', '<script>alert("Clicker")</script>'];
            $title = 'Listado de usuarios';
            
            // Pasamos datos del controlador hacia la vista users_page usando un array asociativo (a Duilio Palacios le parece la más sencilla)
            // return view('users_page', [
            //     'usersArray' => $usersArray,
            //     'title' => 'Listado de usuarios'
            // ]);
            
            // Otra forma de pasar datos del controlador hacia la vista users_page (método with, encadenado a la función view)
            // return view('users_page')->with([
            //     'usersArray' => $usersArray,
            //     'title' => 'Listado de usuarios'
            // ]);
            
            // Pasando las variables de forma individual con with, encadenándolas una tras otra (se utiliza bastante en Laravel)
            // return view('users_page')
            //     -> with('usersArray', $usersArray)
            //     -> with('title', $title);
            
            // var_dump(compact('usersArray','title'));
            // die();
            
            // Forma sencilla y rápida de comprobar el llamado a una función o los datos que tenemos en una variable, etc, usando el helper de Laravel dd
            // dd(compact('usersArray','title'));
            
            // Usando el método compact que va a convertir el nombre de las variables locales en un array asociativo
            return view('users.examples_page', compact('usersArray', 'title'));
        }
        
        
    }
