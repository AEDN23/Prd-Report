<?php
require_once 'config.php';

// PROSES HAPUS - jika ada parameter ID di URL tanpa POST
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo "<script>alert('❌ ID tidak valid!'); window.location.href='../dashboard/index';</script>";
        exit;
    }

    try {
        // Cek apakah data target ada
        $cekData = $pdo->prepare("SELECT id FROM target WHERE id = ?");
        $cekData->execute([$id]);

        if (!$cekData->fetch()) {
            echo "<script>alert('❌ Data target tidak ditemukan!'); window.location.href='../dashboard/index';</script>";
            exit;
        }

        // Hapus data target
        $stmt = $pdo->prepare("DELETE FROM target WHERE id = ?");
        $stmt->execute([$id]);

        echo "<script>
            alert('✅ Target produksi berhasil dihapus!');
            window.location.href = '../dashboard/index';
        </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>
            alert('❌ Gagal menghapus: " . addslashes($e->getMessage()) . "');
            window.location.href = '../dashboard/index';
        </script>";
        exit;
    }
}

// PROSES TAMBAH/EDIT DATA - jika method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null; // ID akan ada untuk edit, null untuk tambah
    $tahun = $_POST['tahun'] ?? '';
    $line_id = $_POST['line_id'] ?? '';
    $target_batch_count = $_POST['target_batch_count'] ?? null;
    $target_productivity = $_POST['target_productivity'] ?? null;
    $target_production_speed = $_POST['target_production_speed'] ?? null;
    $target_batch_weight = $_POST['target_batch_weight'] ?? null;
    $target_operation_factor = $_POST['target_operation_factor'] ?? null;
    $target_cycle_time = $_POST['target_cycle_time'] ?? null;
    $target_grade_change_sequence = $_POST['target_grade_change_sequence'] ?? null;
    $target_grade_change_time = $_POST['target_grade_change_time'] ?? null;
    $target_feed_raw_material = $_POST['target_feed_raw_material'] ?? null;

    try {
        // Validasi input wajib
        if (empty($tahun) || empty($line_id)) {
            showError("Tahun dan Line Produksi wajib diisi!");
        }

        // PROSES EDIT - jika ada ID
        if ($id) {
            // Cek apakah data target ada
            $cekData = $pdo->prepare("SELECT id FROM target WHERE id = ?");
            $cekData->execute([$id]);
            if (!$cekData->fetch()) {
                showError("Data target tidak ditemukan!");
            }

            // Cek apakah kombinasi tahun + line_id sudah ada (kecuali untuk data yang sedang diedit)
            $cek = $pdo->prepare("SELECT id FROM target WHERE tahun_target = ? AND line_id = ? AND id != ?");
            $cek->execute([$tahun, $line_id, $id]);

            if ($cek->fetch()) {
                showError("Target untuk tahun $tahun di line ini sudah ada!");
            }

            // Update data target
            $stmt = $pdo->prepare("
                UPDATE target 
                SET tahun_target = ?, 
                    line_id = ?, 
                    target_batch_count = ?, 
                    target_productivity = ?, 
                    target_production_speed = ?, 
                    target_batch_weight = ?, 
                    target_operation_factor = ?, 
                    target_cycle_time = ?, 
                    target_grade_change_sequence = ?, 
                    target_grade_change_time = ?, 
                    target_feed_raw_material = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");

            $stmt->execute([
                $tahun,
                $line_id,
                $target_batch_count,
                $target_productivity,
                $target_production_speed,
                $target_batch_weight,
                $target_operation_factor,
                $target_cycle_time,
                $target_grade_change_sequence,
                $target_grade_change_time,
                $target_feed_raw_material,
                $id
            ]);

            echo "<script>
                alert('✅ Target produksi berhasil diupdate!');
                window.location.href = '../dashboard/index';
            </script>";
            exit;
        }
        // PROSES TAMBAH - jika tidak ada ID
        else {
            // Cek apakah kombinasi tahun + line_id sudah ada
            $cek = $pdo->prepare("SELECT id FROM target WHERE tahun_target = ? AND line_id = ?");
            $cek->execute([$tahun, $line_id]);

            if ($cek->fetch()) {
                showError("Target untuk tahun $tahun di line ini sudah ada!");
            }

            // Insert ke tabel target
            $stmt = $pdo->prepare("
                INSERT INTO target 
                (tahun_target, line_id, target_batch_count, target_productivity, target_production_speed, target_batch_weight, 
                 target_operation_factor, target_cycle_time, target_grade_change_sequence, target_grade_change_time, 
                 target_feed_raw_material) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $tahun,
                $line_id,
                $target_batch_count,
                $target_productivity,
                $target_production_speed,
                $target_batch_weight,
                $target_operation_factor,
                $target_cycle_time,
                $target_grade_change_sequence,
                $target_grade_change_time,
                $target_feed_raw_material
            ]);

            showSuccessTarget("Target produksi berhasil disimpan!");
        }
    } catch (PDOException $e) {
        showError("Terjadi error: " . $e->getMessage());
    }
}
