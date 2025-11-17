<?php
$page_title = "Edit Data Harian";
include '../layout/header.php';

$id = $_GET['id'] ?? 0;

// Get existing data
$stmt = $pdo->prepare("
    SELECT ih.*, lp.kode_line, lp.nama_line, ms.kode_shift, ms.nama_shift, ms.id as shift_id
    FROM input_harian ih
    JOIN line_produksi lp ON ih.line_id = lp.id
    LEFT JOIN master_shift ms ON ih.shift_id = ms.id
    WHERE ih.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='data-harian.php';</script>";
    exit;
}

$lines = getLineProduksi($pdo);
$shifts = getMasterShift($pdo);
?>

<div class="container">
    <main>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">
                ✅ Data berhasil diupdate!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])):
            $errorMsg = '';
            switch ($_GET['error']) {
                case '1':
                    $errorMsg = '⚠️ Semua field wajib diisi!';
                    break;
                case '2':
                    $errorMsg = '⚠️ Data untuk tanggal, line, dan shift ini sudah ada.';
                    break;
                case 'db':
                    $errorMsg = '❌ Terjadi kesalahan pada database. Silakan coba lagi.';
                    break;
                default:
                    $errorMsg = '❌ Gagal mengupdate data. Pastikan data valid.';
            }
        ?>
            <div class="alert alert-error">
                <?= $errorMsg ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="form-container">
                <form action="../backend/proses-harian.php" method="POST" id="formEditHarian">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">

                    <div class="form-section">
                        <h2 class="calendar-icon">Periode Data</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="tanggal">Tanggal:</label>
                                <input type="text" class="form-control readonly-field"
                                    value="<?= date('d/m/Y', strtotime($data['tanggal'])) ?>"
                                    readonly>
                                <input type="hidden" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>">
                                <small class="form-help text-muted">Tanggal tidak dapat diubah</small>
                            </div>
                            <div class="form-group">
                                <label for="line_display">Line Produksi:</label>
                                <input type="text" class="form-control readonly-field"
                                    value="<?= htmlspecialchars($data['kode_line'] . ' - ' . $data['nama_line']) ?>"
                                    readonly>
                                <input type="hidden" name="line_id" value="<?= htmlspecialchars($data['line_id']) ?>">
                                <small class="form-help text-muted">Line produksi tidak dapat diubah</small>
                            </div>

                            <div class="form-group">
                                <label for="shift_display">Shift:</label>
                                <input type="text" class="form-control readonly-field"
                                    value="<?= htmlspecialchars($data['kode_shift'] . ' - ' . $data['nama_shift']) ?>"
                                    readonly>
                                <input type="hidden" name="shift_id" value="<?= htmlspecialchars($data['shift_id']) ?>">
                                <small class="form-help text-muted">Shift tidak dapat diubah</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="production-icon">Data Produksi</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="batch_count">Batch Count <span class="required">*</span></label>
                                <input type="number" id="batch_count" name="batch_count" step="0.01" min="0" required
                                    value="<?= htmlspecialchars($data['batch_count'] ?? '') ?>">
                                <small class="form-help">Jumlah batch shift ini</small>
                            </div>
                            <div class="form-group">
                                <label for="productivity">Productivity <span class="required">*</span></label>
                                <input type="number" id="productivity" name="productivity" step="0.01" min="0" required
                                    value="<?= htmlspecialchars($data['productivity'] ?? '') ?>">
                                <small class="form-help">Ton per shift</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="production_speed">Production Speed</label>
                                <input type="number" id="production_speed" name="production_speed" step="0.01" min="0"
                                    value="<?= htmlspecialchars($data['production_speed'] ?? '') ?>">
                                <small class="form-help">Kg per menit</small>
                            </div>
                            <div class="form-group">
                                <label for="batch_weight">Batch Weight</label>
                                <input type="number" id="batch_weight" name="batch_weight" step="0.01" min="0"
                                    value="<?= htmlspecialchars($data['batch_weight'] ?? '') ?>">
                                <small class="form-help">Kg per batch</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="metrics-icon">Metrik Operasional</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="operation_factor">Operation Factor <span class="required">*</span></label>
                                <input type="number" id="operation_factor" name="operation_factor" step="0.01" min="0" max="100" required
                                    value="<?= htmlspecialchars($data['operation_factor'] ?? '') ?>">
                                <small class="form-help">Dalam persentase (0-100%)</small>
                            </div>
                            <div class="form-group">
                                <label for="cycle_time">Cycle Time</label>
                                <input type="number" id="cycle_time" name="cycle_time" step="0.01" min="0"
                                    value="<?= htmlspecialchars($data['cycle_time'] ?? '') ?>">
                                <small class="form-help">Menit per batch</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="grade_change_sequence">Grade Change Sequence</label>
                                <input type="number" id="grade_change_sequence" name="grade_change_sequence" step="0.01" min="0"
                                    value="<?= htmlspecialchars($data['grade_change_sequence'] ?? '') ?>">
                                <small class="form-help">Frekuensi perubahan grade</small>
                            </div>
                            <div class="form-group">
                                <label for="grade_change_time">Grade Change Time</label>
                                <input type="number" id="grade_change_time" name="grade_change_time" step="0.01" min="0"
                                    value="<?= htmlspecialchars($data['grade_change_time'] ?? '') ?>">
                                <small class="form-help">Menit per grade change</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="material-icon">Material & Raw Material</h2>
                        <div class="form-group">
                            <label for="feed_raw_material">Feed Raw Material</label>
                            <input type="number" id="feed_raw_material" name="feed_raw_material" step="0.01" min="0"
                                value="<?= htmlspecialchars($data['feed_raw_material'] ?? '') ?>">
                            <small class="form-help">Kg material yang dipakai hari ini</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="material-icon">Informasi Tambahan</h2>
                        <div class="form-group">
                            <textarea id="keterangan" name="keterangan" rows="3"
                                placeholder="Catatan tambahan untuk shift ini..."
                                style="width: 100%; box-sizing: border-box;"></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">💾 Update Data</button>
                        <a href="index.php" class="btn btn-secondary">❌ Kembali ke Dashboard</a>
                        <a href="data-harian.php" class="btn btn-outline-secondary">📋 Lihat Semua Data</a>
                    </div>
                </form>
            </div>
    </main>
</div>

<style>
    .readonly-field {
        background-color: #f8f9fa !important;
        cursor: not-allowed !important;
        border: 1px solid #ced4da;
        color: #6c757d;
    }
</style>

<?php include '../layout/footer.php'; ?>