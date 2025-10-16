<?php
$page_title = "Halaman Utama";
include '../layout/header.php';
?>
<script src="../js/dash.js"></script>

<div class="container-fluid">
    <div class="card shadow mb-4">

        <div class="card-body">

            <!-- ============================== -->
            <!-- ⿡ FILTER DATA PRODUKSI -->
            <!-- ============================== -->
            <section id="filter-section" class="mb-4">
                <b>
                    <h2 class="fw-bold text-dark mb-3">📋 DATA PRODUKSI</h2>
                </b>
                <form id="filterUtama" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Line Produksi</label>
                        <select id="lineUtama" name="line" class="form-select">
                            <?php foreach ($lines as $line): ?>
                                <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($line['nama_line']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Bulan</label>
                        <select id="bulanUtama" name="bulan" class="form-select">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <input id="tahunUtama" type="number" name="tahun" value="<?= $selectedYear ?>" class="form-control">
                    </div>
                </form>

                <div class="table-container mt-3">
                    <div class="text-center py-3 text-muted">Memuat data...</div>
                </div>
            </section>

            <hr>

            <!-- ============================== -->
            <!-- ⿢ PARAMETER LINE -->
            <!-- ============================== -->

            <hr>

            <!-- ============================== -->
            <!-- ⿣ DATA TARGET PRODUKSI (TAHUNAN) -->
            <!-- ============================== -->
            <section id="rangkuman-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-primary mb-0">📘 DATA TARGET PRODUKSI (Rangkuman Tahunan)</h6>

                    <div class="d-flex gap-2 align-items-center">
                        <form id="filterTahunan" class="d-flex gap-2 mb-0">
                            <select id="lineSelectTahunan" name="line" class="form-control form-control-sm" style="width: 140px;">
                                <?php foreach ($lines as $line): ?>
                                    <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($line['nama_line']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input id="tahunTahunan" type="number" name="tahun" class="form-control form-control-sm"
                                value="<?= $selectedYear ?>" style="width: 100px;">
                        </form>

                        <div class="btn-group">
                            <a id="btnPDF" href="../export/exportpdf.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-danger btn-sm">Export PDF</a>
                            <a id="btnExcel" href="../export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-success btn-sm">Export Excel</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive border rounded p-2" id="tabelContainer">
                    <table class="table table-bordered table-sm mb-0">
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
            </section>

        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end container-fluid -->

<?php include '../layout/footer.php'; ?>