<?php
require_once 'config.php';

$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');
$lineId = $_GET['line'] ?? null;

$data = ['lines' => [], 'target' => []];

if ($lineId) {
    // =============================
    // 🔹 Ambil data harian per line
    // =============================
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            batch_count,
            productivity,
            production_speed,
            feed_raw_material
        FROM input_harian
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        ORDER BY hari
    ");
    $stmt->execute([$lineId, $bulan, $tahun]);
    $data['lines'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =============================
    // 🎯 Ambil data target per line
    // =============================
    $stmtTarget = $pdo->prepare("
        SELECT 
            target_batch_count,
            target_productivity,
            target_production_speed,
            target_feed_raw_material
        FROM target
        WHERE line_id = ? AND tahun_target = ?
        LIMIT 1
    ");
    $stmtTarget->execute([$lineId, $tahun]);
    $data['target'] = $stmtTarget->fetch(PDO::FETCH_ASSOC) ?: [];
} else {
    // =============================
    // 🔹 Ambil semua line (optional)
    // =============================
    $lines = $pdo->query("SELECT id, nama_line FROM line_produksi ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($lines as $line) {
        $stmt = $pdo->prepare("
            SELECT 
                DAY(tanggal) AS hari,
                batch_count,
                productivity,
                production_speed,
                feed_raw_material
            FROM input_harian
            WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
            ORDER BY hari
        ");
        $stmt->execute([$line['id'], $bulan, $tahun]);
        $data['lines'][$line['nama_line']] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtTarget = $pdo->prepare("
            SELECT 
                target_batch_count,
                target_productivity,
                target_production_speed,
                target_feed_raw_material
            FROM target
            WHERE line_id = ? AND tahun_target = ?
            LIMIT 1
        ");
        $stmtTarget->execute([$line['id'], $tahun]);
        $data['target'][$line['nama_line']] = $stmtTarget->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
