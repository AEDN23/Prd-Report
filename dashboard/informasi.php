<?php
$page_title = "INFORMASI";
include '../layout/header.php';
?>

<div class="container-fluid">
    <div class="existing-targets">
        <h2>Daftar Informasi</h2>
        <div class="card-body">

            <?php

            try {
                $stmt = $pdo->query("SELECT * FROM info ORDER BY created_at DESC");
                $infos = $stmt->fetchAll();

                if ($infos) {
                    foreach ($infos as $info) {
                        echo "<h1 class='text-center' style='word-break:break-word; white-space:normal;'>" . htmlspecialchars($info['judul']) . "</h1><div>" . htmlspecialchars(date('d-m-Y', strtotime($info['created_at']))) . "</div>";
                        echo "<div class='text-center'>"  . '|  ' . htmlspecialchars($info['deskripsi']) . ' |</div>  <br>';
                        echo "<p class='text-justify' style='word-break:break-word; white-space:normal;'>" . nl2br(htmlspecialchars($info['isi'])) . "</p>";
                        if (!empty($info['file'])) {
                            echo "<a href='../uploads/info/" . htmlspecialchars($info['file']) . "' target='_blank'>Lihat File</a><br>";
                        }
                        echo "<hr>";
                    }
                } else {
                    echo "<p><a href='input-info.php'>Tidak ada informasi tersedia. klik untuk tambah informasi<a></p>";
                }
            } catch (Exception $e) {
                echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
            <b>
                <hr>
            </b>

        </div>

        <div class="table-responsive" id="tabelContainer">
            <table class="table table-bordered table-sm text-justify">
                <thead>
                    <tr>
                        <th class="text-justify">No</th>
                        <th class="text-justify">Judul</th>
                        <th class="text-justify">Deskripsi</th>
                        <th class="text-justify">Isi Informasi</th>
                        <th class="text-justify">File</th>
                        <th class="text-justify">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../backend/config.php';

                    try {
                        $stmt = $pdo->query("SELECT * FROM info ORDER BY created_at DESC");
                        $infos = $stmt->fetchAll();

                        if ($infos) {
                            foreach ($infos as $index => $info) {
                                echo "<tr>";
                                echo "<td class='text-justify'>" . ($index + 1) . "</td>";
                                echo "<td class='text-justify'>" . htmlspecialchars($info['judul']) . "</td>";
                                echo "<td class='text-justify'>" . htmlspecialchars($info['deskripsi']) . "</td>";
                                echo "<td class='text-justify' style='max-width:50000px; '>" . nl2br(htmlspecialchars($info['isi'])) . "</td>";
                                echo "<td class='text-justify'>";
                                if (!empty($info['file'])) {
                                    echo "<a href='../uploads/info/" . htmlspecialchars($info['file']) . "' target='_blank'>Lihat File</a>";
                                } else {
                                    echo "Tidak ada file";
                                }
                                echo "</td>";
                                echo "<td class='text-justify'>
                                    <a href='edit-info.php?id=" . $info['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                    <a href='../backend/proses-informasi.php?id=" . $info['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus informasi ini?');\">Hapus</a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-justify'><p><a href='input-info.php'>Tidak ada informasi tersedia. klik untuk tambah informasi</a></p></td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='6' class='text-justify'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer"></div>
    <a href="input-info.php" class="btn btn-primary">Tambah Informasi Baru</a>

    <?php
    include '../layout/footer.php';
    ?>