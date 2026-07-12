<?php
/**
 * Returns last 7 days of revenue as JSON for Chart.js
 * GET /api/revenue_chart.php
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

// Build the last 7 calendar dates (including today)
$labels = [];
$map = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i day"));
    $labels[] = date('D', strtotime($d));
    $map[$d] = 0;
}

$stmt = $pdo->query("
    SELECT DATE(end_time) AS d, SUM(amount) AS total
    FROM sessions
    WHERE status = 'completed'
      AND end_time >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(end_time)
");
foreach ($stmt->fetchAll() as $row) {
    if (isset($map[$row['d']])) {
        $map[$row['d']] = (float) $row['total'];
    }
}

echo json_encode([
    'labels' => $labels,
    'data'   => array_values($map),
]);
