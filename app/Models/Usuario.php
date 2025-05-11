<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{

    use HasFactory,Notifiable,HasApiTokens;


    protected $fillable = ['nombre_usuario','apellido_paterno','apellido_materno','numero_telefonico','direccion','perfil_usuario','email','password'];


    public function productos(){

        return $this->belongsToMany(Producto::class,'carritos','usuario_id','producto_id')->withPivot('id');

    }


    
}
