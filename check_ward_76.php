<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ward = App\Models\Ward::with('lga')->find(76);
if ($ward) {
    echo "Ward 76: Name='{$ward->name}', LGA_ID={$ward->lga_id}, LGA_Name='{$ward->lga->name}'\n";
} else {
    echo "Ward 76 not found!\n";
}

$lga = App\Models\Lga::find(5);
if ($lga) {
    echo "\nWards for LGA 5 ({$lga->name}):\n";
    $wards = App\Models\Ward::where('lga_id', 5)->get();
    foreach ($wards as $w) {
        echo "  - ID: {$w->id}, Name: '{$w->name}'\n";
    }
}
