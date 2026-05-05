<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    require_once 'config/database.php';

    $errors = [];
    $kode = '';
    $nama = '';
    $deskripsi = '';
    $status = 'Aktif';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        // ========== sanitasi data dari form ==========
        $kode = trim(htmlspecialchars($_POST['kode_kategori'] ?? ''));
        $nama = trim(htmlspecialchars($_POST['nama_kategori'] ?? ''));
        $deskripsi = trim(htmlspecialchars($_POST['deskripsi'] ?? ''));
        $status = $_POST['status'] ?? 'Aktif';

        // ========== validasi kode kategori ==========
        if (empty($kode)){
            $errors['kode'] = "Kode kategori wajib diisi!";
        }else if (strlen($kode) < 4 || strlen($kode) > 10){
            $errors['kode'] = "Kode kategori harus memiliki panjang 4-10 karakter!";
        }else if (substr($kode, 0, 4) != "OGE-"){
            $errors['kode'] = "Kode kategori harus diawali dengan 'OGE-'! (Contoh: OGE-001)";
        }

        // ========== validasi nama kategori ==========
        if (empty($nama)){
            $errors['nama'] = "Nama kategori wajib diisi!";
        }else if (strlen($nama) < 3){
            $errors['nama'] = "Nama kategori minimal 3 karakter!";
        }else if (strlen($nama) > 50){
            $errors['nama'] = "Nama kategori maksimal 50 karakter!";
        }

        // ========== validasi deskripsi ==========
        if (!empty($deskripsi) && strlen($deskripsi) > 200){
            $errors['deskripsi'] = "Deskripsi maksimal 200 karakter!";
        }

        // ========== validasi status ==========
        if ($status != 'Aktif' && $status != 'Nonaktif'){
            $errors['status'] = "Status harus Aktif atau Nonaktif!";
        }

        // ========== cek duplikasi ==========
        if (empty($errors['kode'])) {
            $check_sql = "SELECT id_kategori FROM kategori WHERE kode_kategori = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $kode);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
        
            if ($check_result->num_rows > 0) {
                $errors['kode'] = "Kode kategori sudah ada! Gunakan kode yang berbeda.";
            }
            $check_stmt->close();
        }

        // ========== prepared statement ==========
        if (empty($errors)) {
        $sql = "INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $kode, $nama, $deskripsi, $status);

        if ($stmt->execute()) {
            // jika berhasil: redirect ke index.php dengan pesan sukses
            header("Location: index.php?success=tambah");
            exit();
        } else {
            //jika gagal: tampilkan error
             $errors['global'] = "Gagal menyimpan data: " . $conn->error;
        }
        $stmt->close();
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Kategori Baru</h4>
                    </div>
                    <div class="card-body">

                        <!-- tampilkan error jika ada -->
                        <?php if (isset($errors['global'])): ?>
                            <div class="alert alert-danger"><?php echo $errors['global']; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            
                            <!-- form fields --> 
                            <div class="mb-3">
                                <label for="kode_kategori" class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                                <input type="text" 
                                    class="form-control <?php echo isset($errors['kode']) ? 'is-invalid' : ''; ?>" 
                                    id="kode_kategori" 
                                    name="kode_kategori" 
                                    value="<?php echo htmlspecialchars($kode); ?>"
                                    placeholder="Contoh: OGE-001"
                                    required>
                                <?php if (isset($errors['kode'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['kode']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Wajib diisi. Format: OGE-xxx (4-10 karakter)</small>
                            </div>

                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" 
                                    class="form-control <?php echo isset($errors['nama']) ? 'is-invalid' : ''; ?>" 
                                    id="nama_kategori" 
                                    name="nama_kategori" 
                                    value="<?php echo htmlspecialchars($nama); ?>"
                                    placeholder="Contoh: Algoritma"
                                    required>
                                <?php if (isset($errors['nama'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nama']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Wajib diisi. Minimal 3 karakter, maksimal 50 karakter.</small>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control <?php echo isset($errors['deskripsi']) ? 'is-invalid' : ''; ?>" 
                                        id="deskripsi" 
                                        name="deskripsi" 
                                        rows="3"
                                        placeholder="Tulis deskripsi kategori (opsional)"><?php echo htmlspecialchars($deskripsi); ?></textarea>
                                <?php if (isset($errors['deskripsi'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['deskripsi']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Opsional. Maksimal 200 karakter.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" value="Aktif" id="status_aktif" <?php echo ($status == 'Aktif') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="status_aktif">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" value="Nonaktif" id="status_nonaktif" <?php echo ($status == 'Nonaktif') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="status_nonaktif">Nonaktif</label>
                                    </div>
                                </div>
                                <?php if (isset($errors['status'])): ?>
                                    <div class="text-danger mt-1"><?php echo $errors['status']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- tombol --> 
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>