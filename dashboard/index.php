<?php
$page_title = "Halaman Utama";
include '../layout/header.php';
include '../layout/sidebar.php';
?>
<script src="../js/dash.js"></script>

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
                            <a id="btnPDFHarian" hidden
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
            <!-- ⿣ DATA TARGET PRODUKSI (TAHUNAN) -->
            <!-- ============================== -->
            <section id="rangkuman-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 id="judulTahunan" class="fw-bold text-primary mb-0">
                        📘 DATA TARGET PRODUKSI <?= htmlspecialchars($selectedLineName ?? '') ?> - <?= $selectedYear ?>
                    </h6>

                    <div class="btn-group">
                        <a hidden id="btnPDFTahunan" href="../export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                            class="btn btn-danger btn-sm" style="margin-right: 10px;"> <i class="fas fa-file-pdf"></i> Export PDFdddddd</a>
                        <a id="btnExcelTahunan" href="../export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>"
                            class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>

                <div class="table-responsive border rounded p-2" id="tabelTahunanContainer">
                    <div class="text-center py-3 text-muted">Memuat data...</div>
                </div>
            </section>

            <!-- ============================== -->
            <!-- 📋 DATA TARGET PRODUKSI (SEMUA) -->
            <!-- ============================== -->
            <section id="data-target" class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="fw-bold text-primary mb-0">📋 Daftar Semua Target Produksi</h2>
                    <div class="d-flex align-items-center">
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchTarget" class="form-control" placeholder="Cari tahun atau line..." />
                        </div>
                    </div>
                    <a href="input-target.php" class="btn btn-primary">➕ Tambah Target Baru</a>
                </div>

                <?php
                try {
                    $targets = getSemuaTarget($pdo);
                } catch (Exception $e) {
                    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
                    $targets = [];
                }
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm" id="tableTarget">
                        <thead class="table-primary text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Line Produksi</th>
                                <th>Batch Count<br><small>batch/hari</small></th>
                                <th>Productivity<br><small>ton/shift</small></th>
                                <th>Production Speed<br><small>kg/menit</small></th>
                                <th>Batch Weight<br><small>kg/batch</small></th>
                                <th>Operation Factor<br><small>%</small></th>
                                <th>Cycle Time<br><small>menit/batch</small></th>
                                <th>Grade Change Sequence<br><small>frekuensi</small></th>
                                <th>Grade Change Time<br><small>menit/grade</small></th>
                                <th>Feed Raw Material<br><small>kg/hari</small></th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTarget">
                            <?php if ($targets): ?>
                                <?php foreach ($targets as $index => $target): ?>
                                    <tr data-tahun="<?= htmlspecialchars($target['tahun_target']) ?>"
                                        data-line="<?= htmlspecialchars(strtolower($target['kode_line'] . ' ' . $target['nama_line'])) ?>">
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td class="text-center"><?= htmlspecialchars($target['tahun_target']) ?></td>
                                        <td><?= htmlspecialchars($target['kode_line']) ?> - <?= htmlspecialchars($target['nama_line']) ?></td>
                                        <td class="text-center"><?= !empty($target['target_batch_count']) ? number_format($target['target_batch_count'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_productivity']) ? number_format($target['target_productivity'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_production_speed']) ? number_format($target['target_production_speed'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_batch_weight']) ? number_format($target['target_batch_weight'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_operation_factor']) ? number_format($target['target_operation_factor'], 2) . '%' : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_cycle_time']) ? number_format($target['target_cycle_time'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_grade_change_sequence']) ? number_format($target['target_grade_change_sequence'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_grade_change_time']) ? number_format($target['target_grade_change_time'], 2) : '-' ?></td>
                                        <td class="text-center"><?= !empty($target['target_feed_raw_material']) ? number_format($target['target_feed_raw_material'], 2) : '-' ?></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="edit-target.php?id=<?= $target['id'] ?>" class="btn btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../backend/proses-target.php?id=<?= $target['id'] ?>"
                                                    class="btn btn-danger"
                                                    title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus target tahun <?= $target['tahun_target'] ?> untuk line <?= $target['kode_line'] ?>?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataRow">
                                    <td colspan="13" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Tidak ada data target produksi.</p>
                                            <a href="input-target.php" class="btn btn-primary mt-2">Tambah Target Baru</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Info jumlah data -->
                <div class="mt-3 text-muted d-flex justify-content-between align-items-center">
                    <small>Total: <span id="totalCount"><?= count($targets) ?></span> target produksi</small>
                    <small id="filterInfo" class="text-info" style="display: none;">
                        <i class="fas fa-filter"></i> Filter aktif
                    </small>
                </div>
                
            </section>



        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end container-fluid -->

<?php include '../layout/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lineSelect = document.getElementById('lineUtama');
        const bulanSelect = document.getElementById('bulanUtama');
        const tahunInput = document.getElementById('tahunUtama');
        const btnExcel = document.getElementById('btnExcelHarian');
        const btnPDF = document.getElementById('btnPDFHarian');

        function updateLinks() {
            const line = lineSelect.value;
            const bulan = bulanSelect.value;
            const tahun = tahunInput.value;
            btnExcel.href = `../export/export-excel-harian.php?line=${line}&bulan=${bulan}&tahun=${tahun}`;
            btnPDF.href = `../export/export-pdf-harian.php?line=${line}&bulan=${bulan}&tahun=${tahun}`;
        }

        // Update link setiap kali filter berubah
        [lineSelect, bulanSelect, tahunInput].forEach(el => {
            el.addEventListener('change', updateLinks);
        });

        updateLinks(); // jalankan sekali di awal
    });
