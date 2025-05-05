<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Tienda extends Model
{

    use HasFactory,Notifiable,HasApiTokens;


    protected $fillable = ['nombre_tienda','logo_tienda','descripcion','direccion_web','numero_telefonico','direccion','categoria_id'];

    public function administrador(){

        return $this->hasOne(Administrador::class,'tienda_id');
    }

    public function productos(){

        return $this->hasMany(Producto::class,'tienda_id');

    }

    public function categoria(){

        return $this->belongsTo(Categoria::class,'categoria_id');

    }





    
}
