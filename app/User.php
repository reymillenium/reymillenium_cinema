<?php
    
    namespace Cinema;
    
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    
    class User extends Authenticatable
    {
        use Notifiable;
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'profession_id',
            'firstname',
            'secondname',
            'lastname',
            'email',
            'password',
            'phone',
            'gender',
            'is_active',
            'kind',
        ];
        
        protected $casts = [
            'is_active' => 'boolean'
        ];
        
        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
            'password', 'remember_token',
        ];
        
        public function profession()
        {
            // Especifico que un User pertenece a una Profession
            // return $this->belongsTo(Profession::class, 'profession_id');
            return $this->belongsTo(Profession::class, 'profession_id', 'id');
        }
        
        public static function findByEmail($user_email)
        {
            return static::where(compact('$user_email'))->first();
        }
        
        public function isAdmin()
        {
            // return $this->email == 'reymillenium@gmail.com';
            return strtolower($this->kind) == 'administrator';
        }
        
        
    }
