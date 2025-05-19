<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Venta extends Model
{

    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['usuario_id','tienda_id','precio_final'];


    public function usuario(){

        return $this->belongsTo(Usuario::class,'usuario_id');
    }

    public function tienda(){

        return $this->belongsTo(Tienda::class,'tienda_id');

    }

    public function detallesVenta(){

        return $this->hasMany(DetalleVenta::class);

    }








    
}
