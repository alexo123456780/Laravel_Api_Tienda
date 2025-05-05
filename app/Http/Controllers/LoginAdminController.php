<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{

    public function loginAdmin(Request $request){


        try{

            $credenciales_validacion = $request->validate([

                'email' => 'required|string|email',
                'password' => 'required|string|min:4|max:20'

            ],
            [
                'email.required' => 'El email es obligatorio',
                'email.string' => 'El email debe ser una direccion de correo',
                'email.email' => 'El email debe ser un correo',
                'password.required' => 'El password es obligatorio',
                'password.string' => 'El password debe ser una cadena de caracteres',
                'password.min' => 'El minimo de caracteres para el password es de 4',
                'password.max' => 'El maximo de caracteres para el password es de 20'

            ]
        
        );

        if(Auth::attempt($credenciales_validacion)){

            $administrador = Auth::user();

            $token = $administrador->createToken('authToken')->plainTextToken;

            return response()->json([

                'status' => true,
                'message' => 'Login exitoso',
                'data' => $administrador,
                'token' => $token,
                'code' => 200
            ],200);
        }else{

            return response()->json([

                'status' => false,
                'message' => 'Credenciales invalidas intente de nuevo por favor',
                'code' => 400
            ],400);

        }


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
