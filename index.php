<?php
require_once 'backend/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/images.jpg" rel="icon">

    <title>HALAMAN CHART</title>


    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">


    <div class="container-fluid">
        <br>
        <br>
        <br>
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

                <!-- CHART START -->
                <!-- CHART END -->
                <br>
                <br>
                <br>

                <!-- FUNGSI UNTUK MENAMPILKAN DATA TARGET START -->
                <div class="col-lg-12 mb-5">
                    <br>
                    <div class="card shadow mb-4">
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
                                    <a id="btnPDF" href="export/exportpdf.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-danger btn-sm btn-atas">Export PDF</a>
                                    <a id="btnExcel" href="export/export_excel.php?line=<?= $selectedLine ?>&tahun=<?= $selectedYear ?>" class="btn btn-success btn-sm btn-atas ms-2">Export Excel</a>
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
                <!-- FUNGSI UNTUK MENAMPILKAN DATA TARGET START -->
                <!-- /.container-fluid -->


                <br>
                <br>
                <!-- Scroll to Top Button-->


                <!-- Bootstrap core JavaScript-->
                <script src="vendor/jquery/jquery.min.js"></script>
                <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="js/sb-admin-2.min.js"></script>

                <!-- Page level plugins -->
                <script src="vendor/chart.js/Chart.min.js"></script>

                <!-- Page level custom scripts -->
                <script src="js/demo/chart-area-demo.js"></script>
                <script src="js/demo/chart-pie-demo.js"></script>
                <script src="js/demo/chart-bar-demo.js"></script>
                <script src="js/chart-index.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>
<!-- footer end -->