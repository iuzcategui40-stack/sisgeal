<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Seguridad\UsuariosController;
use App\Http\Controllers\Parametrizar\ActosLegislativosController;
use App\Http\Controllers\Parametrizar\TiposActosController;
use App\Http\Controllers\Parametrizar\SesionesController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\Seguridad\AuditController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web de la aplicación con control de permisos por roles.
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    // ========== INICIO ==========
    Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ========== TIPOS DE ACTOS ==========
    Route::group(['middleware' => 'permission:tiposactos,view'], function () {
        Route::resource('tiposactos', TiposActosController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    });
    Route::get('tiposactos', [TiposActosController::class, 'index'])->name('tiposactos.index');

    Route::get('tiposactos/create', [TiposActosController::class, 'create'])->name('tiposactos.create')->middleware('permission:tiposactos,create');
    Route::post('tiposactos', [TiposActosController::class, 'store'])->name('tiposactos.store')->middleware('permission:tiposactos,create');
    Route::get('tiposactos/{tipoacto}/edit', [TiposActosController::class, 'edit'])->name('tiposactos.edit')->middleware('permission:tiposactos,edit');
    Route::put('tiposactos/{tipoacto}', [TiposActosController::class, 'update'])->name('tiposactos.update')->middleware('permission:tiposactos,edit');
    Route::delete('tiposactos/{id}', [TiposActosController::class, 'destroy'])->name('tiposactos.destroy')->middleware('permission:tiposactos,delete');


    // ========== ACTOS LEGISLATIVOS ==========
    Route::get('actoslegislativos/exportar/excel', [ActosLegislativosController::class, 'exportarExcel'])->name('actoslegislativos.exportar.excel')->middleware('permission:actoslegislativos,view');
    Route::get('actoslegislativos/exportar/pdf', [ActosLegislativosController::class, 'exportarPDF'])->name('actoslegislativos.exportar.pdf')->middleware('permission:actoslegislativos,view'); 
    
    
    Route::get('actoslegislativos', [ActosLegislativosController::class, 'index'])->name('actoslegislativos.index');

    Route::get('actoslegislativos/create', [ActosLegislativosController::class, 'create'])->name('actoslegislativos.create');
    Route::get('actoslegislativos/crear', [ActosLegislativosController::class, 'crear'])->name('actoslegislativos.crear');
    Route::post('actoslegislativos', [ActosLegislativosController::class, 'store'])->name('actoslegislativos.store')->middleware('permission:actoslegislativos,create');
    Route::get('actoslegislativos/{actoslegislativo}/edit', [ActosLegislativosController::class, 'edit'])->name('actoslegislativos.edit')->middleware('permission:actoslegislativos,edit');
    Route::put('actoslegislativos/{actoslegislativo}', [ActosLegislativosController::class, 'update'])->name('actoslegislativos.update')->middleware('permission:actoslegislativos,edit');
    Route::delete('/actos-legislativos/{id}', [ActosLegislativosController::class, 'destroy'])->name('actoslegislativos.destroy')->middleware('permission:actoslegislativos,delete');

Route::group(['middleware' => 'permission:actoslegislativos,view'], function () {
        Route::resource('actoslegislativos', ActosLegislativosController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    });
    // ========== DIARIO DE DEBATES (SESIONES) ==========
    Route::get('sesiones/exportar-excel', [SesionesController::class, 'exportarExcel'])->name('sesiones.exportExcel')->middleware('permission:sesiones,view');
    
   
    Route::get('sesiones/index', [SesionesController::class, 'index'])->name('sesiones.index');
    Route::get('sesiones/create', [SesionesController::class, 'create'])->name('sesiones.create')->middleware('permission:sesiones,create');
    Route::post('sesiones', [SesionesController::class, 'store'])->name('sesiones.store')->middleware('permission:sesiones,create');
    Route::get('sesiones/{sesione}/edit', [SesionesController::class, 'edit'])->name('sesiones.edit')->middleware('permission:sesiones,edit');
    Route::put('sesiones/{sesione}', [SesionesController::class, 'update'])->name('sesiones.update')->middleware('permission:sesiones,edit');
    Route::delete('sesiones/{id}', [SesionesController::class, 'destroy'])->name('sesiones.destroy')->middleware('permission:sesiones,delete');

 Route::group(['middleware' => 'permission:sesiones,view'], function () {
        Route::resource('sesiones', SesionesController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    });
    // ========== USUARIOS Y SEGURIDAD ==========
    Route::post('/usuarios/check-email', [UsuariosController::class, 'checkEmail'])->name('usuarios.checkEmail')->middleware('permission:usuarios,create');
    Route::get('usuarios/{id}/eliminar', [UsuariosController::class, 'eliminar'])->name('usuarios.eliminar')->middleware('permission:usuarios,delete');
    Route::get('usuarios/{id}/cambiar', [UsuariosController::class, 'cambiar'])->name('usuarios.cambiar')->middleware('permission:usuarios,edit');
    Route::put('usuarios/{valor}/updated_password', [UsuariosController::class, 'update_password'])->name('usuarios.update_password')->middleware('permission:usuarios,edit');
    Route::get('usuarios/{id}/estatus', [UsuariosController::class, 'status'])->name('usuarios.estatus')->middleware('permission:usuarios,edit');

 
    Route::get('usuarios/create', [UsuariosController::class, 'create'])->name('usuarios.create')->middleware('permission:usuarios,create');
    Route::post('usuarios', [UsuariosController::class, 'store'])->name('usuarios.store')->middleware('permission:usuarios,create');
    Route::get('usuarios/{usuario}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit')->middleware('permission:usuarios,edit');
    Route::put('usuarios/{usuario}', [UsuariosController::class, 'update'])->name('usuarios.update')->middleware('permission:usuarios,edit');
    Route::delete('usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy')->middleware('permission:usuarios,delete');
   Route::group(['middleware' => 'permission:usuarios,view'], function () {
        Route::resource('usuarios', UsuariosController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    });
Route::group(['middleware' => 'permission:roles,view'], function () {
    Route::resource('roles', RolController::class)->except(['create', 'store', 'edit', 'update', 'destroy', 'show']);
});
Route::get('roles', [RolController::class, 'index'])->name('roles.index')->middleware('permission:roles,view');

Route::get('roles/create', [RolController::class, 'create'])->name('roles.create')->middleware('permission:roles,create');
Route::post('roles', [RolController::class, 'store'])->name('roles.store')->middleware('permission:roles,create');
Route::get('roles/{role}/edit', [RolController::class, 'edit'])->name('roles.edit')->middleware('permission:roles,edit');
Route::put('roles/{role}', [RolController::class, 'update'])->name('roles.update')->middleware('permission:roles,edit');
Route::delete('roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles,delete');


Route::get('/documentacion', function () {
    return view('documentacion.index');
})->name('documentacion.index');



// ========== AUDITORÍA DEL SISTEMA ==========
Route::get('seguridad/audits', [AuditController::class, 'index'])->name('seguridad.audits.index');
Route::get('seguridad/audits/{id}', [AuditController::class, 'show'])->name('seguridad.audits.show');

});