<?php
$page_title = "Input Harian Per Shift";
include '../layout/header.php';

// Get data for dropdowns
$lines = getLineProduksi($pdo);
$shifts = getMasterShift($pdo);

// Get today's date for default value
$today = date('Y-m-d');
?>

<div class="container">
    <main>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
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
                    $errorMsg = '⚠️ Data untuk tanggal, line, dan shift ini sudah ada.';
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
                <input type="hidden" name="action" value="create"> <!-- INI YANG DITAMBAH -->
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
                                <?php foreach ($lines as $line): ?>
                                    <option value="<?= $line['id'] ?>">
                                        <?= htmlspecialchars($line['kode_line']) ?> - <?= htmlspecialchars($line['nama_line']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="shift_id">
                                Shift
                                <span class="required">*</span>
                            </label>
                            <select id="shift_id" name="shift_id" required>
                                <option value="">-- Pilih Shift --</option>
                                <?php foreach ($shifts as $shift): ?>
                                    <option value="<?= $shift['id'] ?>"
                                        data-start="<?= $shift['jam_mulai'] ?>"
                                        data-end="<?= $shift['jam_selesai'] ?>">
                                        <?= htmlspecialchars($shift['kode_shift']) ?> - <?= htmlspecialchars($shift['nama_shift']) ?>
                                        (<?= substr($shift['jam_mulai'], 0, 5) ?> - <?= substr($shift['jam_selesai'], 0, 5) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-help" id="shift-time-display"></small>
                        </div>
                    </div>
                </div>



                <!-- Data Produksi Section (sama seperti sebelumnya) -->
                <div class="form-section">
                    <h2 class="production-icon">Data Produksi</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="batch_count">Batch Count <span class="required">*</span></label>
                            <input type="number" id="batch_count" name="batch_count" step="0.01" min="0" required>
                            <small class="form-help">Jumlah batch shift ini</small>
                        </div>
                        <div class="form-group">
                            <label for="productivity">Productivity <span class="required">*</span></label>
                            <input type="number" id="productivity" name="productivity" step="0.01" min="0" required>
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
                <div class="form-section">
                    <h2 class="material-icon">Informasi Tambahan</h2>
                    <div class="form-group">
                        <textarea id="keterangan" name="keterangan" rows="3"
                            placeholder="Catatan tambahan untuk shift ini..."
                            style="width: 100%; box-sizing: border-box;"></textarea>
                    </div>
                </div>



                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        💾 Simpan Data Shift
                    </button>
                    <a href="index.php" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Tampilkan info jam shift
    document.getElementById('shift_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const startTime = selectedOption.getAttribute('data-start');
        const endTime = selectedOption.getAttribute('data-end');
        const display = document.getElementById('shift-time-display');

        if (startTime && endTime) {
            display.textContent = `Jam operasional: ${startTime.substring(0,5)} - ${endTime.substring(0,5)}`;
        } else {
            display.textContent = '';
        }
    });

    // Cek data existing per shift
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal');
        const lineSelect = document.getElementById('line_id');
        const shiftSelect = document.getElementById('shift_id');
        const warningDiv = document.createElement('div');
        warningDiv.className = 'alert alert-warning';
        warningDiv.style.display = 'none';
        warningDiv.id = 'data-exist-warning';

        const form = document.getElementById('formHarian');
        form.parentNode.insertBefore(warningDiv, form);

        function cekDataExist() {
            const tanggal = tanggalInput.value;
            const lineId = lineSelect.value;
            const shiftId = shiftSelect.value;

            if (tanggal && lineId && shiftId) {
                fetch(`../backend/cek-data-harian.php?tanggal=${tanggal}&line_id=${lineId}&shift_id=${shiftId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            warningDiv.style.display = 'block';
                            warningDiv.innerHTML =
                                `⚠️ Data untuk ${data.tanggal} - ${data.line} - ${data.shift} sudah ada.`;
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
        shiftSelect.addEventListener('change', cekDataExist);
    });
</script>
<?php include '../layout/footer.php'; ?>