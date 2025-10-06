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
        window.location.href = '../input-target.php';
    </script>";
    exit();
}
function showSuccess($message)
{
    echo "<script>
        alert('Berhasil menambahkan: ' + " . json_encode($message) . ");
        window.location.href = '../index.php';
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
