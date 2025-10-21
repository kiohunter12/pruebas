<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoTramite;

class TramiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tramites = TipoTramite::with(['gerencia', 'expedientes'])
            ->orderBy('nombre')
            ->paginate(15);
        
        // Obtener workflows asociados a cada tipo de trámite
        foreach ($tramites as $tramite) {
            // Buscar workflow específico por tipo_tramite_id en configuración
            $tramite->workflow = \App\Models\Workflow::where('activo', true)
                ->where(function($query) use ($tramite) {
                    $query->whereJsonContains('configuracion->tipo_tramite_id', $tramite->id)
                          ->orWhere('configuracion->tipo_tramite_id', (string)$tramite->id);
                })
                ->with(['steps' => function($query) {
                    $query->orderBy('orden');
                }, 'gerencia'])
                ->first();
        }
        
        return view('tramites.index', compact('tramites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tramites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'requisitos' => 'nullable|json',
            'tiempo_estimado' => 'nullable|integer|min:1',
            'costo' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        TipoTramite::create($validated);

        return redirect()->route('tramites.index')
            ->with('success', 'Tipo de trámite creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoTramite $tramite)
    {
        return view('tramites.show', compact('tramite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoTramite $tramite)
    {
        return view('tramites.edit', compact('tramite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoTramite $tramite)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'requisitos' => 'nullable|json',
            'tiempo_estimado' => 'nullable|integer|min:1',
            'costo' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $tramite->update($validated);

        return redirect()->route('tramites.index')
            ->with('success', 'Tipo de trámite actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoTramite $tramite)
    {
        $tramite->delete();

        return redirect()->route('tramites.index')
            ->with('success', 'Tipo de trámite eliminado exitosamente.');
    }
}