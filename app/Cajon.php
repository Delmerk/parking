<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajon extends Model
{
    use HasFactory;
    // Especificar los campos masivos que podemos almacenar y el nombre de nuestra tabla
    protected $table = 'cajones';
    protected $fillable = ['description', 'tipo_id', 'estatus']; //no importa el orden

    // relaciones entre cajones y tipos
}
