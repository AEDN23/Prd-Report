<?php
ob_clean();
ob_start();
require '../vendor/autoload.php';
include '../backend/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$line = $_GET['line'] ?? 1;
$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');

// ambil target
$stmt = $pdo->prepare("SELECT * FROM target WHERE line_id=? AND tahun_target=?");
$stmt->execute([$line, $tahun]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// ===== TABEL ALL: SEMUA METRICS PAKAI SUM (JUMLAH TOTAL) =====
$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        -- SEMUA METRICS PAKAI SUM (TOTAL DARI SEMUA SHIFT)
        SUM(batch_count) as batch_count,
        SUM(productivity) as productivity,
        SUM(production_speed) as production_speed,
        SUM(batch_weight) as batch_weight,
        SUM(operation_factor) as operation_factor,
        SUM(cycle_time) as cycle_time,
        SUM(grade_change_sequence) as grade_change_sequence,
        SUM(grade_change_time) as grade_change_time,
        SUM(feed_raw_material) as feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    GROUP BY DAY(tanggal)
    ORDER BY DAY(tanggal) ASC
");
$stmt->execute([$line, $bulan, $tahun]);
$dataAll = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dataAll[$r['hari']] = $r;
}

// AMBIL DATA PER SHIFT (untuk tabel shift 1,2,3)
$stmt = $pdo->prepare("
    SELECT 
        DAY(tanggal) AS hari,
        shift_id,
        batch_count, productivity, production_speed, batch_weight,
        operation_factor, cycle_time, grade_change_sequence, grade_change_time, feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
    ORDER BY tanggal ASC, shift_id ASC
");
$stmt->execute([$line, $bulan, $tahun]);
$dataPerShift = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $shiftId = $r['shift_id'];
    $hari = $r['hari'];
    if (!isset($dataPerShift[$shiftId])) {
        $dataPerShift[$shiftId] = [];
    }
    $dataPerShift[$shiftId][$hari] = $r;
}

// ambil info shift
$stmt = $pdo->prepare("SELECT id, nama_shift FROM master_shift ORDER BY jam_mulai");
$stmt->execute();
$shifts = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $shifts[$r['id']] = $r['nama_shift'];
}

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

// hitung SUM untuk data ALL (bukan average)
function hitungSum($data, $fields)
{
    $sums = [];
    foreach ($fields as $key => $v) {
        $total = 0;
        $count = 0;
        foreach ($data as $hari => $val) {
            if (!empty($val[$key])) {
                $total += $val[$key];
                $count++;
            }
        }
        $sums[$key] = $count ? round($total, 2) : '-';
    }
    return $sums;
}

// hitung AVERAGE untuk data per shift
function hitungAverage($data, $fields)
{
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
    return $averages;
}

$sumsAll = hitungSum($dataAll, $fields);

// nama line
$stmt = $pdo->prepare("SELECT nama_line FROM line_produksi WHERE id=?");
$stmt->execute([$line]);
$lineName = $stmt->fetchColumn();

// buat Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ====================== TABEL ALL (SUM SEMUA SHIFT) ======================
$sheet->setCellValue('A1', "📋 DATA PRODUKSI HARIAN - {$lineName} (" . date('F', mktime(0, 0, 0, $bulan, 10)) . " {$tahun})");
$sheet->setCellValue('A2', "DATA ALL SHIFT (TOTAL/SUM SEMUA SHIFT)");
$sheet->mergeCells('A1:AH1');
$sheet->mergeCells('A2:AH2');

$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('006600');
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header tabel ALL
$row = 4;
$headers = ['Details', 'Unit', 'Target', 'Total', 'Hasil (%)']; // Ganti 'Average' jadi 'Total'
for ($d = 1; $d <= 31; $d++) $headers[] = $d;

$sheet->fromArray($headers, null, "A{$row}");
$lastCol = Coordinate::stringFromColumnIndex(count($headers));
$sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCE5FF']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Data rows tabel ALL - PAKAI SUM
$row++;
foreach ($fields as $key => [$label, $unit]) {
    $targetVal = $target['target_' . $key] ?? 0;
    $totalVal = $sumsAll[$key]; // Ganti avg dengan total
    $sheet->setCellValue("A{$row}", $label);
    $sheet->setCellValue("B{$row}", $unit);
    $sheet->setCellValue("C{$row}", $targetVal);
    $sheet->setCellValue("D{$row}", $totalVal); // Kolom D sekarang Total, bukan Average

    // Hitung hasil (%)
    $hasil = ($totalVal !== '-' && $targetVal > 0) ? round(($totalVal / $targetVal) * 100, 1) : '-';
    $sheet->setCellValue("E{$row}", $hasil !== '-' ? $hasil . '%' : '-');
    $sheet->getStyle("E{$row}")->getFont()->getColor()->setRGB(($hasil !== '-' && $hasil < 100) ? 'FF0000' : '000000');

    $colIndex = 6; // kolom F untuk tanggal 1
    for ($d = 1; $d <= 31; $d++) {
        $val = $dataAll[$d][$key] ?? '-';
        $col = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue("{$col}{$row}", $val);
        $colIndex++;
    }

    $sheet->getStyle("A{$row}:AG{$row}")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
    $row++;
}

// Spasi sebelum tabel shift
$row += 3;

// ====================== TABEL PER SHIFT ======================
foreach ($dataPerShift as $shiftId => $dataShift) {
    if (!isset($shifts[$shiftId])) continue;

    $shiftName = $shifts[$shiftId];
    $averagesShift = hitungAverage($dataShift, $fields); // Shift pakai Average

    // Judul tabel shift
    $sheet->setCellValue("A{$row}", "📊 DATA SHIFT: {$shiftName}");
    $sheet->mergeCells("A{$row}:AH{$row}");
    $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('000080');
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E6F3FF');
    $row++;

    // Header tabel shift
    $sheet->fromArray($headers, null, "A{$row}");
    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF2CC']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
    $row++;

    // Data rows tabel shift - PAKAI AVERAGE
    foreach ($fields as $key => [$label, $unit]) {
        $targetVal = $target['target_' . $key] ?? 0;
        $avgVal = $averagesShift[$key];
        $sheet->setCellValue("A{$row}", $label);
        $sheet->setCellValue("B{$row}", $unit);
        $sheet->setCellValue("C{$row}", $targetVal);
        $sheet->setCellValue("D{$row}", $avgVal); // Kolom D adalah Average untuk shift

        // Hitung hasil (%)
        $hasil = ($avgVal !== '-' && $targetVal > 0) ? round(($avgVal / $targetVal) * 100, 1) : '-';
        $sheet->setCellValue("E{$row}", $hasil !== '-' ? $hasil . '%' : '-');
        $sheet->getStyle("E{$row}")->getFont()->getColor()->setRGB(($hasil !== '-' && $hasil < 100) ? 'FF0000' : '000000');

        $colIndex = 6; // kolom F untuk tanggal 1
        for ($d = 1; $d <= 31; $d++) {
            $val = $dataShift[$d][$key] ?? '-';
            $col = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue("{$col}{$row}", $val);
            $colIndex++;
        }

        $sheet->getStyle("A{$row}:AG{$row}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $row++;
    }

    // Beri jarak antar tabel shift
    $row += 3;
}

// Auto size kolom
foreach (range('A', $lastCol) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Freeze pane agar header tabel ALL tetap terlihat
$sheet->freezePane('A4');

$filename = "ProduksiHarian_{$lineName}_" . date('F', mktime(0, 0, 0, $bulan, 10)) . "_{$tahun}.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
ob_end_clean();
$writer->save('php://output');
exit;
