<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semaforo extends Model
{
    use HasFactory;
    protected $fillable = ["descripcion", "archivo", "fecha_registro"];

    protected $appends = ["file_path"];

    public function getFilePathAttribute()
    {
        if ($this->archivo && trim($this->archivo) != "") {
            return asset('files/' . $this->archivo);
        }
        return asset('files/default.png');
    }
}
