<?php
// webhook.php — PayMongo Webhook Handler (Debug Version)
include("db_connect.php");

// Basahin ang raw JSON data mula sa PayMongo
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// I-log lahat ng natatanggap para sa debugging (absolute path)
$logFile = __DIR__ . "/webhook_log.txt";
file_put_contents($logFile, date("Y-m-d H:i:s") . " RAW DATA:\n" . $input . "\n\n", FILE_APPEND);

if (!$data || !isset($data['data'])) {
    http_response_code(400);
    exit("Invalid data");
}

$eventType = $data['data']['type'] ?? '';
$attributes = $data['data']['attributes'] ?? [];
$description = $attributes['description'] ?? '';
$status = $attributes['status'] ?? '';
$transaction_id = $data['data']['id'] ?? '';
$payment_method = $attributes['payment_method_used'] ?? 'PayMongo';

// Subukan kunin ang Bill ID sa description
preg_match('/Bill ID:\s*(\d+)/i', $description, $match);
$bill_id = $match[1] ?? null;

if ($bill_id && strtolower($status) === 'paid') {
    // Update billing table
    $stmt = $conn->prepare("
        UPDATE billing 
        SET status='paid',
            transaction_id=?,
            payment_method=?,
            paid_date=NOW()
        WHERE id=?
    ");
    $stmt->bind_param("ssi", $transaction_id, $payment_method, $bill_id);
    $stmt->execute();

    file_put_contents($logFile, "✅ Updated Bill ID: {$bill_id}, Txn: {$transaction_id}\n\n", FILE_APPEND);
} else {
    file_put_contents($logFile, "⚠️ No matching Bill ID or not paid status.\n\n", FILE_APPEND);
}

http_response_code(200);
echo "OK";
?>
