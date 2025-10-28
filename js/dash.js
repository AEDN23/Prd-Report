// // =========================================================================================================================================================
// // SCRIPT UNTUK TABEL PRODUKSI HARIAN
// // =========================================================================================================================================================
// document.addEventListener("DOMContentLoaded", () => {
//   const lineSelect = document.getElementById("lineSelect");
//   const tahunInput = document.getElementById("tahunInput");
//   const tabelContainer = document.getElementById("tabelContainer");
//   const btnPDF = document.getElementById("btnPDF");
//   const btnExcel = document.getElementById("btnExcel");

//   function updateTabel() {
//     const line = lineSelect.value;
//     const tahun = tahunInput.value;

//     // Update link export
//     btnPDF.href = `../export/exportpdf.php?line=${line}&tahun=${tahun}`;
//     btnExcel.href = `../export/export_excel.php?line=${line}&tahun=${tahun}`;

//     // Tampilkan loading
//     tabelContainer.innerHTML =
//       '<div class="text-center py-3 text-muted">Loading data...</div>';

//     // Ambil data via AJAX
//     fetch(`../backend/rangkuman_ajax.php?line=${line}&tahun=${tahun}`)
//       .then((res) => res.text())
//       .then((html) => {
//         tabelContainer.innerHTML = html;
//       })
//       .catch((err) => {
//         tabelContainer.innerHTML =
//           '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
//         console.error(err);
//       });
//   }

//   // Trigger otomatis saat user ubah filter
//   lineSelect.addEventListener("change", updateTabel);
//   tahunInput.addEventListener("change", updateTabel);

//   // Load pertama kali
//   updateTabel();
// });



