<?php
include 'config.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM input_harian WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ../dashboard/data-harian.php?success=1");
        exit;
    } catch (PDOException $e) {
        header("Location: ../dashboard/data-harian.php?error=1");
        exit;
    }
} else {
    header("Location: ../dashboard/data-harian.php");
    exit;
}
