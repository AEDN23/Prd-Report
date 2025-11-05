<?php
require_once 'config.php';

// Jika ada parameter ID di URL, berarti ini proses hapus
if (isset($_GET['id']) && !isset($_POST['judul'])) {
    $id = $_GET['id'];

    try {
        // Ambil data info untuk mendapatkan nama file
        $stmt = $pdo->prepare("SELECT file FROM info WHERE id = ?");
        $stmt->execute([$id]);
        $info = $stmt->fetch();

        if ($info) {
            // Hapus file jika ada
            if (!empty($info['file'])) {
                $filePath = __DIR__ . '/../uploads/info/' . $info['file'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus data dari database
            $stmt = $pdo->prepare("DELETE FROM info WHERE id = ?");
            $stmt->execute([$id]);

            echo "<script>alert('✅ Informasi berhasil dihapus!'); window.location.href='../dashboard/informasi.php';</script>";
        } else {
            echo "<script>alert('❌ Data tidak ditemukan!'); window.location.href='../dashboard/informasi.php';</script>";
        }
        exit;
    } catch (Exception $e) {
        echo "<script>alert('❌ Gagal menghapus: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}

// PROSES TAMBAH/EDIT DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $isi_informasi = trim($_POST['isi_informasi'] ?? '');
    $file = $_FILES['file'] ?? null;

    // Validasi input wajib
    if (empty($judul) || empty($deskripsi) || empty($isi_informasi)) {
        echo "<script>alert('❌ Semua field wajib diisi!'); window.history.back();</script>";
        exit;
    }

    try {
        // Cek apakah judul sudah ada (kecuali untuk data yang sedang diedit)
        $cek = $pdo->prepare("SELECT id FROM info WHERE judul = ? AND id != ?");
        $cek->execute([$judul, $id]);
        if ($cek->fetch()) {
            echo "<script>alert('⚠️ Judul sudah ada, silakan gunakan judul lain!'); window.history.back();</script>";
            exit;
        }

        $namaFile = null;

        // Proses upload file baru jika ada
        if (!empty($file['name'])) {
            $uploadDir = __DIR__ . '/../uploads/info/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Jika edit, hapus file lama terlebih dahulu
            if ($id) {
                $stmt = $pdo->prepare("SELECT file FROM info WHERE id = ?");
                $stmt->execute([$id]);
                $oldFile = $stmt->fetchColumn();

                if (!empty($oldFile)) {
                    $oldFilePath = $uploadDir . $oldFile;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeJudul = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower(str_replace(' ', '_', $judul)));
            $namaFile = $safeJudul . '_' . uniqid() . '.' . strtolower($ext);
            $targetPath = $uploadDir . $namaFile;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                echo "<script>alert('⚠️ Gagal mengunggah file!'); window.history.back();</script>";
                exit;
            }
        } else if ($id) {
            // Jika edit tapi tidak upload file baru, pertahankan file lama
            $stmt = $pdo->prepare("SELECT file FROM info WHERE id = ?");
            $stmt->execute([$id]);
            $namaFile = $stmt->fetchColumn();
        }

        // PROSES UPDATE jika ada ID
        if ($id) {
            $stmt = $pdo->prepare("
                UPDATE info 
                SET judul = ?, deskripsi = ?, isi = ?, file = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$judul, $deskripsi, $isi_informasi, $namaFile, $id]);

            echo "<script>alert('✅ Data berhasil diupdate!'); window.location.href='../dashboard/informasi.php';</script>";
        }
        // PROSES INSERT jika tidak ada ID
        else {
            $stmt = $pdo->prepare("
                INSERT INTO info (judul, deskripsi, isi, file, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$judul, $deskripsi, $isi_informasi, $namaFile]);

            echo "<script>alert('✅ Data berhasil disimpan!'); window.location.href='../dashboard/informasi.php';</script>";
        }
        exit;
    } catch (Exception $e) {
        echo "<script>alert('❌ Terjadi kesalahan: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}
