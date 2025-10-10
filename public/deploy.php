<?php
// Simple, secure deploy webhook for GitHub
// Set a strong secret in your Laravel .env: WEBHOOK_SECRET=change_me

$secret = getenv('WEBHOOK_SECRET') ?: '';
if ($secret === '') {
    http_response_code(500);
    echo "Secret not set";
    exit;
}

$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$body      = file_get_contents('php://input');
$calc      = 'sha256=' . hash_hmac('sha256', $body, $secret);

if (!hash_equals($calc, $signature)) {
    http_response_code(403);
    echo "Invalid signature";
    exit;
}

// Run deploy in background to avoid webhook timeout
$cmd = '/usr/local/bin/imhotion-deploy.sh';
$log = '/var/log/imhotion-deploy.log';
$full = "nohup $cmd >> $log 2>&1 &";
shell_exec($full);

http_response_code(202);
echo "Deploy started";
