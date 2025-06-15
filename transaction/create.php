<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Tambah Transaksi Baru';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../config/database.php';

$customers = [];
$petugas = [];
$sales = [];

// Fetch customers
$customer_query = "SELECT id_customer, nama_customer FROM customer ORDER BY nama_customer ASC";
$customer_result = $conn->query($customer_query);
if ($customer_result) {
    while ($row = $customer_result->fetch_assoc()) {
        $customers[] = $row;
    }
}

// Fetch petugas (officers)
$petugas_query = "SELECT id_petugas, nama_petugas FROM petugas ORDER BY nama_petugas ASC";
$petugas_result = $conn->query($petugas_query);
if ($petugas_result) {
    while ($row = $petugas_result->fetch_assoc()) {
        $petugas[] = $row;
    }
}

// Fetch sales (optional)
$sales_query = "SELECT id_sales, nama_sales FROM sales ORDER BY nama_sales ASC";
$sales_result = $conn->query($sales_query);
if ($sales_result) {
    while ($row = $sales_result->fetch_assoc()) {
        $sales[] = $row;
    }
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_transaksi = $_POST['tanggal_transaksi'] ?? '';
    $id_customer = $_POST['id_customer'] ?? '';
    $id_petugas = $_POST['id_petugas'] ?? '';
    $id_sales = $_POST['id_sales'] ?? null; // Can be null
    $total_harga = $_POST['total_harga'] ?? '';
    $status_pembayaran = $_POST['status_pembayaran'] ?? '';

    // Basic validation
    if (empty($tanggal_transaksi)) {
        $errors[] = "Tanggal transaksi wajib diisi.";
    }
    if (empty($id_customer)) {
        $errors[] = "Customer wajib diisi.";
    }
    if (empty($id_petugas)) {
        $errors[] = "Petugas wajib diisi.";
    }
    if (empty($total_harga) || !is_numeric($total_harga) || $total_harga < 0) {
        $errors[] = "Total harga wajib diisi dan harus angka positif.";
    }
    if (empty($status_pembayaran)) {
        $errors[] = "Status pembayaran wajib diisi.";
    }

    if (empty($errors)) {
        // Sanitize inputs
        $tanggal_transaksi = $conn->real_escape_string($tanggal_transaksi);
        $id_customer = (int)$id_customer;
        $id_petugas = (int)$id_petugas;
        $id_sales = $id_sales ? (int)$id_sales : null;
        $total_harga = (float)$total_harga;
        $status_pembayaran = $conn->real_escape_string($status_pembayaran);

        $stmt = $conn->prepare("INSERT INTO transaction (tanggal_transaksi, id_customer, id_petugas, id_sales, total_harga, status_pembayaran) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            // Bind parameters
            if ($id_sales === null) {
                $stmt->bind_param("siiids", $tanggal_transaksi, $id_customer, $id_petugas, $id_sales, $total_harga, $status_pembayaran);
            } else {
                $stmt->bind_param("siiids", $tanggal_transaksi, $id_customer, $id_petugas, $id_sales, $total_harga, $status_pembayaran);
            }

            if ($stmt->execute()) {
                $_SESSION['success'] = "Transaksi berhasil ditambahkan!";
                header('Location: index.php');
                exit();
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Error preparing statement: " . $conn->error;
        }
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Transaksi Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Transaksi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Transaksi</h3>
                        </div>
                        <form action="create.php" method="POST">
                            <div class="card-body">
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul>
                                            <?php foreach ($errors as $error): ?>
                                                <li><?php echo $error; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="tanggal_transaksi">Tanggal Transaksi</label>
                                    <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_customer">Customer</label>
                                    <select class="form-control" id="id_customer" name="id_customer" required>
                                        <option value="">-- Pilih Customer --</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?php echo $customer['id_customer']; ?>"><?php echo $customer['nama_customer']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_petugas">Petugas</label>
                                    <select class="form-control" id="id_petugas" name="id_petugas" required>
                                        <option value="">-- Pilih Petugas --</option>
                                        <?php foreach ($petugas as $p): ?>
                                            <option value="<?php echo $p['id_petugas']; ?>"><?php echo $p['nama_petugas']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_sales">Sales (Opsional)</label>
                                    <select class="form-control" id="id_sales" name="id_sales">
                                        <option value="">-- Pilih Sales --</option>
                                        <?php foreach ($sales as $s): ?>
                                            <option value="<?php echo $s['id_sales']; ?>"><?php echo $s['nama_sales']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="total_harga">Total Harga</label>
                                    <input type="number" class="form-control" id="total_harga" name="total_harga" step="0.01" min="0" placeholder="Masukkan Total Harga" required>
                                </div>
                                <div class="form-group">
                                    <label for="status_pembayaran">Status Pembayaran</label>
                                    <select class="form-control" id="status_pembayaran" name="status_pembayaran" required>
                                        <option value="">-- Pilih Status Pembayaran --</option>
                                        <option value="lunas">Lunas</option>
                                        <option value="cicilan">Cicilan</option>
                                        <option value="belum lunas">Belum Lunas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                <a href="index.php" class="btn btn-secondary">Batal</a>
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