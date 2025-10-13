<?php
require_once 'config.php';

// Ambil data dari form
$judul          = trim($_POST['judul'] ?? '');
$deskripsi      = trim($_POST['deskripsi'] ?? '');
$isi_informasi  = trim($_POST['isi_informasi'] ?? '');
$file           = $_FILES['file'] ?? null;

// Validasi input wajib
if (empty($judul) || empty($deskripsi) || empty($isi_informasi)) {
    echo "<script>alert('❌ Semua field wajib diisi!'); window.history.back();</script>";
    exit;
}

try {
    // Cek apakah judul sudah ada (hindari duplikat)
    $cek = $pdo->prepare("SELECT id FROM info WHERE judul = ?");
    $cek->execute([$judul]);
    if ($cek->fetch()) {
        echo "<script>alert('⚠️ Judul sudah ada, silakan gunakan judul lain!'); window.history.back();</script>";
        exit;
    }

    // Inisialisasi nama file
    $namaFile = null;

    // Proses upload file jika ada
    if (!empty($file['name'])) {
        $uploadDir = __DIR__ . '/../uploads/info/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeJudul = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower(str_replace(' ', '_', $judul)));
        $namaFile = $safeJudul . '_' . uniqid() . '.' . strtolower($ext);
        $targetPath = $uploadDir . $namaFile;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo "<script>alert('⚠️ Gagal mengunggah file!'); window.history.back();</script>";
            exit;
        }
    }

    // Simpan data ke database
    $stmt = $pdo->prepare("
        INSERT INTO info (judul, deskripsi, isi, file, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$judul, $deskripsi, $isi_informasi, $namaFile]);

    echo "<script>alert('✅ Data berhasil disimpan!'); window.location.href='../produksi-report/informasi';</script>";
    exit;
} catch (Exception $e) {
    echo "<script>alert('❌ Terjadi kesalahan: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}
