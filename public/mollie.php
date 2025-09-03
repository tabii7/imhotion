<?php
/**
 * File: public/mollie.php
 * Purpose: Minimal Mollie test payment in one file.
 * Usage:   Visit /mollie.php then click "Pay €1.00 (test)".
 */
declare(strict_types=1);

session_start();

/* 1) Composer autoload */
$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php', // Laravel default
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
];
$autoloadLoaded = false;
foreach ($autoloadPaths as $p) {
    if (file_exists($p)) {
        require $p;
        $autoloadLoaded = true;
        break;
    }
}
if (!$autoloadLoaded) {
    http_response_code(500);
    echo "Composer autoload not found. Run composer install or place this file in Laravel's public folder.";
    exit;
}

/* 2) Config */
$apiKey = getenv('MOLLIE_KEY') ?: 'test_vHNgSDKwfz7X8qJQh4FdPnKxRc3Wq2'; // test key
$amountValue = '1.00';
$currency = 'EUR';

/* Build this script URL */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$path = strtok($_SERVER['SCRIPT_NAME'] ?? '/mollie.php', '?'); // this file
$scriptUrl = $scheme . '://' . $host . $path;

/* 3) Init Mollie */
try {
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey($apiKey);
} catch (\Throwable $e) {
    http_response_code(500);
    echo "Mollie init error: " . htmlspecialchars($e->getMessage());
    exit;
}

/* 4) Simple router inside one file */
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (isset($_GET['webhook'])) {
    /* Webhook endpoint. Mollie sends POST with payment id in $_POST['id']. */
    $paymentId = $_POST['id'] ?? null;
    if (!$paymentId) {
        http_response_code(400);
        echo "Missing id";
        exit;
    }
    try {
        $payment = $mollie->payments->get($paymentId);
        // Optional: quick log to file next to this script
        @file_put_contents(__DIR__ . '/mollie_webhook.log',
            sprintf("[%s] %s status=%s\n", date('c'), $paymentId, $payment->status),
            FILE_APPEND
        );
        http_response_code(200);
        echo "OK";
    } catch (\Throwable $e) {
        http_response_code(500);
        echo "Webhook error: " . htmlspecialchars($e->getMessage());
    }
    exit;
}

if (isset($_GET['return'])) {
    /* Return page after checkout */
    $paymentId = $_GET['pid'] ?? ($_SESSION['mollie_payment_id'] ?? null);
    if (!$paymentId) {
        http_response_code(404);
        echo "Payment not found in session or URL.";
        exit;
    }
    try {
        $payment = $mollie->payments->get($paymentId);
        $status = $payment->status;
        $isPaid = $payment->isPaid();
        $isOpen = $payment->isOpen() || $payment->isPending();

        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Mollie Test Return</title>
            <style>
                body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem; color: #0f172a; }
                .card { max-width: 560px; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
                .row { margin: 10px 0; }
                .status { font-weight: 600; }
                .btn { display: inline-block; padding: 10px 14px; border-radius: 8px; text-decoration: none; border: 1px solid #e5e7eb; }
                .btn-primary { background: #111827; color: #fff; border-color: #111827; }
            </style>
        </head>
        <body>
        <div class="card">
            <h2>Mollie payment status</h2>
            <div class="row">Payment ID: <code><?= htmlspecialchars($paymentId) ?></code></div>
            <div class="row">Status: <span class="status"><?= htmlspecialchars($status) ?></span></div>
            <?php if ($isPaid): ?>
                <div class="row">Result: Paid</div>
            <?php elseif ($isOpen): ?>
                <div class="row">Result: Pending or Open</div>
            <?php else: ?>
                <div class="row">Result: Failed or Canceled or Expired</div>
            <?php endif; ?>
            <div class="row"><a class="btn" href="<?= htmlspecialchars($scriptUrl) ?>">Create new test payment</a></div>
        </div>
        </body>
        </html>
        <?php
    } catch (\Throwable $e) {
        http_response_code(500);
        echo "Return error: " . htmlspecialchars($e->getMessage());
    }
    exit;
}

/* Default view and create action */
if ($method === 'POST' && ($_POST['action'] ?? null) === 'create') {
    try {
        $payment = $mollie->payments->create([
            'amount' => [
                'currency' => $currency,
                'value' => $amountValue, // string with two decimals
            ],
            'description' => 'Mollie test payment',
            'redirectUrl' => $scriptUrl . '?return=1&pid={paymentId}', // placeholder replaced by Mollie
            'webhookUrl'  => $scriptUrl . '?webhook=1',
            'metadata'    => ['session' => session_id()],
        ]);

        // Some environments do not replace {paymentId}. Safe approach uses known id.
        $redirect = $scriptUrl . '?return=1&pid=' . urlencode($payment->id);

        $_SESSION['mollie_payment_id'] = $payment->id;

        header('Location: ' . $payment->getCheckoutUrl(), true, 302);
        // If you prefer to force your own return URL after payment, comment the line above and use:
        // header('Location: ' . $redirect, true, 302);
        exit;
    } catch (\Throwable $e) {
        http_response_code(500);
        echo "Create payment error: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

/* Simple landing page with a create button */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mollie Test Payment</title>
    <style>
        :root { color-scheme: light dark; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 0; display: grid; place-items: center; min-height: 100dvh; background: #f8fafc; color: #0f172a; }
        .card { width: 92%; max-width: 560px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        h1 { font-size: 22px; margin: 0 0 12px 0; }
        p { margin: 0 0 8px 0; }
        code { background: #f1f5f9; padding: 2px 6px; border-radius: 6px; }
        form { margin-top: 16px; }
        button { padding: 12px 16px; border: 0; border-radius: 10px; background: #111827; color: #fff; cursor: pointer; font-weight: 600; }
        .muted { color: #475569; font-size: 14px; }
    </style>
</head>
<body>
<div class="card">
    <h1>Mollie test payment</h1>
    <p>Amount <strong>€<?= htmlspecialchars($amountValue) ?></strong> <?= htmlspecialchars($currency) ?></p>
    <p class="muted">API key in use: <code><?= htmlspecialchars(substr($apiKey, 0, 8)) ?>••••</code> (test)</p>
    <form method="post" action="<?= htmlspecialchars($scriptUrl) ?>">
        <input type="hidden" name="action" value="create">
        <button type="submit">Pay €<?= htmlspecialchars($amountValue) ?> (test)</button>
    </form>
</div>
</body>
</html>
