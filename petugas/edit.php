<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}
$page_title = 'Edit Petugas';

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$level_options = ['admin', 'petugas'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM petugas WHERE id_petugas = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$petugas = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitizeInput($_POST['nama']);
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $level = sanitizeInput($_POST['level']);

    // Cek apakah username sudah ada (kecuali untuk petugas ini)
    $query_check = "SELECT id_petugas FROM petugas WHERE username = ? AND id_petugas != ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("si", $username, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        $error = 'Username sudah digunakan';
    } else {
        // Jika password diisi, update password
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE petugas SET nama_petugas = ?, username = ?, password = ?, level = ? WHERE id_petugas = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $nama, $username, $hashed_password, $level, $id);
        } else {
            $query = "UPDATE petugas SET nama_petugas = ?, username = ?, level = ? WHERE id_petugas = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $nama, $username, $level, $id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Petugas berhasil diperbarui';
            header("Location: index.php");
            exit();
        } else {
            $error = 'Gagal memperbarui petugas: ' . $conn->error;
        }
        $stmt->close();
    }
    $stmt_check->close();
}

require_once '../config/database.php';

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Petugas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Petugas</a></li>
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
                            <h3 class="card-title">Form Edit Petugas</h3>
                        </div>
                        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="nama">Nama Petugas</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $petugas['nama_petugas']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $petugas['username']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="level">Level</label>
                                    <select class="form-control" id="level" name="level" required>
                                        <option value="">Pilih Level</option>
                                        <?php foreach ($level_options as $option): ?>
                                            <option value="<?php echo $option; ?>" <?php echo ($option == $petugas['level']) ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($option); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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