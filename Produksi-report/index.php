      <?php
        $page_title = "Halaman Utama";
        include '../layout/header.php';
        ?>
      <!-- End of Topbar -->

      <!-- Begin Page Content -->
      <div class="container-fluid">
          <div class="existing-targets">
              <h3>📋 DATA PRODUKSI</h3>

              <!-- FILTER -->
              <form id="filterUtama" class="row g-2 mb-3">
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

              <!-- TABEL DATA -->
              <div class="table-container">
                  <div class="text-center py-3 text-muted">Memuat data...</div>
              </div>





              <br>
              <div class="row">
                  <!-- Content Column -->
                  <div class="col-lg-6 mb-4">
                      <!-- Project Card Example -->
                      <div class="card shadow mb-4">
                          <div class="card-header py-3 d-flex justify-content-between align-items-center">
                              <h6 class="m-0 font-weight-bold text-primary">PARAMETER LINE</h6>
                              <div class="d-flex gap-2 align-items-center">
                                  <select id="lineB" class="form-control form-control-sm" style="width: 120px;">
                                      <option value="1">Line A</option>
                                      <option value="2">Line B</option>
                                      <option value="3">Line C</option>
                                  </select>
                                  <input id="tahunB" type="number" class="form-control form-control-sm" value="<?= date('Y') ?>" style="width: 100px;">
                              </div>
                          </div>
                          <div class="card-body">
                              <div id="parameterB" class="text-center py-3 text-muted">Memuat data...</div>
                          </div>
                      </div>

                  </div>
                  <div class="col-lg-6 mb-4">
                      <!-- Illustrations -->
                      <div class="card shadow mb-4">
                          <div class="card-header py-3">
                              <h6 class="m-0 font-weight-bold text-primary">
                                  CHART
                              </h6>
                          </div>
                          <div class="card-body">
                              <button class="btn btn-primary">filter bulan dan tahun</button>
                          </div>
                          <div class="chart-container"></div>
                          <canvas id="myChart"></canvas>
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



      <!-- SCRIPT FUNGSI UNTUK PARAMETER LINE -->
      <script>
          document.addEventListener('DOMContentLoaded', () => {
              const lineB = document.getElementById('lineB');
              const tahunB = document.getElementById('tahunB');
              const containerB = document.getElementById('parameterB');

              function updateParameterB() {
                  const line = lineB.value;
                  const tahun = tahunB.value;
                  containerB.innerHTML = '<div class="text-center py-3 text-muted">Loading...</div>';

                  fetch(`../backend/parameter.php?line=${line}&tahun=${tahun}`)
                      .then(res => res.text())
                      .then(html => containerB.innerHTML = html)
                      .catch(err => {
                          containerB.innerHTML = '<div class="text-danger">Gagal memuat data.</div>';
                          console.error(err);
                      });
              }

              // auto update saat filter berubah
              [lineB, tahunB].forEach(el => el.addEventListener('change', updateParameterB));

              // load pertama
              updateParameterB();
          });
      </script>

      <script>
          document.addEventListener('DOMContentLoaded', () => {
              const lineUtama = document.getElementById('lineUtama');
              const bulanUtama = document.getElementById('bulanUtama');
              const tahunUtama = document.getElementById('tahunUtama');
              const tabelUtama = document.querySelector('.table-container');

              function updateTabelUtama() {
                  const line = lineUtama.value;
                  const bulan = bulanUtama.value;
                  const tahun = tahunUtama.value;

                  tabelUtama.innerHTML = '<div class="text-center py-3 text-muted">Loading data...</div>';

                  fetch(`../backend/inputharianajax.php?line=${line}&bulan=${bulan}&tahun=${tahun}`)
                      .then(res => res.text())
                      .then(html => tabelUtama.innerHTML = html)
                      .catch(err => {
                          tabelUtama.innerHTML = '<div class="text-danger text-center py-3">Gagal memuat data!</div>';
                          console.error(err);
                      });
              }

              // Auto update ketika filter berubah
              [lineUtama, bulanUtama, tahunUtama].forEach(el => {
                  el.addEventListener('change', updateTabelUtama);
              });

              // Load pertama kali
              updateTabelUtama();
          });
      </script>