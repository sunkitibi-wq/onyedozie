<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::with('roles')->get();
echo "Total Users: " . $users->count() . "\n";
foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->implode(', ');
    echo "ID: {$user->id}, Name: '{$user->name}', Phone: '{$user->phone}', Roles: '{$roles}', Status: '{$user->status}'\n";
}
