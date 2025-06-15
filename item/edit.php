<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Edit Produk';

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once '../config/database.php';


$kategori_options = ['Elektronik', 'Furniture', 'Dapur'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM item WHERE id_item = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$item = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitizeInput($_POST['nama']);
    $kategori = sanitizeInput($_POST['kategori']);
    $harga_beli = str_replace('.', '', sanitizeInput($_POST['harga_beli']));
    $harga_jual = str_replace('.', '', sanitizeInput($_POST['harga_jual']));
    $stok = sanitizeInput($_POST['stok']);
    $satuan = sanitizeInput($_POST['satuan']);

    $query = "UPDATE item SET nama_item = ?, kategori = ?, harga_beli = ?, harga_jual = ?, stok = ?, satuan = ? WHERE id_item = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssddssi", $nama, $kategori, $harga_beli, $harga_jual, $stok, $satuan, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Produk berhasil diperbarui';
        header("Location: index.php");
        exit();
    } else {
        $error = 'Gagal memperbarui produk: ' . $conn->error;
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
                    <h1>Edit Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
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
                            <h3 class="card-title">Form Edit Produk</h3>
                        </div>
                        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label for="nama">Nama Produk</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $item['nama_item']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($kategori_options as $option): ?>
                                            <option value="<?php echo $option; ?>" <?php echo ($option == $item['kategori']) ? 'selected' : ''; ?>>
                                                <?php echo $option; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli</label>
                                    <input type="text" class="form-control" id="harga_beli" name="harga_beli" 
                                           value="<?php echo number_format($item['harga_beli'], 0, ',', '.'); ?>" 
                                           onkeyup="formatCurrency(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <input type="text" class="form-control" id="harga_jual" name="harga_jual" 
                                           value="<?php echo number_format($item['harga_jual'], 0, ',', '.'); ?>" 
                                           onkeyup="formatCurrency(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" min="0" value="<?php echo $item['stok']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan</label>
                                    <input type="text" class="form-control" id="satuan" name="satuan" value="<?php echo $item['satuan']; ?>" required>
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

<script>
    function formatCurrency(input) {
        // Hapus semua karakter selain angka
        let value = input.value.replace(/\D/g, '');
        
        // Format dengan titik sebagai pemisah ribuan
        value = new Intl.NumberFormat('id-ID').format(value);
        
        // Set nilai kembali ke input
        input.value = value;
    }
</script>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>