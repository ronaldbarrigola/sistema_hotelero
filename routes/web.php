<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\AgenciaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolMenuController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioRolController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CargoController;

use Illuminate\Support\Facades\Route;

//==============================================================================================================
//rutas BASE DE LOGIN Y OTROS BASICOS
//==============================================================================================================
 Route::get('/login', [LoginController::class, 'mostrarPagina']);
 Route::post('/login',[LoginController::class,'login'])->name('login');
 Route::post('/logout',[LoginController::class,'logout'])->name('logout');

Route::get('/home', [HomeController::class,'index'])->name('home');
Route::get('/{slug}', [HomeController::class,'index']);
Route::get('/', [HomeController::class,'index']);

// SUCURSAL
Route::resource('seguridad/sucursales', SucursalController::class);
// AGENCIA
Route::get('/seguridad/agencias/obtener_agencias_por_sucural/{idSucursal}', [AgenciaController::class,'obtenerAgenciasPorSucursal']);
Route::resource('seguridad/agencias', AgenciaController::class);

//ROLES

Route::get('/seguridad/roles/seleccionar', [RolController::class,'seleccionarRol'])->name('seleccionar');
Route::post('/seguridad/roles/ingresar', [RolController::class,'ingresarSistema'])->name('ingresar');
Route::resource('seguridad/roles', RolController::class);


// MENU  POR ROLES
//Route::resource('seguridad/rolmenus', 'RolMenuController');
Route::get('/seguridad/rolmenu/asignacion_menus', [RolMenuController::class,'asignacionMenusPorIdRol']);
Route::post('/seguridad/rolmenu/guardar_asignacion_menus', [RolMenuController::class,'guardarAsignacionMenus']);
Route::get('/seguridad/rolmenu/lista_menus', [RolMenuController::class,'listaMenusPorIdRol']);

//PERSONA
Route::get('seguridad/personas/buscar_por_num_doc_id', [PersonaController::class,'buscarPorNumDocId']);
Route::get('seguridad/personas/create_edit/{id}', [PersonaController::class,'create_edit']);
Route::resource('seguridad/personas', PersonaController::class);

//USUARIOS

Route::get('/seguridad/usuarios/editpass', [UsuarioController::class,'editPassword'])->name('editpass');
Route::post('/seguridad/usuarios/updatepass', [UsuarioController::class,'updatePassword'])->name('updatepass');
Route::get('seguridad/usuarios/create_edit/{id}', [UsuarioController::class,'create_edit']);
Route::resource('seguridad/usuarios', UsuarioController::class);

//USUARIO_ROL
Route::get('/seguridad/obtenerRolesPorIdUsuario', [UsuarioRolController::class,'obtenerRolesPorIdUsuario'])->name('getRolByUsu');
Route::get('/seguridad/obtenerRolesFaltantesPorIdUsuario', [UsuarioRolController::class,'obtenerRolesFaltantesPorIdUsuario'])->name('getRolFalByUsu');

//==============================================================================================================
//rutas proyecto negocio
//==============================================================================================================
Route::get('/busines/cliente/createcliente', [ClienteController::class,'create'])->name('createcliente');
Route::get('/busines/cliente/editcliente', [ClienteController::class,'edit'])->name('editcliente');
Route::get('/busines/habitacion/edithabitacion', [HabitacionController::class,'edit'])->name('edithabitacion');
Route::get('/business/habitacion/obtenerHabitaciones', [HabitacionController::class,'obtenerHabitaciones'])->name('obtenerHabitaciones');
Route::get('/busines/categoria/editcategoria', [CategoriaController::class,'edit'])->name('editcategoria');
Route::get('/busines/producto/editproducto', [ProductoController::class,'edit'])->name('editproducto');
Route::get('/busines/reserva/createreserva', [ReservaController::class,'create'])->name('createreserva');
Route::get('/busines/reserva/editreserva', [ReservaController::class,'edit'])->name('editreserva');
Route::get('/busines/cargo/createcargo', [CargoController::class,'create'])->name('createcargo');
Route::get('/busines/cargo/editcargo', [CargoController::class,'edit'])->name('editcargo');
Route::get('/busines/reserva/obtenerReservas', [ReservaController::class,'obtenerReservasTimeLines'])->name('obtenerReservas');
Route::get('/business/ciudad/listaciudades', [CiudadController::class,'obtenerCiudadesPorPaisId'])->name('listaciudades');
Route::get('/base/persona/buscarPersonaDocId', [PersonaController::class,'buscarPersonaClientePorDocId'])->name('buscarPersonaDocId');



//Rutas generales
Route::resource('business/cliente', ClienteController::class);
Route::resource('business/habitacion', HabitacionController::class);
Route::resource('business/categoria', CategoriaController::class);
Route::resource('business/producto', ProductoController::class);
Route::resource('business/reserva', ReservaController::class);
Route::resource('business/cargo', CargoController::class);


