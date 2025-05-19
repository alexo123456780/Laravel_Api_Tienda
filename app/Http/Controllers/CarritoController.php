<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
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

    //obligatorio si o si debes de agregar un campo de cantidad en el carrito para saber cuantos productos quieres agregar o tienes agregado en el carrito

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

                $cantidadPrecio = $productito['precio_producto'] * $productito['pivot']['cantidad'];

                $cantidadTotal += $cantidadPrecio;

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


    //validar manana que no pase del stock y terminar la vemta en angular ya manana

    public function actualizarCantidadCarrito(Request $request, $id_carrito){

        try{

            $cantidad_validada = $request->validate([

                'cantidad' => 'required|numeric'
            ],
            [
                'cantidad.required' => 'La cantidad es obligatoria',
                'cantidad.numeric' => 'La cantidad debe ser numerica'

            ]
        );

        $carrito = Carrito::find($id_carrito);

        if(!$carrito){

            return response()->json([

                'status' => false,
                'message' => 'No se encontro informacion de este carrito',
                'code' => 404
            ],404);
        };

        $producto_carrito = $carrito->producto_id;

        $producto = Producto::find($producto_carrito);


        $producto_stock = $producto->stock;


        $cantidad_real = $carrito->cantidad;

        $cantidad = $cantidad_validada['cantidad'];

        if($cantidad <=0){

            return response()->json([

                'status' => false,
                'message' => 'Ingresa una cantidad positiva o mayor a 0',
                'code' => 400
            ],400);

        }

        $cantidad_actualizada = $cantidad_real += $cantidad;

        if($cantidad_actualizada > $producto_stock){

            return response()->json([

                'status' => false,
                'message' => 'No hay mas productos disponibles para agregar al carro',
                'code' => 400
            ],400);
        }


        $carrito->update(['cantidad' => $cantidad_actualizada]);

        return response()->json([

            'status' => true,
            'message' => 'Cantidad del carrito actualizada correctamente',
            'data' => $carrito,
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



    public function eliminarcantidadCarrito(Request $request, $id_carrito){

        try{

            $cantidad_validada = $request->validate([

                'cantidad' => 'required|numeric|min:1'
            ],
            [
                'cantidad.required' => 'La cantidad es obliagotoria',
                'cantidad.numeric' => 'La cantidad debe ser un valor numerico',
                'cantidad.min' => 'Ingresa una cantidad mayor a 0'
            ]
        
        );

            $carrito = Carrito::find($id_carrito);

            if(!$carrito){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion sobre este carrito',
                    'code' => 404
                ],404);
            }

            $cantidad_carrito = $carrito->cantidad;

            if($cantidad_carrito === 1){

                return response()->json([

                    'status' => false,
                    'message' => 'Este es el minimo que puedes tener en tu carrito',
                    'code' => 400
                ],400);

            }


            $cantidad_final = $cantidad_carrito -= $cantidad_validada['cantidad'];

            $carrito->update(['cantidad' => $cantidad_final]);

            return response()->json([

                'status' => true,
                'message' => 'Cantidad actualizada correctamente',
                'data' => $carrito,
                'code' => 200
            ],200);



        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el campo solicitado',
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
