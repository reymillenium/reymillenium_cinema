<?php
    
    namespace Cinema;
    
    use Illuminate\Database\Eloquent\Model;
    
    class Profession extends Model
    {
        
        // Reescribimos la propiedad table, por si queremos definir un nombre de tabla diferente para el modelo.
        protected $table = 'professions';
        
        // Para especificar que no quiero usar el timestamp (campos created_at y updated_at) en mi tabla
        // public $timestamps = false;
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'profession_name'
        ];
        
        public function users()
        {
            // return $this->hasMany(User::class, 'user_profession_id');
            return $this->hasMany(User::class, 'user_profession_id', 'id');
        }
        
        
    }