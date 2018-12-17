<?php
    
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    use Cinema\Profession;
    
    class ProfessionsTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        
        
        public function run()
        {
            // Trabajando directamente con la BD con SQL, sin usar el Constructor de Consultas SQL de Laravel
            // DB::insert('INSERT INTO professions (name) VALUES ("Junior Back-end Developer")');
            
            // Más seguro que en la línea anterior. Uso un marcador para indicar la posición de un parámetro dinámico.
            //Previene de inyecciones. Lo paso como 2do argumento
            // DB::insert('INSERT INTO professions (name) VALUES (?)', ['Junior Back-end Developer']);
            
            // Cuando tenemos muchos parámetros. Usamos un parámetro de sustitución con nombre
            // DB::insert('INSERT INTO professions (profession_name) VALUES (:profession_name)', [
            //     'name' => 'Junior Back-end Developer'
            // ]);
            
            # ----------------------------------------------------------------------------------------------------------
            
            // Usando el Constructor de Consultas SQL de Laravel (mucho más sencillo)
            // Uso un array asociativo para representar las columnas para insertar los datos en la tabla professions
            // DB::table('professions')->insert([
            //     'name' => 'Junior Back-end Developer'
            // ]);
            //
            // DB::table('professions')->insert([
            //     'name' => 'Junior Front-end Developer'
            // ]);
            //
            // DB::table('professions')->insert([
            //     'name' => 'Senior Back-end Developer'
            // ]);
            //
            // DB::table('professions')->insert([
            //     'name' => 'Senior Front-end Developer'
            // ]);
            //
            // DB::table('professions')->insert([
            //     'name' => 'Web Developer'
            // ]);
            
            # ----------------------------------------------------------------------------------------------------------
            
            // Usando Eloquent ORM. Muchísimo más sencillo aun!!! Usamos el Modelo Profession y llamamos a la
            // función create, pasándole un array asociativo con los datos que queremos insertar
            Profession::create([
                'name' => 'Junior Back-end Developer'
            ]);
            
            Profession::create([
                'name' => 'Junior Front-end Developer'
            ]);
            
            Profession::create([
                'name' => 'Senior Back-end Developer'
            ]);
            
            Profession::create([
                'name' => 'Senior Front-end Developer'
            ]);
            
            Profession::create([
                'name' => 'Web Developer'
            ]);
            
            // Usando Model Factory creo 20 empleos creíbles de manera aleatoria
            factory(Profession::class, 20)->create();
            
            
        }
    }
