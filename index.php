<?php
$page_title = "Halaman Utama";
include 'layout/header.php';
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
                        <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">
                            Server Migration <span class="float-right">20%</span>
                        </h4>
                        <div class="progress mb-4">
                            <div
                                class="progress-bar bg-danger"
                                role="progressbar"
                                style="width: 20%"
                                aria-valuenow="20"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">
                            Sales Tracking <span class="float-right">40%</span>
                        </h4>
                        <div class="progress mb-4">
                            <div
                                class="progress-bar bg-warning"
                                role="progressbar"
                                style="width: 40%"
                                aria-valuenow="40"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">
                            Customer Database <span class="float-right">60%</span>
                        </h4>
                        <div class="progress mb-4">
                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: 60%"
                                aria-valuenow="60"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">
                            Payout Details <span class="float-right">80%</span>
                        </h4>
                        <div class="progress mb-4">
                            <div
                                class="progress-bar bg-info"
                                role="progressbar"
                                style="width: 80%"
                                aria-valuenow="80"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">
                            Account Setup <span class="float-right">Complete!</span>
                        </h4>
                        <div class="progress">
                            <div
                                class="progress-bar bg-success"
                                role="progressbar"
                                style="width: 100%"
                                aria-valuenow="100"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Color System -->
            </div>

            <!--  ASUUU-->
            <div class="col-lg-6 mb-4"> <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">DATA TARGET PRODUKSI</h6> <select class="form-control form-control-sm" style="width: auto;">
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table table-sm" style="font-size: 12px; width: auto; max-width: 100%; overflow-x: auto;">
                            </table>
                        </div>
                    </div>
                </div> <!-- Approach --> <!-- </div> -->
            </div>
            <!-- ASDASD -->


            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">DATA TARGET PRODUKSI (Rangkuman Tahunan)</h6>

                <div class="d-flex gap-2">
                    <form method="GET" class="d-flex gap-2 mb-0">
                        <select name="line" class="form-control form-control-sm" style="width: 140px;">
                            <?php foreach ($lines as $line): ?>
                                <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($line['nama_line']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="number" name="tahun" class="form-control form-control-sm"
                            value="<?= $selectedYear ?>" style="width: 100px;">
                        <button class="btn btn-primary btn-sm">Tampilkan</button>
                    </form>

                    <!-- Tombol Export -->
                    <div class="btn-group">
                        <a href="export_pdf.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-danger btn-sm">Export PDF</a>
                        <a href="export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-success btn-sm">Export Excel</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php
include 'layout/footer.php';
