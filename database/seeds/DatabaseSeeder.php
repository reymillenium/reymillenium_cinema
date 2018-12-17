<?php
    
    use Illuminate\Database\Seeder;
    
    class DatabaseSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            // dd(ProfessionsTableSeeder::class);
            // dd("ProfessionsTableSeeder");
            
            // Elimino el contenido de las tablas antes de insertar los datos nuevamente en ellas
            $this->truncateTables([
                'users',
                'professions'
            ]);
            
            // Ejecuto el seeder ProfessionsTableSeeder
            $this->call(ProfessionsTableSeeder::class);
            
            # Es lo mismo que la línea anterior, aunque de la otra forma el editor sí me va a avisar si tecleo mal el nombre de la clase
            // $this->call('ProfessionsTableSeeder');
    
            // Ejecuto el seeder UsersTableSeeder
            $this->call(UsersTableSeeder::class);
        }
        
        protected function truncateTables(array $tables)
        {
            // Desactivo la revisión de las llaves foráneas en la BD (permite borrar valores de un campo que sea llave foránea en otra tabla)
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            
            foreach ($tables as $table) {
                # Elimino todas las profesiones de la BD antes de crearlas nuevamente (vaciar la tabla sin eliminarla)
                // No va a funcionar si la tabla posee un campo que es una llave foránea en otra tabla (profession_id con user_profession_id en la tabla users)
                // ** Para eso justamente es que antes se desactiva la revisión de las llaves foráneas en la BD
                DB::table($table)->truncate();
            }
            
            //Activo nuevamente la revisión de las llaves foráneas en la BD
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
