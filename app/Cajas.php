<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajas extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    // podemos llenar de forma masiva
    protected $fillable = ['monto', 'tipo', 'concepto', 'comprobante', 'user_id'];
}
