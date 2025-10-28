<!-- FUNCTION EXPORT EXCEL DI TABEL REPORT PRODUKSI HARIAN -->

<?php
require '../vendor/autoload.php';
include '../backend/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// ambil data
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

// field
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

// average
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

// buat Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', "📋 DATA PRODUKSI HARIAN - {$lineName} (" . date('F', mktime(0, 0, 0, $bulan, 10)) . " {$tahun})");
$sheet->mergeCells('A1:AG1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header
$row = 3;
$headers = ['Details', 'Unit', 'Target', 'Average'];
for ($d = 1; $d <= 31; $d++) $headers[] = $d;
$sheet->fromArray($headers, null, "A{$row}");
$lastCol = chr(64 + count($headers));
$sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCE5FF']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Data rows
$row++;
foreach ($fields as $key => [$label, $unit]) {
    $targetVal = $target['target_' . $key] ?? 0;
    $avgVal = $averages[$key];
    $sheet->setCellValue("A{$row}", $label);
    $sheet->setCellValue("B{$row}", $unit);
    $sheet->setCellValue("C{$row}", $targetVal);
    $sheet->setCellValue("D{$row}", $avgVal);

    $colIndex = 5; // mulai dari kolom E
    for ($d = 1; $d <= 31; $d++) {
        $val = $data[$d][$key] ?? '-';
        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue("{$col}{$row}", $val);
        $colIndex++;
    }

    $sheet->getStyle("A{$row}:AG{$row}")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
    $row++;
}

foreach (range('A', $lastCol) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$filename = "ProduksiHarian_{$lineName}_{$bulan}_{$tahun}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
