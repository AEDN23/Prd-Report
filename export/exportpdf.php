<!-- FUNCTION EXPORT PDF DI TABEL REPORT PRODUKSI TAHUNAN -->

<?php
include '../backend/config.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

// Ambil data dari DB
$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$selectedLine]);
$lineName = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$selectedLine, $selectedYear]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT MONTH(tanggal) AS bulan,
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
    GROUP BY bulan ORDER BY bulan
");
$stmt->execute([$selectedLine, $selectedYear]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$fields = [
    'batch_count' => 'Batch Count',
    'productivity' => 'Productivity',
    'production_speed' => 'Production Speed',
    'batch_weight' => 'Batch Weight',
    'operation_factor' => 'Operation Factor',
    'cycle_time' => 'Cycle Time',
    'grade_change_sequence' => 'Grade Change Sequence',
    'grade_change_time' => 'Grade Change Time',
    'feed_raw_material' => 'Feed Raw Material'
];

// Buat HTML tabel
$html = "<h3 style='text-align:center'>DATA TARGET PRODUKSI - {$lineName} ({$selectedYear})</h3>
<table border='1' cellspacing='0' cellpadding='4' width='100%' style='font-size:11px; border-collapse:collapse;'>
<tr style='background:#cce5ff;'>
<th>Details</th><th>Target</th><th>Average</th>";
foreach ($bulan as $b) $html .= "<th>{$b}</th>";
$html .= "</tr>";

foreach ($fields as $key => $label) {
    $html .= "<tr><td>{$label}</td><td>" . ($target['target_' . $key] ?? '-') . "</td>";
    $sum = 0;
    $count = 0;
    foreach ($data as $row) {
        if ($row['avg_' . $key] !== null) {
            $sum += $row['avg_' . $key];
            $count++;
        }
    }
    $avg = $count > 0 ? round($sum / $count, 2) : '-';
    $html .= "<td>{$avg}</td>";

    for ($m = 1; $m <= 12; $m++) {
        $bulanRow = array_filter($data, fn($d) => $d['bulan'] == $m);
        $bulanRow = reset($bulanRow);
        $val = $bulanRow ? round($bulanRow['avg_' . $key], 2) : '-';
        $html .= "<td>{$val}</td>";
    }

    $html .= "</tr>";
}
$html .= "</table>";

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Rangkuman_{$lineName}_{$selectedYear}.pdf", ["Attachment" => true]);
