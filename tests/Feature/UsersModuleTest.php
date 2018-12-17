<?php
    
    namespace Tests\Feature;
    
    use Cinema\Http\Controllers\UserController;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Cinema\Profession;
    use Cinema\User;
    use Illuminate\Support\Facades\DB;
    use Tests\TestCase;
    use Throwable;
    
    class UsersModuleTest extends TestCase
    {
        
        // No funciona este trait con los tests: "it_displays_the_user_details_page" y "it_creates_a_new_user"
        // use RefreshDatabase;
        
        // Este funciona, pero es más lento
        use DatabaseMigrations;
        
        
        # * -------------------------------------------------------------------------------------------------- *
        # *                                                                                                    *
        # *                      *** Tests de Reglas de Validación RETRIEVE ***                                *
        # *                                                                                                    *
        # *                          Views: users.users_page, user_details_page ***                            *
        # *                                                                                                    *
        # * -------------------------------------------------------------------------------------------------- *
        
        /**
         * Comprueba si se muestra correctamente la página de los users (users_page)
         *
         * @test
         */
        public function it_displays_the_users_page()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Creamos una profession cualquiera en la BD para que luego no haya problemas con la llave foránea profession_id de la tabla users
            // (Se elimina al terminarse ESTA prueba, gracias al trait RefreshDatabase)
            factory(Profession::class)->create();
            
            // Creamos un user en la BD para esta prueba (se elimina al terminarse, gracias al Trait RefreshDatabase)
            factory(User::class)->create([
                'firstname' => 'Reinier',
                // 'website' => 'thelastofus.com',
            ]);
            
            // $this->assertDatabaseHas('users', [
            //     'firstname' => 'Reinier'
            // ]);
            
            // $this->assertTrue(true);
            $this->get('/users_page')
                ->assertStatus(200)
                // ->assertSee('thelastofus.com')
                ->assertSee('Reinier');
            
        }
        
        /**
         * Comprueba si se muestra un mensaje default en la página de los users (users_page) cuando no hay users en la BD
         *
         * @test
         */
        public function it_shows_a_default_message_if_the_users_list_is_empty()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Vaciamos la tabla users justo antes de ejecutar la prueba (no es necesario con el Trait RefreshDatabase)
            // DB::table('users')->truncate();
            
            // $this->assertTrue(true);
            $this->get('/users_page')
                ->assertStatus(200)
                ->assertSee('No hay usuarios registrados');
        }
        
        /**
         * Comprueba si se muestra la página de detalles del user (user_details_page)
         *
         * @test
         */
        public function it_displays_the_user_details_page()
        {
            // Permite obtener más detalles al correr las pruebas
            // $this->withoutExceptionHandling();
            
            
            // Creamos una profession para que no haya problemas con la llave foránea profession_id de la tabla users
            // factory(Profession::class)->create([
            //     'name' => 'Pulidor de pisos',
            // ]);
            
            // Creamos una profession en la BD y la guardamos en una variable
            $profession = factory(Profession::class)->create();
            
            // Creamos un user
            // $user = factory(User::class)->create([
            //     // 'profession_id' => 1,
            //     'firstname' => 'Mayra',
            // ]);
            
            // Creamos un user en la BD y lo guardamos en una variable
            $user = factory(User::class)->create();
            
            // dd($user);
            
            // Verificamos que en la BD tengamos la misma profesión creada
            $this->assertDatabaseHas('professions', [
                // 'name' => 'Pulidor de pisos'
                'name' => $profession->name
            ]);
            
            // Y verificamos que el user creado esté en la base de datos
            $this->assertDatabaseHas('users', [
                // 'firstname' => 'Mayra'
                'firstname' => $user->firstname
            ]);
            
            // Verificamos que sea un acceso exitoso a la vista y que aparezca el texto escogido
            $this->get('/user_details_page/' . $user->id)
                ->assertStatus(200)
                // ->assertSee('Mayra')
                ->assertSee($user->firstname);
        }
        
        /**
         * Comprueba si se muestra la página de error 404 si se intenta acceder a los detalles de un user que no existe
         *
         * @test
         */
        public function it_displays_a_404_error_page_if_the_user_is_not_found()
        {
            $this->get('/user_details_page/' . (User::count() + 1))
                ->assertStatus(404)
                ->assertSee('OOPS!');
            
        }
        
        
        # * -------------------------------------------------------------------------------------------------- *
        # *                                                                                                    *
        # *               *** Tests de Reglas de Validación CREATE: users.create_user_script ***                *
        # *                                                                                                    *
        # * -------------------------------------------------------------------------------------------------- *
        
        /**
         * Comprueba si se muestra bien la página con el formulario de nuevo user (new_user_page)
         *
         * @test
         */
        public function it_displays_the_new_user_page()
        {
            
            $this->get('/new_user_page')
                ->assertStatus(200)
                ->assertSee('Crear un usuario');
        }
        
        
        /**
         * Comprueba si se puede crear bien un nuevo user
         *
         * @test
         */
        public function it_creates_a_new_user()
        {
            // Permite obtener más detalles al correr las pruebas
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados y luego verifico que se redirija hacia users_page
            // $this->post('/create_user_script', $data)->assertRedirect('/users_page');
            // $this->post('/create_user_script', $data)
            $this->post(route('users.create_user_script'), $data)
                ->assertRedirect(route('users.users_page'));
            
            // Verifico que los datos enviados estén en la BD (casi siempre utilizaremos este método)
            // $this->assertDatabaseHas('users', [
            //     'profession_id' => $data['profession_id'],
            //     'firstname' => $data['firstname'],
            //     'secondname' => $data['secondname'],
            //     'lastname' => $data['lastname'],
            //     'email' => $data['email'],
            //     // No lo verifica bien. Cada vez que se ejecute este método helper se generan cadenas diferentes
            //     // 'password' => bcrypt('123456'),
            //     'phone' => $data['phone'],
            //     'gender' => $data['gender'],
            //     'is_active' => $data['is_active'],
            //     'kind' => $data['kind']
            // ]);
            
            // Mejor forma para verificar que los datos enviados estén en la BD (debido a que verificamos una contraseña)
            // No funciona comparando valores nulos (uno dejado en blanco por el usuario y otro vacío en la BD)
            // $this->assertCredentials([
            //     'profession_id' => $data['profession_id'],
            //     'firstname' => $data['firstname'],
            //     'secondname' => $data['secondname'],
            //     'lastname' => $data['lastname'],
            //     'email' => $data['email'],
            //     // Ahora sí se verifica bien el password
            //     'password' => $data['password'],
            //     'phone' => $data['phone'],
            //     'gender' => $data['gender'],
            //     'is_active' => $data['is_active'],
            //     'kind' => $data['kind']
            // ]);
            
            // $this->assertCredentials($data);
            // Funciona OK
            $this->assertCredentials([
                'profession_id' => $data['profession_id'],
                'firstname' => $data['firstname'],
                'secondname' => $data['secondname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                // Ahora sí se verifica bien el password
                'password' => $data['password'],
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'is_active' => $data['is_active'],
                'kind' => $data['kind']
            ]);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(1, User::count());
        }
        
        // Reglas de Validación CREATE
        
        
        /**
         * Comprueba si el campo firstname es obligatorio
         *
         * @test
         */
        public function it_checks_the_firstname_is_required()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => '',
                'secondname' => null,
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['firstname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['firstname' => 'Debe teclear su nombre']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo firstname es tiene un formato adecuado
         *
         * @test
         */
        public function it_checks_the_firstname_has_an_adequate_format()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon/*',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['firstname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['firstname' => 'El nombre solo puede estar conformado por letras, tildes, espacios y guiones']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo firstname es demasiado largo
         *
         * @test
         */
        public function it_checks_the_firstname_is_not_too_long_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                
                // ** Nombre demasiado largo **
                'firstname' => 'Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon',
                
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['firstname'])
                // Podemos incluso ser más explícitos:
                // ->assertSessionHasErrors(['firstname' => 'El nombre solo puede tener hasta' . UserController::$firstnameMaxLenght . 'caracteres'])
                ->assertSessionHasErrors(['firstname' => 'El nombre solo puede tener hasta 48 caracteres']);
            
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo secondname tiene un formato adecuado
         *
         * @test
         */
        public function it_checks_the_secondname_has_an_adequate_format_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                
                // Second name con caracteres especiales
                'secondname' => 'Esperanto*',
                
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['secondname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['secondname' => 'El segundo nombre solo puede estar conformado por letras, tildes, espacios y guiones']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo secondname es demasiado largo
         *
         * @test
         */
        public function it_checks_the_secondname_is_not_too_long_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                
                // Second Name demasiado largo
                'secondname' => 'EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperanto',
                
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['secondname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['secondname' => 'El segundo nombre solo puede tener hasta 48 caracteres']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo lastname es obligatorio
         *
         * @test
         */
        public function it_checks_the_lastname_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                
                // Apellidos faltantes
                'lastname' => '',
                
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['lastname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['lastname' => 'Debe teclear sus apellidos']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo lastname es tiene un formato adecuado
         *
         * @test
         */
        public function it_checks_the_lastname_has_an_adequate_format_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                
                // Apellidos con caracteres especiales
                'lastname' => 'García Ramos/*',
                
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['lastname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['lastname' => 'Los apellidos solo pueden estar conformados por letras, tildes, espacios y guiones']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo lastname es demasiado largo
         *
         * @test
         */
        public function it_checks_the_lastname_is_not_too_long_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                
                // ** Apellidos demasiado largos **
                'lastname' => 'García RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía Ramos',
                
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['lastname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['lastname' => 'Los apellidos solo pueden tener hasta 64 caracteres']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo email es obligatorio
         *
         * @test
         */
        public function it_checks_the_email_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => '',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'Debe teclear su email']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo email es válido
         *
         * @test
         */
        public function it_checks_the_email_is_valid_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'correo-no-valido',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'El email debe poseer un formato adecuado. Del tipo: email_example@domain.com']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo email es único
         *
         * @test
         */
        public function it_checks_the_email_is_unique_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (con el mismo email del user anteriormente insertado)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creo un Model factory para insertar un user con un email específico
            factory(User::class)->create([
                'email' => 'reymillenium@gmail.com',
            ]);
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'El email tecleado ya está en uso. Use otro por favor']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el 2do user no haya sido insertado en la BD (si la cantidad de users = 1)
            $this->assertEquals(1, User::count());
            
        }
        
        /**
         * Comprueba si el campo email es demasiado largo
         *
         * @test
         */
        public function it_checks_the_email_is_not_too_long_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                
                // Dato: (Los emails realmente pueden ser de hasta 64 caracteres de username + 1 caracter de la arroba + 255 caracteres del dominio = 320 caracteres en total)**
                
                // 64 en username, 64 en el dominio contando la arroba y 64 en la extension contando el punto (192 caracteres en total) aunque no es por sumatoria, sino por secciones
                // Este cumple justo con la regla email de laravel!!!
                // 'email' => 'reymillenium1reymillenium1reymilleooooooooooooooooooooooooooooop@GranDominioGranDominioGranDominioGranDominioGranDominioGranDomi.commmmmmmmmmmmmmmmmmmmmmmmmmmdfsdgfffgvvvvvvvvvvvvllllllllllppi',
                
                // ** Email demasiado largo según la regla de largo máximo (hasta 48) (Mayor que 48 en este caso, pues tiene 49) **
                'email' => 'reymilleniumooooooooooooooooooooooooooP@gmail.com',
                
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'El email solo puede tener hasta 48 caracteres']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo password es obligatorio
         *
         * @test
         */
        public function it_checks_the_password_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '',
                'password_confirmation' => '123456',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['password'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['password' => 'Debe teclear su contraseña']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo password no es demasiado pequeño
         *
         * @test
         */
        public function it_checks_the_password_is_not_too_small_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = ['profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                
                // Password demasiado pequeño
                'password' => '123',
                
                'password_confirmation' => '123',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['password'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['password' => 'El password debe poseer un mínimo de 6 caracteres']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo password no es demasiado grande
         *
         * @test
         */
        public function it_checks_the_password_is_not_too_big_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = ['profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                
                // Password demasiado grande (más de 255 caracteres) pues posee 256 en este caso
                'password' => 'aaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeee123456',
                
                'password_confirmation' => 'aaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeee123456',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['password'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['password' => 'El password solo puede tener hasta 255 caracteres']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo password de confirmación es obligatorio
         *
         * @test
         */
        public function it_checks_the_password_confirmation_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo nombre en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '123456',
                'password_confirmation' => '',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                ->assertSessionHasErrors(['password_confirmation'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['password_confirmation' => 'Debe confirmar su contraseña']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si los dos passwords son iguales
         *
         * @test
         */
        public function it_checks_the_passwords_must_be_equals_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (contraseñas diferentes)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                
                // La contraseña de confirmación no es igual
                'password_confirmation' => '12345678',
                
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                // ->assertSessionHasErrors(['password'])
                ->assertSessionHasErrors(['password_confirmation'])
                // Podemos incluso ser más explícitos:
                // ->assertSessionHasErrors(['password' => 'Las contraseñas deben coincidir'])
                ->assertSessionHasErrors(['password_confirmation' => 'Las contraseñas deben coincidir']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo phone es obligatorio
         *
         * @test
         */
        public function it_checks_the_phone_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo phone en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567890',
                'password_confirmation' => '1234567890',
                
                // Falta el teléfono
                'phone' => '',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                // Verifico que exista un error asociado con el campo
                ->assertSessionHasErrors(['phone'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['phone' => 'Debe teclear su teléfono']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo phone es válido
         *
         * @test
         */
        public function it_checks_the_phone_is_valid_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo phone en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567890',
                'password_confirmation' => '1234567890',
                
                // Teléfono inválido
                'phone' => '786AMD21kk',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                // Verifico que exista un error asociado con el campo
                ->assertSessionHasErrors(['phone'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['phone' => 'El teléfono solo puede estar formado por números']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        /**
         * Comprueba si el campo phone es no es muy pequeño o muy grande
         *
         * @test
         */
        public function it_checks_the_phone_is_not_too_small_or_too_big_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            # *** Phone muy pequeño
            
            // Preparo los datos a enviar por post (con un campo phone muy pequeño en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567890',
                'password_confirmation' => '1234567890',
                
                // Teléfono muy pequeño (menor que 6)
                'phone' => '123',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                // Verifico que exista un error asociado con el campo
                ->assertSessionHasErrors(['phone'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['phone' => 'El teléfono debe poseer de 6 a 21 dígitos']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
            # *** Phone muy grande
            
            // Ahora preparo otros datos a enviar por post (con un campo phone muy grande en este caso)
            $data2 = [
                'profession_id' => '1',
                'firstname' => 'Reinier',
                'secondname' => null,
                'lastname' => 'Garcia Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567890',
                'password_confirmation' => '1234567890',
                
                // Teléfono muy grande (mayor que 21), pues tiene 22 en este caso
                'phone' => '1234567890123456789012',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data2)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data2)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                // Verifico que exista un error asociado con el campo
                ->assertSessionHasErrors(['phone'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['phone' => 'El teléfono debe poseer de 6 a 21 dígitos']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
            
        }
        
        
        /**
         * Comprueba si el campo gender es obligatorio
         *
         * @test
         */
        public function it_checks_the_gender_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo gender en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => '',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                //.. y que exista un campo "firstname" en el array de errores en la sesión (pues no fue incluido)
                ->assertSessionHasErrors(['gender'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['gender' => 'Debe escoger su sexo (género)']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo kind es obligatorio
         *
         * @test
         */
        public function it_checks_the_kind_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo kind en este caso)
            $data = [
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                
                // Falta el kind
                'kind' => ''
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                // Verificamos que haya un error asociado al campo
                ->assertSessionHasErrors(['kind'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['kind' => 'Debe escoger sus privilegios en el sistema']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        /**
         * Comprueba si el campo profession_id es obligatorio
         *
         * @test
         */
        public function it_checks_the_profession_id_is_required_when_creating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            // $this->withoutExceptionHandling();
            
            // Preparo los datos a enviar por post (menos el campo kind en este caso)
            $data = [
                // Falta el profession_id
                'profession_id' => '',
                
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ejecuto la ruta post, enviando los datos ya preparados
            // $this->from(route('users.new_user_page'))->post('/create_user_script', $data)
            $this->from(route('users.new_user_page'))->post(route('users.create_user_script'), $data)
                // Luego verifico que se redirija de regreso hacia new_user_page...
                ->assertRedirect(route('users.new_user_page'))
                // Verificamos que haya un error asociado al campo
                ->assertSessionHasErrors(['profession_id'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['profession_id' => 'Debe escoger su profesión']);
            
            // Y verificamos que los datos (el array $data) NO ESTÉN en la tabla users de la BD. Funciona OK
            // $this->assertDatabaseMissing('users', $data);
            
            // Otra manera de verificar que el user no haya sido insertado en la BD (si la cantidad de users = 0)
            $this->assertEquals(0, User::count());
            
        }
        
        
        
        # * -------------------------------------------------------------------------------------------------- *
        # *                                                                                                    *
        # *               *** Tests de Reglas de Validación UPDATE: users.update_user_script ***               *
        # *                                                                                                    *
        # * -------------------------------------------------------------------------------------------------- *
        
        
        /**
         * Comprueba si se muestra bien la página con el formulario de actualización del user (update_user_page)
         *
         * @test
         */
        public function it_displays_the_edit_user_page()
        {
            // Permite obtener más detalles al correr las pruebas (no sirve para las pruebas de errores)
            $this->withoutExceptionHandling();
            
            // Creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creo ahora un user
            $user = factory(User::class)->create();
            
            // Ejecuto la ruta get, enviando el id del user a editar
            // $this->get('/edit_user_page/' . $user->id)
            $this->get("/edit_user_page/{$user->id}")
                ->assertStatus(200)
                ->assertViewIs('users.edit_user_page')
                ->assertSee('Actualizar el usuario')
                ->assertViewHas('user', function ($view_user) use ($user) {
                    return $view_user->id == $user->id;
                    
                });
        }
        
        
        /**
         * Comprueba si se puede actualizar bien un user
         *
         * @test
         */
        public function it_updates_a_new_user()
        {
            
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia users_page
            $this->put("/update_user_script/{$user->id}", $data)
                ->assertRedirect(route('users.users_page'));
            
            // Funciona OK
            $this->assertCredentials($data);
            
            // Funciona OK
            $this->assertCredentials([
                'profession_id' => $data['profession_id'],
                'firstname' => $data['firstname'],
                'secondname' => $data['secondname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                // Ahora sí se verifica bien el password
                'password' => $data['password'],
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'is_active' => $data['is_active'],
                'kind' => $data['kind']
            ]);
            
        }
        
        
        // Reglas de Validación UPDATE
        
        /**
         * Comprueba si el campo firstname es obligatorio al actualizar un User
         *
         * @test
         */
        public function it_checks_the_firstname_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => '',
                'secondname' => null,
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id]))
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            
            //.. y que exista un campo "firstname" en el array de errores en la sesión
            ->assertSessionHasErrors(['firstname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['firstname' => 'Debe teclear su nombre']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo firstname es tiene un formato adecuado
         *
         * @test
         */
        public function it_checks_the_firstname_has_an_adequate_format_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                
                // Firstname con formato inadecuado
                'firstname' => 'Kennedy-Winbleddon/*',
                
                'secondname' => null,
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id]))
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "firstname" en el array de errores en la sesión (pues tiene un mal formato)
            ->assertSessionHasErrors(['firstname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['firstname' => 'El nombre solo puede estar conformado por letras, tildes, espacios y guiones']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo firstname es demasiado largo
         *
         * @test
         */
        public function it_checks_the_firstname_is_not_too_long_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                
                // ** Nombre demasiado largo **
                'firstname' => 'Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon
                Kennedy-WinbleddonKennedy-WinbleddonKennedy-WinbleddonKennedy-Winbleddon',
                
                'secondname' => null,
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id]))
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "firstname" en el array de errores en la sesión (pues tiene un mal formato)
            ->assertSessionHasErrors(['firstname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['firstname' => 'El nombre solo puede tener hasta 48 caracteres']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo secondname tiene un formato adecuado
         *
         * @test
         */
        public function it_checks_the_secondname_has_an_adequate_format_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                
                // Second name con caracteres especiales
                'secondname' => 'Esperanto*',
                
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id]))
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "secondname" en el array de errores en la sesión (pues tiene un mal formato)
            ->assertSessionHasErrors(['secondname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['secondname' => 'El segundo nombre solo puede estar conformado por letras, tildes, espacios y guiones']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo secondname es demasiado largo
         *
         * @test
         */
        public function it_checks_the_secondname_is_not_too_long_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                
                // Second name demasiado largo
                'secondname' => 'EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto
                EsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperantoEsperanto',
                
                'lastname' => 'García Ramos',
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "secondname" en el array de errores en la sesión
            ->assertSessionHasErrors(['secondname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['secondname' => 'El segundo nombre solo puede tener hasta 48 caracteres']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo lastname es obligatorio
         *
         * @test
         */
        public function it_checks_the_lastname_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                
                // Apellidos faltantes
                'lastname' => '',
                
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "lastname" en el array de errores en la sesión
            ->assertSessionHasErrors(['lastname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['lastname' => 'Debe teclear sus apellidos']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo lastname es tiene un formato adecuado
         *
         * @test
         */
        public function it_checks_the_lastname_has_an_adequate_format_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                
                // Apellidos con caracteres especiales
                'lastname' => 'García Ramos/*',
                
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "lastname" en el array de errores en la sesión
            ->assertSessionHasErrors(['lastname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['lastname' => 'Los apellidos solo pueden estar conformados por letras, tildes, espacios y guiones']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo lastname es demasiado largo
         *
         * @test
         */
        public function it_checks_the_lastname_is_not_too_long_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                
                // ** Apellidos demasiado largos **
                'lastname' => 'García RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía RamosGarcía
                RamosGarcía RamosGarcía RamosGarcía RamosGarcía Ramos',
                
                'email' => 'reymillenium@gmail.com',
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "lastname" en el array de errores en la sesión
            ->assertSessionHasErrors(['lastname'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['lastname' => 'Los apellidos solo pueden tener hasta 64 caracteres']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo email es obligatorio
         *
         * @test
         */
        public function it_checks_the_email_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                
                // Falta el email
                'email' => '',
                
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "email" en el array de errores en la sesión
            ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'Debe teclear su email']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo email es válido
         *
         * @test
         */
        public function it_checks_the_email_is_valid_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creamos un user aleatorio
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                
                // Email no válido
                'email' => 'correo-no-valido',
                
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "email" en el array de errores en la sesión
            ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'El email debe poseer un formato adecuado. Del tipo: email_example@domain.com']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo email es único
         *
         * @test
         */
        public function it_checks_the_email_is_unique_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creo un Model factory para insertar un user con un email específico
            factory(User::class)->create([
                'email' => 'reymillenium@gmail.com',
            ]);
            
            // Ahora creamos otro User aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                
                // Email ya existente
                'email' => 'reymillenium@gmail.com',
                
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "email" en el array de errores en la sesión
            ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'El email tecleado ya está en uso. Use otro por favor']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo email puede permanecer igual al actualizar el User
         *
         * @test
         */
        public function it_checks_the_email_can_stay_the_same_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Defino el email inicial del User
            $initial_email = 'reymillenium@gmail.com';
            
            // Creamos un User aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create([
                'email' => $initial_email
            ]);
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                
                // Email igual al ya existente en el User a actualizar
                'email' => 'reymillenium@gmail.com',
                
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia users_page, pues no encontró errores
            ->assertRedirect("/users_page/");
            
            //Verificamos que los nuevos datos existan en la BD (pues sí se actualizó) y que el email sea el mismo inicial
            $this->assertDatabaseHas('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $initial_email,
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo email es demasiado largo
         *
         * @test
         */
        public function it_checks_the_email_is_not_too_long_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                
                // * Dato: (Los emails realmente pueden ser de hasta 64 caracteres de username + 1 caracter de la arroba + 255 caracteres del dominio = 320 caracteres en total)**
                
                // 64 en username, 64 en el dominio contando la arroba y 64 en la extension contando el punto (192 caracteres en total) aunque no es por sumatoria, sino por secciones
                // Este cumple justo con la regla email de laravel!!!
                // 'email' => 'reymillenium1reymillenium1reymilleooooooooooooooooooooooooooooop@GranDominioGranDominioGranDominioGranDominioGranDominioGranDomi.commmmmmmmmmmmmmmmmmmmmmmmmmmdfsdgfffgvvvvvvvvvvvvllllllllllppi',
                
                // ** Email demasiado largo según la regla de largo máximo del programador (hasta 48 caracteres) (Mayor que 48 en este caso, pues tiene 49) **
                'email' => 'reymilleniumooooooooooooooooooooooooooP@gmail.com',
                
                'password' => '1234567',
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "email" en el array de errores en la sesión
            ->assertSessionHasErrors(['email'])
                // Podemos incluso ser más explícitos:
                ->assertSessionHasErrors(['email' => 'El email solo puede tener hasta 48 caracteres']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo password es no es obligatorio
         *
         * @test
         */
        public function it_checks_the_password_is_not_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            // $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            $oldPassword = '123456';
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create([
                'password' => bcrypt($oldPassword)
            ]);
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                
                // Password faltante
                'password' => '',
                'password_confirmation' => '',
                
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia users_page, pues no se encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect(route('users.users_page'));
            
            // Verificamos que los nuevos datos ahora existan en la BD, excepto el password, que debe ser aun el viejo
            $this->assertCredentials([
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    
                    // Verificamos que aun permanezca la vieja contraseña (pues se dejó el campo password nulo en el formulario)
                    'password' => $oldPassword,
                    
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo password no es demasiado pequeño
         *
         * @test
         */
        public function it_checks_the_password_is_not_too_small_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                
                // Password demasiado pequeño
                'password' => '123',
                
                'password_confirmation' => '1234567',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "password" en el array de errores en la sesión
            ->assertSessionHasErrors(['password' => 'El password debe poseer un mínimo de 6 caracteres']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo password no es demasiado grande
         *
         * @test
         */
        public function it_checks_the_password_is_not_too_big_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                
                // Password demasiado grande (más de 255 caracteres) pues posee 256 en este caso (el password confirmation es idéntico en este caso, para no mezclar los errores)
                'password' => 'aaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeee123456',
                'password_confirmation' => 'aaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeeeaaaaaaaaaabbbbbbbbbbccccccccccddddddddddeeeeeeeeee123456',
                
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "password" en el array de errores en la sesión
            ->assertSessionHasErrors(['password' => 'El password solo puede tener hasta 255 caracteres']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo password de confirmación es obligatorio
         *
         * @test
         */
        // public function it_checks_the_password_confirmation_is_required_when_updating_a_user()
        // {
        //     // Permite obtener más detalles al correr las pruebas
        //     $this->withoutExceptionHandling();
        //
        //     // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
        //     factory(Profession::class)->create();
        //
        //     // Ahora creamos un user aleatorio para luego tratar de actualizarlo
        //     $user = factory(User::class)->create();
        //
        //     // Preparo los datos a enviar por PUT
        //     $data = [
        //         'id' => $user->id,
        //         'profession_id' => '1',
        //         'firstname' => 'Kennedy-Winbleddon',
        //         'secondname' => 'Esperanto',
        //         'lastname' => 'García Ramos',
        //         'email' => 'reymilleniumP@gmail.com',
        //         'password' => '123456',
        //
        //         // Password confirmation faltante
        //         'password_confirmation' => '',
        //
        //         'phone' => '7864582150',
        //         'gender' => 'male',
        //         'is_active' => '1',
        //         'kind' => 'administrator'
        //     ];
        //
        //     // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
        //     $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
        //     // $this->put("/update_user_script/{$user->id}", $data) //    OK
        //
        //     // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
        //     // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
        //     ->assertRedirect("/edit_user_page/{$user->id}")// OK
        //     //.. y que exista un campo "password_confirmation" en el array de errores en la sesión
        //     ->assertSessionHasErrors(['password_confirmation'])
        //         // Podemos incluso ser más explícitos:
        //         ->assertSessionHasErrors(['password_confirmation' => 'Debe confirmar su contraseña']);
        //
        //     //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
        //     $this->assertDatabaseMissing('users', [
        //             'profession_id' => $data['profession_id'],
        //             'firstname' => $data['firstname'],
        //             'secondname' => $data['secondname'],
        //             'lastname' => $data['lastname'],
        //             'email' => $data['email'],
        //             'password' => bcrypt($data['password']),
        //             'phone' => $data['phone'],
        //             'gender' => $data['gender'],
        //             'is_active' => $data['is_active'],
        //             'kind' => $data['kind']
        //         ]
        //
        //     );
        //
        // }
        
        /**
         * Comprueba si los dos passwords son iguales
         *
         * @test
         */
        public function it_checks_the_passwords_must_be_equals_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                
                'password' => '123456',
                // La contraseña de confirmación no es igual a la contraseña
                'password_confirmation' => '12345678',
                
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "password_confirmation" en el array de errores en la sesión
            ->assertSessionHasErrors(['password_confirmation' => 'Las contraseñas deben coincidir']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo phone es obligatorio
         *
         * @test
         */
        public function it_checks_the_phone_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Creo un Model factory para insertar un user con un email específico
            factory(User::class)->create([
                'email' => 'reymillenium@gmail.com',
            ]);
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                
                // Falta el teléfono
                'phone' => '',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "phone" en el array de errores en la sesión
            ->assertSessionHasErrors(['phone' => 'Debe teclear su teléfono']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo phone es válido
         *
         * @test
         */
        public function it_checks_the_phone_is_valid_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                
                // Teléfono inválido
                'phone' => '786AMD21kk',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "phone" en el array de errores en la sesión
            ->assertSessionHasErrors(['phone' => 'El teléfono solo puede estar formado por números']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        /**
         * Comprueba si el campo phone es no es muy pequeño o muy grande
         *
         * @test
         */
        public function it_checks_the_phone_is_not_too_small_or_too_big_when_updating_a_user()
        {
            
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            # *** Phone muy pequeño
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                
                // Teléfono muy pequeño (menor que 6 dígitos)
                'phone' => '123',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "phone" en el array de errores en la sesión
            ->assertSessionHasErrors(['phone' => 'El teléfono debe poseer de 6 a 21 dígitos']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
            # *** Phone muy grande
            
            // Preparo los datos a enviar por PUT
            $data2 = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                
                // Teléfono muy grande (mayor que 21 dígitos), pues tiene 22 en este caso
                'phone' => '1234567890123456789012',
                
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data2)// OK
            // $this->put("/update_user_script/{$user->id}", $data2) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "phone" en el array de errores en la sesión
            ->assertSessionHasErrors(['phone' => 'El teléfono debe poseer de 6 a 21 dígitos']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data2['profession_id'],
                    'firstname' => $data2['firstname'],
                    'secondname' => $data2['secondname'],
                    'lastname' => $data2['lastname'],
                    'email' => $data2['email'],
                    'password' => bcrypt($data2['password']),
                    'phone' => $data2['phone'],
                    'gender' => $data2['gender'],
                    'is_active' => $data2['is_active'],
                    'kind' => $data2['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo gender es obligatorio
         *
         * @test
         */
        public function it_checks_the_gender_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'phone' => '7864582150',
                
                // Falta el sexo (género)
                'gender' => '',
                
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "gender" en el array de errores en la sesión
            ->assertSessionHasErrors(['gender' => 'Debe escoger su sexo (género)']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo kind es obligatorio
         *
         * @test
         */
        public function it_checks_the_kind_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                'profession_id' => '1',
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                
                // Falta el kind
                'kind' => ''
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "kind" en el array de errores en la sesión
            ->assertSessionHasErrors(['kind' => 'Debe escoger sus privilegios en el sistema']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        /**
         * Comprueba si el campo profession_id es obligatorio
         *
         * @test
         */
        public function it_checks_the_profession_id_is_required_when_updating_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un user aleatorio para luego tratar de actualizarlo
            $user = factory(User::class)->create();
            
            // Preparo los datos a enviar por PUT
            $data = [
                'id' => $user->id,
                
                // Falta el profession_id
                'profession_id' => '',
                
                'firstname' => 'Kennedy-Winbleddon',
                'secondname' => 'Esperanto',
                'lastname' => 'García Ramos',
                'email' => 'reymilleniumP@gmail.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'phone' => '7864582150',
                'gender' => 'male',
                'is_active' => '1',
                'kind' => 'administrator'
            ];
            
            // Ejecuto la ruta put, enviando los datos ya preparados y luego verifico que se redirija hacia edit_user_page
            $this->from(route('users.edit_user_page', ['user' => $user->id]))->put("/update_user_script/{$user->id}", $data)// OK
            // $this->put("/update_user_script/{$user->id}", $data) //    OK
            
            // Luego verifico que se redirija de regreso hacia edit_user_page, pues encontró errores
            // ->assertRedirect(route('users.edit_user_page', ['id' => $user->id])) // OK
            ->assertRedirect("/edit_user_page/{$user->id}")// OK
            //.. y que exista un campo "profession_id" en el array de errores en la sesión
            ->assertSessionHasErrors(['profession_id' => 'Debe escoger su profesión']);
            
            //Verificamos que los nuevos datos no existan en la BD (pues no se actualizó)
            $this->assertDatabaseMissing('users', [
                    'profession_id' => $data['profession_id'],
                    'firstname' => $data['firstname'],
                    'secondname' => $data['secondname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'is_active' => $data['is_active'],
                    'kind' => $data['kind']
                ]
            
            );
            
        }
        
        
        # * -------------------------------------------------------------------------------------------------- *
        # *                                                                                                    *
        # *               *** Tests de Reglas de Validación DELETE: users.delete_user_script ***               *
        # *                                                                                                    *
        # * -------------------------------------------------------------------------------------------------- *
        
        
        /**
         * Comprueba que se pueda borrar bien un User
         *
         * @test
         */
        public function it_deletes_a_user()
        {
            // Permite obtener más detalles al correr las pruebas
            $this->withoutExceptionHandling();
            
            // Primero creo una profesión, para que no tener problemas con el campo profession_id de la tabla users
            factory(Profession::class)->create();
            
            // Ahora creamos un User aleatorio para luego tratar de borrarlo
            $user = factory(User::class)->create();
            
            // Mandamos a borrar al User aleatorio creado
            $this->from(route('users.users_page'))->delete("/delete_user_script/{$user->id}")
                // Verificamos que redireccionamos hacia users_page pues no encontró errores
                ->assertRedirect(route('users.users_page'));
            
            // Verificamos que no haya un User en la BD con un id igual al creado inicialmente (pues lo acabamos de borrar)
            $this->assertDatabaseMissing('users', [
                'id' => $user->id
            ]);
            
            // Verificamos que no haya Users en la BD (el único que había luego se borró)
            $this->assertSame(0, User::count());
        }
        
        
    }