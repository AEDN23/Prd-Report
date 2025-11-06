<?php
$page_title = "Edit Target Produksi";
include '../layout/header.php';

require_once '../backend/config.php';

// Ambil ID dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('❌ ID tidak valid!'); window.location.href='data-target.php';</script>";
    exit;
}

try {
    // Ambil data target yang akan diedit
    $stmt = $pdo->prepare("
        SELECT 
            t.*,
            lp.kode_line,
            lp.nama_line
        FROM target t
        JOIN line_produksi lp ON t.line_id = lp.id
        WHERE t.id = ?
    ");
    $stmt->execute([$id]);
    $target = $stmt->fetch();

    if (!$target) {
        echo "<script>alert('❌ Data target tidak ditemukan!'); window.location.href='data-target.php';</script>";
        exit;
    }
} catch (Exception $e) {
    echo "<script>alert('❌ Error: " . addslashes($e->getMessage()) . "'); window.location.href='data-target.php';</script>";
    exit;
}
?>

<div class="container">
    <main>
        <div class="form-container">
            <!-- UBAH ACTION MENJADI proses-target.php -->
            <form action="../backend/proses-target.php" method="POST" id="formEditTarget">
                <input type="hidden" name="id" value="<?= htmlspecialchars($target['id']) ?>">

                <div class="form-section">
                    <h2>📅 Periode Target</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tahun_display">Tahun:</label>
                            <input type="text" id="tahun_display" class="form-control readonly-field"
                                value="<?= htmlspecialchars($target['tahun_target']) ?>"
                                readonly>
                            <input type="hidden" name="tahun" value="<?= htmlspecialchars($target['tahun_target']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="line_display">Line Produksi:</label>
                            <input type="text" id="line_display" class="form-control readonly-field"
                                value="<?= htmlspecialchars($target['kode_line'] . ' - ' . $target['nama_line']) ?>"
                                readonly>
                            <input type="hidden" name="line_id" value="<?= htmlspecialchars($target['line_id']) ?>">
                        </div>
                    </div>
                </div>



                <div class="form-section">
                    <h2>📊 Target Produksi</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="target_batch_count">Target Batch Count <span class="required">*</span></label>
                            <input type="number" id="target_batch_count" name="target_batch_count" step="0.01" min="0" required
                                value="<?= htmlspecialchars($target['target_batch_count'] ?? '') ?>">
                            <small class="form-help">Jumlah batch per hari</small>
                        </div>
                        <div class="form-group">
                            <label for="target_productivity">Target Productivity <span class="required">*</span></label>
                            <input type="number" id="target_productivity" name="target_productivity" step="0.01" min="0" required
                                value="<?= htmlspecialchars($target['target_productivity'] ?? '') ?>">
                            <small class="form-help">Ton per shift</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="target_operation_factor">Target Operation Factor <span class="required">*</span></label>
                            <input type="number" id="target_operation_factor" name="target_operation_factor" step="0.01" min="0" max="100" required
                                value="<?= htmlspecialchars($target['target_operation_factor'] ?? '') ?>">
                            <small class="form-help">Dalam persentase (0-100%)</small>
                        </div>
                        <div class="form-group">
                            <label for="target_cycle_time">Target Cycle Time</label>
                            <input type="number" id="target_cycle_time" name="target_cycle_time" step="0.01" min="0"
                                value="<?= htmlspecialchars($target['target_cycle_time'] ?? '') ?>">
                            <small class="form-help">Menit per batch</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>⚙️ Target Lainnya</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="target_production_speed">Target Production Speed</label>
                            <input type="number" id="target_production_speed" name="target_production_speed" step="0.01" min="0"
                                value="<?= htmlspecialchars($target['target_production_speed'] ?? '') ?>">
                            <small class="form-help">Kg per menit</small>
                        </div>
                        <div class="form-group">
                            <label for="target_batch_weight">Target Batch Weight</label>
                            <input type="number" id="target_batch_weight" name="target_batch_weight" step="0.01" min="0"
                                value="<?= htmlspecialchars($target['target_batch_weight'] ?? '') ?>">
                            <small class="form-help">Kg per batch</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="target_grade_change_sequence">Target Grade Change Sequence</label>
                            <input type="number" id="target_grade_change_sequence" name="target_grade_change_sequence" step="0.01" min="0"
                                value="<?= htmlspecialchars($target['target_grade_change_sequence'] ?? '') ?>">
                            <small class="form-help">Frekuensi perubahan grade</small>
                        </div>
                        <div class="form-group">
                            <label for="target_grade_change_time">Target Grade Change Time</label>
                            <input type="number" id="target_grade_change_time" name="target_grade_change_time" step="0.01" min="0"
                                value="<?= htmlspecialchars($target['target_grade_change_time'] ?? '') ?>">
                            <small class="form-help">Menit per grade change</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="target_feed_raw_material">Target Feed Raw Material</label>
                        <input type="number" id="target_feed_raw_material" name="target_feed_raw_material" step="0.01" min="0"
                            value="<?= htmlspecialchars($target['target_feed_raw_material'] ?? '') ?>">
                        <small class="form-help">Kg material per hari</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">💾 Update Target</button>
                    <a href="data-target.php" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php
include '../layout/footer.php';
?>

<style>
    .readonly-field {
        background-color: #f8f9fa !important;
        cursor: not-allowed !important;
        border: 1px solid #ced4da;
        color: #6c757d;
    }
</style>