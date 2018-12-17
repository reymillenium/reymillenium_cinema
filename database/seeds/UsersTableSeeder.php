<?php
    
    use Cinema\Profession;
    use Cinema\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    
    class UsersTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            // Sin usar el Constructor de Consultas SQL de Laravel (no es seguro)
            // $profession_query = DB::select('SELECT id FROM professions WHERE name = "Senior Back-end Developer"');
            
            // Usando un marcador. Usa el driver PDO
            // $profession_query = DB::select('SELECT id FROM professions WHERE name = ?', ['Senior Back-end Developer']);
            
            // Usando un parámetro de sustitución con nombre
            // $profession_query = DB::select('SELECT profession_id FROM professions WHERE profession_name = :name', [
            //     'name' => 'Senior Back-end Developer'
            // ]);
            
            // $profession_id = $profession_query[0]->id;
            // dd($profession_query);
            // dd($profession_id);
            
            // Usando el Constructor de Consultas SQL de Laravel. Trabajamos con una colección de profesiones
            // $profession_query = DB::table('professions')->select('id')->take(1)->get();
            
            // $profession_id = $profession_query->first()->id;
            // dd($profession_query);
            // dd($profession_id);
            
            // Usando el Constructor de Consultas SQL de Laravel
            // Cuando trabajamos con una sola profesión
            // $profession_query_object = DB::table('professions')->select('id')->first();
            // $profession_id = $profession_query_object->profession_id;
            
            // dd($profession_query_object);
            // dd($user_profession_id);
            
            // Usando el Constructor de Consultas SQL de Laravel
            // Usando condicionales
            // $profession_query_object = DB::table('professions')->select('id')->where('profession_name', '=', 'Senior Back-end Developer')->first();
            // $profession_id = $profession_query_object->id;
            
            // Usando el Constructor de Consultas SQL de Laravel
            // Seleccionando más de un campo
            // $profession_query_object = DB::table('professions')->select('id', 'name')->where('name', '=', 'Senior Back-end Developer')->first();
            // $profession_id = $profession_query_object->id;
            
            // Usando el Constructor de Consultas SQL de Laravel
            // Omitiendo el método select (Laravel va a seleccionar todas las columnas)
            // $profession_query_object = DB::table('professions')->where('name', '=', 'Senior Back-end Developer')->first();
            // $user_profession_id = $profession_query_object->id;
            
            // Usando el Constructor de Consultas SQL de Laravel
            // Omitiendo el método select (Laravel va a seleccionar todas las columnas) y omitiendo el operador. Por dafault toma el =
            // $profession_query_object = DB::table('professions')->where('name', 'Senior Back-end Developer')->first();
            // $profession_id = $profession_query_object->id;
            
            // Usando el Constructor de Consultas SQL de Laravel. No hay que preocuparse por la inyección SQL (Pero con DB::row sí)
            // Omitiendo el método select y el operador. Pasando un array asociativo
            // $profession_query_object = DB::table('professions')->where(['name' => 'Senior Back-end Developer'])->first();
            // $profession_id = $profession_query_object->id;
            
            // dd($profession_query_object);
            // dd($profession_id);
            
            // Usando el Constructor de Consultas SQL de Laravel. No hay que preocuparse por la inyección SQL (Pero con DB::row sí)
            // Omitiendo el método select y el operador. Pasando un array asociativo en líneas distintas
            // $profession_query_object = DB::table('professions')
            //     ->where(['name' => 'Senior Back-end Developer'])
            //     ->first();
            
            // $profession_id = $profession_query_object->id;
            
            // dd($profession_query_object);
            // dd($profession_id);
            
            // Usando el Constructor de Consultas SQL de Laravel. No hay que preocuparse por la inyección SQL (Pero con DB::row sí)
            // Omitiendo el método select y el operador. Pasando un array asociativo y usando value, se haya directamente el valor
            // $profession_id = DB::table('professions')
            // ->where(['profession_name' => 'Senior Back-end Developer'])
            // ->value('id');
            
            // Usando el Constructor de Consultas SQL de Laravel. No hay que preocuparse por la inyección SQL (Pero con DB::row sí)
            // Omitiendo el método select y el operador. Pasando un array asociativo y usando value, se haya directamente el valor
            // De forma más simplificada
            // $profession_id = DB::table('professions')
            //     ->where('profession_name', 'Senior Back-end Developer')
            //     ->value('id');
            
            // Usando el Constructor de Consultas SQL de Laravel. No hay que preocuparse por la inyección SQL (Pero con DB::row sí)
            // Omitiendo el método select y el operador. Pasando un array asociativo y usando value, se haya directamente el valor
            // De forma más simplificada. Usando métodos dinámicos (mágicos)
            // $profession_id = DB::table('professions')
            //     ->whereProfessionName('Senior Back-end Developer')// NO SHIT!!!!!!!
            //     ->value('id');
            
            // Usando el Constructor de Consultas SQL de Laravel. No hay que preocuparse por la inyección SQL (Pero con DB::row sí)
            // Omitiendo el método select y el operador. Pasando un array asociativo y usando value, se haya directamente el valor
            // De forma más simplificada. Usando métodos dinámicos (mágicos). En una sola línea
            // $profession_id = DB::table('professions')->whereProfessionName('Senior Back-end Developer')->value('id'); // NO SHIT!!!!!!!
            
            
            // dd($profession_id);
            
            // Uso un array asociativo para representar las columnas para insertar los datos en la tabla professions
            // DB::table('users')->insert([
            //
            //
            //     // 'profession_id' => '1',
            //     // 'profession_id' => $profession_id,
            //     // Fucking awesome!!!
            //     'profession_id' => DB::table('professions')->whereProfessionName('Junior Back-end Developer')->value('profession_id'),
            //
            //     'firstname' => 'Reinier',
            //     'secondname' => '',
            //     'lastname' => 'Garcia Ramos',
            //
            //     'email' => 'reymillenium@gmail.com',
            //     'password' => bcrypt('123456'),
            //
            //     'phone' => '786482150',
            //     'gender' => 'Male',
            //
            //     'is_active' => '1',
            //     'kind' => 'administrator'
            // ]);
            
            // Obtenemos el profession_id para usarlo luego con Eloquent ORM
            // $profession_id = DB::table('professions')
            //     ->where('name', 'Senior Back-end Developer')
            //     ->value('id');
            
            // Obtenemos el user_profession_id directamente con Eloquent ORM
            $profession_id = Profession::where('name', 'Senior Back-end Developer')->value('id');
            
            // Usando Eloquent ORM. Muchísimo más sencillo aun!!! Usamos el Modelo User y llamamos a la
            // función create, pasándole un array asociativo con los datos que queremos insertar
            // Usuario especial # 1
            User::create([
               
                'profession_id' => $profession_id,
               
                'firstname' => 'Reinier',
                'secondname' => '',
                'lastname' => 'Garcia Ramos',
               
                'email' => 'example1@gmail.com',
                'password' => bcrypt('123456'),
               
                'phone' => '1111111111',
                'gender' => 'male',
               
                'is_active' => '1',
                'kind' => 'administrator'
            ]);
            
            // Creamos el usuario especial # 2
            User::create([
                
                'profession_id' => 3,
                
                'firstname' => 'Pedro',
                'secondname' => 'Picapiedras',
                'lastname' => 'Roca Dura',
                
                'email' => 'pedrito@gmail.com',
                'password' => bcrypt('123456'),
                
                'phone' => '2222222222',
                'gender' => 'male',
                
                'is_active' => '1',
                'kind' => 'guest'
            ]);
            
            // Obtenemos la cantidad de profesiones que existen en la BD con Eloquent ORM
            // $professionsAmount = Profession::count();
            
            // Creamos aleatoriamente 100 usuarios
            // for ($users = 0; $users < 100; $users++) {
            //
            //     User::create([
            //
            //         'user_profession_id' => rand(1, $professionsAmount),
            //
            //         // Para evitar que se repitan los campos, usamos unas funciones que crean strings, letters and numbers al azar
            //         'firstname' => static::create_random_word(12),
            //         'secondname' => static::create_random_word(12),
            //         'lastname' => static::create_random_word(12) . ' ' . static::create_random_word(12),
            //
            //         'email' => static::create_random_string(5) . '@' . static::create_random_string(5) . '.' . static::create_random_string(3),
            //         'password' => bcrypt('123456'),
            //
            //         'phone' => static::create_random_numbers_string(10),
            //         'gender' => static::create_random_gender(),
            //
            //         'is_active' => '1',
            //         'kind' => static::create_random_kind()
            //     ]);
            //
            // }
            
            // Creamos 100 usuarios aleatorios usando Model Factories
            // factory(User::class)->times(100)->create();
            factory(User::class, 200)->create();
            
            
        }
        
        
        
        
        ###############################################
        #    ******  Funciones Utilitarias  ******    #
        ###############################################
        
        function create_random_string($string_length)
        {
            
            $sample_string = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            
            // Determinamos cuántos caracteres hay en la mustra de caracteres
            $sample_string_length = strlen($sample_string);
            
            // Creamos el string aleatorio y lo dejamos en blanco por ahora
            $random_string = '';
            
            // Creamos nuestro string aleatorio
            for ($i = 0; $i < $string_length; $i++) {
                $random_string .= $sample_string[rand(0, $sample_string_length - 1)];
            }
            
            return $random_string;
            
        }
        
        function create_random_word($word_length)
        {
            $sample_letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            
            // Determinamos cuántos números hay en la muestra de números
            $sample_letters_length = strlen($sample_letters);
            
            // Creamos el string aleatorio y lo dejamos en blanco por ahora
            $random_word = '';
            
            // Creamos nuestro string aleatorio
            for ($i = 0; $i < $word_length; $i++) {
                $random_word .= $sample_letters[rand(0, $sample_letters_length - 1)];
            }
            
            return $random_word;
            
        }
        
        function create_random_numbers_string($number_length)
        {
            $sample_numbers = '1234567890';
            
            // Determinamos cuántos números hay en la muestra de números
            $sample_numbers_length = strlen($sample_numbers);
            
            // Creamos el string aleatorio y lo dejamos en blanco por ahora
            $random_number = '';
            
            // Creamos nuestro string aleatorio
            for ($i = 0; $i < $number_length; $i++) {
                $random_number .= $sample_numbers[rand(0, $sample_numbers_length - 1)];
            }
            
            return $random_number;
            
        }
        
        function create_random_gender()
        {
            
            $sample_genders = ['Female', 'Male'];
            
            // Determinamos cuántos números hay en la muestra de números
            $sample_genders_length = count($sample_genders);
            
            // Creamos la variable para el gender aleatorio y lo dejamos en blanco por ahora
            $random_gender = '';
            
            // Creamos nuestro gender aleatorio
            $random_gender .= $sample_genders[rand(0, $sample_genders_length - 1)];
            
            return $random_gender;
            
        }
        
        function create_random_kind()
        {
            
            $sample_kinds = ['administrator', 'operator', 'guest'];
            
            // Determinamos cuántos números hay en la muestra de números
            $sample_kinds_length = count($sample_kinds);
            
            // Creamos la variable para el gender aleatorio y lo dejamos en blanco por ahora
            $random_kind = '';
            
            // Creamos nuestro gender aleatorio
            $random_kind .= $sample_kinds[rand(0, $sample_kinds_length - 1)];
            
            return $random_kind;
            
        }
        
        
    }
