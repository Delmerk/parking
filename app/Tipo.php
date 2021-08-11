<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipos';
    // Insercción-Actualizacion masiva de info. Es obligatorio especificar los campos que se van a permitir manipular de forma masiva; 
    protected $fillable = ['description'];
    // protected $rules = ['description' => 'required|min:4'];

}
