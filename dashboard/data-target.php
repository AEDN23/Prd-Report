<?php
$page_title = "Data Target Produksi";
include '../layout/header.php';
include '../layout/sidebar.php';

require_once '../backend/config.php';

try {
    $targets = getSemuaTarget($pdo);
} catch (Exception $e) {
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    $targets = [];
}
?>

<div class="container-fluid">
    <div class="existing-targets">
        <h2>📋 Daftar Target Produksi</h2>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Line Produksi</th>
                        <th>Batch Count</th>
                        <th>Productivity</th>
                        <th>Operation Factor</th>
                        <th>Production Speed</th>
                        <th>Batch Weight</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($targets): ?>
                        <?php foreach ($targets as $index => $target): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($target['tahun_target']) ?></td>
                                <td><?= htmlspecialchars($target['kode_line']) ?> - <?= htmlspecialchars($target['nama_line']) ?></td>
                                <td><?= htmlspecialchars($target['target_batch_count'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($target['target_productivity'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($target['target_operation_factor'] ?? '-') ?>%</td>
                                <td><?= htmlspecialchars($target['target_production_speed'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($target['target_batch_weight'] ?? '-') ?></td>
                                <td>
                                    <a href="edit-target.php?id=<?= $target['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <!-- UBAH LINK HAPUS MENJADI proses-target.php -->
                                    <a href="../backend/proses-target.php?id=<?= $target['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin ingin menghapus target ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <p>Tidak ada data target. <a href="input-target.php">Klik untuk tambah target</a></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <a href="input-target.php" class="btn btn-primary">➕ Tambah Target Baru</a>
        </div>
    </div>
</div>

<?php
include '../layout/footer.php';
?>