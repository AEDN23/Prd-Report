// =============================
// 📌 SCRIPT UNTUK INPUT HARIAN
// =============================
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
      `backend/inputharianajax.php?line=${line}&bulan=${bulan}&tahun=${tahun}`
    )
      .then((res) => res.text())
      .then((html) => (tabelUtama.innerHTML = html))
      .catch((err) => {
        tabelUtama.innerHTML =
          '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
        console.error(err);
      });
  }

  [lineUtama, bulanUtama, tahunUtama].forEach((el) =>
    el.addEventListener("change", updateTabelUtama)
  );

  updateTabelUtama(); // load awal
});

// ==================================
// 📘 SCRIPT UNTUK RANGKUMAN TAHUNAN
// ==================================
document.addEventListener("DOMContentLoaded", () => {
  const lineSelect = document.getElementById("lineSelect");
  const tahunInput = document.getElementById("tahunInput");
  const tabelContainer = document.getElementById("tabelContainer");
  const btnPDF = document.getElementById("btnPDF");
  const btnExcel = document.getElementById("btnExcel");

  function updateTabel() {
    const line = lineSelect.value;
    const tahun = tahunInput.value;

    btnPDF.href = `export/exportpdf.php?line=${line}&tahun=${tahun}`;
    btnExcel.href = `export/export_excel.php?line=${line}&tahun=${tahun}`;

    tabelContainer.innerHTML =
      '<div class="text-center py-3 text-muted">Loading data...</div>';

    fetch(`backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
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

  updateTabel(); // load awal
});


