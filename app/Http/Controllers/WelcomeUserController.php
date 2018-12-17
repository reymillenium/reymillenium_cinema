<?php
    
    namespace Cinema\Http\Controllers;
    
    use Cinema\User;
    use Cinema\Profession;
    use Illuminate\Support\Facades\DB;

    class WelcomeUserController extends Controller
    {
        public function __invoke($id = 1, $nickName = null)
        {
            // $user = User::where('id', $id)->get();
    
            // NO Funciona
            // $user = DB::table('users')->where('id', $id)->first();
            
            // Funciona OK
            $user = User::find($id);
            
            // dd($user);
            
            $profession = $user->profession;
            
            // dd($profession);
            
            
            if ($nickName) {
                
                return 'My name is ' . $user->firstname . ', I work as a ' . $profession->name . ' and mi nickname is ' . $nickName;
                
            } else {
                
                return 'My name is ' . $user->firstname . ', I work as a ' . $profession->name . " and I don't have a nickname";
                
            }
        }
    }
