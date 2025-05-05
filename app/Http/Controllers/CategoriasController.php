<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{

    public function crearCategoria(Request $request){

        try{

            $categoria_validada = $request->validate([

                'nombre' => 'required|string|max:255',
                'imagen' => 'nullable|string',
                'descripcion' => 'required|string|max:255'
            ],

            [
                'nombre.required' => 'El nombre de la categoria es obligatorio',
                'descripcion.required' => 'La descripcion de la categoria es obligatoria',
                'nombre.string' => 'El nombre de la categoria debe ser una cadena de texto',
                'descripcion.string' => 'La descripcion de la categoria debe ser una cadena de texto',
                'descripcion.max' => 'El maximo de el tamano la descripcion es de 255 caracteres',
                'nombre.max' => 'El maximo del tamano de el nombre de la categoria debe ser de 255 caracteres',

            ]
        
        
        );


            $categoria_creada = Categoria::create($categoria_validada);


            return response()->json([

                'status' => true,
                'message' => 'Categoria creada correctamente',
                'data' => $categoria_creada,
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


    public function verCategorias(){

        try{

            $categorias = Categoria::all();

            if($categorias->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay categorias',
                    'data' => [],
                    'code' => 200
                ],200);
            }


            return response()->json([

                'status' => true,
                'message' => 'Categorias traidas correctamente',
                'data' => $categorias,
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


    public function verInfoCategoria($id_categoria){

        try{

            $categoria = Categoria::find($id_categoria);

            if(!$categoria){

                return response()->json([

                    'status' => false,
                    'message' => 'No existe o no se encontro informacion de la categoria solicitada',
                    'code' => 404
                ],404);


            }

            return response()->json([

                'status' => true,
                'message' => 'Categoria encontrada exitosamente',
                'data' => $categoria,
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


    public function eliminarCategoria($id_categoria){

        try{

            $categoria = Categoria::find($id_categoria);

            if(!$categoria){

                return response()->json([

                    'status' => false,
                    'message' => 'La categoria no existe aun',
                    'code' => 400
                ],400);
            }

            $categoria->delete();

            return response()->json([

                'status' => true,
                'message' => 'Categoria eliminada exitosamente',
                'code' => 200
            ],200);


        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error del metodo correspondiente',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }




    }












    
}
