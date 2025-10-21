<?php

namespace App\Http\Controllers;

use App\Models\DocumentoExpediente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    /**
     * Descargar documento de expediente.
     */
    public function download(DocumentoExpediente $documento): Response
    {
        // Verificar que el usuario tenga acceso al expediente
        $expediente = $documento->expediente;
        
        if (!$this->usuarioPuedeAcceder($expediente)) {
            abort(403, 'No tienes permiso para acceder a este documento');
        }

        // Verificar que el archivo existe
        if (!Storage::disk('private')->exists($documento->archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        // Obtener el archivo
        $archivo = Storage::disk('private')->get($documento->archivo);
        
        // Registrar descarga en historial
        $this->registrarDescarga($documento);

        // Retornar archivo para descarga
        return response($archivo)
            ->header('Content-Type', $documento->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $documento->nombre . '.' . $documento->extension . '"')
            ->header('Content-Length', $documento->tamaño);
    }

    /**
     * Ver documento en el navegador.
     */
    public function view(DocumentoExpediente $documento): Response
    {
        // Verificar que el usuario tenga acceso al expediente
        $expediente = $documento->expediente;
        
        if (!$this->usuarioPuedeAcceder($expediente)) {
            abort(403, 'No tienes permiso para acceder a este documento');
        }

        // Verificar que el archivo existe
        if (!Storage::disk('private')->exists($documento->archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        // Obtener el archivo
        $archivo = Storage::disk('private')->get($documento->archivo);
        
        // Registrar visualización en historial
        $this->registrarVisualizacion($documento);

        // Retornar archivo para visualización
        return response($archivo)
            ->header('Content-Type', $documento->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $documento->nombre . '.' . $documento->extension . '"')
            ->header('Content-Length', $documento->tamaño);
    }

    /**
     * Verificar si el usuario puede acceder al expediente.
     */
    private function usuarioPuedeAcceder($expediente): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Mesa de Partes puede ver todos los expedientes que registró
        if ($user->hasRole('mesa_partes')) {
            return $expediente->usuario_registro_id === $user->id;
        }

        // Gerente Urbano puede ver expedientes de su gerencia
        if ($user->hasRole('gerente_urbano')) {
            return $expediente->gerencia_id === ($user->gerencia_id ?? 0);
        }

        // Secretaria General puede ver expedientes que requieren revisión legal
        if ($user->hasRole('secretaria_general')) {
            return $expediente->requiere_informe_legal;
        }

        // Alcalde puede ver expedientes que son actos administrativos mayores
        if ($user->hasRole('alcalde')) {
            return $expediente->es_acto_administrativo_mayor;
        }

        // Usuarios con permisos específicos
        if ($user->hasPermissionTo('ver_expedientes')) {
            return true;
        }

        return false;
    }

    /**
     * Registrar descarga de documento.
     */
    private function registrarDescarga(DocumentoExpediente $documento): void
    {
        // Aquí podrías registrar la descarga en un log o historial
        // Por ejemplo, crear un registro en la tabla de historial
        $expediente = $documento->expediente;
        
        $expediente->historial()->create([
            'usuario_id' => Auth::id(),
            'accion' => 'descargar_documento',
            'estado_anterior' => $expediente->estado,
            'estado_nuevo' => $expediente->estado,
            'descripcion' => 'Documento descargado: ' . $documento->nombre,
            'datos_adicionales' => [
                'documento_id' => $documento->id,
                'documento_nombre' => $documento->nombre,
                'tipo_documento' => $documento->tipo_documento,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Registrar visualización de documento.
     */
    private function registrarVisualizacion(DocumentoExpediente $documento): void
    {
        // Aquí podrías registrar la visualización en un log o historial
        $expediente = $documento->expediente;
        
        $expediente->historial()->create([
            'usuario_id' => Auth::id(),
            'accion' => 'visualizar_documento',
            'estado_anterior' => $expediente->estado,
            'estado_nuevo' => $expediente->estado,
            'descripcion' => 'Documento visualizado: ' . $documento->nombre,
            'datos_adicionales' => [
                'documento_id' => $documento->id,
                'documento_nombre' => $documento->nombre,
                'tipo_documento' => $documento->tipo_documento,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
