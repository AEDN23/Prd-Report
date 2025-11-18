<?php
$page_title = "Data Harian per Shift";
include '../layout/header.php';
include '../layout/sidebar.php';

// Get all data with shift information
$dataHarian = getAllInputHarian($pdo);
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">📋 Data Produksi Harian per Shift</h6>
            <a href="input-harian.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Input Data Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="dataTable">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Line</th>
                            <th>Shift</th>
                            <th>Batch Count</th>
                            <th>Productivity</th>
                            <th>Production Speed</th>
                            <th>Batch Weight</th>
                            <th>Operation Factor</th>
                            <th>Cycle Time</th>
                            <th>Grade Change Seq</th>
                            <th>Grade Change Time</th>
                            <th>Feed Raw Material</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($dataHarian): ?>
                            <?php foreach ($dataHarian as $index => $data): ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($data['kode_line']) ?> - <?= htmlspecialchars($data['nama_line']) ?></td>
                                    <td class="text-center">
                                        <?= htmlspecialchars($data['kode_shift'] ?? '-') ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($data['nama_shift'] ?? '') ?></small>
                                    </td>
                                    <td class="text-center"><?= $data['batch_count'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['productivity'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['production_speed'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['batch_weight'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['operation_factor'] ? $data['operation_factor'] . '%' : '-' ?></td>
                                    <td class="text-center"><?= $data['cycle_time'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['grade_change_sequence'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['grade_change_time'] ?? '-' ?></td>
                                    <td class="text-center"><?= $data['feed_raw_material'] ?? '-' ?></td>
                                    <td><?= htmlspecialchars($data['keterangan'] ?? '') ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="edit-harian.php?id=<?= $data['id'] ?>" 
                                               class="btn btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../backend/hapus-harian.php?id=<?= $data['id'] ?>" 
                                               class="btn btn-danger" 
                                               title="Hapus"
                                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="15" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Belum ada data produksi harian.</p>
                                    <a href="input-harian.php" class="btn btn-primary mt-2">Input Data Pertama</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>