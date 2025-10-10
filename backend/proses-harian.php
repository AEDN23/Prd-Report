<?php
include 'config.php';


// BACK LINK DARI ../produksi-report/input-harian.php

// Ambil data dari form
$tanggal                = $_POST['tanggal'] ?? '';
$line_id                = $_POST['line_id'] ?? '';
$batch_count            = $_POST['batch_count'] ?? null;
$productivity           = $_POST['productivity'] ?? null;
$production_speed       = $_POST['production_speed'] ?? null;
$batch_weight           = $_POST['batch_weight'] ?? null;
$operation_factor       = $_POST['operation_factor'] ?? null;
$cycle_time             = $_POST['cycle_time'] ?? null;
$grade_change_sequence  = $_POST['grade_change_sequence'] ?? null;
$grade_change_time      = $_POST['grade_change_time'] ?? null;
$feed_raw_material      = $_POST['feed_raw_material'] ?? null;

try {
    // Validasi input wajib
    if (empty($tanggal) || empty($line_id) || empty($batch_count) || empty($productivity) || empty($operation_factor)) {
        header("Location: ../produksi-report/input-harian.php?error=1");
        exit;
    }

    // Cek apakah data untuk tanggal + line sudah ada (supaya gak dobel)
    $cek = $pdo->prepare("SELECT id FROM input_harian WHERE tanggal = ? AND line_id = ?");
    $cek->execute([$tanggal, $line_id]);

    if ($cek->fetch()) {
        header("Location: ../produksi-report/input-harian.php?error=2"); // error duplikat
        exit;
    }

    // Insert data baru
    $stmt = $pdo->prepare("
        INSERT INTO input_harian 
        (line_id, tanggal, batch_count, productivity, production_speed, batch_weight, 
         operation_factor, cycle_time, grade_change_sequence, grade_change_time, feed_raw_material) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $line_id,
        $tanggal,
        $batch_count,
        $productivity,
        $production_speed,
        $batch_weight,
        $operation_factor,
        $cycle_time,
        $grade_change_sequence,
        $grade_change_time,
        $feed_raw_material
    ]);

    // Redirect sukses
    header("Location: ../produksi-report/input-harian.php?success=1");
    exit;
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    header("Location: ../produksi-report/input-harian.php?error=db");
    exit;
}
