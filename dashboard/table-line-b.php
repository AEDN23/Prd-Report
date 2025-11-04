<?php
$page_title = "Halaman Utama";
include '../layout/header.php';
?>

<div class="container-fluid">
    <div class="card shadow mb-4">

        <div class="card-body">

            <!-- ============================== -->
            <!-- ⿡ Data Produksi Harian -->
            <!-- ============================== -->
            <section id="Harian-section" class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 id="judulHarian" class="fw-bold text-primary mb-0">
                        📋 DATA PRODUKSI <?= htmlspecialchars($selectedLineName ?? '') ?> -
                        <?= date('F', mktime(0, 0, 0, $selectedMonth, 10)) ?> <?= $selectedYear ?>
                    </h6>

                    <div class="d-flex gap-2 align-items-center">
                        <form id="filterUtama" class="d-flex gap-2 mb-0">
                            <select id="lineUtama" name="line" class="form-control form-control-sm" style="width: 160px; margin-right: 10px;">
                                <?php foreach ($lines as $line): ?>
                                    <option value="<?= $line['id'] ?>" <?= $line['id'] == $selectedLine ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($line['nama_line']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select id="bulanUtama" name="bulan" class="form-control form-control-sm" style="width: 160px; margin-right: 10px;">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $m == $selectedMonth ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>

                            <input id="tahunUtama" type="number" name="tahun" value="<?= $selectedYear ?>"
                                class="form-control form-control-sm" style="width: 100px; margin-right: 10px;">
                        </form>

                        <div class="btn-group">
                            <a id="btnPDFHarian"
                                href="../export/export-pdf-harian.php?line=<?= $selectedLine ?>&bulan=<?= $selectedMonth ?>&tahun=<?= $selectedYear ?>"
                                class="btn btn-danger btn-sm" style="margin-right: 10px;">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>

                            <a id="btnExcelHarian"
                                href="../export/export-excel-harian.php?line=<?= $selectedLine ?>&bulan=<?= $selectedMonth ?>&tahun=<?= $selectedYear ?>"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>

                        </div>
                    </div>
                </div>

                <div class="table-container mt-3">
                    <div class="text-center py-3 text-muted">Memuat data...</div>
                </div>
            </section>
            <!-- ============================== -->
            <!-- ⿢ PARAMETER LINE -->
            <!-- ============================== -->
            <section id="parameter-section" class="mb-4">
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 id="judulParameter" class="fw-bold text-primary mb-0">
                        📈 PARAMETER <?= htmlspecialchars($selectedLineName ?? '') ?> - <?= $selectedYear ?>
                    </h6>

                    <div class="btn-group">
                        <a id="btnPDFParameter" href="../export/export-pdf-parameter.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                            class="btn btn-danger btn-sm" style="margin-right: 10px;">
                            <i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a id="btnExcelParameter" href="../export/export-excel-parameter.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                            class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>

                <div id="parameterB" class=" border rounded p-3 text-center text-muted">
                    Memuat data...
                </div>
            </section>
            <!-- ============================== -->
            <!-- ⿣ DATA TARGET PRODUKSI (TAHUNAN) -->
            <!-- ============================== -->
            <section id="rangkuman-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 id="judulTahunan" class="fw-bold text-primary mb-0">
                        📘 DATA TARGET PRODUKSI <?= htmlspecialchars($selectedLineName ?? '') ?> - <?= $selectedYear ?>
                    </h6>

                    <div class="btn-group">
                        <a id="btnPDFTahunan" href="../export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                            class="btn btn-danger btn-sm" style="margin-right: 10px;"> <i class="fas fa-file-pdf"></i> Export PDFdddddd</a>
                        <a id="btnExcelTahunan" href="../export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                            class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>

                <div class="table-responsive border rounded p-2" id="tabelTahunanContainer">
                    <div class="text-center py-3 text-muted">Memuat data...</div>
                </div>
            </section>



        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end container-fluid -->

<?php include '../layout/footer.php'; ?>