<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function registroAdmin(Request $request){


        try{

            $informacion_validada = $request->validate([

                'nombre_administrador'  => 'required|string|max:255',
                'apellido_paterno' => 'string|nullable',
                'apellido_materno' => 'string|nullable',
                'numero_telefonico' => 'required|string|max:10',
                'perfil' => 'nullable|image|mimes:png,jpg,jpeg',
                'email' => 'required|string|email',
                'password' => 'required|string|min:4|max:20',
                'tienda_id' => 'required|numeric|exists:tiendas,id'
            ]);


            if($request->hasFile('perfil')){

                $imagen_admin = $request->file('perfil');

                $ruta_guardada = time().'.'.$imagen_admin->getClientOriginalExtension();

                $imagen_admin->storeAs('imagenes_admin',$ruta_guardada,'public');

                $informacion_validada['perfil'] = 'imagenes_admin/'.$ruta_guardada;


            }


            $validacion_repetidos = Administrador::where('tienda_id',$informacion_validada['tienda_id'])->exists();

            if($validacion_repetidos){

                return response()->json([

                    'status' => false,
                    'message' => 'Lo sentimos pero esta tienda ya le pertenece a un administrador',
                    'code' => 400
                ],400);
            }

            $informacion_validada['password'] = Hash::make($informacion_validada['password']);

            $administrador_nuevo = Administrador::create($informacion_validada);

            return response()->json([

                'status' => true,
                'message' => 'Tu cuenta ha sido registrada correctamente',
                'data' => $administrador_nuevo,
                'code' => 201,
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


    public function infoAdmin($id_admin){

        try{

            $administrador = Administrador::find($id_admin);

            if(!$administrador){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del admin',
                    'code' => 404
                ],404);
            }

            $administrador->perfil = asset('storage/'.$administrador->perfil);


            return response()->json([

                'status' => true,
                'message' => 'Info del admin obtenida correctamente',
                'data' => $administrador,
                'code' => 200
            ],200);

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
