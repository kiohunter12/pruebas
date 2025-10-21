<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Support\Facades\DB;

class WebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login', 'showRegister', 'register']);
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Log para debug
        \Log::info('Intento de login', [
            'email' => $credentials['email'],
            'ip' => $request->ip()
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            \Log::info('Login exitoso', ['user_id' => Auth::id()]);
            
            // Si es una request AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'redirect' => route('dashboard')
                ]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        \Log::warning('Login fallido', ['email' => $credentials['email']]);

        // Si es una request AJAX, devolver JSON con errores
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
                'errors' => [
                    'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros.']
                ]
            ], 422);
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration (siempre con rol ciudadano)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'size:8', 'unique:users,dni', 'regex:/^[0-9]{8}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.size' => 'El DNI debe tener 8 dígitos.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'dni.regex' => 'El DNI debe contener solo números.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'dni' => $request->dni,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Asignar rol de ciudadano automáticamente
            $user->assignRole('ciudadano');

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error en registro', ['error' => $e->getMessage()]);
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Ocurrió un error al crear tu cuenta.');
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_expedientes' => 0,
            'expedientes_pendientes' => 0,
            'expedientes_completados' => 0,
            'usuarios_activos' => User::count(),
        ];

        $recent_expedientes = collect();

        return view('dashboard', compact('stats', 'recent_expedientes'));
    }

    /**
     * Show users management
     */
    public function usuarios()
    {
        return view('usuarios.index');
    }

    /**
     * Show expedientes
     */
    public function expedientes()
    {
        return view('expedientes.index');
    }

    /**
     * Show roles management
     */
    public function roles()
    {
        return view('roles.index');
    }

    /**
     * Show create expediente
     */
    public function createExpediente()
    {
        return view('expedientes.create');
    }

    /**
     * Show permissions
     */
    public function permisos()
    {
        // Obtener todos los permisos agrupados por módulo
        $permisos = \Spatie\Permission\Models\Permission::all();
        
        // Definir categorías de módulos
        $modulosMap = [
            'expedientes' => ['ver_expedientes', 'registrar_expediente', 'editar_expediente', 'derivar_expediente', 'emitir_resolucion', 'rechazar_expediente', 'finalizar_expediente', 'archivar_expediente', 'subir_documento', 'ver_todos_expedientes', 'asignar_expediente', 'reasignar_expediente', 'consultar_historial', 'exportar_expedientes', 'eliminar_expediente'],
            'usuarios' => ['gestionar_usuarios', 'crear_usuarios', 'editar_usuarios', 'eliminar_usuarios', 'ver_todos_usuarios', 'asignar_usuarios_gerencia'],
            'roles_permisos' => ['asignar_roles', 'gestionar_permisos'],
            'gerencias' => ['gestionar_gerencias', 'crear_gerencias', 'editar_gerencias'],
            'procedimientos' => ['gestionar_procedimientos', 'crear_procedimientos', 'eliminar_procedimientos'],
            'tipos_tramite' => ['gestionar_tipos_tramite', 'crear_tipos_tramite', 'editar_tipos_tramite', 'eliminar_tipos_tramite', 'activar_tipos_tramite', 'ver_tipos_tramite'],
            'reportes' => ['ver_reportes', 'exportar_datos', 'ver_estadisticas_gerencia', 'ver_estadisticas_sistema'],
            'configuracion' => ['configurar_sistema', 'gestionar_respaldos', 'ver_logs'],
            'notificaciones' => ['enviar_notificaciones', 'gestionar_notificaciones'],
            'pagos' => ['gestionar_pagos', 'confirmar_pagos', 'ver_pagos'],
            'quejas' => ['gestionar_quejas', 'responder_quejas', 'escalar_quejas'],
            'workflows' => ['gestionar_workflows', 'crear_workflows', 'editar_workflows', 'eliminar_workflows', 'ver_workflows', 'activar_workflows', 'clonar_workflows', 'crear_reglas_flujo', 'editar_reglas_flujo', 'eliminar_reglas_flujo', 'ver_reglas_flujo', 'activar_desactivar_reglas', 'crear_etapas_flujo', 'editar_etapas_flujo', 'eliminar_etapas_flujo', 'ver_etapas_flujo'],
        ];
        
        // Agrupar permisos por módulo
        $permisosPorModulo = collect();
        foreach ($modulosMap as $modulo => $permisosNombres) {
            $permisosModulo = $permisos->filter(function($permiso) use ($permisosNombres) {
                return in_array($permiso->name, $permisosNombres);
            });
            
            if ($permisosModulo->count() > 0) {
                $permisosPorModulo->put($modulo, $permisosModulo);
            }
        }
        
        // Agregar permisos no categorizados
        $permisosCategorizados = collect($modulosMap)->flatten()->toArray();
        $permisosOtros = $permisos->filter(function($permiso) use ($permisosCategorizados) {
            return !in_array($permiso->name, $permisosCategorizados);
        });
        
        if ($permisosOtros->count() > 0) {
            $permisosPorModulo->put('otros', $permisosOtros);
        }
        
        // Estadísticas
        $stats = [
            'total_permisos' => $permisos->count(),
            'permisos_activos' => $permisos->count(),
            'total_modulos' => $permisosPorModulo->count(),
            'permisos_criticos' => $permisos->filter(function($permiso) {
                return str_contains($permiso->name, 'eliminar') || 
                       str_contains($permiso->name, 'gestionar_respaldos') ||
                       str_contains($permiso->name, 'configurar_sistema');
            })->count()
        ];
        
        return view('permisos.index', compact('permisosPorModulo', 'stats'));
    }

    /**
     * Show edit expediente
     */
    public function editExpediente($id)
    {
        try {
            $expediente = Expediente::findOrFail($id);
            return view('expedientes.edit', compact('expediente'));
        } catch (\Exception $e) {
            return redirect()->route('expedientes.index')->with('error', 'Expediente no encontrado.');
        }
    }

    /**
     * Show mesa de partes
     */
    public function mesaPartes()
    {
        return view('mesa-partes.index');
    }

    /**
     * Show administracion
     */
    public function administracion()
    {
        return view('administracion.index');
    }

    /**
     * Show reportes
     */
    public function reportes()
    {
        return view('reportes.index');
    }

    /**
     * Show configuration
     */
    public function configuracion()
    {
        return view('configuracion.index');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        return view('profile.show');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Show welcome page (for testing routes)
     */
    public function welcome()
    {
        return view('welcome');
    }

    // Mesa de Partes methods
    public function mesaPartesDerivacion()
    {
        return view('mesa-partes.derivacion');
    }

    public function mesaPartesRegistro()
    {
        return view('mesa-partes.registro');
    }

    // Expedientes filtering methods
    public function expedientesPendientes()
    {
        return view('expedientes.pendientes');
    }

    public function expedientesProceso()
    {
        return view('expedientes.proceso');
    }

    public function expedientesFinalizados()
    {
        return view('expedientes.finalizados');
    }

    // Reportes methods
    public function reportesExpedientes()
    {
        return view('reportes.expedientes');
    }

    public function reportesTramites()
    {
        return view('reportes.tramites');
    }

    public function reportesTiempos()
    {
        return view('reportes.tiempos');
    }

    // Settings method
    public function settings()
    {
        return view('settings.index');
    }
}
