<?php
$page_title = "Input Info";
include '../layout/header.php';
?>

<div class="form-container">
    <form action="../backend/proses-informasi.php" method="POST" enctype="multipart/form-data" id="formHarian">

        <div class="form-section">
            <h2><i class="bi bi-info"></i>INFORMASI</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="judul">
                        Judul
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" required
                        value="<?= htmlspecialchars($data['judul'] ?? '') ?>"
                        style="width: 100%; cursor: text;">
                    <label for="deskripsi">
                        Deskripsi
                        <span class="required">*</span>
                    </label>
                    <textarea id="deskripsi" name="deskripsi" required
                        style="width: 100%; cursor: text;"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>

                    <label for="isi_informasi">
                        Isi Informasi
                        <span class="required">*</span>
                    </label>
                    <textarea id="isi_informasi" name="isi_informasi" required
                        style="width: 100%; cursor: text;"><?= htmlspecialchars($data['isi_informasi'] ?? '') ?></textarea>

                    <label for="file">
                        Upload File (jika ada)
                    </label>

                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png" style="width: 100%; cursor: text;" onclick="">

                </div>

            </div>
        </div>


        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">
                💾 Simpan Data
            </button>
            <!-- <a href="data-harian.php" class="btn btn-secondary">📋 Lihat Data</a> -->
            <a href="index.php" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<?php
include '../layout/footer.php';
?>