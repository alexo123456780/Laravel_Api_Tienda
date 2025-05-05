<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    use HasFactory,Notifiable,HasApiTokens;

    protected $fillable = ['nombre_administrador','apellido_paterno','apellido_materno','numero_telefonico','perfil','email','password','tienda_id'];


    public function tienda(){

        return $this->belongsTo(Tienda::class,'tienda_id');

    }


    
}
