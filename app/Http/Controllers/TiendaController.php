<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Tienda;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class TiendaController extends Controller
{


    public function registrarTienda(Request $request){

        try{

            $validaciones_datos = $request->validate([

                'nombre_tienda' => 'required|string|max:255',
                'logo_tienda' => 'required|image|mimes:png,jpg,jpeg',
                'descripcion' => 'required|string',
                'direccion_web' => 'required|string|max:255',
                'numero_telefonico' => 'required|string|max:10',
                'direccion' => 'required|string|max:255',
                'categoria_id' => 'required|numeric|exists:categorias,id'
            ]);

            if($request->hasFile('logo_tienda')){

                $imagen_logo = $request->file('logo_tienda');

                $ruta_imagen = time().'.'.$imagen_logo->getClientOriginalExtension();

                $imagen_logo->storeAs('logo_tiendas',$ruta_imagen,'public');

                $validaciones_datos['logo_tienda'] = 'logo_tiendas/'.$ruta_imagen;

            }

            $tiendaNueva = Tienda::create($validaciones_datos);

            return response()->json([

                'status' => true,
                'message' => 'Empresa dada de alta correctamente',
                'data' => $tiendaNueva,
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
                'message' => 'Error en el metodo o funciones del codigo',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }
    }

    //funcion que trae toda la informacion de la tienda
    public function verTiendas(){

        try{

            $allTiendas = Tienda::all();

            if($allTiendas->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay tiendas registradas registra tu tienda para continuar',
                    'data' => [],
                    'code' => 200
                ],200);
            }


            return response()->json([

                'status' => true,
                'message' => 'Tiendas traidas correctamente',
                'data' => $allTiendas,
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

    //funcion para ver la informacion de la tienda del admin
    public function infoTienda($id_admin){

        try{

            $admin_tienda = Administrador::with('tienda.administrador')->find($id_admin);

            if(!$admin_tienda){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del administrador disponible',
                    'code' => 404
                ],404);
            }

            $tienda = $admin_tienda->tienda;

            $tienda->logo_tienda = asset('storage/'.$tienda->logo_tienda);


            return response()->json([

                'status' => true,
                'message' => 'Informacion de la tienda traida correctamente',
                'data' => $tienda,
                'code' => 200
            ]);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);
        }

    }

    //3.funcion para ver los productos que le corresponden a una tienda
    public function productosTienda($id_tienda){

        try{

            $tienda_busqueda = Tienda::with('productos.categoria')->find($id_tienda);

            if(!$tienda_busqueda){

                return response()->json([

                    'status' => false,
                    'message' => 'La tienda no ha sido registrada aun',
                    'code' => 404
                ],404);

            }

            $productos_tienda = $tienda_busqueda->productos;

            if($productos_tienda->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay productos registrados para esta tienda',
                    'data' => [],
                    'code' => 200
                ],200);
            }

            //map collection no cambia el original

            $productos_generales = $productos_tienda->map(function($producto){

                $producto['imagen_producto'] = asset('storage/'.$producto['imagen_producto']);
                return $producto;
            });
            
            return response()->json([

                'status' => true,
                'message' => 'Productos de la tienda obtenidos correctamente',
                'data' => $productos_generales,
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

    public function editarInfoTienda(Request $request, $id_tienda){


        try{

            $informacion_validada = $request->validate([

                'nombre_tienda' => 'string|max:255',
                'logo_tienda' => 'image|mimes:png,jpg,jpeg',
                'descripcion' => 'string|max:255',
                'direccion_web' => 'string|max:255',
                'numero_telefonico' => 'string|max:10',
                'direccion' => 'string|max:255'
            ]);

            $tienda = Tienda::find($id_tienda);

            if(!$tienda){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion de la tienda solicitada',
                    'code' => 404
                ],404);
            }

            $datos_repetidos = false;

            if(isset($informacion_validada['nombre_tienda'])){

                $datos_repetidos = Tienda::where('nombre_tienda',$informacion_validada['nombre_tienda'])->exists();

            }

            if(!$datos_repetidos && isset($informacion_validada['numero_telefonico'])){

                $datos_repetidos = Tienda::where('numero_telefonico',$informacion_validada['numero_telefonico'])->exists();

            }


            if($datos_repetidos){

                return response()->json([

                    'status' => false,
                    'message' => 'Revise que sus datos sean correctos y que no se repitan si desea actualizar correctamente',
                    'code' => 400
                ],400);
            }

            if($request->hasFile('logo_tienda')){

                $logo_tienda = $request->file('logo_tienda');

                $ruta_imagen = time().'.'.$logo_tienda->getClientOriginalExtension();

                $logo_tienda->storeAs('logos_actualizados',$ruta_imagen,'public');

                $informacion_validada['logo_tienda'] = 'logos_actualizados/'.$ruta_imagen;

            }

            $tienda->update($informacion_validada);

            return response()->json([

                'status' => true,
                'message' => 'Informacion de la tienda actualizada correctamente',
                'data' => $tienda,
                'code' => 200
            ],200);



        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion el campo solicitado intente de nuevo porfavor',
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
