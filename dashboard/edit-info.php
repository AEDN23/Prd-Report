<?php
$page_title = "Edit Informasi";
include '../layout/header.php';
include '../layout/sidebar.php';

require_once '../backend/config.php';

// Ambil ID dari parameter URL
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('❌ ID tidak valid!'); window.location.href='informasi.php';</script>";
    exit;
}

try {
    // Ambil data informasi yang akan diedit
    $stmt = $pdo->prepare("SELECT * FROM info WHERE id = ?");
    $stmt->execute([$id]);
    $info = $stmt->fetch();

    if (!$info) {
        echo "<script>alert('❌ Data tidak ditemukan!'); window.location.href='informasi.php';</script>";
        exit;
    }
} catch (Exception $e) {
    echo "<script>alert('❌ Error: " . addslashes($e->getMessage()) . "'); window.location.href='informasi.php';</script>";
    exit;
}
?>

<div class="form-container">
    <form action="../backend/proses-informasi.php" method="POST" enctype="multipart/form-data" id="formInfo">
        <input type="hidden" name="id" value="<?= htmlspecialchars($info['id']) ?>">

        <div class="form-section">
            <h2><i class="bi bi-pencil"></i>EDIT INFORMASI</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="judul">
                        Judul
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" required
                        value="<?= htmlspecialchars($info['judul']) ?>"
                        style="width: 100%; cursor: text;">

                    <label for="deskripsi">
                        Deskripsi
                        <span class="required">*</span>
                    </label>
                    <textarea id="deskripsi" name="deskripsi" required
                        style="width: 100%; cursor: text;"><?= htmlspecialchars($info['deskripsi']) ?></textarea>

                    <label for="isi_informasi">
                        Isi Informasi
                        <span class="required">*</span>
                    </label>
                    <textarea id="isi_informasi" name="isi_informasi" required
                        style="width: 100%; cursor: text;"><?= htmlspecialchars($info['isi']) ?></textarea>

                    <label for="file">
                        Upload File Baru (jika ingin mengganti)
                    </label>
                    <input type="file" id="file" name="file"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png"
                        style="width: 100%; cursor: text;">

                    <?php if (!empty($info['file'])): ?>
                        <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <strong>File Saat Ini:</strong><br>
                            <a href="../uploads/info/<?= htmlspecialchars($info['file']) ?>" target="_blank">
                                📎 <?= htmlspecialchars($info['file']) ?>
                            </a>
                            <br><small>Kosongkan file di atas jika tidak ingin mengganti file.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">
                💾 Update Data
            </button>
            <a href="informasi.php" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<?php
include '../layout/footer.php';
?>