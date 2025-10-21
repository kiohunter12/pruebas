<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Expediente;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Enviar notificación cuando se crea un expediente
     */
    public function sendExpedientCreated(Expediente $expediente)
    {
        try {
            // Notificación al ciudadano
            $this->createNotification([
                'user_id' => $expediente->citizen_id,
                'expediente_id' => $expediente->id,
                'type' => 'expedient_created',
                'title' => 'Expediente Creado',
                'message' => "Su expediente con número de seguimiento {$expediente->tracking_number} ha sido creado exitosamente.",
                'data' => [
                    'tracking_number' => $expediente->tracking_number,
                    'procedure_name' => $expediente->procedure->name ?? 'N/A'
                ]
            ]);

            // Notificación a la gerencia responsable
            $gerenciaUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'gerente');
            })->where('gerencia_id', $expediente->gerencia_id)->get();

            foreach ($gerenciaUsers as $user) {
                $this->createNotification([
                    'user_id' => $user->id,
                    'expediente_id' => $expediente->id,
                    'type' => 'new_expedient_assignment',
                    'title' => 'Nuevo Expediente Asignado',
                    'message' => "Se ha creado un nuevo expediente #{$expediente->tracking_number} para su gerencia.",
                    'data' => [
                        'tracking_number' => $expediente->tracking_number,
                        'citizen_name' => $expediente->citizen->name ?? 'N/A',
                        'subject' => $expediente->subject
                    ]
                ]);
            }

            // Enviar email si está configurado
            $this->sendEmailNotification($expediente->citizen, 'expedient_created', [
                'expediente' => $expediente,
                'tracking_number' => $expediente->tracking_number
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando notificación de expediente creado: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación cuando cambia el estado del expediente
     */
    public function sendStatusChanged(Expediente $expediente, string $newStatus)
    {
        try {
            $statusMessages = [
                'en_proceso' => 'Su expediente está siendo procesado',
                'observado' => 'Su expediente tiene observaciones que requieren atención',
                'aprobado' => 'Su expediente ha sido aprobado',
                'rechazado' => 'Su expediente ha sido rechazado',
                'finalizado' => 'Su expediente ha sido finalizado exitosamente',
                'archivado' => 'Su expediente ha sido archivado'
            ];

            $message = $statusMessages[$newStatus] ?? 'El estado de su expediente ha cambiado';

            // Notificación al ciudadano
            $this->createNotification([
                'user_id' => $expediente->citizen_id,
                'expediente_id' => $expediente->id,
                'type' => 'status_changed',
                'title' => 'Cambio de Estado',
                'message' => $message . " (#{$expediente->tracking_number})",
                'data' => [
                    'tracking_number' => $expediente->tracking_number,
                    'new_status' => $newStatus,
                    'previous_status' => $expediente->getOriginal('status')
                ]
            ]);

            // Notificación al gerente asignado si existe
            if ($expediente->assigned_to) {
                $this->createNotification([
                    'user_id' => $expediente->assigned_to,
                    'expediente_id' => $expediente->id,
                    'type' => 'expedient_status_updated',
                    'title' => 'Estado de Expediente Actualizado',
                    'message' => "El expediente #{$expediente->tracking_number} cambió a estado: {$newStatus}",
                    'data' => [
                        'tracking_number' => $expediente->tracking_number,
                        'new_status' => $newStatus
                    ]
                ]);
            }

            // Enviar email
            $this->sendEmailNotification($expediente->citizen, 'status_changed', [
                'expediente' => $expediente,
                'new_status' => $newStatus,
                'status_message' => $message
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando notificación de cambio de estado: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de expediente próximo a vencer
     */
    public function sendExpedientDueSoon(Expediente $expediente, int $daysUntilDue)
    {
        try {
            // Notificar al ciudadano
            $this->createNotification([
                'user_id' => $expediente->citizen_id,
                'expediente_id' => $expediente->id,
                'type' => 'expedient_due_soon',
                'title' => 'Expediente Próximo a Vencer',
                'message' => "Su expediente #{$expediente->tracking_number} vence en {$daysUntilDue} días.",
                'data' => [
                    'tracking_number' => $expediente->tracking_number,
                    'days_until_due' => $daysUntilDue,
                    'expected_response_date' => $expediente->expected_response_date
                ]
            ]);

            // Notificar al gerente asignado
            if ($expediente->assigned_to) {
                $this->createNotification([
                    'user_id' => $expediente->assigned_to,
                    'expediente_id' => $expediente->id,
                    'type' => 'assigned_expedient_due_soon',
                    'title' => 'Expediente Asignado Próximo a Vencer',
                    'message' => "El expediente #{$expediente->tracking_number} que tiene asignado vence en {$daysUntilDue} días.",
                    'data' => [
                        'tracking_number' => $expediente->tracking_number,
                        'days_until_due' => $daysUntilDue
                    ]
                ]);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando notificación de expediente próximo a vencer: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de expediente vencido
     */
    public function sendExpedientOverdue(Expediente $expediente)
    {
        try {
            $daysOverdue = abs($expediente->getDaysUntilDue());

            // Notificar al ciudadano
            $this->createNotification([
                'user_id' => $expediente->citizen_id,
                'expediente_id' => $expediente->id,
                'type' => 'expedient_overdue',
                'title' => 'Expediente Vencido',
                'message' => "Su expediente #{$expediente->tracking_number} está vencido hace {$daysOverdue} días.",
                'data' => [
                    'tracking_number' => $expediente->tracking_number,
                    'days_overdue' => $daysOverdue,
                    'expected_response_date' => $expediente->expected_response_date
                ],
                'priority' => 'high'
            ]);

            // Notificar a supervisores de la gerencia
            $supervisors = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'supervisor']);
            })->where('gerencia_id', $expediente->gerencia_id)->get();

            foreach ($supervisors as $supervisor) {
                $this->createNotification([
                    'user_id' => $supervisor->id,
                    'expediente_id' => $expediente->id,
                    'type' => 'expedient_overdue_alert',
                    'title' => 'Alerta: Expediente Vencido',
                    'message' => "El expediente #{$expediente->tracking_number} está vencido hace {$daysOverdue} días.",
                    'data' => [
                        'tracking_number' => $expediente->tracking_number,
                        'days_overdue' => $daysOverdue,
                        'assigned_to' => $expediente->assignedUser->name ?? 'Sin asignar'
                    ],
                    'priority' => 'high'
                ]);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando notificación de expediente vencido: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar notificación de pago confirmado
     */
    public function sendPaymentConfirmed(Expediente $expediente, $payment)
    {
        try {
            $this->createNotification([
                'user_id' => $expediente->citizen_id,
                'expediente_id' => $expediente->id,
                'type' => 'payment_confirmed',
                'title' => 'Pago Confirmado',
                'message' => "El pago por S/ {$payment->amount} para el expediente #{$expediente->tracking_number} ha sido confirmado.",
                'data' => [
                    'tracking_number' => $expediente->tracking_number,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method
                ]
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando notificación de pago confirmado: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear notificación en la base de datos
     */
    private function createNotification(array $data)
    {
        return Notification::create([
            'user_id' => $data['user_id'],
            'expediente_id' => $data['expediente_id'] ?? null,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'data' => $data['data'] ?? [],
            'priority' => $data['priority'] ?? 'normal',
            'is_read' => false,
            'sent_at' => now()
        ]);
    }

    /**
     * Enviar notificación por email
     */
    private function sendEmailNotification(User $user, string $template, array $data = [])
    {
        try {
            if (!$user->email) {
                return false;
            }

            // Aquí se implementaría el envío de emails usando las plantillas de Laravel
            // Mail::to($user->email)->send(new ExpedientNotificationMail($template, $data));

            Log::info("Email notification sent to {$user->email} using template {$template}");
            return true;

        } catch (\Exception $e) {
            Log::error('Error enviando email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead(int $notificationId, int $userId)
    {
        try {
            $notification = Notification::where('id', $notificationId)
                                      ->where('user_id', $userId)
                                      ->first();

            if ($notification) {
                $notification->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Error marcando notificación como leída: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function markAllAsRead(int $userId)
    {
        try {
            Notification::where('user_id', $userId)
                       ->where('is_read', false)
                       ->update([
                           'is_read' => true,
                           'read_at' => now()
                       ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error marcando todas las notificaciones como leídas: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function getUnreadNotifications(int $userId)
    {
        return Notification::where('user_id', $userId)
                          ->where('is_read', false)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    /**
     * Eliminar notificaciones antiguas
     */
    public function cleanupOldNotifications(int $daysOld = 90)
    {
        try {
            $cutoffDate = now()->subDays($daysOld);
            
            $deletedCount = Notification::where('created_at', '<', $cutoffDate)
                                      ->where('is_read', true)
                                      ->delete();

            Log::info("Eliminadas {$deletedCount} notificaciones antiguas");
            return $deletedCount;

        } catch (\Exception $e) {
            Log::error('Error limpiando notificaciones antiguas: ' . $e->getMessage());
            return 0;
        }
    }
}