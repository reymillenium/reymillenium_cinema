<?php
    
    use Faker\Generator as Faker;
    use Cinema\Profession;
    
    /*
    |--------------------------------------------------------------------------
    | Model Factories
    |--------------------------------------------------------------------------
    |
    | This directory should contain each of the model factory definitions for
    | your application. Factories provide a convenient way to generate new
    | model instances for testing / seeding your application's database.
    |
    */
    
    $factory->define(Cinema\User::class, function (Faker $faker) {
        
        $kinds = ['administrator', 'operator', 'guest'];
        $gender = $faker->randomElements(['male', 'female'])[0];
        $professions_amount = Profession::count();
        
        // dd($gender[0]);
        
        return [
            'profession_id' => rand(1, $professions_amount),
            'firstname' => $faker->firstName($gender),
            // 'secondname' => $faker->name,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            // 'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
            'phone' => $faker->phoneNumber,
            'gender' => $gender,
            'is_active' => 1,
            'kind' => $kinds[rand(0, sizeof($kinds) - 1)],
            // 'website' => $faker->url,
        ];
    });
