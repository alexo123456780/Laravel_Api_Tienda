<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{

    public function ventaProducto(Request $request){

        try{

            $info_validada = $request->validate([

                'usuario_id' => 'required|numeric|exists:usuarios,id'

            ],

            [
                'usuario_id.required' => 'El id del usuario es obligatorio',
                'usuario_id.numeric' => 'El id debe ser un valor numerico',
                'usuario_id.exists' => 'No se encontro informacion de este usuario'

            ]
        
        );

            $usuario = Usuario::with('productos')->find($info_validada['usuario_id']);

            $carrito_productos = $usuario->productos;
            

            if($carrito_productos->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay productos en el carrito',
                    'data' => [],
                    'code'  => 200
                ],200);

            }


            $ventas = [];

            foreach($carrito_productos as $carrito){

                $productos_general = Producto::find($carrito['pivot']['producto_id']);

                $cantidad_carrito = $carrito['pivot']['cantidad'];

                $precio_producto = $carrito['precio_producto'];

                $stock_productos = $productos_general->stock;

                $tienda_id = $carrito['tienda_id'];

                $precio_final = $cantidad_carrito * $precio_producto;

                $stock_final = $stock_productos -= $cantidad_carrito;

                $numero_ventas = $productos_general->numero_ventas;

                $numero_ventas += $cantidad_carrito;


                $productos_general->update([

                    'stock' => $stock_final,
                    'numero_ventas' => $numero_ventas,
                    
                ]);

                 $venta = Venta::create([

                    'usuario_id' => $usuario->id,
                    'tienda_id' => $tienda_id,
                    'precio_final' => $precio_final,

                ]);

                DetalleVenta::create([

                    'venta_id' => $venta->id,
                    'producto_id' => $carrito['pivot']['producto_id'],
                    'cantidad' => $carrito['pivot']['cantidad'],
                    'precio_unitario' => $carrito['precio_producto']
                ]);

                $ventas[] = $venta;

            }

            //el detach vacia lo que haya en una relacion pero solo funciona en el modelo no en una colreccon

            $usuario->productos()->detach();

            return response()->json([

                'status' => true,
                'message' => 'Compra realizada exitosamente',
                'data' =>$ventas,
                'code' => 200
            ]);


        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de codficacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);
        }

    }




    public function calcularingresosTotales($id_tienda){

        try{

            $tienda_busqueda = Tienda::with('ventas')->find($id_tienda);

            if(!$tienda_busqueda){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion relacionada a esta tienda',
                    'code' => 404
                ],404);
            }

            //esto es una coleccion(como array de algo pero relacionado)
            $ventas_tienda = $tienda_busqueda->ventas;

            /*

            ejemplo que se puede usar en una coleccion

            $ventas_tienda->sum('precio_final') -> opcion mas corta de un foreach
            */

            $venta_calculada = 0;

            foreach($ventas_tienda as $ventas){

                $venta_calculada += $ventas['precio_final'];

            }

            return response()->json([
                'status' => true,
                'message' => 'Ingresos de la tienda calculado correctamente',
                'ventas' => $venta_calculada,
                'code' => 200
            ],200);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de programacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }

    }


    

    













    

    
    
}
