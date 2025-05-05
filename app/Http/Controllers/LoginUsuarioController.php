<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUsuarioController extends Controller
{

    public function loginUsuario(Request $request){

        try{

            $credenciales_validadas = $request->validate([

                'email' => 'required|string|email',
                'password' => 'required|string|min:4|max:20'
            ]);

            if(Auth::guard('usuarios')->attempt($credenciales_validadas)){

                $usuario_encontrado = Auth::guard('usuarios')->user();

                $token_creado = $usuario_encontrado->createToken('authToken')->plainTextToken;

                return response()->json([

                    'status' => true,
                    'message' => 'Logeado exitosamente',
                    'data' => $usuario_encontrado,
                    'token' => $token_creado,
                    'code' => 200
                ],200);

            }else{

                return response()->json([

                    'status' => false,
                    'message' => 'Credenciales invalidas crack',
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
