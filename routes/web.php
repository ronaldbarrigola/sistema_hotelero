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
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\HotelProductoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\TransaccionPagoController;
use App\Http\Controllers\TransaccionAnticipoController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\DatoFacturaController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\ClienteCiudadController;
use App\Http\Controllers\ProfesionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\GrupoController;

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
Route::get('/base/persona/obtenerclientes', [ClienteController::class,'obtenerClientes'])->name('obtenerclientes');
Route::get('/busines/habitacion/edithabitacion', [HabitacionController::class,'edit'])->name('edithabitacion');
Route::get('/business/habitacion/obtenerHabitaciones', [HabitacionController::class,'obtenerHabitaciones'])->name('obtenerHabitaciones');
Route::get('/busines/tipo_habitacion/edittipohabitacion', [TipoHabitacionController::class,'edit'])->name('edittipohabitacion');
Route::get('/busines/categoria/editcategoria', [CategoriaController::class,'edit'])->name('editcategoria');
Route::get('/busines/producto/editproducto', [ProductoController::class,'edit'])->name('editproducto');
Route::get('/busines/hotel_producto/edithotelproducto', [HotelProductoController::class,'edit'])->name('edithotelproducto');
Route::get('/busines/reserva/createreserva', [ReservaController::class,'create'])->name('createreserva');
Route::get('/busines/reserva/editreserva', [ReservaController::class,'edit'])->name('editreserva');
Route::get('/busines/reserva/obtenerReservaPorId', [ReservaController::class,'obtenerReservaPorId'])->name('obtenerReservaPorId');
Route::get('/busines/cargo/createcargo', [CargoController::class,'create'])->name('createcargo');
Route::get('/busines/cargo/editcargo', [CargoController::class,'edit'])->name('editcargo');
Route::get('/busines/transaccion/createtransaccion', [TransaccionController::class,'create'])->name('createtransaccion');
Route::get('/busines/transaccion/edittransaccion', [TransaccionController::class,'edit'])->name('edittransaccion');
Route::get('/busines/transaccion_pago/edittransaccionpago', [TransaccionPagoController::class,'edit'])->name('edittransaccionpago');
Route::get('/busines/transaccion_pago/createtransaccionpago', [TransaccionPagoController::class,'create'])->name('createtransaccionpago');
Route::get('/busines/transaccion_anticipo/createanticipo', [TransaccionAnticipoController::class,'create'])->name('createanticipo');
Route::get('/busines/huesped/createhuesped', [HuespedController::class,'create'])->name('createhuesped');
Route::get('/busines/huesped/edithuesped', [HuespedController::class,'edit'])->name('edithuesped');
Route::get('/busines/pais/editpais', [PaisController::class,'edit'])->name('editpais');
Route::get('/busines/ciudad/editciudad', [ClienteCiudadController::class,'edit'])->name('editciudad');
Route::get('/busines/ciudad/editprofesion', [ProfesionController::class,'edit'])->name('editprofesion');
Route::get('/busines/ciudad/obtener_profesiones', [ProfesionController::class,'obtenerProfesiones'])->name('obtener_profesiones');
Route::get('/busines/reserva/obtenerReservas', [ReservaController::class,'obtenerReservasTimeLines'])->name('obtenerReservasTimeLine');
Route::get('/busines/reserva/obtenerReservaPorIdTimeLines', [ReservaController::class,'obtenerReservasPorIdTimeLines'])->name('obtenerReservaPorIdTimeLines');
Route::get('/busines/reserva/validar_eliminacion', [ReservaController::class,'validarEliminacion'])->name('validar_eliminacion');
Route::get('/busines/reserva/detalle_cargo', [ReservaController::class,'generarComprobanteDetalleCargo'])->name('detalle_cargo');
Route::get('/business/ciudad/listaciudades', [ClienteCiudadController::class,'obtenerCiudadesPorPaisId'])->name('listaciudades');
Route::get('/base/persona/buscarPersonaDocId', [PersonaController::class,'buscarPersonaClientePorDocId'])->name('buscarPersonaDocId');
Route::get('/base/persona/obtenerpersonas', [PersonaController::class,'obtenerPersonas'])->name('obtenerpersonas');
Route::get('/business/datofactura/nit', [DatoFacturaController::class,'obtenerDatoFacturaPorNit'])->name('dato_factura');

//Grupos
Route::get('/business/grupo/obtener_grupo_reserva', [GrupoController::class,'obtenerGruposPorReservaId'])->name('obtener_grupo_reserva');

//Post
Route::post('/business/hotel_producto/activatehotelproducto', [HotelProductoController::class,'activate'])->name('activatehotelproducto');
Route::post('/business/reserva/estado', [ReservaController::class,'estadoReserva'])->name('estadoreserva');
Route::post('/business/huesped/estado', [HuespedController::class,'estadoHuesped'])->name('estadohuesped');

//Reportes
Route::get('/business/reporte/reservas', [ReporteController::class,'obtenerReservas'])->name('reportereservas');
Route::get('/business/reporte/exportar_reservas', [ReporteController::class,'exportarReporteReservas'])->name('exportar_reservas');
Route::get('/business/reporte/huespedes', [ReporteController::class,'obtenerHuespedes'])->name('reportehuespedes');
Route::get('/business/reporte/exportar_huespedes', [ReporteController::class,'exportarReporteHuespedes'])->name('exportar_huespedes');
Route::get('/business/reporte/siat', [ReporteController::class,'obtenerReporteSiat'])->name('reporte_siat');
Route::get('/business/reporte/exportar_siat', [ReporteController::class,'exportarReporteSiat'])->name('exportar_siat');
Route::get('/business/reporte/produccion', [ReporteController::class,'obtenerReporteProduccion'])->name('reporte_produccion');
Route::get('/business/reporte/exportar_produccion', [ReporteController::class,'exportarReporteProduccion'])->name('exportar_produccion');

//Rutas generales
Route::resource('business/cliente', ClienteController::class);
Route::resource('business/habitacion', HabitacionController::class);
Route::resource('business/tipo_habitacion', TipoHabitacionController::class);
Route::resource('business/categoria', CategoriaController::class);
Route::resource('business/producto', ProductoController::class);
Route::resource('business/hotel_producto', HotelProductoController::class);
Route::resource('business/reserva', ReservaController::class);
Route::resource('business/cargo', CargoController::class);
Route::resource('business/transaccion', TransaccionController::class);
Route::resource('business/transaccion_pago', TransaccionPagoController::class);
Route::resource('business/transaccion_anticipo', TransaccionAnticipoController::class);
Route::resource('business/huesped', HuespedController::class);
Route::resource('business/pais', PaisController::class);
Route::resource('business/ciudad', ClienteCiudadController::class);
Route::resource('business/profesion', ProfesionController::class);
Route::resource('business/grupo', GrupoController::class);


