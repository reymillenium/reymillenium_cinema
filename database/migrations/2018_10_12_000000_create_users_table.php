<?php
    
    use Cinema\Profession;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    
    class CreateUsersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            
            Schema::create('users', function (Blueprint $table) {
                
                // ** Columnas de la tabla users **
                $table->increments('id', 11);
                $table->integer('profession_id')->unsigned();
                // $table->unsignedInteger('user_profession_id');
                $table->foreign('profession_id')->references('id')->on('professions');
                
                $table->string('firstname', 48);
                $table->string('secondname', 48)->nullable();
                $table->string('lastname', 64);
                $table->string('email', 48)->unique();
                $table->string('password', 255);
                
                
                $table->string('phone', 21)->unique();
                $table->string('gender', 6);
                $table->boolean('is_active');
                $table->string('kind', 24);
                // $table->string('website', 255)->nullable();
                
                // Método helper incluido en Laravel para permitir generar columnas comunes en nuestra aplicación
                $table->rememberToken();
                
                // Método helper conveniente con el cual se van a declarar 2 columnas (created_at y updated_at) que pueden ser nulas
                // $table->timestamps();
                
                $table->timestamp('created_at');
                $table->timestamp('updated_at')->nullable();
                
                $table->string('image')->nullable();
            });
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('users');
        }
    }
