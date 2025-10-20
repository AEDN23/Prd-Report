<?php
$page_title = "Input Harian";
include '../layout/header.php';

// Get data for dropdowns
$lines = getLineProduksi($pdo);

// Get today's date for default value
$today = date('Y-m-d');
?>

<div class="container">

    <main>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">
                ✅ Data berhasil disimpan!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])):
            $errorMsg = '';
            switch ($_GET['error']) {
                case '1':
                    $errorMsg = '⚠️ Semua field wajib diisi!';
                    break;
                case '2':
                    $errorMsg = '⚠️ Data untuk tanggal dan line ini sudah ada. Silakan ubah tanggal atau line.';
                    break;
                case 'db':
                    $errorMsg = '❌ Terjadi kesalahan pada database. Silakan coba lagi.';
                    break;
                default:
                    $errorMsg = '❌ Gagal menyimpan data. Pastikan data valid.';
            }
        ?>
            <div class="alert alert-error">
                <?= $errorMsg ?>
            </div>
        <?php endif; ?>


        <div class="form-container">
            <form action="../backend/proses-harian.php" method="POST" id="formHarian">
                <div class="form-section">
                    <h2 class="calendar-icon">Periode Data</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal">
                                Tanggal
                                <span class="required">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal" required
                                value="<?= $today ?>"
                                max="<?= $today ?>"
                                style="width: 100%; cursor: pointer;"
                                onclick="this.showPicker && this.showPicker();">
                        </div>

                        <div class="form-group">
                            <label for="line_id">
                                Line Produksi
                                <span class="required">*</span>
                            </label>
                            <select id="line_id" name="line_id" required>
                                <option value="">-- Pilih Line --</option>
                                <?php
                                if (!empty($lines)) {
                                    foreach ($lines as $line):
                                ?>
                                        <option value="<?= $line['id'] ?>">
                                            <?= htmlspecialchars($line['kode_line']) ?> - <?= htmlspecialchars($line['nama_line']) ?>
                                        </option>
                                <?php
                                    endforeach;
                                } else {
                                    echo '<option value="">-- Data line tidak tersedia --</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="production-icon">Data Produksi</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="batch_count">
                                Batch Count
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="batch_count" name="batch_count"
                                step="0.01" min="0" required
                                value="">
                            <small class="form-help">Jumlah batch hari ini</small>
                        </div>

                        <div class="form-group">
                            <label for="productivity">
                                Productivity
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="productivity" name="productivity"
                                step="0.01" min="0" required
                                value=""
                            <small class="form-help">Ton per shift</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="production_speed">
                                Production Speed
                            </label>
                            <input type="number" id="production_speed" name="production_speed"
                                step="0.01" min="0"
                                value=""
                                required>
                            <small class="form-help">Kg per menit</small>
                        </div>

                        <div class="form-group">
                            <label for="batch_weight">
                                Batch Weight
                            </label>
                            <input type="number" id="batch_weight" name="batch_weight"
                                step="0.01" min="0"
                                required
                                value="">
                            <small class="form-help">Kg per batch</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="metrics-icon">Metrik Operasional</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="operation_factor">
                                Operation Factor
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="operation_factor" name="operation_factor"
                                step="0.01" min="0" max="100" required
                                value="">
                            <small class="form-help">Dalam persentase (0-100%)</small>
                        </div>

                        <div class="form-group">
                            <label for="cycle_time">
                                Cycle Time
                            </label>
                            <input type="number" id="cycle_time" name="cycle_time"
                                step="0.01" min="0" required
                                value="">
                            <small class="form-help">Menit per batch</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="grade_change_sequence">
                                Grade Change Sequence
                            </label>
                            <input type="number" id="grade_change_sequence" name="grade_change_sequence"
                                step="0.01" min="0" required
                                value="">
                            <small class="form-help">Frekuensi perubahan grade</small>
                        </div>

                        <div class="form-group">
                            <label for="grade_change_time">
                                Grade Change Time
                            </label>
                            <input type="number" id="grade_change_time" name="grade_change_time"
                            step="0.01" min="0" required
                                value="">
                            <small class="form-help">Menit per grade change</small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="material-icon">Material & Raw Material</h2>

                    <div class="form-group">
                        <label for="feed_raw_material">
                            Feed Raw Material
                        </label>
                        <input type="number" id="feed_raw_material" name="feed_raw_material"
                            step="0.01" min="0" required
                            value="">
                        <small class="form-help">Kg material yang dipakai hari ini</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        💾 Simpan Data
                    </button>
                    <!-- <a href="data-harian.php" class="btn btn-secondary">📋 Lihat Data</a> -->
                    <a href="index.php" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>

    </main>
</div>

<script>
    // Cek data existing
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal');
        const lineSelect = document.getElementById('line_id');
        const warningDiv = document.createElement('div');
        warningDiv.className = 'alert alert-warning';
        warningDiv.style.display = 'none';
        warningDiv.id = 'data-exist-warning';

        const form = document.getElementById('formHarian');
        form.parentNode.insertBefore(warningDiv, form);

        function cekDataExist() {
            const tanggal = tanggalInput.value;
            const lineId = lineSelect.value;

            if (tanggal && lineId) {
                fetch(`../backend/cek-data-harian.php?tanggal=${tanggal}&line_id=${lineId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            warningDiv.style.display = 'block';
                            warningDiv.innerHTML =
                                `⚠️ Data untuk ${data.tanggal} - ${data.line} sudah ada.`;
                        } else {
                            warningDiv.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                warningDiv.style.display = 'none';
            }
        }

        tanggalInput.addEventListener('change', cekDataExist);
        lineSelect.addEventListener('change', cekDataExist);
    });
</script>
<?php
include '../layout/footer.php';
?>