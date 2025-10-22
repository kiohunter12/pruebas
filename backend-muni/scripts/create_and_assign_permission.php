<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$permName = 'aprobar_tramite';
$permission = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);

$roles = ['gerente', 'subgerente'];
foreach ($roles as $rname) {
    $role = Role::where('name', $rname)->first();
    if ($role) {
        $role->givePermissionTo($permission);
        echo "Assigned permission '{$permName}' to role '{$rname}'\n";
    } else {
        echo "Role '{$rname}' not found\n";
    }
}

// Reset permission cache
echo shell_exec('php artisan permission:cache-reset');

echo "Done\n";
