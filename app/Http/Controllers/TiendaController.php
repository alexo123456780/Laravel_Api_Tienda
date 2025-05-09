<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Tienda;
use Illuminate\Http\Request;

class TiendaController extends Controller
{


    public function registrarTienda(Request $request){

        try{

            $validaciones_datos = $request->validate([

                'nombre_tienda' => 'required|string|max:255',
                'logo_tienda' => 'string|nullable',
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

    public function infoTienda($id_admin){

        try{

            $admin_tienda = Administrador::with('tienda')->find($id_admin);

            if(!$admin_tienda){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del administrador disponible',
                    'code' => 404
                ],404);
            }

            $tienda = $admin_tienda->tienda;

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


            return response()->json([

                'status' => true,
                'message' => 'Productos de la tienda obtenidos correctamente',
                'data' => $productos_tienda,
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
