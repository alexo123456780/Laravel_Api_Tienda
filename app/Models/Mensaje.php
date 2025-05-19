<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mensaje extends Model
{
    use HasFactory,Notifiable,HasApiTokens;

    protected $fillable = ['usuario_id','tienda_id','asunto_id','mensaje'];
    

    public function usuario(){

        return $this->belongsTo(Usuario::class,'usuario_id');

    }

    public function tienda(){

        return $this->belongsTo(Tienda::class,'tienda_id');

    }

    public function asunto(){

        return $this->belongsTo(Asunto::class,'asunto_id');

    }




    
}
