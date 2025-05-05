<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Categoria extends Model
{
    use HasFactory,Notifiable,HasApiTokens;

    protected $fillable = ['nombre','imagen','descripcion'];


    public function productos(){

        return $this->hasMany(Producto::class);

    }

    public function tiendas(){

        return $this->hasMany(Tienda::class);

    }







    
}
