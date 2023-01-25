<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionModulo extends Model
{
    use HasFactory;

    protected $fillable = [
        "modulo", "editar", "eliminar",
    ];
}
