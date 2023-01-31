<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Cliente;
use App\Models\ConfiguracionModulo;
use App\Models\Log;
use App\Models\Tcont;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $validacion = [
        'nombre' => 'required|min:4',
        'paterno' => 'required|min:4',
        'ci' => 'required|numeric|digits_between:4, 20|unique:users,ci',
        'ci_exp' => 'required',
        'fono' => 'required|min:4',
        'cargo' => 'required',
        'lugar_trabajo' => 'required',
        'descripcion_puesto' => 'required',
        'unidad_id' => 'required',
        'tipo' => 'required',
        'acceso' => 'required',
    ];

    public $mensajes = [
        'nombre.required' => 'Este campo es obligatorio',
        'nombre.min' => 'Debes ingressar al menos 4 carácteres',
        'paterno.required' => 'Este campo es obligatorio',
        'paterno.min' => 'Debes ingresar al menos 4 carácteres',
        'ci.required' => 'Este campo es obligatorio',
        'ci.numeric' => 'Debes ingresar un valor númerico',
        'ci.unique' => 'Este número de C.I. ya fue registrado',
        'ci_exp.required' => 'Este campo es obligatorio',
        'fono.required' => 'Este campo es obligatorio',
        'fono.min' => 'Debes ingresar al menos 4 carácteres',
        'cargo.required' => 'Debes ingresar un cargo',
        'lugar_trabajo.required' => 'Debes ingresar un lugar de trabajo',
        'descripcion_puesto.required' => 'Debes ingresar la descripción del puesto',
        'unidad_id.required' => 'Debes seleccionar una unidad',
        'tipo.required' => 'Este campo es obligatorio',
    ];

    public $permisos = [
        'SUPER USUARIO' => [
            'usuarios.index',
            'usuarios.create',
            'usuarios.edit',
            'usuarios.destroy',

            'personals.index',
            'personals.create',
            'personals.edit',
            'personals.destroy',

            'unidads.index',
            'unidads.create',
            'unidads.edit',
            'unidads.destroy',

            'subdireccions.index',
            'subdireccions.create',
            'subdireccions.edit',
            'subdireccions.destroy',

            'partidas.index',
            'partidas.create',
            'partidas.edit',
            'partidas.destroy',

            'formulario_uno.index',

            'formulario_dos.index',

            'formulario_tres.index',

            'formulario_cuatro.index',
            'formulario_cuatro.create',
            'formulario_cuatro.edit',
            'formulario_cuatro.destroy',

            'detalle_formularios.index',
            'detalle_formularios.create',
            'detalle_formularios.edit',
            'detalle_formularios.destroy',

            'formulario_cinco.index',
            'formulario_cinco.create',
            'formulario_cinco.edit',
            'formulario_cinco.destroy',

            'memoria_calculos.index',
            'memoria_calculos.create',
            'memoria_calculos.edit',
            'memoria_calculos.destroy',

            'saldo_presupuesto.index',

            'certificacions.index',
            'certificacions.create',
            'certificacions.edit',
            'certificacions.destroy',

            'pei.index',

            'fisicos.index',
            'fisicos.create',
            'fisicos.edit',
            'fisicos.destroy',

            'financieras.index',
            'financieras.create',
            'financieras.edit',
            'financieras.destroy',

            'semaforos.index',
            'semaforos.create',
            'semaforos.edit',
            'semaforos.destroy',

            'actividad_realizadas.index',
            'actividad_realizadas.create',
            'actividad_realizadas.edit',
            'actividad_realizadas.destroy',

            'verificacion_actividads.index',
            'verificacion_actividads.edit',

            "seguimiento_trimestral.index",

            "informe_actividad.index",

            'configuracion.index',
            'configuracion.edit',

            'configuracion.modulos',

            "reportes.formulario_cuatro",
            "reportes.formulario_cinco",
            "reportes.memoria_calculos",
            "reportes.saldos_actividad",
            "reportes.saldos_partida",
            "reportes.ejecucion_presupuestos",
            "reportes.ejecucion_presupuestos_g",
            "reportes.fisicos",
            "reportes.financieros",
            "reportes.semaforos",

        ],
        'ENLACE' => [
            'formulario_uno.index',

            'formulario_dos.index',

            'formulario_tres.index',

            'formulario_cuatro.index',
            'formulario_cuatro.create',
            'formulario_cuatro.edit',
            'formulario_cuatro.destroy',

            'detalle_formularios.index',
            'detalle_formularios.create',
            'detalle_formularios.edit',
            'detalle_formularios.destroy',

            'formulario_cinco.index',
            'formulario_cinco.create',
            'formulario_cinco.edit',
            'formulario_cinco.destroy',

            'memoria_calculos.index',
            'memoria_calculos.create',
            'memoria_calculos.edit',
            'memoria_calculos.destroy',

            'saldo_presupuesto.index',

            'certificacions.index',
            'certificacions.create',
            'certificacions.edit',
            'certificacions.destroy',

            ' pei.index',

            'fisicos.index',
            'fisicos.create',
            'fisicos.edit',
            'fisicos.destroy',

            'financieras.index',
            'financieras.create',
            'financieras.edit',
            'financieras.destroy',

            'semaforos.index',
            'semaforos.create',
            'semaforos.edit',
            'semaforos.destroy',

            'actividad_realizadas.index',
            'actividad_realizadas.create',
            'actividad_realizadas.edit',
            'actividad_realizadas.destroy',

            "seguimiento_trimestral.index",

            "informe_actividad.index",

            "reportes.formulario_cuatro",
            "reportes.formulario_cinco",
            "reportes.memoria_calculos",
            "reportes.saldos_actividad",
            "reportes.saldos_partida",
            "reportes.ejecucion_presupuestos",
            "reportes.ejecucion_presupuestos_g",
            "reportes.fisicos",
            "reportes.financieros",
            "reportes.semaforos",
        ],
        'JEFES DE UNIDAD' => [
            'certificacions.index',
            'certificacions.create',
            'certificacions.edit',
            'certificacions.destroy',

            "seguimiento_trimestral.index",

            'formulario_cuatro.index',
            'formulario_cuatro.create',
            'formulario_cuatro.edit',
            'formulario_cuatro.destroy',

            'detalle_formularios.index',
            'detalle_formularios.create',
            'detalle_formularios.edit',
            'detalle_formularios.destroy',

            'formulario_cinco.index',
            'formulario_cinco.create',
            'formulario_cinco.edit',
            'formulario_cinco.destroy',

            'memoria_calculos.index',
            'memoria_calculos.create',
            'memoria_calculos.edit',
            'memoria_calculos.destroy',

            'aprobar.modulos',

            "reportes.formulario_cuatro",
            "reportes.formulario_cinco",
            "reportes.memoria_calculos",
            "reportes.saldos_actividad",
            "reportes.saldos_partida",
            "reportes.ejecucion_presupuestos",
            "reportes.ejecucion_presupuestos_g",
        ],
        'DIRECTORES' => [
            'certificacions.index',
            'certificacions.create',
            'certificacions.edit',
            'certificacions.destroy',

            "seguimiento_trimestral.index",

            'formulario_cuatro.index',
            'formulario_cuatro.create',
            'formulario_cuatro.edit',
            'formulario_cuatro.destroy',

            'detalle_formularios.index',
            'detalle_formularios.create',
            'detalle_formularios.edit',
            'detalle_formularios.destroy',

            'formulario_cinco.index',
            'formulario_cinco.create',
            'formulario_cinco.edit',
            'formulario_cinco.destroy',

            'memoria_calculos.index',
            'memoria_calculos.create',
            'memoria_calculos.edit',
            'memoria_calculos.destroy',

            'aprobar.modulos',

            "reportes.formulario_cuatro",
            "reportes.formulario_cinco",
            "reportes.memoria_calculos",
            "reportes.saldos_actividad",
            "reportes.saldos_partida",
            "reportes.ejecucion_presupuestos",
            "reportes.ejecucion_presupuestos_g",
        ],
        'JEFES DE ÁREAS' => [
            'certificacions.index',
            'certificacions.create',
            'certificacions.edit',
            'certificacions.destroy',

            "seguimiento_trimestral.index",

            'formulario_cuatro.index',
            'formulario_cuatro.create',
            'formulario_cuatro.edit',
            'formulario_cuatro.destroy',
            
            'detalle_formularios.index',
            'detalle_formularios.create',
            'detalle_formularios.edit',
            'detalle_formularios.destroy',
            
            'formulario_cinco.index',
            'formulario_cinco.create',
            'formulario_cinco.edit',
            'formulario_cinco.destroy',
            
            'memoria_calculos.index',
            'memoria_calculos.create',
            'memoria_calculos.edit',
            'memoria_calculos.destroy',
            
            'aprobar.modulos',

            "reportes.formulario_cuatro",
            "reportes.formulario_cinco",
            "reportes.memoria_calculos",
            "reportes.saldos_actividad",
            "reportes.saldos_partida",
            "reportes.ejecucion_presupuestos",
            "reportes.ejecucion_presupuestos_g",
        ],
        'FINANCIERA' => [
            'formulario_cinco.index',
            'formulario_cinco.create',
            'formulario_cinco.edit',
            'formulario_cinco.destroy',

            'memoria_calculos.index',
            'memoria_calculos.create',
            'memoria_calculos.edit',
            'memoria_calculos.destroy',
        ],
        'MAE' => [
            "reportes.fisicos",
            "reportes.financieros",
            "reportes.semaforos",
        ],
    ];


    public function index(Request $request)
    {
        $usuarios = User::where('id', '!=', 1)->get();
        return response()->JSON(['usuarios' => $usuarios, 'total' => count($usuarios)], 200);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:2048';
        }

        $request->validate($this->validacion, $this->mensajes);
        $cont = 0;
        do {
            $nombre_usuario = User::getNombreUsuario($request->nombre, $request->paterno);
            if ($cont > 0) {
                $nombre_usuario = $nombre_usuario . $cont;
            }
            $request['usuario'] = $nombre_usuario;
            $cont++;
        } while (User::where('usuario', $nombre_usuario)->get()->first());
        $request['password'] = 'NoNulo';
        $request['fecha_registro'] = date('Y-m-d');
        // CREAR EL USER
        $nuevo_usuario = User::create(array_map('mb_strtoupper', $request->except('foto')));
        $nuevo_usuario->password = Hash::make($request->ci);
        $nuevo_usuario->save();
        $nuevo_usuario->foto = 'default.png';
        if ($request->hasFile('foto')) {
            $file = $request->foto;
            $nom_foto = time() . '_' . $nuevo_usuario->usuario . '.' . $file->getClientOriginalExtension();
            $nuevo_usuario->foto = $nom_foto;
            $file->move(public_path() . '/imgs/users/', $nom_foto);
        }

        $nuevo_usuario->save();

        $user = Auth::user();
        Log::registrarLog("CREACIÓN", "USUARIOS", "EL USUARIO $user->id REGISTRO UN USUARIO", $user);

        return response()->JSON([
            'sw' => true,
            'usuario' => $nuevo_usuario,
            'msj' => 'El registro se realizó de forma correcta',
        ], 200);
    }

    public function update(Request $request, User $usuario)
    {
        $this->validacion['ci'] = 'required|min:4|numeric|unique:users,ci,' . $usuario->id;
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:2048';
        }

        $request->validate($this->validacion, $this->mensajes);

        $usuario->update(array_map('mb_strtoupper', $request->except('foto')));

        if ($request->hasFile('foto')) {
            $antiguo = $usuario->foto;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/users/' . $antiguo);
            }
            $file = $request->foto;
            $nom_foto = time() . '_' . $usuario->usuario . '.' . $file->getClientOriginalExtension();
            $usuario->foto = $nom_foto;
            $file->move(public_path() . '/imgs/users/', $nom_foto);
        }
        $usuario->save();


        $user = Auth::user();
        Log::registrarLog("MODIFICACIÓN", "USUARIOS", "EL USUARIO $user->id MODIFICÓ UN USUARIO", $user);

        return response()->JSON([
            'sw' => true,
            'usuario' => $usuario,
            'msj' => 'El registro se actualizó de forma correcta'
        ], 200);
    }

    public function show(User $usuario)
    {
        return response()->JSON([
            'sw' => true,
            'usuario' => $usuario
        ], 200);
    }

    public function actualizaContrasenia(User $usuario, Request $request)
    {
        $request->validate([
            'password_actual' => ['required', function ($attribute, $value, $fail) use ($usuario, $request) {
                if (!\Hash::check($request->password_actual, $usuario->password)) {
                    return $fail(__('La contraseña no coincide con la actual.'));
                }
            }],
            'password' => 'required|confirmed|min:4',
            'password_confirmation' => 'required|min:4'
        ]);

        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return response()->JSON([
            'sw' => true,
            'msj' => 'La contraseña se actualizó correctamente'
        ], 200);
    }

    public function actualizaFoto(User $usuario, Request $request)
    {
        if ($request->hasFile('foto')) {
            $antiguo = $usuario->foto;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/users/' . $antiguo);
            }
            $file = $request->foto;
            $nom_foto = time() . '_' . $usuario->usuario . '.' . $file->getClientOriginalExtension();
            $usuario->foto = $nom_foto;
            $file->move(public_path() . '/imgs/users/', $nom_foto);
        }
        $usuario->save();
        return response()->JSON([
            'sw' => true,
            'usuario' => $usuario,
            'msj' => 'Foto actualizada con éxito'
        ], 200);
    }

    public function destroy(User $usuario)
    {

        $existe = Certificacion::where("solicitante_id", $usuario->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "user" => $usuario, "msj" => "No es posible eliminar este registro, porque esta siendo utilizado por otros modulos"]);
        }
        $existe = Certificacion::where("superior_id", $usuario->id)->get();
        if (count($existe) > 0) {
            return response()->JSON(["sw" => false, "user" => $usuario, "msj" => "No es posible eliminar este registro, porque esta siendo utilizado por otros modulos"]);
        }
        $antiguo = $usuario->foto;
        if ($antiguo != 'default.png') {
            \File::delete(public_path() . '/imgs/users/' . $antiguo);
        }
        $usuario->delete();

        $user = Auth::user();
        Log::registrarLog("ELIMINACIÓN", "USUARIOS", "EL USUARIO $user->id ELIMINÓ UN USUARIO", $user);

        return response()->JSON([
            'sw' => true,
            'msj' => 'El registro se eliminó correctamente'
        ], 200);
    }

    public function getPermisos(User $usuario)
    {
        $tipo = $usuario->tipo;

        $permisos = $this->permisos[$tipo];

        $index = [];

        $conf_form4 = ConfiguracionModulo::where("modulo", "FORMULARIO 4")->get()->first();
        if ($conf_form4->editar == 0) {
            // agregar index permiso
            $index[] = array_search("formulario_cuatro.edit", $permisos);
        }
        if ($conf_form4->eliminar == 0) {
            // agregar index permiso
            $index[] = array_search("formulario_cuatro.destroy", $permisos);
        }
        $conf_dform4 = ConfiguracionModulo::where("modulo", "DETALLE FORMULARIO 4")->get()->first();
        if ($conf_dform4->editar == 0) {
            // agregar index permiso
            $index[] = array_search("detalle_formularios.edit", $permisos);
        }
        if ($conf_dform4->eliminar == 0) {
            // agregar index permiso
            $index[] = array_search("detalle_formularios.destroy", $permisos);
        }
        $conf_mcalculo = ConfiguracionModulo::where("modulo", "MEMORIA DE CÁLCULO")->get()->first();
        if ($conf_mcalculo->editar == 0) {
            // agregar index permiso
            $index[] = array_search("memoria_calculos.edit", $permisos);
        }
        if ($conf_mcalculo->eliminar == 0) {
            // agregar index permiso
            $index[] = array_search("memoria_calculos.destroy", $permisos);
        }

        // remover permisos
        foreach ($index as $value) {
            unset($permisos[$value]);
        }

        return response()->JSON($permisos);
    }

    public function getInfoBox()
    {
        $tipo = Auth::user()->tipo;
        $array_infos = [];
        if (in_array('usuarios.index', $this->permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Usuarios',
                'cantidad' => count(User::where('id', '!=', 1)->get()),
                'color' => 'bg-info',
                'icon' => 'fas fa-users',
            ];
        }
        return response()->JSON($array_infos);
    }

    public function userActual()
    {
        return response()->JSON(Auth::user());
    }

    public function getEstudiantes()
    {
        return response()->JSON(User::where('tipo', 'ESTUDIANTE')->get());
    }

    public function getDocentes()
    {
        return response()->JSON(User::where('tipo', 'DOCENTE')->get());
    }

    public function getUsuario(User $usuario)
    {
        return response()->JSON($usuario);
    }
}
