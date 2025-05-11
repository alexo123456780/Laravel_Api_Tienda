<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CarritoController extends Controller
{

    public function agregarCarrito(Request $request,$id_usuario){

        try{

            $producto_validado = $request->validate([

                'producto_id' => 'required|numeric|exists:productos,id'

            ],

            [
                'producto_id.exists' => 'El producto no existe actualmente'

            ]
        
        );

            $usuario = Usuario::find($id_usuario);

            if(!$usuario){

                return response()->json([

                    'status' => false,
                    'message' => 'El usuario no existe o no se ha registrado aun',
                    'code' => 400
                ],400);
            }

            $usuarioId = $usuario->id;


            $validacionDuplicados = Carrito::where('producto_id',$producto_validado['producto_id'])->where('usuario_id',$usuarioId)->exists();

            if($validacionDuplicados){

                return response()->json([

                    'status' => false,
                    'message' => 'Este producto ya esta en tu carrito',
                    'code' => 400
                ],400);

            }


            $carritoNuevo = Carrito::create([

                'usuario_id' => $usuarioId,
                'producto_id' => $producto_validado['producto_id']

            ]);

            return response()->json([

                'status' => true,
                'message' => 'Producto asignado al carrito correctamente',
                'data' => $carritoNuevo,
                'code' => 200
            ],200);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion',
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

    public function verProductosCarro($id_usuario){

        try{

            $usuarioCarro = Usuario::with('productos')->find($id_usuario);

            if(!$usuarioCarro){

                return response()->json([

                    'status' => false,
                    'message' => 'El usuario no existe o no se ha registrado aun',
                    'code' => 404
                ],404);

            }

            $productosCarro = $usuarioCarro->productos;

            if($productosCarro->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'El usuario aun no tiene productos en su carrito',
                    'data' => [],
                    'code' => 200


                ]);

            }

            foreach($productosCarro as $productito){

                $productito['imagen_producto'] = asset('storage/'.$productito['imagen_producto']);
                

            }

            return response()->json([

                'status' => true,
                'message' => 'Productos del carrito obtenidos correctamente',
                'data' => $productosCarro,
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



    public function eliminarProductoCarro($id_carro){

        try{

            $carroProducto = Carrito::find($id_carro);
            
            
            if(!$carroProducto){

                return response()->json([

                    'status' => false,
                    'message' => 'El carrito no existe o no se encuentra actualmente',
                    'code' => 404
                ],404);

            }

            $carroProducto->delete();

            return response()->json([

                'status' => true,
                'message' => 'Producto del carrito eliminado exitosamente',
                'code' => 200
            ],200);


        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message'=> 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);
        }

    }


    public function calcularTotalCarrito($id_usuario){

        try{

            $usuarioCarro = Usuario::with('productos')->find($id_usuario);

            if(!$usuarioCarro){

                return response()->json([

                    'status' => false,
                    'message' => 'El usuario no existe o no se ha registrado aun',
                    'code' => 404
                ],404);

            }

            $productosCarro = $usuarioCarro->productos;

            $cantidadTotal = 0;

            foreach($productosCarro as $productito){


                if($productito['precio_producto']){

                    $cantidadTotal += $productito['precio_producto'];

                }

            }

             return response()->json([
                    'status' => true,
                    'message' => 'Cantidad calculada correctamente',
                    'precio_total' => $cantidadTotal,
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
