<?php
    
    /*
      |--------------------------------------------------------------------------
      | Web Routes
      |--------------------------------------------------------------------------
      |
      | Here is where you can register web routes for your application. These
      | routes are loaded by the RouteServiceProvider within a group which
      | contains the "web" middleware group. Now create something great!
      |
     */
    
    use Cinema\User;
    use Illuminate\Support\Facades\Route;
    
    # Muestra la vista de Bienvenida de forma directa
    # Muestra la página con el listado de todos los users, a través del controlador UserController
    Route::get('/', 'UserController@show_users_page')->name('welcome_page');
    
    #-------------------------------------- * Users Module * -----------------------------------------------------------
    
    # Muestra la página con el listado de todos los Users
    Route::get('/users_page', 'UserController@show_users_page')->name('users.users_page');
    
    # Muestra la página con los detalles de un User, dado su ID
    Route::get('/user_details_page/{user}', 'UserController@show_user_details_page')->where('user', '[0-9]+')->name('users.user_details_page');
    // Route::get('/user_details_page/{id}', 'UserController@show_user_details_page')->where('id', '/^(?:[5-9]|(?:[1-9][0-9])|(?:[1-4][0-9][0-9])|(?:' . 100 . '))$/')->name('users.user_details_page');
    
    # Muestra la página con el formulario para la creación de un nuevo User
    Route::get('/new_user_page', 'UserController@show_new_user_page')->name('users.new_user_page');
    
    // Recibe los datos del formulario de nuevo User, para validarlos y luego almacenarlos en la BD
    Route::post('/create_user_script', 'UserController@goto_create_user_script')->name('users.create_user_script');
    
    # Muestra la página con el formulario para la actualización de un User, dado su ID
    Route::get('/edit_user_page/{user}', 'UserController@show_edit_user_page')->name('users.edit_user_page');
    
    # Recibe los datos del formulario de actualización del User, para validarlos y luego reescribirlos en la BD
    // Route::post('/update_user_script/', 'UserController@goto_update_user_script')->name('users.update_user_script');
    Route::put('/update_user_script/{user}', 'UserController@goto_update_user_script')->name('users.update_user_script');
    
    // Borra un User en la BD a partir de su ID
    Route::delete('/delete_user_script/{user}', 'UserController@goto_delete_user_script')->name('users.delete_user_script');
    
    #-------------------------------------- * / Users Module * ---------------------------------------------------------
    #
    #
    #
    #
    #
    #------------------------------------------ * Example * ------------------------------------------------------------
    
    #  *** Example Only *** : Muestra la vista examples a través del controlador UserController
    Route::get('/examples_page', 'UserController@users.show_examples_page')->name('users.examples_page');
    
    #----------------------------------------- * / Example * ------------------------------------------------------------
    
    
    # Rutas con varios parámetros
    Route::get('/information/{id}/{nickName?}', 'WelcomeUserController');
    