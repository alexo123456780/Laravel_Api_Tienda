<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LoginUsuarioController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\UsuarioController;

//administrador
Route::post('login-admin',[LoginAdminController::class,'loginAdmin']);
Route::post('alta-tienda',[TiendaController::class,'registrarTienda']);
Route::post('registro-admin',[AdminController::class,'registroAdmin']);
Route::get('info_admin/{id}',[AdminController::class,'infoAdmin']);



//usuarios
Route::post('registro-usuario',[UsuarioController::class,'registroUsuario']);
Route::post('login-usuario',[LoginUsuarioController::class,'loginUsuario']);



//tiendas
Route::get('ver-tiendas',[TiendaController::class,'verTiendas']);
Route::get('info_tienda/{id}',[TiendaController::class,'infoTienda']);



//categorias
Route::get('categorias',[CategoriasController::class,'verCategorias']);
Route::get('ver_categoria/{id}',[CategoriasController::class,'verInfoCategoria']);
Route::post('crear_categoria',[CategoriasController::class,'crearCategoria']);
Route::delete('eliminar_categoria/{id}',[CategoriasController::class,'eliminarCategoria']);

//Productos
Route::post('crear_producto/{id}',[ProductosController::class,'registrarProducto']);
Route::get('traer_productos',[ProductosController::class,'verProductos']);
Route::get('info_producto/{id}',[ProductosController::class,'infoProducto']);

