<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarritoController;
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
Route::put('actualizar_info/{id}',[AdminController::class,'editarPerfil']);
Route::put('actualizar_password_admin/{id}',[AdminController::class,'actualizarPassword']);




//usuarios
Route::post('registro-usuario',[UsuarioController::class,'registroUsuario']);
Route::post('login-usuario',[LoginUsuarioController::class,'loginUsuario']);
Route::get('ver_usuarios',[UsuarioController::class,'verUsuarios']);
Route::put('actualizar_usuario/{id}',[UsuarioController::class,'actualizarInfo']);
Route::put('actualizar_password/{id}',[UsuarioController::class,'actualizarPassword']);
Route::get('ver_info_usuario/{id}',[UsuarioController::class,'verinfoUsuario']);


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
Route::get('productos_tienda/{id}',[TiendaController::class,'productosTienda']);
Route::get('ver_ventas/{id}',[ProductosController::class,'numeroVentas']);


//carrito
Route::post('agregar_carrito/{id}',[CarritoController::class,'agregarCarrito']);
Route::get('ver_productos_carro/{id}',[CarritoController::class,'verProductosCarro']);


