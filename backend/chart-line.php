<?php
include 'config.php';

$tahun = $_GET['tahun'] ?? date('Y');
$bulan = $_GET['bulan'] ?? date('n');

// Ambil data untuk Line A (id=1) & Line B (id=2)
function ambilDataLine($pdo, $line, $bulan, $tahun)
{
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            AVG(batch_count) AS batch_count,
            AVG(productivity) AS productivity,
            AVG(production_speed) AS production_speed,
            AVG(feed_raw_material) AS feed_raw_material
        FROM input_harian
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        GROUP BY DAY(tanggal)
        ORDER BY hari
    ");
    $stmt->execute([$line, $bulan, $tahun]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$dataA = ambilDataLine($pdo, 1, $bulan, $tahun);
$dataB = ambilDataLine($pdo, 2, $bulan, $tahun);

echo json_encode([
    'lineA' => $dataA,
    'lineB' => $dataB
]);
