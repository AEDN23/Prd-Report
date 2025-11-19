<?php
// backend/chart-shift.php - FIXED VERSION
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');
$line_id = $_GET['line_id'] ?? 1;

// Debug log
error_log("Chart Shift Request: bulan=$bulan, tahun=$tahun, line_id=$line_id");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Untuk development

try {
    // PERBAIKAN: ganti 'shift' menjadi 'shift_id'
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            shift_id as shift,  -- ALIAS shift_id menjadi shift untuk kompatibilitas
            COALESCE(SUM(batch_count), 0) as batch_count,
            COALESCE(SUM(productivity), 0) as productivity,
            COALESCE(SUM(production_speed), 0) as production_speed,
            COALESCE(SUM(feed_raw_material), 0) as feed_raw_material,
            COALESCE(SUM(batch_weight), 0) as batch_weight,
            COALESCE(SUM(operation_factor), 0) as operation_factor,
            COALESCE(SUM(cycle_time), 0) as cycle_time,
            COALESCE(SUM(grade_change_sequence), 0) as grade_change_sequence,
            COALESCE(SUM(grade_change_time), 0) as grade_change_time
        FROM input_harian 
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        GROUP BY tanggal, shift_id
        ORDER BY hari, shift_id
    ");

    $stmt->execute([$line_id, $bulan, $tahun]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: lihat data yang diambil
    error_log("Data fetched: " . count($data) . " records");

    // Format response
    $response = [
        'status' => 'success',
        'shifts' => [
            1 => [],
            2 => [],
            3 => []
        ],
        'data_count' => count($data),
        'debug' => [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'line_id' => $line_id,
            'query_data_sample' => $data[0] ?? 'no_data'
        ]
    ];

    // Organize data by shift
    foreach ($data as $row) {
        $shift = (int)$row['shift']; // Sekarang ini adalah shift_id yang di-alias
        $hari = (int)$row['hari'];

        if ($shift >= 1 && $shift <= 3) {
            $response['shifts'][$shift][$hari] = $row;
        }
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    $error_response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        'debug' => [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'line_id' => $line_id
        ]
    ];
    echo json_encode($error_response, JSON_PRETTY_PRINT);
    error_log("Chart Shift Error: " . $e->getMessage());
}
