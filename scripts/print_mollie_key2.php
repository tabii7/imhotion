<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$k = config('mollie.key');
echo 'MOLLIE_KEY_PRESENT: ' . (empty($k) ? '(empty)' : substr($k,0,20) . '...') . PHP_EOL;
echo 'APP_URL=' . config('app.url') . PHP_EOL;
