<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductosController extends Controller
{

    public function registrarProducto(Request $request, $id_admin){

        try{

            $datos_validados = $request->validate([

                'nombre_producto' => 'required|string|max:255',
                'imagen_producto' => 'required|string',
                'stock' => 'required|numeric',
                'precio_producto' => 'required|numeric',
                'descripcion_producto' => 'required|string|max:255',
                'categoria_id' => 'required|numeric|exists:categorias,id'
            ]);

            $admin_encontrado = Administrador::find($id_admin);

            if(!$admin_encontrado){

                return response()->json([

                    'status' => false,
                    'message' => 'No puedes crear un producto por que aun no estas registrado en la plataforma',
                    'code' => 400
                ],400);
            }

            if($request->hasFile('imagen_producto')){

                $imagen_producto = $request->file('imagen_producto');

                $ruta_bdImagen = time().'.'.$imagen_producto->getClientOriginalExtension();

                $imagen_producto->storeAs('imagenes_producto',$ruta_bdImagen,'public');

                $datos_validados['imagen_producto'] = 'imagenes_producto/'.$ruta_bdImagen;

            }

            $tienda_id = $admin_encontrado->tienda_id;

            $producto_creado = Producto::create([

                'nombre_producto' => $datos_validados['nombre_producto'],
                'imagen_producto' => $datos_validados['imagen_producto'],
                'stock' => $datos_validados['stock'],
                'precio_producto' => $datos_validados['precio_producto'],
                'descripcion_producto' => $datos_validados['descripcion_producto'],
                'categoria_id' => $datos_validados['categoria_id'],
                'tienda_id' => $tienda_id
            ]);


            return response()->json([

                'status' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto_creado,
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
                'message' => 'Error interno en la solicitud',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);
        }
    }



    //2.ver productos (usuarioos)

    public function verProductos(){

        try{

            $productos = Producto::all();

            if($productos->isEmpty()){

                return response()->json([

                    'status' => false,
                    'message' => 'Aun no hay productos disponibles',
                    'code' => 404
                ],404);
            }

            return response()->json([

                'status' => true,
                'message' => 'Productos obtenidos correctamente',
                'data' => $productos,
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


    //3. Info producto indivudual

    public function infoProducto($id_producto){

        try{

            $producto = Producto::find($id_producto);

            if(!$producto){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del  producto solicitado',
                    'code' => 404
                ],404);

            }

            return response()->json([

                'status' => true,
                'message' => 'Info del producto obtenida correctamente',
                'data' => $producto,
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
