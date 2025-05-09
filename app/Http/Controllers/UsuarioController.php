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


    public function verUsuarios(){

        try{

            $usuarios = Usuario::all();

            if($usuarios->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay usuarios registrados',
                    'code' => 200
                ],200);
            }

            return response()->json([

                'status' => true,
                'message' => 'Usuarios obtenidos correctamente',
                'data' => $usuarios,
                'code' => 200
            ],200);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ]);
        }

    }


    public function actualizarInfo($id_usuario,Request $request){

        try{

            $informacion_validada = $request->validate([

                'nombre_usuario' => 'string|max:255',
                'apellido_paterno' => 'string|max:255',
                'apellido_materno' => 'string|max:255',
                'numero_telefonico' => 'string|max:10',
                'direccion' => 'string|max:255',
                'perfil_usuario ' => 'image|mimes:png,jpg,jpeg',
                'email' => 'string|email'
            ]);


            $usuario = Usuario::find($id_usuario);

            if(!$usuario){

                return response()->json([

                    'status' => false,
                    'message ' => 'El usuario no existe o no se ha registrado aun',
                    'code' => 404
                ],404);

            }

            if($request->hasFile('perfil_usuario')){

                $imagen_usuario = $request->file('perfil_usuario');

                $ruta_imagen = time().'.'.$imagen_usuario->getClientOriginalExtension();

                $imagen_usuario->storeAs('imagen_usuario_actualizada',$ruta_imagen,'public');

                $informacion_validada['perfil_usuario'] = 'imagen_usuario_actualizada/'.$ruta_imagen;

            }

            $usuario->update($informacion_validada);

            return response()->json([

                'status' => true,
                'message' => 'Informacion actualizada correctamente',
                'data' => $usuario,
                'code' => 200
            ],200);


        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validaciones en los campos solicitados',
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


    public function actualizarPassword(Request $request, $id_usuario){

        try{

            $password_validado = $request->validate([

                'password' => 'required|string|min:4|max:20'
            ]);

            $usuario = Usuario::find($id_usuario);

            if(!$usuario){

                return response()->json([

                    'status' => false,
                    'message' => 'El usuario no  existe o no se ha registrado aun',
                    'code' => 404
                ],404);

            }


            if(Hash::check($password_validado['password'],$usuario->password)){

                return response()->json([

                    'status' => false,
                    'message' => 'Ingresa un password diferente o no igual a tu password anterior',
                    'code' => 400

                ],400);
            }

            $password_validado['password'] = Hash::make($password_validado['password']);

            $usuario->update($password_validado);

            return response()->json([

                'status' => true,
                'message' => 'Password actualizado correctamente',
                'data' => $usuario,
                'code' => 200
            ],200);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en el campo del password',
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
