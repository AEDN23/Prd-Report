<?php
$page_title = "Tables";
include 'layout/header.php';
?>
<!-- End of Topbar -->

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tabel Produksi</h1>

    <!-- DataTales Example -->
    <div class="existing-targets">
        <h3>📋 DATA TARGET </h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>TAHUN</th>
                        <th>Line</th>
                        <th>Batch Count</th>
                        <th>Productivity A Compound</th>
                        <th>Production Speed</th>
                        <th>Batch Weight</th>
                        <th>Operational Factor</th>
                        <th>Cycle Time</th>
                        <th>Grade Change Sequence</th>
                        <th>Grade Change Time</th>
                        <th>Feed Raw</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $data = getSemuaTarget($pdo);
                    if (!empty($data)) {
                        foreach ($data as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['tahun_target']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_line']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_batch_count']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_productivity']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_production_speed']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_batch_weight']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_operation_factor']) . "%</td>";
                            echo "<td>" . htmlspecialchars($row['target_cycle_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_grade_change_sequence']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_grade_change_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_feed_raw_material']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>Belum ada data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php
include 'layout/footer.php';
?>