</script>

<!-- script untuk search data target -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchTarget');
        const tableBody = document.getElementById('tbodyTarget');
        const totalCount = document.getElementById('totalCount');
        const filterInfo = document.getElementById('filterInfo');
        const rows = tableBody.querySelectorAll('tr[data-tahun]');
        const noDataRow = document.getElementById('noDataRow');

        // Simpan semua data rows untuk reset
        const allRows = Array.from(rows);

        // Auto focus pada search input
        searchInput.focus();

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            // Sembunyikan row "no data" jika ada
            if (noDataRow) {
                noDataRow.style.display = 'none';
            }

            if (searchTerm === '') {
                // Reset - tampilkan semua rows
                allRows.forEach(row => {
                    row.style.display = '';
                    visibleCount++;
                });
                filterInfo.style.display = 'none';
            } else {
                // Filter rows berdasarkan search term
                allRows.forEach(row => {
                    const tahun = row.getAttribute('data-tahun');
                    const line = row.getAttribute('data-line');

                    // Cek apakah search term cocok dengan tahun atau line
                    const matchTahun = tahun.includes(searchTerm);
                    const matchLine = line.includes(searchTerm);

                    if (matchTahun || matchLine) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                filterInfo.style.display = 'inline';
            }

            // Update counter
            totalCount.textContent = visibleCount;

            // Tampilkan pesan tidak ada data jika tidak ada yang match
            if (visibleCount === 0 && allRows.length > 0) {
                if (!noDataRow) {
                    // Buat row pesan tidak ada data
                    const newRow = document.createElement('tr');
                    newRow.id = 'noDataFilterRow';
                    newRow.innerHTML = `
                    <td colspan="13" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <p>Tidak ada data yang cocok dengan "<strong>${searchTerm}</strong>"</p>
                            <button class="btn btn-secondary mt-2" onclick="clearSearch()">
                                Tampilkan Semua Data
                            </button>
                        </div>
                    </td>
                `;
                    tableBody.appendChild(newRow);
                } else if (noDataRow) {
                    noDataRow.style.display = '';
                }
            } else {
                // Hapus row pesan filter jika ada
                const filterRow = document.getElementById('noDataFilterRow');
                if (filterRow) {
                    filterRow.remove();
                }
            }
        });

        // Debounce untuk performa (opsional)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Logic sudah di handler utama
            }, 300);
        });
    });

    // Fungsi untuk clear search
    function clearSearch() {
        document.getElementById('searchTarget').value = '';
        document.getElementById('searchTarget').dispatchEvent(new Event('input'));

        // Focus kembali ke search input
        document.getElementById('searchTarget').focus();
    }

    // Hotkey untuk clear search dengan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const searchInput = document.getElementById('searchTarget');
            if (searchInput && document.activeElement === searchInput && searchInput.value !== '') {
                clearSearch();
                e.preventDefault();
            }
        }
    });
</script>
