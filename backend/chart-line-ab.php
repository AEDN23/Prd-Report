<?php
// <!-- FUNGSI UNTUK CHART LINE-AB DI FILE DASHBOARD/CHART-LINE-AB -->

// backend/chart-line-ab.php - UNTUK CHART GABUNGAN SEMUA LINE
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

$data = ['lines' => []];

// 🔹 Ambil semua line produksi
$stmtLines = $pdo->query("SELECT id, kode_line, nama_line FROM line_produksi ORDER BY kode_line");
$lines = $stmtLines->fetchAll(PDO::FETCH_ASSOC);

foreach ($lines as $line) {
    // Pastikan semua kolom yang dipanggil di JS ada di sini
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
    $stmt->execute([$line['id'], $bulan, $tahun]);
    $lineData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Hilangkan error_log di production, tapi berguna untuk debug
    // error_log("Line {$line['nama_line']}: " . count($lineData) . " records");

    $data['lines'][$line['nama_line']] = $lineData;
}

// Hilangkan error_log di production, tapi berguna untuk debug
// error_log("Total lines data: " . count($data['lines']));

header('Content-Type: application/json');
echo json_encode($data);
