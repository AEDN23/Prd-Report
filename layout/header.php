<?php
require_once '../backend/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../img/images.jpg" rel="icon">

    <title><?= $page_title; ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">


            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">


                <div class="mx-3" style="text-align:center;  color:#fff; font-weight:bold;">Produksi Report</div>
            </a>
            <div style="text-align:center; font-size:14px; color:#fff; font-weight:bold;">
                <?php
                date_default_timezone_set('Asia/Jakarta');
                echo '<b>' . date('d - M- Y') . '</b>';
                ?>
                <br>
                <span id="jam" style="font-weight:bold;">
                    <?php echo date('H:i:s'); ?>
                </span>
            </div>
            <script>
                function updateClock() {
                    var now = new Date();
                    var jam = now.getHours().toString().padStart(2, '0');
                    var menit = now.getMinutes().toString().padStart(2, '0');
                    var detik = now.getSeconds().toString().padStart(2, '0');
                    document.getElementById('jam').textContent = jam + ':' + menit + ':' + detik;
                }
                setInterval(updateClock, 1000);
            </script>
            <br>

            <!-- Divider -->
            <div class="sidebar-heading">
                Master Data
            </div>
            <li <?php if ($page_title === "Halaman Utama") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="index">
                    <i class="bi bi-clipboard2-data"></i>
                    <span>Dashboard</span></a>
            </li>
            <li <?php if ($page_title === "INFORMASI") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="informasi">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Informasi</span></a>
            </li>
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                INPUT
            </div>


            <li <?php if ($page_title === "Input Target Produksi") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="input-target">
                    <i class="bi bi-clipboard"></i></i>
                    <span>INPUT TARGET</span></a>
            </li>

            <li <?php if ($page_title === "Input Harian") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="input-harian">
                    <i class="bi bi-calendar"></i>
                    <span>INPUT HARIAN</span></a>
            </li>
            <li <?php if ($page_title === "Input Info") echo 'class="active nav-item"'; ?> class="nav-item">
                <a class="nav-link" href="input-info">
                    <i class="bi bi-info"></i>
                    <span>INPUT INFO</span></a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">

                <br>