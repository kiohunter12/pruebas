<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

$perms = Permission::orderBy('id')->get()->pluck('name')->toArray();
echo json_encode($perms, JSON_PRETTY_PRINT) . PHP_EOL;
