<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\CategoriaGlobal;
use Illuminate\Http\Request;

class CategoriaGeneralController extends Controller
{

    public function crearCategoriaGlobal(Request $request){

        try{

            $categoria_validacion = $request->validate([

                'nombre_categoria' => 'required|string|max:255',
                'imagen_categoria' => 'required|string|max:255',
                'descripcion_categoria' => 'required|string|max:255'
            ]);


            $categoria_nueva = CategoriaGlobal::create($categoria_validacion);

            return response()->json([

                'status' => true,
                'message' => 'Categoria creada exitosamente',
                'data' => $categoria_nueva,
                'code' => 201

            ],201);



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
                'message' => 'Error de programacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }
    }

    public function allCategoriasProductos(){
        
        try{

            $categorias = CategoriaGlobal::all();

            if($categorias->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay categorias disponibles',
                    'data' => [],
                    'code' => 200
                ],200);

            }

            return response()->json([

                'status' => true,
                'message' => 'Categorias obtenidas correctamente',
                'data' => $categorias,
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
