<?php
$page_title = "Input Target Produksi";
include '../layout/header.php';
?>

<script>
    $(document).ready(function() {
        $("#tahun, #line_id").change(function() {
            var tahun = $("#tahun").val();
            var line_id = $("#line_id").val();
            if (tahun && line_id) {
                $.post("../backend/cek-target.php", {
                    tahun: tahun,
                    line_id: line_id
                }, function(response) {
                    if (response.exists) {
                        alert("⚠️ Target untuk tahun " + tahun + " dan line ini sudah ada!");
                    }
                }, "json");
            }
        });
    });
</script>

<div class="container">
    <main> <?php if (isset($_GET['error'])): ?> <div class="alert alert-error"> ❌ Gagal menyimpan target. Pastikan data valid. </div> <?php endif; ?> <div class="form-container">
            <form action="../backend/proses-target.php" method="POST" id="formTarget">
                <div class="form-section">
                    <h2>📅 Periode Target</h2>
                    <div class="form-row">
                        <div class="form-group"> <label for="tahun">Tahun:</label>
                            <select name="tahun" id="tahun" required>
                                <option value="">-- Pilih Tahun --</option>
                                <?php $tahunSekarang = date("Y");
                                for ($tahun = $tahunSekarang; $tahun >= 2013; $tahun--) {
                                    echo "<option value='$tahun'>$tahun</option>";
                                } ?>
                            </select>

                        </div>
                        <div class="form-group"> <label for="line_id">Line Produksi:</label>
                            <select id="line_id" name="line_id" required>
                                <option value="">Pilih Line</option>
                                <?php $lines = getLineProduksi($pdo);
                                foreach ($lines as $line): $selected = $edit_mode ? ($existing_data['line_id'] == $line['id'] ? 'selected' : '') : ''; ?> <option value="<?= $line['id'] ?>" <?= $selected ?>> <?= $line['kode_line'] ?> - <?= $line['nama_line'] ?> </option> <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h2>📊 Target Produksi</h2>
                    <div class="form-row">
                        <div class="form-group"> <label for="target_batch_count"> Target Batch Count <span class="required">*</span> </label> <input type="number" id="target_batch_count" name="target_batch_count" step="0.01" min="0" required value="<?= $existing_data['target_batch_count'] ?? '' ?>"> <small class="form-help">Jumlah batch per hari</small> </div>
                        <div class="form-group"> <label for="target_productivity"> Target Productivity <span class="required">*</span> </label> <input type="number" id="target_productivity" name="target_productivity" step="0.01" min="0" required value="<?= $existing_data['target_productivity'] ?? '' ?>"> <small class="form-help">Ton per shift</small> </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"> <label for="target_operation_factor"> Target Operation Factor <span class="required">*</span> </label> <input type="number" id="target_operation_factor" name="target_operation_factor" step="0.01" min="0" max="100" required value="<?= $existing_data['target_operation_factor'] ?? '' ?>"> <small class="form-help">Dalam persentase (0-100%)</small> </div>
                        <div class="form-group"> <label for="target_cycle_time">Target Cycle Time</label> <input type="number" id="target_cycle_time" name="target_cycle_time" step="0.01" min="0" required value="<?= $existing_data['target_cycle_time'] ?? '' ?>"> <small class="form-help">Menit per batch</small> </div>
                    </div>
                </div>
                <div class="form-section">
                    <h2>⚙️ Target Lainnya</h2>
                    <div class="form-row">
                        <div class="form-group"> <label for="target_production_speed">Target Production Speed</label> <input type="number" id="target_production_speed" name="target_production_speed" step="0.01" min="0" required value="<?= $existing_data['target_production_speed'] ?? '' ?>"> <small class="form-help">Kg per menit</small> </div>
                        <div class="form-group"> <label for="target_batch_weight">Target Batch Weight</label> <input type="number" id="target_batch_weight" name="target_batch_weight" step="0.01" min="0" required value="<?= $existing_data['target_batch_weight'] ?? '' ?>"> <small class="form-help">Kg per batch</small> </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"> <label for="target_grade_change_sequence">Target Grade Change Sequence</label> <input type="number" id="target_grade_change_sequence" name="target_grade_change_sequence" step="0.01" min="0" required value="<?= $existing_data['target_grade_change_sequence'] ?? '' ?>"> <small class="form-help">Frekuensi perubahan grade</small> </div>
                        <div class="form-group"> <label for="target_grade_change_time">Target Grade Change Time</label> <input type="number" id="target_grade_change_time" name="target_grade_change_time" step="0.01" min="0" required value="<?= $existing_data['target_grade_change_time'] ?? '' ?>"> <small class="form-help">Menit per grade change</small> </div>
                    </div>
                    <div class="form-group"> <label for="target_feed_raw_material">Target Feed Raw Material</label> <input type="number" id="target_feed_raw_material" name="target_feed_raw_material" step="0.01" min="0" required value="<?= $existing_data['target_feed_raw_material'] ?? '' ?>" placeholder="Contoh: 5000"> <small class="form-help">Kg material per hari</small>
                        <div class="form-actions"> <button type="submit" class="btn btn-primary btn-large"> 💾 Simpan Target </button> <a href="index.php" class="btn btn-secondary">❌ Batal</a> </div>
                    </div>
                </div>
            </form>
        </div>
</div>
</main>

<?php
include '../layout/footer.php';

?>