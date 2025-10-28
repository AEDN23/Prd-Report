</div>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../vendor/chart.js/Chart.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->


</body>

</html>





<!-- ===========================================================================================================-->
<!-- // 📘 SCRIPT UNTUK RANGKUMAN TAHUNAN -->
<!-- =========================================================================================================== -->

<!-- ===========================================================================================================-->
<!-- // 📘 SCRIPT UNTUK TABEL PARAMETER-->
<!-- =========================================================================================================== -->
<!-- <script>
    document.addEventListener("DOMContentLoaded", () => {
        const lineB = document.getElementById("lineSelectParam");
        const tahunB = document.getElementById("tahunParam");
        const containerB = document.getElementById("parameterB");

        function updateParameterB() {
            const line = lineB.value;
            const tahun = tahunB.value;
            containerB.innerHTML =
                '<div class="text-center py-3 text-muted">Loading...</div>';

            fetch(`../backend/parameter.php?line=${line}&tahun=${tahun}`)
                .then((res) => res.text())
                .then((html) => (containerB.innerHTML = html))
                .catch((err) => {
                    containerB.innerHTML =
                        '<div class="text-danger">Gagal memuat data.</div>';
                    console.error(err);
                });
        }

        // auto update saat filter berubah
        [lineB, tahunB].forEach((el) =>
            el.addEventListener("change", updateParameterB)
        );

        // load pertama
        updateParameterB();
    });
</script> -->

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // === FILTER UTAMA (HARIAN) ===
        const lineUtama = document.getElementById("lineUtama");
        const bulanUtama = document.getElementById("bulanUtama");
        const tahunUtama = document.getElementById("tahunUtama");

        // === PARAMETER ===
        const parameterContainer = document.getElementById("parameterB");
        const btnPDFParameter = document.getElementById("btnPDFParameter");
        const btnExcelParameter = document.getElementById("btnExcelParameter");
        const judulParameter = document.getElementById("judulParameter");

        // Data nama line untuk update judul
        const lineNames = <?= json_encode(array_column($lines, 'nama_line', 'id')) ?>;

        // Fungsi untuk update tabel & judul Parameter
        function updateParameterDariHarian() {
            const line = lineUtama.value;
            const bulan = bulanUtama.value;
            const tahun = tahunUtama.value;
            const namaLine = lineNames[line] || "Line";
            const namaBulan = bulanUtama.options[bulanUtama.selectedIndex].text;

            // Update judul
            judulParameter.textContent = `📈 PARAMETER ${namaLine} - ${namaBulan} ${tahun}`;

            // Update tombol export
            btnPDFParameter.href = `../export/export-pdf-parameter.php?line=${line}&bulan=${bulan}&tahun=${tahun}`;
            btnExcelParameter.href = `../export/export-excel-parameter.php?line=${line}&bulan=${bulan}&tahun=${tahun}`;

            // Update isi tabel parameter
            parameterContainer.innerHTML = '<div class="text-center py-3 text-muted">Loading data...</div>';
            fetch(`../backend/parameter.php?line=${line}&bulan=${bulan}&tahun=${tahun}`)
                .then(res => res.text())
                .then(html => parameterContainer.innerHTML = html)
                .catch(err => {
                    parameterContainer.innerHTML = '<div class="text-danger py-3 text-center">Gagal memuat data!</div>';
                    console.error(err);
                });
        }

        // Ketika filter Harian berubah, update Parameter juga
        [lineUtama, bulanUtama, tahunUtama].forEach(el => {
            el.addEventListener("change", updateParameterDariHarian);
        });

        // Load awal
        updateParameterDariHarian();
    });
</script>

<!-- // ========================================================================================================================================================= -->
<!-- // SCRIPT UNTUK TABEL PRODUKSI HARIAN -->
<!-- // ========================================================================================================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const lineUtama = document.getElementById("lineUtama");
        const bulanUtama = document.getElementById("bulanUtama");
        const tahunUtama = document.getElementById("tahunUtama");
        const tabelUtama = document.querySelector(".table-container");

        function updateTabelUtama() {
            const line = lineUtama.value;
            const bulan = bulanUtama.value;
            const tahun = tahunUtama.value;

            tabelUtama.innerHTML =
                '<div class="text-center py-3 text-muted">Loading data...</div>';

            fetch(
                    `../backend/inputharianajax.php?line=${line}&bulan=${bulan}&tahun=${tahun}`
                )
                .then((res) => res.text())
                .then((html) => (tabelUtama.innerHTML = html))
                .catch((err) => {
                    tabelUtama.innerHTML =
                        '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                    console.error(err);
                });
        }

        // Auto update ketika filter berubah
        [lineUtama, bulanUtama, tahunUtama].forEach((el) => {
            el.addEventListener("change", updateTabelUtama);
        });

        // Load pertama kali
        updateTabelUtama();
    });
