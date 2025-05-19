<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CategoriaGlobal extends Model
{

    use HasFactory, Notifiable , HasApiTokens;

    protected $fillable = ['nombre_categoria','imagen_categoria','descripcion_categoria'];


    public function productos(){

        return $this->hasMany(Producto::class);

    }




    
}
