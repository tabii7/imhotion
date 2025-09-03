<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Mollie\Laravel\Facades\Mollie::api()->setApiKey(config('mollie.key'));
    $client = Mollie\Laravel\Facades\Mollie::api();
    $methods = $client->methods->all();
    echo "METHODS OK: " . count($methods) . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
