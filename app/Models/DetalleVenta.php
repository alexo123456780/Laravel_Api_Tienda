<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DetalleVenta extends Model
{

    use HasFactory , Notifiable, HasApiTokens;

    protected $fillable = ['venta_id','producto_id','cantidad','precio_unitario'];


    public function venta(){

        return $this->belongsTo(Venta::class,'venta_id');

    }

    public function producto(){

        return $this->belongsTo(Producto::class,'producto_id');

    }









    
}
