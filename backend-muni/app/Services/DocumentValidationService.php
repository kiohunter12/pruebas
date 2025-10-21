<?php

namespace App\Services;

use App\Models\Expediente;
use App\Models\WorkflowStep;
use Carbon\Carbon;

class DocumentValidationService
{
    /**
     * Validate if expediente meets TUPA requirements for the current step
     */
    public function validateTupaRequirements(Expediente $expediente): array
    {
        $errors = [];

        // Check TUPA deadline compliance
        if ($this->isBeyondTupaDeadline($expediente)) {
            $errors[] = 'Expediente ha excedido el plazo TUPA';
        }

        // Check required documents for current step
        if ($this->missingRequiredDocuments($expediente)) {
            $errors[] = 'Faltan documentos requeridos para este paso';
        }

        // Check business day calculations
        if ($this->isOutsideBusinessHours($expediente)) {
            $errors[] = 'Procesamiento fuera del horario de atención';
        }

        return $errors;
    }

    /**
     * Calculate TUPA deadline based on procedure type and complexity
     */
    public function calculateTupaDeadline(Expediente $expediente, ?WorkflowStep $step = null): Carbon
    {
        $baseDate = now();
        $days = $this->getBaseTupaDays($expediente);

        if ($step && $step->tiempo_estimado) {
            $days = $step->tiempo_estimado;
        }

        // Add business days only (skip weekends and holidays)
        return $this->addBusinessDays($baseDate, $days);
    }

    /**
     * Validate document completeness for specific workflow step
     */
    public function validateStepDocuments(Expediente $expediente, WorkflowStep $step): bool
    {
        // Check if step requires specific documents
        $requiredDocs = $step->configuracion['required_documents'] ?? [];
        
        if (empty($requiredDocs)) {
            return true;
        }

        $expedienteDocs = $expediente->documentos->pluck('tipo_documento')->toArray();

        foreach ($requiredDocs as $requiredDoc) {
            if (!in_array($requiredDoc, $expedienteDocs)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get standardized TUPA categories and their base processing days
     */
    public function getTupaCategories(): array
    {
        return [
            'licencia_funcionamiento' => ['days' => 15, 'category' => 'Licencias'],
            'certificado_zonificacion' => ['days' => 7, 'category' => 'Certificados'],
            'permiso_construccion' => ['days' => 30, 'category' => 'Permisos'],
            'solicitud_general' => ['days' => 10, 'category' => 'Solicitudes'],
            'reclamo' => ['days' => 30, 'category' => 'Reclamos'],
            'consulta' => ['days' => 5, 'category' => 'Consultas']
        ];
    }

    /**
     * Validate fee payment status for fee-based procedures
     */
    public function validateFeePayment(Expediente $expediente): bool
    {
        // Check if procedure requires payment
        $workflow = $expediente->workflow;
        $requiresPayment = $workflow->configuracion['requires_payment'] ?? false;

        if (!$requiresPayment) {
            return true;
        }

        // Verify payment records
        return $expediente->payments()
                         ->where('estado', 'aprobado')
                         ->exists();
    }

    protected function isBeyondTupaDeadline(Expediente $expediente): bool
    {
        if (!$expediente->fecha_limite) {
            return false;
        }

        return now()->gt($expediente->fecha_limite);
    }

    protected function missingRequiredDocuments(Expediente $expediente): bool
    {
        $currentStep = $expediente->currentStep;
        
        if (!$currentStep) {
            return false;
        }

        return !$this->validateStepDocuments($expediente, $currentStep);
    }

    protected function isOutsideBusinessHours(Expediente $expediente): bool
    {
        $now = now();
        
        // Skip weekends
        if ($now->isWeekend()) {
            return true;
        }

        // Check business hours (8 AM to 5 PM)
        $hour = $now->hour;
        return $hour < 8 || $hour >= 17;
    }

    protected function getBaseTupaDays(Expediente $expediente): int
    {
        $categories = $this->getTupaCategories();
        $expedienteType = $expediente->tipo ?? 'solicitud_general';
        
        return $categories[$expedienteType]['days'] ?? 15;
    }

    protected function addBusinessDays(Carbon $date, int $days): Carbon
    {
        $result = $date->copy();
        
        while ($days > 0) {
            $result->addDay();
            
            // Skip weekends and holidays
            if (!$result->isWeekend() && !$this->isHoliday($result)) {
                $days--;
            }
        }
        
        return $result;
    }

    protected function isHoliday(Carbon $date): bool
    {
        // Define Peruvian national holidays
        $holidays = [
            '01-01', // New Year
            '05-01', // Labor Day
            '07-28', // Independence Day
            '07-29', // Independence Day
            '08-30', // Santa Rosa de Lima
            '10-08', // Combat of Angamos
            '11-01', // All Saints Day
            '12-08', // Immaculate Conception
            '12-25', // Christmas
        ];
        
        $monthDay = $date->format('m-d');
        return in_array($monthDay, $holidays);
    }
}