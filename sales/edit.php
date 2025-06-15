<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Edit Sales';

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM sales WHERE id_sales = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$sales = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitizeInput($_POST['nama']);
    $alamat = sanitizeInput($_POST['alamat']);
    $no_telepon = sanitizeInput($_POST['no_telepon']);
    $email = sanitizeInput($_POST['email']);

    $query = "UPDATE sales SET nama_sales = ?, alamat = ?, no_telepon = ?, email = ? WHERE id_sales = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama, $alamat, $no_telepon, $email, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Sales berhasil diperbarui';
        header("Location: index.php");
        exit();
    } else {
        $error = 'Gagal memperbarui sales: ' . $conn->error;
    }
    $stmt->close();
}
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Sales</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Sales</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Form Edit Sales</h3>
                        </div>
                        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="nama">Nama Sales</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $sales['nama_sales']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $sales['alamat']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo $sales['no_telepon']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $sales['email']; ?>">
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