<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Detail Transaksi';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_transaksi = $_GET['id'];

// Ambil data transaksi
$query_transaksi = "SELECT t.*, c.nama_customer, p.nama_petugas, s.nama_sales 
                    FROM transaction t
                    JOIN customer c ON t.id_customer = c.id_customer
                    JOIN petugas p ON t.id_petugas = p.id_petugas
                    LEFT JOIN sales s ON t.id_sales = s.id_sales
                    WHERE t.id_transaksi = ?";
$stmt_transaksi = $conn->prepare($query_transaksi);
$stmt_transaksi->bind_param("i", $id_transaksi);
$stmt_transaksi->execute();
$result_transaksi = $stmt_transaksi->get_result();

if ($result_transaksi->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$transaksi = $result_transaksi->fetch_assoc();
$stmt_transaksi->close();

// Ambil detail transaksi
$query_detail = "SELECT dt.*, i.nama_item, i.kategori 
                 FROM detail_transaction dt
                 JOIN item i ON dt.id_item = i.id_item
                 WHERE dt.id_transaksi = ?
                 ORDER BY dt.id_detail";
$stmt_detail = $conn->prepare($query_detail);
$stmt_detail->bind_param("i", $id_transaksi);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();
$detail_items = $result_detail->fetch_all(MYSQLI_ASSOC);
$stmt_detail->close();
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Transaksi #<?php echo $id_transaksi; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail</li>
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
                            <h3 class="card-title">Informasi Transaksi</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Tanggal Transaksi</dt>
                                <dd class="col-sm-8"><?php echo date('d M Y', strtotime($transaksi['tanggal_transaksi'])); ?></dd>
                                
                                <dt class="col-sm-4">Customer</dt>
                                <dd class="col-sm-8"><?php echo $transaksi['nama_customer']; ?></dd>
                                
                                <dt class="col-sm-4">Petugas</dt>
                                <dd class="col-sm-8"><?php echo $transaksi['nama_petugas']; ?></dd>
                                
                                <?php if ($transaksi['nama_sales']): ?>
                                <dt class="col-sm-4">Sales</dt>
                                <dd class="col-sm-8"><?php echo $transaksi['nama_sales']; ?></dd>
                                <?php endif; ?>
                                
                                <dt class="col-sm-4">Status Pembayaran</dt>
                                <dd class="col-sm-8">
                                    <?php 
                                    $status_class = '';
                                    switch ($transaksi['status_pembayaran']) {
                                        case 'lunas':
                                            $status_class = 'badge-success';
                                            break;
                                        case 'cicilan':
                                            $status_class = 'badge-warning';
                                            break;
                                        case 'belum lunas':
                                            $status_class = 'badge-danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>">
                                        <?php echo ucfirst($transaksi['status_pembayaran']); ?>
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Total Harga</dt>
                                <dd class="col-sm-8"><?php echo formatRupiah($transaksi['total_harga']); ?></dd>
                                
                                <?php if ($transaksi['catatan']): ?>
                                <dt class="col-sm-4">Catatan</dt>
                                <dd class="col-sm-8"><?php echo $transaksi['catatan']; ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Item Pembelian</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($detail_items as $item): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $item['nama_item']; ?></td>
                                            <td><?php echo $item['kategori']; ?></td>
                                            <td><?php echo formatRupiah($item['harga_satuan']); ?></td>
                                            <td><?php echo $item['jumlah']; ?></td>
                                            <td><?php echo formatRupiah($item['subtotal']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th><?php echo formatRupiah($transaksi['total_harga']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <a href="index.php" class="btn btn-default">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>