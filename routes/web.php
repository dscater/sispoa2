<?php

use App\Http\Controllers\ActividadRealizadaController;
use App\Http\Controllers\ActividadTareaController;
use App\Http\Controllers\CertificacionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConfiguracionModuloController;
use App\Http\Controllers\DetalleFormularioController;
use App\Http\Controllers\FinancieraController;
use App\Http\Controllers\FisicoController;
use App\Http\Controllers\FormularioCincoController;
use App\Http\Controllers\FormularioCuatroController;
use App\Http\Controllers\FormularioDosController;
use App\Http\Controllers\FormularioTresController;
use App\Http\Controllers\FormularioUnoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemoriaCalculoController;
use App\Http\Controllers\MemoriaOperacionDetalleController;
use App\Http\Controllers\OperacionController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\PeiController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SemaforoController;
use App\Http\Controllers\SubdireccionController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificacionActividadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// LOGIN
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);


// CONFIGURACIÓN
Route::get('/configuracion/getConfiguracion', [ConfiguracionController::class, 'getConfiguracion']);
Route::post('/configuracion/update', [ConfiguracionController::class, 'update']);

// PEI
Route::get('/pei/mision', [PeiController::class, "mision"]);
Route::get('/pei/vision', [PeiController::class, "vision"]);
Route::get('/pei/objetivos', [PeiController::class, "objetivos"]);

Route::prefix('admin')->group(function () {

    // CONFIGURACION MODULOS
    Route::get('/configuracion_modulos', [ConfiguracionModuloController::class, 'index']);
    Route::put('/configuracion_modulos/{configuracion_modulo}', [ConfiguracionModuloController::class, 'update']);

    // APROBAR FORMULARIOS
    Route::get('/aprobar_formularios', [ConfiguracionModuloController::class, 'get_aprobar_formularios']);
    Route::get('/get_aprobados', [ConfiguracionModuloController::class, 'get_aprobados']);

    // USUARIOS
    Route::get('usuarios/getUsuario/{usuario}', [UserController::class, 'getUsuario']);
    Route::get('usuarios/userActual', [UserController::class, 'userActual']);
    Route::get('usuarios/getInfoBox', [UserController::class, 'getInfoBox']);
    Route::get('usuarios/getPermisos/{usuario}', [UserController::class, 'getPermisos']);
    Route::get('usuarios/getEncargadosRepresentantes', [UserController::class, 'getEncargadosRepresentantes']);
    Route::post('usuarios/actualizaContrasenia/{usuario}', [UserController::class, 'actualizaContrasenia']);
    Route::post('usuarios/actualizaFoto/{usuario}', [UserController::class, 'actualizaFoto']);
    Route::resource('usuarios', UserController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // UNIDADES ORGANIZACIONALES
    Route::resource('unidads', UnidadController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // PARTIDAS
    Route::resource('partidas', PartidaController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // FORMULARIO CUATRO
    Route::get("formulario_cuatro/getOperaciones", [FormularioCuatroController::class, "getOperaciones"]);
    Route::resource('formulario_cuatro', FormularioCuatroController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // DETALLE FORMULARIO CUATRO
    Route::get('detalle_formularios/seguimiento_trimestral/{detalle_formulario}', [DetalleFormularioController::class, "seguimiento_trimestral"]);
    Route::resource('detalle_formularios', DetalleFormularioController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // operacions
    Route::get('operacions/getTareas', [OperacionController::class, "getTareas"]);

    Route::resource('operacions', OperacionController::class)->only([
        'store', 'update', 'destroy'
    ]);

    // tareas-partidas
    Route::get("actividad_tareas/getPartidas", [ActividadTareaController::class, 'getPartidas']);

    // FORMULARIO UNO
    Route::get("formulario_uno", [FormularioUnoController::class, 'formulario_uno']);

    // FORMULARIO DOS
    Route::get("formulario_dos", [FormularioDosController::class, 'formulario_dos']);

    // FORMULARIO TRES
    Route::get("formulario_tres", [FormularioTresController::class, 'formulario_tres']);

    // FORMULARIO CINCO
    Route::get("formulario_cinco/tabla/getTabla/{formulario_cinco}", [FormularioCincoController::class, 'getTabla']);
    Route::resource('formulario_cinco', FormularioCincoController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // MEMORIA CALCULOS
    Route::get("memoria_calculos/getOperaciones", [MemoriaCalculoController::class, "getOperaciones"]);
    Route::resource('memoria_calculos', MemoriaCalculoController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // MEMORIA OPERACION DETALLES
    Route::get("memoria_operacion_detalles/getDetalles", [MemoriaOperacionDetalleController::class, "getDetalles"]);

    // CERTIFICACION
    Route::get("certificacions/getNroCorrelativo", [CertificacionController::class, "getNroCorrelativo"]);
    Route::POST("certificacions/aprobar/{certificacion}", [CertificacionController::class, "aprobar"]);
    Route::POST("certificacions/pdf/{certificacion}", [CertificacionController::class, "pdf"]);
    Route::resource('certificacions', CertificacionController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // VERIFICACION ACTIVIDAD
    Route::get('/verificacion_actividads/getVerificacionActividad', [VerificacionActividadController::class, 'getVerificacionActividad']);
    Route::resource('verificacion_actividads', VerificacionActividadController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // FISICOS
    Route::resource('fisicos', FisicoController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // SUBDIRECCIÓN
    Route::resource('subdireccions', SubdireccionController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // FINANCIERAS
    Route::resource('financieras', FinancieraController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // SEMAFOROS
    Route::resource('semaforos', SemaforoController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // ACTIVIDAD REALIZADA
    Route::post('actividad_realizadas/archivo/{actividad_realizada}', [ActividadRealizadaController::class, 'archivo']);
    Route::resource('actividad_realizadas', ActividadRealizadaController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // REPORTES
    Route::post('reportes/usuarios', [ReporteController::class, 'usuarios']);
    Route::post('reportes/saldo_presupuesto', [ReporteController::class, 'saldo_presupuesto']);
    Route::post('reportes/ejecucion_presupuestos', [ReporteController::class, 'ejecucion_presupuestos']);
    Route::post('reportes/ejecucion_presupuestos_g', [ReporteController::class, 'ejecucion_presupuestos_g']);
    Route::post('reportes/formulario_cuatro', [ReporteController::class, 'formulario_cuatro']);
    Route::post('reportes/formulario_cinco', [ReporteController::class, 'formulario_cinco']);
    Route::post('reportes/memoria_calculos', [ReporteController::class, 'memoria_calculos']);
    Route::post('reportes/saldos_actividad', [ReporteController::class, 'saldos_actividad']);
    Route::post('reportes/saldos_partida', [ReporteController::class, 'saldos_partida']);
    Route::post('reportes/fisicos', [ReporteController::class, 'fisicos']);
    Route::post('reportes/financieros', [ReporteController::class, 'financieros']);
    Route::post('reportes/semaforos', [ReporteController::class, 'semaforos']);

    Route::post('reportes/formulario_cuatro_excel', [ReporteController::class, 'formulario_cuatro_excel']);
    Route::post('reportes/formulario_cinco_excel', [ReporteController::class, 'formulario_cinco_excel']);
    Route::post('reportes/memoria_calculo_excel', [ReporteController::class, 'memoria_calculo_excel']);
});

// ---------------------------------------
Route::get('/{optional?}', function () {
    return view('app');
})->name('base_path')->where('optional', '.*');
