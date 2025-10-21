<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadministrador|administrador')->except(['show']);
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'gerencia'])
                    ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('gerencia_id')) {
            $query->where('gerencia_id', $request->gerencia_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->paginate(15);
        
        // Para filtros
        $roles = Role::all();
        $gerencias = Gerencia::where('activo', true)->get();
        
        // Estadísticas
        $stats = [
            'total' => User::count(),
            'activos' => User::where('estado', 'activo')->count(),
            'inactivos' => User::where('estado', 'inactivo')->count(),
            'con_roles' => User::has('roles')->count(),
        ];

        return view('usuarios.index', compact('usuarios', 'roles', 'gerencias', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $gerencias = Gerencia::where('activo', true)->get();
        
        return view('usuarios.create', compact('roles', 'gerencias'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'dni' => 'nullable|string|max:20|unique:users',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'estado' => 'required|in:activo,inactivo,suspendido',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'telefono' => $validated['telefono'],
            'password' => Hash::make($validated['password']),
            'gerencia_id' => $validated['gerencia_id'],
            'estado' => $validated['estado'],
        ]);

        // Asignar roles
        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $usuario)
    {
        $usuario->load(['roles', 'gerencia', 'permissions']);
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $usuario)
    {
        $roles = Role::all();
        $gerencias = Gerencia::where('activo', true)->get();
        
        return view('usuarios.edit', compact('usuario', 'roles', 'gerencias'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'dni' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($usuario->id)],
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'estado' => 'required|in:activo,inactivo,suspendido',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'telefono' => $validated['telefono'],
            'gerencia_id' => $validated['gerencia_id'],
            'estado' => $validated['estado'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $usuario->update($updateData);

        // Sincronizar roles
        if (isset($validated['roles'])) {
            $usuario->syncRoles($validated['roles']);
        } else {
            $usuario->syncRoles([]);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $usuario)
    {
        // No permitir eliminar el usuario actual
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // No permitir eliminar superadministrador
        if ($usuario->hasRole('superadministrador')) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No se puede eliminar un superadministrador.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(User $usuario)
    {
        $nuevoEstado = $usuario->estado === 'activo' ? 'inactivo' : 'activo';
        $usuario->update(['estado' => $nuevoEstado]);

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario {$nuevoEstado} exitosamente.");
    }
}