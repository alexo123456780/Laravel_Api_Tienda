<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Producto extends Model
{
    use HasFactory,Notifiable,HasApiTokens;


    protected $fillable = ['nombre_producto','imagen_producto','stock','precio_producto','descripcion_producto','numero_ventas','tienda_id','categoria_id'];

    public function tienda(){

        return $this->belongsTo(Tienda::class,'tienda_id');

    }

    public function categoria(){

        return $this->belongsTo(Categoria::class,'categoria_id');
    }

    public function usuarios(){

        return $this->belongsToMany(Usuario::class,'carritos','usuario_id','producto_id');

    }


    
}
