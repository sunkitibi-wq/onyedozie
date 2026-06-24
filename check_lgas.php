<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$lgas = App\Models\Lga::all();
echo "Total LGAs: " . $lgas->count() . "\n";
foreach ($lgas as $lga) {
    echo "ID: {$lga->id}, Name: '{$lga->name}', State: '{$lga->state}'\n";
}

$wards = App\Models\Ward::all();
echo "Total Wards: " . $wards->count() . "\n";
