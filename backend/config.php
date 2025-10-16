<?php
session_start();
$host = 'localhost';
$dbname = 'db_produksi_report';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Function helper
function formatAngka($angka) {}




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

// FUNGSI UNTUK MENDAPATKAN DATA INPUT TARGET END


// FUNGSI UNTUK MENDAPATKAN DATA INPUT HARIAN
function getAllInputHarian($pdo)
{
    $sql = "
        SELECT 
            ih.*,
            lp.kode_line,
            lp.nama_line
        FROM input_harian ih
        JOIN line_produksi lp ON ih.line_id = lp.id
        ORDER BY ih.tanggal DESC, lp.kode_line
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}
// FUNGSI UNTUK MENDAPATKAN DATA INPUT HARIAN END


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

function getDataHarianByLine($pdo, $lineId)
{
    $stmt = $pdo->prepare("
        SELECT 
            ih.*,
            lp.kode_line,
            lp.nama_line
        FROM input_harian ih
        JOIN line_produksi lp ON ih.line_id = lp.id
        WHERE ih.line_id = ?
        ORDER BY ih.tanggal DESC
    ");
    $stmt->execute([$lineId]);
    return $stmt->fetchAll();
}



// INDEX PROSES HARIAN
// Ambil daftar line untuk dropdown
$lines = getLineProduksi($pdo);

// Default filter
$selectedLine = $_GET['line'] ?? 1;
$selectedMonth = $_GET['bulan'] ?? date('m');
$selectedYear = $_GET['tahun'] ?? date('Y');

// Ambil data input_harian sesuai filter
$stmt = $pdo->prepare("
    SELECT * FROM input_harian 
    WHERE line_id = ? 
    AND MONTH(tanggal) = ? 
    AND YEAR(tanggal) = ?
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

// Hitung average per kolom
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
        if (!empty($row[$f])) {
            $sum += $row[$f];
            $count++;
        }
    }
    $averages[$f] = $count > 0 ? round($sum / $count, 2) : 0;
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

// Ambil data harian selama 1 tahun untuk line & tahun yang dipilih
$stmt = $pdo->prepare("
    SELECT 
        MONTH(tanggal) AS bulan,
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

// Hitung average tahunan dari data bulan
$averages = [];
foreach ($fields as $f => $v) {
    $sum = 0;
    $count = 0;
    for ($m = 1; $m <= 12; $m++) {
        if (!empty($bulanData[$m]['avg_' . $f])) {
            $sum += $bulanData[$m]['avg_' . $f];
            $count++;
        }
    }
    $averages[$f] = $count > 0 ? round($sum / $count, 2) : '-';
}
