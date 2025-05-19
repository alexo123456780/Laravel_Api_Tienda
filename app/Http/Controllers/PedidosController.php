<?php

namespace App\Http\Controllers;

use App\Models\Tienda;
use Illuminate\Http\Request;

class PedidosController extends Controller
{

    public function pedidosTienda($id_tienda){

        try{

            $tienda = Tienda::with('ventas.usuario')->find($id_tienda);

            if(!$tienda){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion de esta tienda',
                    'code' => 404
                ],404);
            }


            $colecction_ventas = $tienda->ventas;


            if($colecction_ventas->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay ventas realizadas en esta tienda',
                    'data' => [],
                    'code' => 200
                ],200);
            }


            //hau que verificar si con una ves que se obtenga el id del usuario guardas en un arreglo el id una ves se haya generado

            $coleccionCache = [];

            foreach($colecction_ventas as $venta){

                $usuarioId = $venta->usuario->id;

                if(!isset($coleccionCache[$usuarioId])){

                    $coleccionCache[$usuarioId] = asset('storage/'.$venta->usuario->perfil_usuario);

                }

                $venta->usuario->perfil_usuario = $coleccionCache[$usuarioId];

            }
    
            return response()->json([

                'status' => true,
                'message' => 'Pedidos de la tienda obtenidos correctamente',
                'data' => $colecction_ventas,
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
