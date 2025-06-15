<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Edit Customer';

// Pastikan session sudah dimulai jika Anda menggunakan $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

// Fungsi sanitizeInput (contoh sederhana, sesuaikan dengan kebutuhan keamanan Anda)
// Pastikan fungsi ini didefinisikan atau di-include dari file lain
if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM customer WHERE id_customer = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$customer = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitizeInput($_POST['nama']);
    $alamat = sanitizeInput($_POST['alamat']);
    $no_telepon = sanitizeInput($_POST['no_telepon']);
    $email = sanitizeInput($_POST['email']);

    $query = "UPDATE customer SET nama_customer = ?, alamat = ?, no_telepon = ?, email = ? WHERE id_customer = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama, $alamat, $no_telepon, $email, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Customer berhasil diperbarui';
        header("Location: index.php");
        exit();
    } else {
        $error = 'Gagal memperbarui customer: ' . $conn->error;
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
                    <h1>Edit Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Customer</a></li>
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
                            <h3 class="card-title">Form Edit Customer</h3>
                        </div>
                        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="nama">Nama Customer</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $customer['nama_customer']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $customer['alamat']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo $customer['no_telepon']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['email']; ?>">
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
ob_end_flush(); // Akhiri output buffering dan kirimkan output ke browser
?>


<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>