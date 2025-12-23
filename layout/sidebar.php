<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
            <div class="mx-3" style="text-align:center; color:#fff; font-weight:bold;">Produksi Report</div>
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

        <li class="nav-item <?php if ($is_chart_active) echo 'active'; ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseChart"
                aria-expanded="<?php echo $is_chart_active ? 'true' : 'false'; ?>" aria-controls="collapseChart">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Chart</span>
            </a>
            <div id="collapseChart" class="collapse <?php if ($is_chart_active) echo 'show'; ?>"
                data-parent="#accordionSidebar"> <!-- Tambahkan data-parent di sini -->
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Pilihan Chart:</h6>
                    <a class="collapse-item <?php if ($page_title === "chart") echo 'active'; ?>" href="chart">Chart</a>
                    <a class="collapse-item <?php if ($page_title === "Chart Perbandingan") echo 'active'; ?>" href="chart-line-ab">Chart Perbandingan</a>
                    <a class="collapse-item <?php if ($page_title === "Chart Shift") echo 'active'; ?>" href="chart-shift">Chart Per Shift</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider my-0">

        <hr class="sidebar-divider">

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


        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <br>