<?php
session_start();
$host = 'localhost';
$dbname = 'db_produksi_report';
$username = 'root';
$password = '';

// FUNGSI UNTUK MENDAPATKAN DATA SHIFT
function getMasterShift($pdo)
{
    $stmt = $pdo->query("SELECT * FROM master_shift ORDER BY jam_mulai");
    return $stmt->fetchAll();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Function helper
function formatAngka($angka)
{
    if ($angka === null || $angka === '' || $angka == 0) {
        return '-';
    }
    return number_format(floatval($angka), 2);
}

// FUNGSI UNTUK MENDAPATKAN DATA LINE PRODUKSI
function getLineProduksi($pdo)
{
    $stmt = $pdo->query("SELECT * FROM line_produksi ORDER BY kode_line");
    return $stmt->fetchAll();
}

// FUNGSI UNTUK MENDAPATKAN DATA TARGET
function getSemuaTarget($pdo)
{
    $stmt = $pdo->query("
        SELECT 
            t.*,
            lp.kode_line,
            lp.nama_line
        FROM target t
        JOIN line_produksi lp ON t.line_id = lp.id
        ORDER BY t.tahun_target DESC, lp.kode_line
        LIMIT 20
    ");
    return $stmt->fetchAll();
}

// FUNGSI UNTUK MENDAPATKAN DATA INPUT HARIAN
function getAllInputHarian($pdo)
{
    $sql = "
        SELECT 
            ih.*,
            lp.kode_line,
            lp.nama_line,
            ms.kode_shift,
            ms.nama_shift,
            ms.jam_mulai,
            ms.jam_selesai
        FROM input_harian ih
        JOIN line_produksi lp ON ih.line_id = lp.id
        LEFT JOIN master_shift ms ON ih.shift_id = ms.id
        ORDER BY ih.tanggal DESC, lp.kode_line, ms.jam_mulai
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getDataHarianByLine($pdo, $lineId)
{
    $stmt = $pdo->prepare("
        SELECT 
            ih.*,
            lp.kode_line,
            lp.nama_line,
            ms.kode_shift,
            ms.nama_shift
        FROM input_harian ih
        JOIN line_produksi lp ON ih.line_id = lp.id
        LEFT JOIN master_shift ms ON ih.shift_id = ms.id
        WHERE ih.line_id = ?
        ORDER BY ih.tanggal DESC, ms.jam_mulai
    ");
    $stmt->execute([$lineId]);
    return $stmt->fetchAll();
}

function showError($message)
{
    echo "<script>
        alert('Gagal menambahkan: ' + " . json_encode($message) . ");
        window.location.href = '../dashboard/input-target';
    </script>";
    exit();
}

function showSuccess($message)
{
    echo "<script>
        alert('Berhasil menambahkan: ' + " . json_encode($message) . ");
        window.location.href = '../index';
    </script>";
    exit();
}

function showSuccessTarget($message)
{
    echo "<script>
        alert('Berhasil menambahkan: ' + " . json_encode($message) . ");
        window.location.href = '../dashboard/input-target';
    </script>";
    exit();
}

function showErrorEdit($message)
{
    echo "<script>
        alert('Gagal mengupdate: ' + " . json_encode($message) . ");
        window.location.href = '../dashboard/data-target.php';
    </script>";
    exit();
}

// =============================================
// BAGIAN UNTUK DASHBOARD INDEX.PHP
// =============================================

// Ambil daftar line untuk dropdown
$lines = getLineProduksi($pdo);

// Default filter
$selectedLine = $_GET['line'] ?? 1;
$selectedMonth = $_GET['bulan'] ?? date('m');
$selectedYear = $_GET['tahun'] ?? date('Y');

// Ambil data input_harian sesuai filter (AGREGAT PER TANGGAL dari semua shift) - FIX DECIMAL
$stmt = $pdo->prepare("
    SELECT 
        tanggal,
        SUM(batch_count) as batch_count,
        SUM(productivity) as productivity,
        ROUND(AVG(production_speed), 2) as production_speed,
        ROUND(AVG(batch_weight), 2) as batch_weight,
        ROUND(AVG(operation_factor), 2) as operation_factor,
        ROUND(AVG(cycle_time), 2) as cycle_time,
        SUM(grade_change_sequence) as grade_change_sequence,
        ROUND(AVG(grade_change_time), 2) as grade_change_time,
        SUM(feed_raw_material) as feed_raw_material
    FROM input_harian 
    WHERE line_id = ? 
    AND MONTH(tanggal) = ? 
    AND YEAR(tanggal) = ?
    GROUP BY tanggal
    ORDER BY tanggal
");
$stmt->execute([$selectedLine, $selectedMonth, $selectedYear]);
$dataHarian = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil target tahunan untuk line yang dipilih
$stmtTarget = $pdo->prepare("
    SELECT * FROM target 
    WHERE line_id = ? AND tahun_target = ?
");
$stmtTarget->execute([$selectedLine, $selectedYear]);
$target = $stmtTarget->fetch(PDO::FETCH_ASSOC);

// Buat data per tanggal (1–31)
$perTanggal = [];
foreach ($dataHarian as $row) {
    $day = date('j', strtotime($row['tanggal']));
    $perTanggal[$day] = $row;
}

// Hitung average per kolom (dari data agregat) dengan format 2 digit
$fields = [
    'batch_count',
    'productivity',
    'production_speed',
    'batch_weight',
    'operation_factor',
    'cycle_time',
    'grade_change_sequence',
    'grade_change_time',
    'feed_raw_material'
];

$averages = [];
foreach ($fields as $f) {
    $sum = 0;
    $count = 0;
    foreach ($dataHarian as $row) {
        if (!empty($row[$f]) && $row[$f] != 0) {
            $sum += $row[$f];
            $count++;
        }
    }
    $averages[$f] = $count > 0 ? number_format($sum / $count, 2) : 0;
}

// RANGKUMAN TAHUN INPUT HARIAN
$lines = getLineProduksi($pdo);

// Ambil filter dari URL
$selectedLine = $_GET['line'] ?? 1;
$selectedYear = $_GET['tahun'] ?? date('Y');

// Ambil target
$stmt = $pdo->prepare("
    SELECT * FROM target 
    WHERE line_id = ? AND tahun_target = ?
");
$stmt->execute([$selectedLine, $selectedYear]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data harian selama 1 tahun untuk line & tahun yang dipilih - FIX DECIMAL
$stmt = $pdo->prepare("
    SELECT 
        MONTH(tanggal) AS bulan,
        AVG(batch_count) AS avg_batch_count,
        AVG(productivity) AS avg_productivity,
        ROUND(AVG(production_speed), 2) AS avg_production_speed,
        ROUND(AVG(batch_weight), 2) AS avg_batch_weight,
        ROUND(AVG(operation_factor), 2) AS avg_operation_factor,
        ROUND(AVG(cycle_time), 2) AS avg_cycle_time,
        AVG(grade_change_sequence) AS avg_grade_change_sequence,
        ROUND(AVG(grade_change_time), 2) AS avg_grade_change_time,
        AVG(feed_raw_material) AS avg_feed_raw_material
    FROM input_harian
    WHERE line_id = ? AND YEAR(tanggal) = ?
    GROUP BY bulan
    ORDER BY bulan
");
$stmt->execute([$selectedLine, $selectedYear]);
$dataBulan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Susun data per bulan (1–12)
$bulanData = [];
foreach ($dataBulan as $d) {
    $bulanData[$d['bulan']] = $d;
}

// Buat daftar field & label
$fields = [
    'batch_count' => ['Batch Count', 'Per Day'],
    'productivity' => ['Productivity', 'Ton/Shift'],
    'production_speed' => ['Production Speed', 'Kg/Min'],
    'batch_weight' => ['Batch Weight', 'Kg/Batch'],
    'operation_factor' => ['Operation Factor', '%'],
    'cycle_time' => ['Cycle Time', 'Min/Batch'],
    'grade_change_sequence' => ['Grade Change Sequence', 'Frequently'],
    'grade_change_time' => ['Grade Change Time', 'Min/Grade'],
    'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
];

// Hitung average tahunan dari data bulan dengan format 2 digit
$averages = [];
foreach ($fields as $f => $v) {
    $sum = 0;
    $count = 0;
    for ($m = 1; $m <= 12; $m++) {
        if (!empty($bulanData[$m]['avg_' . $f]) && $bulanData[$m]['avg_' . $f] != 0) {
            $sum += $bulanData[$m]['avg_' . $f];
            $count++;
        }
    }
    $averages[$f] = $count > 0 ? number_format($sum / $count, 2) : '-';
}

// =============================================
// FUNGSI UNTUK MENDAPATKAN DATA RATA-RATA HARIAN DARI SHIFT
// =============================================
function getDailyAverageFromShifts($pdo, $lineId, $bulan, $tahun)
{
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
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
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        GROUP BY hari
        ORDER BY hari
    ");
    $stmt->execute([$lineId, $bulan, $tahun]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// FUNGSI UNTUK MENDAPATKAN DATA PER SHIFT (DETAIL)
function getShiftData($pdo, $lineId, $bulan, $tahun)
{
    $stmt = $pdo->prepare("
        SELECT 
            DAY(tanggal) AS hari,
            shift_id,
            ms.kode_shift,
            ms.nama_shift,
            batch_count,
            productivity,
            production_speed,
            batch_weight,
            operation_factor,
            cycle_time,
            grade_change_sequence,
            grade_change_time,
            feed_raw_material
        FROM input_harian ih
        LEFT JOIN master_shift ms ON ih.shift_id = ms.id
        WHERE line_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
        ORDER BY hari, ms.jam_mulai
    ");
    $stmt->execute([$lineId, $bulan, $tahun]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// =============================================
// AKHIR FUNGSI UNTUK MENDAPATKAN DATA RATA-RATA HARIAN DARI SHIFT
// =============================================