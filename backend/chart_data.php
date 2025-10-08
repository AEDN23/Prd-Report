<?php
require_once 'config.php';
header('Content-Type: application/json');

// Ambil parameter
$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// Ambil data harian dari database
$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        AVG(batch_count) AS batch_count,
        AVG(productivity) AS productivity,
        AVG(production_speed) AS production_speed,
        AVG(batch_weight) AS batch_weight,
        AVG(operation_factor) AS operation_factor,
        AVG(cycle_time) AS cycle_time,
        AVG(grade_change_sequence) AS grade_change_sequence,
        AVG(grade_change_time) AS grade_change_time,
        AVG(feed_raw_material) AS feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    GROUP BY hari
    ORDER BY hari ASC
");
$stmt->execute([$line, $bulan, $tahun]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bentuk ulang data agar rapi (1-31)
$result = [];
for ($i = 1; $i <= 31; $i++) {
    $row = array_filter($data, fn($d) => $d['hari'] == $i);
    $row = reset($row);
    $result[$i] = $row ?: [
        'hari' => $i,
        'batch_count' => 0,
        'productivity' => 0,
        'production_speed' => 0,
        'batch_weight' => 0,
        'operation_factor' => 0,
        'cycle_time' => 0,
        'grade_change_sequence' => 0,
        'grade_change_time' => 0,
        'feed_raw_material' => 0
    ];
}

echo json_encode(array_values($result));
