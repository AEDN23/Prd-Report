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
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const lineSelect = document.getElementById("lineSelectTahunan");
        const tahunInput = document.getElementById("tahunTahunan");
        const tabelContainer = document.getElementById("tabelContainer");
        const btnPDF = document.getElementById("btnPDF");
        const btnExcel = document.getElementById("btnExcel");

        function updateTabel() {
            const line = lineSelect.value;
            const tahun = tahunInput.value;

            btnPDF.href = `../export/exportpdf.php?line=${line}&tahun=${tahun}`;
            btnExcel.href = `../export/export_excel.php?line=${line}&tahun=${tahun}`;

            tabelContainer.innerHTML =
                '<div class="text-center py-3 text-muted">Loading data...</div>';

            fetch(`../backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
                .then((res) => res.text())
                .then((html) => (tabelContainer.innerHTML = html))
                .catch((err) => {
                    tabelContainer.innerHTML =
                        '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                    console.error(err);
                });
        }

        lineSelect.addEventListener("change", updateTabel);
        tahunInput.addEventListener("change", updateTabel);

        updateTabel();
    });
</script>
<!-- ===========================================================================================================-->
<!-- // 📘 SCRIPT UNTUK TABEL PARAMETER-->
<!-- =========================================================================================================== -->
<script>
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
</script>