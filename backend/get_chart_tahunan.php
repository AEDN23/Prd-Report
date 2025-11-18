<?php
// get_chart_tahunan.php
require_once 'config.php';

$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$selectedLine, $selectedYear]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// AMBIL DATA HARIAN DENGAN RATA-RATA PER SHIFT
$stmt = $pdo->prepare("
    SELECT 
        tanggal,
        MONTH(tanggal) as bulan,
        SUM(batch_count) AS daily_batch_count,
        SUM(productivity) AS daily_productivity,
        SUM(production_speed) AS daily_production_speed,
        SUM(batch_weight) AS daily_batch_weight,
        SUM(operation_factor) AS daily_operation_factor,
        SUM(cycle_time) AS daily_cycle_time,
        SUM(grade_change_sequence) AS daily_grade_change_sequence,
        SUM(grade_change_time) AS daily_grade_change_time,
        SUM(feed_raw_material) AS daily_feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND YEAR(tanggal) = ?
    GROUP BY tanggal
    ORDER BY tanggal
");
$stmt->execute([$selectedLine, $selectedYear]);
$harianData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// KELOMPOKKAN DATA PER BULAN
$bulanData = [];
foreach ($harianData as $harian) {
    $bulan = $harian['bulan'];

    if (!isset($bulanData[$bulan])) {
        $bulanData[$bulan] = [
            'count_hari' => 0,
            'sum_batch_count' => 0,
            'sum_productivity' => 0,
            'sum_production_speed' => 0,
            'sum_batch_weight' => 0,
            'sum_operation_factor' => 0,
            'sum_cycle_time' => 0,
            'sum_grade_change_sequence' => 0,
            'sum_grade_change_time' => 0,
            'sum_feed_raw_material' => 0
        ];
    }

    $bulanData[$bulan]['count_hari']++;
    $bulanData[$bulan]['sum_batch_count'] += $harian['daily_batch_count'];
    $bulanData[$bulan]['sum_productivity'] += $harian['daily_productivity'];
    $bulanData[$bulan]['sum_production_speed'] += $harian['daily_production_speed'];
    $bulanData[$bulan]['sum_batch_weight'] += $harian['daily_batch_weight'];
    $bulanData[$bulan]['sum_operation_factor'] += $harian['daily_operation_factor'];
    $bulanData[$bulan]['sum_cycle_time'] += $harian['daily_cycle_time'];
    $bulanData[$bulan]['sum_grade_change_sequence'] += $harian['daily_grade_change_sequence'];
    $bulanData[$bulan]['sum_grade_change_time'] += $harian['daily_grade_change_time'];
    $bulanData[$bulan]['sum_feed_raw_material'] += $harian['daily_feed_raw_material'];
}

// HITUNG RATA-RATA PER BULAN
$rataBulan = [];
for ($m = 1; $m <= 12; $m++) {
    if (isset($bulanData[$m]) && $bulanData[$m]['count_hari'] > 0) {
        $rataBulan[$m] = [
            'avg_batch_count' => round($bulanData[$m]['sum_batch_count'] / $bulanData[$m]['count_hari'], 2),
            'avg_productivity' => round($bulanData[$m]['sum_productivity'] / $bulanData[$m]['count_hari'], 2),
            'avg_production_speed' => round($bulanData[$m]['sum_production_speed'] / $bulanData[$m]['count_hari'], 2),
            'avg_batch_weight' => round($bulanData[$m]['sum_batch_weight'] / $bulanData[$m]['count_hari'], 2),
            'avg_operation_factor' => round($bulanData[$m]['sum_operation_factor'] / $bulanData[$m]['count_hari'], 2),
            'avg_cycle_time' => round($bulanData[$m]['sum_cycle_time'] / $bulanData[$m]['count_hari'], 2),
            'avg_grade_change_sequence' => round($bulanData[$m]['sum_grade_change_sequence'] / $bulanData[$m]['count_hari'], 2),
            'avg_grade_change_time' => round($bulanData[$m]['sum_grade_change_time'] / $bulanData[$m]['count_hari'], 2),
            'avg_feed_raw_material' => round($bulanData[$m]['sum_feed_raw_material'] / $bulanData[$m]['count_hari'], 2)
        ];
    } else {
        $rataBulan[$m] = null;
    }
}

$response = [
    'bulanData' => $rataBulan,
    'target' => $target
];

header('Content-Type: application/json');
echo json_encode($response);
