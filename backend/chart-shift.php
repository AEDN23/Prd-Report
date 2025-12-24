<?php
// backend/chart-shift.php - PERBAIKAN UNTUK SHIFT 4
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');
$line_id = $_GET['line_id'] ?? 1;

// Debug log
error_log("Chart Shift Request: bulan=$bulan, tahun=$tahun, line_id=$line_id");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Untuk development

try {
    // Query untuk mengambil data shift 1-3 (data per shift)
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            shift_id as shift,
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
            AND shift_id IN (1, 2, 3)  -- Hanya ambil shift 1-3
        GROUP BY tanggal, shift_id
        ORDER BY hari, shift_id
    ");

    $stmt->execute([$line_id, $bulan, $tahun]);
    $dataShift = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query untuk menghitung TOTAL/SEMUA SHIFT (ini akan menjadi shift 4)
    $stmtTotal = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            4 as shift,  -- Shift 4 mewakili TOTAL semua shift
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
        GROUP BY tanggal
        ORDER BY hari
    ");

    $stmtTotal->execute([$line_id, $bulan, $tahun]);
    $dataTotal = $stmtTotal->fetchAll(PDO::FETCH_ASSOC);

    // Gabungkan data shift dan total
    $allData = array_merge($dataShift, $dataTotal);

    // Debug: lihat data yang diambil
    error_log("Data shift fetched: " . count($dataShift) . " records");
    error_log("Data total fetched: " . count($dataTotal) . " records");

    // Format response
    $response = [
        'status' => 'success',
        'shifts' => [
            1 => [],
            2 => [],
            3 => [],
            4 => []   
        ],
        'data_count' => count($allData),
        'debug' => [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'line_id' => $line_id,
            'shift_records' => count($dataShift),
            'total_records' => count($dataTotal)
        ]
    ];

    // Organize data by shift
    foreach ($allData as $row) {
        $shift = (int)$row['shift'];
        $hari = (int)$row['hari'];

        if ($shift >= 1 && $shift <= 4) {   
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
