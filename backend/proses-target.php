<?php
require_once 'config.php';
// BACKLINK DARI ../produksi-report/input-target.php

// Ambil data dari form
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

    showSuccess("Target produksi berhasil disimpan!");
} catch (PDOException $e) {
    showError("Terjadi error: " . $e->getMessage());
}
