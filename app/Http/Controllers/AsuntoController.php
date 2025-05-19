<?php

namespace App\Http\Controllers;

use App\Models\Asunto;
use Illuminate\Http\Request;

class AsuntoController extends Controller
{

    public function crearAsunto(Request $request){

        try{

            $asunto_validado = $request->validate([

                'nombre_asunto' => 'required|string|max:255|min:5'
            ],
            [
                'nombre_asunto.required' => 'El asunto es obligatorio',
                'nombre_asunto.string' => 'El asunto debe ser una cadena de texto',
                'nombre_asunto.max' => 'El maximo de caracteres es de 255',
                'nombre_asunto.min' => 'El minimo de caracteres es de 5'
            ]
        
        );

        $asunto_nuevo = Asunto::create($asunto_validado);

        return response()->json([

            'status' => true,
            'message' => 'Asunto creado exitosamente',
            'data' => $asunto_nuevo,
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
                'message' => 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }

    }


    
}
