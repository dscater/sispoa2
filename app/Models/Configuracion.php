<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_sistema', 'alias', 'razon_social', 'ciudad', 'dir',
        'fono', 'web', 'actividad', 'correo', 'logo', 'logo2',
    ];

    protected $appends = ['path_image', 'path_image2'];
    public function getPathImageAttribute()
    {
        return asset('imgs/' . $this->logo);
    }
    public function getPathImage2Attribute()
    {
        return asset('imgs/' . $this->logo2);
    }
}
