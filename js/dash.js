// =========================================================================================================================================================
// SCRIPT UNTUK TABEL PRODUKSI HARIAN
// =========================================================================================================================================================
document.addEventListener("DOMContentLoaded", () => {
  const lineSelect = document.getElementById("lineSelect");
  const tahunInput = document.getElementById("tahunInput");
  const tabelContainer = document.getElementById("tabelContainer");
  const btnPDF = document.getElementById("btnPDF");
  const btnExcel = document.getElementById("btnExcel");

  function updateTabel() {
    const line = lineSelect.value;
    const tahun = tahunInput.value;

    // Update link export
    btnPDF.href = `../export/exportpdf.php?line=${line}&tahun=${tahun}`;
    btnExcel.href = `../export/export_excel.php?line=${line}&tahun=${tahun}`;

    // Tampilkan loading
    tabelContainer.innerHTML =
      '<div class="text-center py-3 text-muted">Loading data...</div>';

    // Ambil data via AJAX
    fetch(`../backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
      .then((res) => res.text())
      .then((html) => {
        tabelContainer.innerHTML = html;
      })
      .catch((err) => {
        tabelContainer.innerHTML =
          '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
        console.error(err);
      });
  }

  // Trigger otomatis saat user ubah filter
  lineSelect.addEventListener("change", updateTabel);
  tahunInput.addEventListener("change", updateTabel);

  // Load pertama kali
  updateTabel();
});

// =========================================================================================================================================================
// SCRIPT UNTUK PARAMETER LINE
// =========================================================================================================================================================
document.addEventListener("DOMContentLoaded", () => {
  const lineB = document.getElementById("lineB");
  const tahunB = document.getElementById("tahunB");
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

// =========================================================================================================================================================
// SCRIPT UNTUK TABEL PRODUKSI HARIAN
// =========================================================================================================================================================
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