</script>

<!-- ===========================================================================================================-->
<!-- // 📘 SCRIPT UNTUK UPDATE JUDUL DYNAMIC BERDASARKAN FILTER YANG DIPILIH -->
<!-- =========================================================================================================== -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const judulHarian = document.getElementById("judulHarian");
        const judulParameter = document.getElementById("judulParameter");
        const judulTahunan = document.getElementById("judulTahunan");

        // === HARIAN ===
        const lineUtama = document.getElementById("lineUtama");
        const bulanUtama = document.getElementById("bulanUtama");
        const tahunUtama = document.getElementById("tahunUtama");
        const lineNames = <?= json_encode(array_column($lines, 'nama_line', 'id')) ?>;

        function updateJudulHarian() {
            const lineName = lineNames[lineUtama.value] || 'Line';
            const bulanName = bulanUtama.options[bulanUtama.selectedIndex].text;
            const tahun = tahunUtama.value;
            judulHarian.textContent = `📋 DATA PRODUKSI ${lineName} - ${bulanName} ${tahun}`;
        }

        // === PARAMETER ===
        const lineParam = document.getElementById("lineSelectParam");
        const tahunParam = document.getElementById("tahunParam");

        function updateJudulParameter() {
            const lineName = lineNames[lineParam.value] || 'Line';
            const tahun = tahunParam.value;
            judulParameter.textContent = `📈 PARAMETER ${lineName} - ${tahun}`;
        }

        // === TAHUNAN ===
        const lineTahunan = document.getElementById("lineSelectTahunan");
        const tahunTahunan = document.getElementById("tahunTahunan");

        function updateJudulTahunan() {
            const lineName = lineNames[lineTahunan.value] || 'Line';
            const tahun = tahunTahunan.value;
            judulTahunan.textContent = `📘 DATA TARGET PRODUKSI ${lineName} - ${tahun}`;
        }

        // Listener semua
        [lineUtama, bulanUtama, tahunUtama].forEach((el) => el.addEventListener("change", updateJudulHarian));
        [lineParam, tahunParam].forEach((el) => el.addEventListener("change", updateJudulParameter));
        [lineTahunan, tahunTahunan].forEach((el) => el.addEventListener("change", updateJudulTahunan));

        // Load pertama
        updateJudulHarian();
        updateJudulParameter();
        updateJudulTahunan();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // === FILTER UTAMA (HARIAN) ===
        const lineUtama = document.getElementById("lineUtama");
        const bulanUtama = document.getElementById("bulanUtama");
        const tahunUtama = document.getElementById("tahunUtama");

        // === RANGKUMAN TAHUNAN ===
        const tabelTahunan = document.getElementById("tabelTahunanContainer");
        const btnPDFTahunan = document.getElementById("btnPDFTahunan");
        const btnExcelTahunan = document.getElementById("btnExcelTahunan");
        const judulTahunan = document.getElementById("judulTahunan");

        // Data nama line dari PHP
        const lineNames = <?= json_encode(array_column($lines, 'nama_line', 'id')) ?>;

        // Fungsi update tahunan
        function updateTahunanDariHarian() {
            const line = lineUtama.value;
            const tahun = tahunUtama.value;
            const namaLine = lineNames[line] || "Line";

            // Update judul
            judulTahunan.textContent = `📘 DATA TARGET PRODUKSI ${namaLine} - ${tahun}`;

            // Update tombol export
            btnPDFTahunan.href = `../export/exportpdf.php?line=${line}&tahun=${tahun}`;
            btnExcelTahunan.href = `../export/export_excel.php?line=${line}&tahun=${tahun}`;

            // Update tabel tahunan
            tabelTahunan.innerHTML = '<div class="text-center py-3 text-muted">Loading data...</div>';
            fetch(`../backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
                .then(res => res.text())
                .then(html => tabelTahunan.innerHTML = html)
                .catch(err => {
                    tabelTahunan.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                    console.error(err);
                });
        }

        // Saat filter harian berubah → tahunan ikut update
        [lineUtama, bulanUtama, tahunUtama].forEach(el => {
            el.addEventListener("change", updateTahunanDariHarian);
        });

        // Load awal
        updateTahunanDariHarian();
    });
</script>