<?php
// chart-line.php
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');
$lineId = $_GET['line'] ?? null;

$data = ['lines' => [], 'target' => []];

if ($lineId) {
    // 🔹 Ambil data harian per line (RATA-RATA DARI SEMUA SHIFT)
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            SUM(batch_count) as batch_count,
            SUM(productivity) as productivity,
            SUM(production_speed) as production_speed,
            SUM(feed_raw_material) as feed_raw_material,
            SUM(batch_weight) as batch_weight,
            SUM(operation_factor) as operation_factor,
            SUM(cycle_time) as cycle_time,
            SUM(grade_change_sequence) as grade_change_sequence,
            SUM(grade_change_time) as grade_change_time
        FROM input_harian
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        GROUP BY tanggal
        ORDER BY hari
    ");
    $stmt->execute([$lineId, $bulan, $tahun]);
    $data['lines'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🎯 Ambil data target per line
    $stmtTarget = $pdo->prepare("
        SELECT 
            target_batch_count,
            target_productivity,
            target_production_speed,
            target_feed_raw_material,
            target_batch_weight,
            target_operation_factor,
            target_cycle_time,
            target_grade_change_sequence,
            target_grade_change_time
        FROM target
        WHERE line_id = ? AND tahun_target = ?
        LIMIT 1
    ");
    $stmtTarget->execute([$lineId, $tahun]);
    $data['target'] = $stmtTarget->fetch(PDO::FETCH_ASSOC) ?: [];
}

header('Content-Type: application/json');
echo json_encode($data);
