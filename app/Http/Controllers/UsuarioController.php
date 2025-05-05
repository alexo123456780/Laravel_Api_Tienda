<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{


    public function registroUsuario(Request $request){

        try{

            $datos_validados = $request->validate([

                'nombre_usuario' => 'required|string|max:255',
                'apellido_paterno' => 'string|nullable|max:255',
                'apellido_materno' => 'string|nullable|max:255',
                'numero_telefonico' => 'required|string|max:10',
                'direccion' => 'required|string',
                'perfil_usuario' => 'image|mimes:png,jgp,jpeg',
                'email' => 'required|string|email',
                'password' => 'required|string|min:4|max:20',
            ]);


            if($request->hasFile('perfil_usuario')){

                $perfil_usuario = $request->file('perfil_usuario');

                $ruta_guardada = time().'.'.$perfil_usuario->getClientOriginalExtension();

                $perfil_usuario->storeAs('perfil_usuarios',$ruta_guardada,'public');

                $datos_validados['perfil_usuario'] = 'perfil_usuarios/'.$ruta_guardada;
            }

            $datos_validados['password'] = Hash::make($datos_validados['password']);

            $usuario_nuevo = Usuario::create($datos_validados);

            return response()->json([

                'status' => true,
                'message' => 'Registro exitoso',
                'data' => $usuario_nuevo,
                'code' => 201
            ],201);


        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en los campos solicitados',
                'warning' => $e->errors(),
                'code' => 400
            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }

    }





    
}
