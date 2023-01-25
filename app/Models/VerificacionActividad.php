<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificacionActividad extends Model
{
    use HasFactory;

    protected $fillable = ["gestion", "actividad",];
}
