<?php require_once 'config.php';
$tahun = $_POST['tahun'] ?? '';
$line_id = $_POST['line_id'] ?? '';
$stmt = $pdo->prepare("SELECT id FROM target WHERE line_id = ? AND target = ?");
$stmt->execute([$line_id, $tahun]);
$exists = $stmt->fetch() ? true : false;
echo json_encode(['exists' => $exists]);
