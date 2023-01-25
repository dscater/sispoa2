<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        "accion",
        "modulo",
        "detalle",
        "user_id",
        "fecha",
        "hora"
    ];

    public static function registrarLog($accion, $modulo, $detalle, $user)
    {
        Log::create([
            "accion" => $accion,
            "modulo" => $modulo,
            "detalle" => $detalle,
            "user_id" => $user->id,
            "fecha" => date("Y-m-d"),
            "hora" => date("H:i:s")
        ]);

        return true;
    }
}
