<?php
require_once 'config.php';

$lineId = isset($_GET['line']) ? (int)$_GET['line'] : 1;
$tahun  = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// Ambil target per line & tahun (mengambil semua kolom target_*)
$stmt = $stmt = $pdo->prepare("SELECT * FROM target WHERE line_id = ? AND tahun_target = ?");
$stmt->execute([$lineId, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data harian untuk line & tahun itu — ambil AVG untuk setiap metric
$stmt = $pdo->prepare("
    SELECT 
        MONTH(tanggal) AS bulan,
        AVG(batch_count) AS avg_batch_count,
        AVG(productivity) AS avg_productivity,
        AVG(production_speed) AS avg_production_speed,
        AVG(batch_weight) AS avg_batch_weight,
        AVG(operation_factor) AS avg_operation_factor,
        AVG(cycle_time) AS avg_cycle_time,
        AVG(grade_change_sequence) AS avg_grade_change_sequence,
        AVG(grade_change_time) AS avg_grade_change_time,
        AVG(feed_raw_material) AS avg_feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND YEAR(tanggal) = ?
    GROUP BY bulan
    ORDER BY bulan
");
$stmt->execute([$lineId, $tahun]);
$dataBulan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bentuk array bulan [1..12]
$bulanData = [];
foreach ($dataBulan as $d) {
    $bulanData[(int)$d['bulan']] = $d;
}

echo json_encode([
    "tahun" => $tahun,
    "bulanData" => $bulanData,
    "target" => $target
], JSON_NUMERIC_CHECK);
