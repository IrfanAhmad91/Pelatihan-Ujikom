<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Tambah Customer';

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitizeInput($_POST['nama']);
    $alamat = sanitizeInput($_POST['alamat']);
    $no_telepon = sanitizeInput($_POST['no_telepon']);
    $email = sanitizeInput($_POST['email']);

    $query = "INSERT INTO customer (nama_customer, alamat, no_telepon, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nama, $alamat, $no_telepon, $email);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Customer berhasil ditambahkan';
        header("Location: index.php");
        exit();
    } else {
        $error = 'Gagal menambahkan customer: ' . $conn->error;
    }
    $stmt->close();
}

// Setelah semua processing PHP selesai, baru include header dan HTML
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Customer</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Customer</h3>
                        </div>
                        <form action="create.php" method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="nama">Nama Customer</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="index.php" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>