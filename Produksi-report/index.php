<?php
$page_title = "Halaman Utama";
include '../layout/header.php';
?>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="existing-targets">
        <h3>📋 DATA PRODUKSI </h3>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <label class="form-label">Line Produksi</label>
                <select name="line" class="form-select">
                    <?php foreach ($lines as $line): ?>
                        <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                            <?= htmlspecialchars($line['nama_line']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" value="<?= $selectedYear ?>" class="form-control">
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>

        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Details</th>
                        <th>Unit</th>
                        <th>Target</th>
                        <th>Average</th>
                        <?php for ($d = 1; $d <= 31; $d++): ?>
                            <th><?= $d ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $details = [
                        'batch_count' => ['Batch Count', 'per day'],
                        'productivity' => ['Productivity', 'Ton/Shift'],
                        'production_speed' => ['Production Speed', 'Kg/min'],
                        'batch_weight' => ['Batch Weight', 'Kg/Batch'],
                        'operation_factor' => ['Operation Factor', '%'],
                        'cycle_time' => ['Cycle Time', 'min/Batch'],
                        'grade_change_sequence' => ['Grade Change Sequence', 'frequenly'],
                        'grade_change_time' => ['Grade Change Time', 'min/grade'],
                        'feed_raw_material' => ['Feed Raw Material', 'Kg/Day']
                    ];

                    foreach ($details as $key => [$label, $unit]):
                    ?>
                        <tr>
                            <td><?= $label ?></td>
                            <td><?= $unit ?></td>
                            <td><?= $target['target_' . $key] ?? '-' ?></td>
                            <td><?= $averages[$key] ?></td>
                            <?php for ($d = 1; $d <= 31; $d++): ?>
                                <td>
                                    <?= isset($perTanggal[$d][$key]) ? $perTanggal[$d][$key] : '-' ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>




        <br>
        <div class="row">
            <!-- Content Column -->
            <div class="col-lg-6 mb-4">
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">PARAMETER LINE B</h6>
                    </div>
                    <div class="card-body">
                        <!-- MAIN KONTEN -->
                        <button class="btn btn-primary">filter bulan dan tahun</button>

                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Parameter check</th>
                                        <th>Target</th>
                                        <th>Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $details = [
                                        'batch_count' => ['Batch Count', 'per day'],
                                        'productivity' => ['Productivity', 'Ton/Shift'],
                                        'operation_factor' => ['Operation Factor', '%'],
                                        'cycle_time' => ['Cycle Time', 'min/Batch'],
                                        'grade_change_time' => ['Grade Change Time', 'min/grade'],
                                    ];
                                    ?>
                                    <tr>
                                        <td>batch_count</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>productivity</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>operation_factor</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>cycle_time</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>grade_change_time</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>grade_change_time</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- MAIN KONTEN SELESAI -->

                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            PARAMETER CHECK LINE A
                        </h6>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary">filter bulan dan tahun</button>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Parameter check</th>
                                        <th>Target</th>
                                        <th>Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $details = [
                                        'batch_count' => ['Batch Count', 'per day'],
                                        'productivity' => ['Productivity', 'Ton/Shift'],
                                        'operation_factor' => ['Operation Factor', '%'],
                                        'cycle_time' => ['Cycle Time', 'min/Batch'],
                                        'grade_change_time' => ['Grade Change Time', 'min/grade'],
                                    ];
                                    ?>
                                    <tr>
                                        <td>batch_count</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>productivity</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>operation_factor</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>cycle_time</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>grade_change_time</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>grade_change_time</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Color System -->
            </div>



            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">DATA TARGET PRODUKSI (Rangkuman Tahunan)</h6>

                <div class="d-flex gap-2 align-items-center">
                    <!-- Filter -->
                    <form id="filterForm" class="d-flex gap-2 mb-0">
                        <select id="lineSelect" name="line" class="form-control form-control-sm" style="width: 140px;">
                            <?php foreach ($lines as $line): ?>
                                <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($line['nama_line']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input id="tahunInput" type="number" name="tahun" class="form-control form-control-sm"
                            value="<?= $selectedYear ?>" style="width: 100px;">
                    </form>

                    <!-- Tombol Export -->
                    <div class="btn-group">
                        <a id="btnPDF" href="../export/exportpdf.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-danger btn-sm btn-atas">Export PDF</a>
                        <a id="btnExcel" href="../export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-success btn-sm btn-atas ms-2">Export Excel</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive" id="tabelContainer">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Details</th>
                                <th>Unit</th>
                                <th>Target</th>
                                <th>Average</th>
                                <?php
                                $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                foreach ($namaBulan as $b): ?>
                                    <th><?= $b ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fields as $key => [$label, $unit]): ?>
                                <tr>
                                    <td><?= $label ?></td>
                                    <td><?= $unit ?></td>
                                    <td><?= $target['target_' . $key] ?? '-' ?></td>
                                    <td><?= $averages[$key] ?></td>
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <td>
                                            <?= isset($bulanData[$m]['avg_' . $key]) ? round($bulanData[$m]['avg_' . $key], 2) : '-' ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-center py-4 text-muted">Silakan pilih line / tahun</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php
include '../layout/footer.php';
?>


<!-- // script untuk menampilkan data target -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const lineSelect = document.getElementById('lineSelect');
        const tahunInput = document.getElementById('tahunInput');
        const tabelContainer = document.getElementById('tabelContainer');
        const btnPDF = document.getElementById('btnPDF');
        const btnExcel = document.getElementById('btnExcel');

        function updateTabel() {
            const line = lineSelect.value;
            const tahun = tahunInput.value;

            // Update link export
            btnPDF.href = `../export/exportpdf.php?line=${line}&tahun=${tahun}`;
            btnExcel.href = `../export/export_excel.php?line=${line}&tahun=${tahun}`;

            // Tampilkan loading
            tabelContainer.innerHTML = '<div class="text-center py-3 text-muted">Loading data...</div>';

            // Ambil data via AJAX
            fetch(`../backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
                .then(res => res.text())
                .then(html => {
                    tabelContainer.innerHTML = html;
                })
                .catch(err => {
                    tabelContainer.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                    console.error(err);
                });
        }

        // Trigger otomatis saat user ubah filter
        lineSelect.addEventListener('change', updateTabel);
        tahunInput.addEventListener('change', updateTabel);

        // Load pertama kali
        updateTabel();
    });
</script>