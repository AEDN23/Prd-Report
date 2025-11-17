<?php
include 'config.php';

$action = $_POST['action'] ?? 'create'; // 'create' atau 'update'
$id = $_POST['id'] ?? 0;

$tanggal = $_POST['tanggal'] ?? '';
$line_id = $_POST['line_id'] ?? '';
$shift_id = $_POST['shift_id'] ?? '';
$batch_count = $_POST['batch_count'] ?? null;
$productivity = $_POST['productivity'] ?? null;
$production_speed = $_POST['production_speed'] ?? null;
$batch_weight = $_POST['batch_weight'] ?? null;
$operation_factor = $_POST['operation_factor'] ?? null;
$cycle_time = $_POST['cycle_time'] ?? null;
$grade_change_sequence = $_POST['grade_change_sequence'] ?? null;
$grade_change_time = $_POST['grade_change_time'] ?? null;
$feed_raw_material = $_POST['feed_raw_material'] ?? null;
$keterangan = $_POST['keterangan'] ?? null;

try {
    // Validasi input wajib
    if (
        empty($tanggal) || empty($line_id) || empty($shift_id) ||
        empty($batch_count) || empty($productivity) || empty($operation_factor)
    ) {
        if ($action === 'update') {
            header("Location: ../dashboard/edit-harian.php?id=$id&error=1");
        } else {
            header("Location: ../dashboard/input-harian.php?error=1");
        }
        exit;
    }

    if ($action === 'update') {
        // ========== UPDATE DATA ==========

        // Cek apakah data yang akan diupdate ada
        $cekData = $pdo->prepare("SELECT id FROM input_harian WHERE id = ?");
        $cekData->execute([$id]);

        if (!$cekData->fetch()) {
            header("Location: ../dashboard/edit-harian.php?id=$id&error=db");
            exit;
        }

        // Update data
        $stmt = $pdo->prepare("
            UPDATE input_harian 
            SET 
                batch_count = ?,
                productivity = ?,
                production_speed = ?,
                batch_weight = ?,
                operation_factor = ?,
                cycle_time = ?,
                grade_change_sequence = ?,
                grade_change_time = ?,
                feed_raw_material = ?,
                keterangan = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $batch_count,
            $productivity,
            $production_speed,
            $batch_weight,
            $operation_factor,
            $cycle_time,
            $grade_change_sequence,
            $grade_change_time,
            $feed_raw_material,
            $keterangan,
            $id
        ]);

        header("Location: ../dashboard/edit-harian.php?id=$id&success=1");
    } else {
        // ========== CREATE DATA BARU ==========

        // Cek apakah data untuk tanggal + line + shift sudah ada
        $cek = $pdo->prepare("SELECT id FROM input_harian WHERE tanggal = ? AND line_id = ? AND shift_id = ?");
        $cek->execute([$tanggal, $line_id, $shift_id]);

        if ($cek->fetch()) {
            header("Location: ../dashboard/input-harian.php?error=2");
            exit;
        }

        // Insert data baru
        $stmt = $pdo->prepare("
            INSERT INTO input_harian 
            (line_id, shift_id, tanggal, batch_count, productivity, production_speed, batch_weight, 
             operation_factor, cycle_time, grade_change_sequence, grade_change_time, feed_raw_material, keterangan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $line_id,
            $shift_id,
            $tanggal,
            $batch_count,
            $productivity,
            $production_speed,
            $batch_weight,
            $operation_factor,
            $cycle_time,
            $grade_change_sequence,
            $grade_change_time,
            $feed_raw_material,
            $keterangan
        ]);

        header("Location: ../dashboard/input-harian.php?success=1");
    }

    exit;
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());

    if ($action === 'update') {
        header("Location: ../dashboard/edit-harian.php?id=$id&error=db");
    } else {
        header("Location: ../dashboard/input-harian.php?error=db");
    }
    exit;
}
