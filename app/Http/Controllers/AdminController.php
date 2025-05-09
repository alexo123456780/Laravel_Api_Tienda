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

            $administrador = Administrador::with('tienda')->find($id_admin);

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


    public function editarPerfil(Request $request, $id_admin){


        try{

            $datos_validados = $request->validate([

                'nombre_administrador' => 'string|max:255',
                'apellido_paterno' => 'string|max:255',
                'apellido_materno' => 'string|max:255',
                'numero_telefonico' => 'string|max:10',
                'perfil' => 'image|mimes:png,jpg,jpeg',
                'email' => 'string|email'
            ],

            [
                'nombre_administrador.string' => 'El nombre debe ser una cadena de texto',
                'nombre_administrador.max' => 'El maximo de caracteres es de 255',
                'apellido_paterno.string' => 'El apellido paterno debe ser una cadena de texto',
                'apellido_materno.string' => 'El apellido materno debe ser una cadena de texto',
                'numero_telefonico.max' => 'El numero de telefono es de maximo 10 caracteres',
                'perfil.image' => 'El perfil debe ser una imagen',
                'perfil.mimes' => 'El perfil debe ser en formato [png,jpg,jpeg]',
                'email.email' => 'El email debe ser un correo electronico'

            ]
        
        );


            $administrador = Administrador::find($id_admin);

            if(!$administrador){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del administrador',
                    'code' => 404
                ],404);

            }

            if($request->hasFile('perfil')){

                $imagen_nueva = $request->file('perfil');

                $ruta_imagen = time().'.'.$imagen_nueva->getClientOriginalExtension();

                $imagen_nueva->storeAs('imagenadmin_nuevas',$ruta_imagen,'public');

                $datos_validados['perfil'] = 'imagenadmin_nuevas/'.$ruta_imagen;

            }

            $administrador->update($datos_validados);

            return response()->json([

                'status' => true,
                'message' => 'Datos actualizados correctamente',
                'data' => $administrador,
                'code' => 200
            ],200);


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

    public function actualizarPassword(Request $request, $id_admin){

        try{

            $password_validado = $request->validate([

                'password' => 'required|string|min:4|max:20'
            ],
            [
                'password.required' => 'El password es obligatorio',
                'password.string' => 'El password es una cadena de caracteres',
                'password.min' => 'El minimo de caracteres es de 4',
                'password.max' => 'El maximo de caracteres es de 20'

            ]
        );

        $admin_encontrado = Administrador::find($id_admin);

        if(!$admin_encontrado){

            return response()->json([

                'status' => false,
                'message' => 'El administrador no existe o no se ha registrado aun',
                'code' => 404
            ],404);
        }

        if(Hash::check($password_validado['password'],$admin_encontrado->password)){

            return response()->json([

                'status' => false,
                'message' => 'Debes de ingresar un password diferente al que tienes actualmente',
                'code' => 400
            ],400);
        }

        $password_validado['password'] = Hash::make($password_validado['password']);

        $admin_encontrado->update($password_validado);


        return response()->json([

            'status' => true,
            'message' => 'Password actualizado correctamente',
            'data' => $admin_encontrado,
            'code' => 200
        ],200);


        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en el campo solicitado',
                'warning' => $e->errors(),
                'code' => 400

            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el metodo de cambio_password',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }


    }

    

    















    
}
