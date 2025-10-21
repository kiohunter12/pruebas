<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Probando relación tiposDocumentos ===\n\n";

// Probar con el primer tipo de trámite
$tramite = App\Models\TipoTramite::with('tiposDocumentos')->first();

if ($tramite) {
    echo "Tipo Trámite: {$tramite->nombre}\n";
    echo "ID: {$tramite->id}\n";
    echo "Documentos relacionados: {$tramite->tiposDocumentos->count()}\n\n";
    
    if ($tramite->tiposDocumentos->count() > 0) {
        echo "Lista de documentos:\n";
        foreach ($tramite->tiposDocumentos as $doc) {
            echo "  - {$doc->nombre}\n";
        }
    } else {
        echo "No hay documentos asignados a este tipo de trámite.\n";
    }
} else {
    echo "No hay tipos de trámite en la base de datos.\n";
}

echo "\n=== Prueba completada ===\n";
