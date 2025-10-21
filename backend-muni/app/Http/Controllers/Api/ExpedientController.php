<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expediente;
use App\Models\Procedure;
use App\Models\Gerencia;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExpedientController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of expedients
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Expediente::with(['citizen', 'procedure', 'gerencia', 'assignedUser']);

            // Filtros
            if ($request->has('status')) {
                $query->byStatus($request->status);
            }

            if ($request->has('gerencia_id')) {
                $query->byGerencia($request->gerencia_id);
            }

            if ($request->has('citizen_id')) {
                $query->byCitizen($request->citizen_id);
            }

            if ($request->has('tracking_number')) {
                $query->byTrackingNumber($request->tracking_number);
            }

            if ($request->has('overdue') && $request->overdue == 'true') {
                $query->overdue();
            }

            // Paginación
            $perPage = $request->get('per_page', 15);
            $expedients = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $expedients,
                'message' => 'Expedientes obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener expedientes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created expedient
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'citizen_id' => 'required|exists:users,id',
                'procedure_id' => 'required|exists:procedures,id',
                'gerencia_id' => 'required|exists:gerencias,id',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'priority' => 'nullable|in:baja,media,alta,urgente',
                'files' => 'nullable|array',
                'files.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,png'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear expediente
            $expediente = Expediente::create([
                'citizen_id' => $request->citizen_id,
                'procedure_id' => $request->procedure_id,
                'gerencia_id' => $request->gerencia_id,
                'subject' => $request->subject,
                'description' => $request->description,
                'priority' => $request->priority ?? 'media',
                'status' => Expediente::STATUS_INICIADO,
                'payment_status' => 'pending'
            ]);

            // Calcular fecha esperada de respuesta
            $procedure = Procedure::find($request->procedure_id);
            if ($procedure && $procedure->max_days) {
                $expediente->expected_response_date = now()->addDays($procedure->max_days);
                $expediente->save();
            }

            // Procesar archivos si existen
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('expedients/' . $expediente->id, 'public');
                    
                    $expediente->files()->create([
                        'filename' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_by' => Auth::id()
                    ]);
                }
            }

            // Enviar notificación
            $this->notificationService->sendExpedientCreated($expediente);

            return response()->json([
                'success' => true,
                'data' => $expediente->load(['citizen', 'procedure', 'gerencia', 'files']),
                'message' => 'Expediente creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear expediente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified expedient
     */
    public function show(string $id): JsonResponse
    {
        try {
            $expediente = Expediente::with([
                'citizen', 
                'procedure', 
                'gerencia', 
                'assignedUser',
                'files',
                'payments',
                'notifications',
                'actionLogs',
                'historial',
                'complaints'
            ])->find($id);

            if (!$expediente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expediente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $expediente,
                'message' => 'Expediente obtenido exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener expediente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified expedient
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $expediente = Expediente::find($id);

            if (!$expediente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expediente no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'nullable|in:iniciado,en_proceso,observado,aprobado,rechazado,finalizado,archivado',
                'priority' => 'nullable|in:baja,media,alta,urgente',
                'subject' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'notes' => 'nullable|string',
                'assigned_to' => 'nullable|exists:users,id',
                'actual_response_date' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $expediente->update($request->only([
                'status', 'priority', 'subject', 'description', 
                'notes', 'assigned_to', 'actual_response_date'
            ]));

            // Enviar notificación si cambió el estado
            if ($request->has('status')) {
                $this->notificationService->sendStatusChanged($expediente, $request->status);
            }

            return response()->json([
                'success' => true,
                'data' => $expediente->load(['citizen', 'procedure', 'gerencia', 'assignedUser']),
                'message' => 'Expediente actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar expediente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified expedient
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $expediente = Expediente::find($id);

            if (!$expediente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expediente no encontrado'
                ], 404);
            }

            // Soft delete
            $expediente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expediente eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar expediente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get expedient by tracking number (public method)
     */
    public function getByTrackingNumber(string $trackingNumber): JsonResponse
    {
        try {
            $expediente = Expediente::with([
                'procedure:id,name,description',
                'gerencia:id,name'
            ])->byTrackingNumber($trackingNumber)->first();

            if (!$expediente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expediente no encontrado con ese número de seguimiento'
                ], 404);
            }

            // Solo mostrar información pública
            $publicData = [
                'tracking_number' => $expediente->tracking_number,
                'status' => $expediente->status,
                'subject' => $expediente->subject,
                'created_at' => $expediente->created_at,
                'expected_response_date' => $expediente->expected_response_date,
                'procedure' => $expediente->procedure,
                'gerencia' => $expediente->gerencia,
                'is_overdue' => $expediente->isOverdue()
            ];

            return response()->json([
                'success' => true,
                'data' => $publicData,
                'message' => 'Expediente encontrado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar expediente: ' . $e->getMessage()
            ], 500);
        }
    }
}
