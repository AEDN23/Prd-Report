<!-- FUNCTION EXPORT PDF DI TABEL REPORT PRODUKSI  HARIAN ( export-pdf-harian.php )-->


<?php
include '../backend/config.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// ambil data harian
$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        batch_count, productivity, production_speed, batch_weight,
        operation_factor, cycle_time, grade_change_sequence, grade_change_time, feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    ORDER BY tanggal ASC
");
$stmt->execute([$line, $bulan, $tahun]);
$data = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) $data[$r['hari']] = $r;

// field dan unit
$fields = [
    'batch_count' => ['Batch Count', 'per day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'production_speed' => ['Production Speed', 'Kg/min'],
    'batch_weight' => ['Batch Weight', 'Kg/Batch'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'min/Batch'],
    'grade_change_sequence' => ['Grade Change Sequence', 'frequenly'],
    'grade_change_time' => ['Grade Change Time', 'min/grade'],
    'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
];

// hitung average
$averages = [];
foreach ($fields as $key => $v) {
    $sum = 0;
    $count = 0;
    foreach ($data as $hari => $val) {
        if (!empty($val[$key])) {
            $sum += $val[$key];
            $count++;
        }
    }
    $averages[$key] = $count ? round($sum / $count, 2) : '-';
}

// nama line
$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$line]);
$lineName = $stmt->fetchColumn();

// generate HTML
$html = "
<h3 style='text-align:center'>
📋 DATA PRODUKSI HARIAN - {$lineName} (" . date('F', mktime(0, 0, 0, $bulan, 10)) . " {$tahun})
</h3>
<table border='1' cellspacing='0' cellpadding='4' width='100%' style='border-collapse:collapse; font-size:11px; text-align:center'>
<thead style='background:#cce5ff;'>
<tr>
    <th>Details</th>
    <th>Unit</th>
    <th>Target</th>
    <th>Average</th>";
for ($d = 1; $d <= 31; $d++) $html .= "<th>{$d}</th>";
$html .= "</tr></thead><tbody>";

foreach ($fields as $key => [$label, $unit]) {
    $targetVal = $target['target_' . $key] ?? 0;
    $avgVal = $averages[$key];
    $color = ($avgVal !== '-' && $targetVal > 0 && $avgVal < $targetVal) ? 'red' : 'black';

    $html .= "<tr>
        <td><b>{$label}</b></td>
        <td>{$unit}</td>
        <td><b>{$targetVal}</b></td>
        <td style='color:{$color}'>{$avgVal}</td>";

    for ($d = 1; $d <= 31; $d++) {
        $val = $data[$d][$key] ?? '-';
        $style = '';
        if ($val !== '-' && $targetVal > 0) {
            $style = ($val < $targetVal) ? 'color:red;font-weight:bold;' : 'color:black;';
        }
        $html .= "<td style='{$style}'>{$val}</td>";
    }
    $html .= "</tr>";
}
$html .= "</tbody></table>";

// output PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("ProduksiHarian_{$lineName}_{$bulan}_{$tahun}.pdf", ["Attachment" => true]);
exit;
