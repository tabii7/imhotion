<?php
// Quick Mollie test runner for debugging (temporary)
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Ensure the API key is set explicitly from config
    Mollie\Laravel\Facades\Mollie::api()->setApiKey(config('mollie.key'));

    $payment = Mollie\Laravel\Facades\Mollie::api()->payments->create([
        'amount' => [
            'currency' => 'EUR',
            'value' => '0.01',
        ],
        'description' => 'Imhotion test payment',
        'redirectUrl' => 'https://imhotion.easyred.com/',
        'webhookUrl' => 'https://imhotion.easyred.com/payment/webhook',
        'metadata' => [ 'test' => true ],
    ]);

    echo "OK\n";
    echo "id: " . ($payment->id ?? '(none)') . "\n";
    echo "checkout_url: " . ($payment->getCheckoutUrl() ?? '(none)') . "\n";
    echo "status: " . ($payment->status ?? '(unknown)') . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    file_put_contents(__DIR__ . '/mollie_test_error.log', $e->getMessage() . "\n" . $e->getTraceAsString());
}
