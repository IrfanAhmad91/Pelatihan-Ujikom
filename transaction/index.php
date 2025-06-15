<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Transaksi';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../config/database.php';

$query = "SELECT t.id_transaksi, t.tanggal_transaksi, c.nama_customer, 
          p.nama_petugas, s.nama_sales, t.total_harga, t.status_pembayaran
          FROM transaction t
          JOIN customer c ON t.id_customer = c.id_customer
          JOIN petugas p ON t.id_petugas = p.id_petugas
          LEFT JOIN sales s ON t.id_sales = s.id_sales
          ORDER BY t.tanggal_transaksi DESC";
$result = $conn->query($query);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Transaksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Transaksi</h3>
                            <div class="card-tools">
                                <a href="create.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            
                            <div class="table-responsive">
                                <table id="transactionTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Customer</th>
                                            <th>Petugas</th>
                                            <th>Sales</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo date('d M Y', strtotime($row['tanggal_transaksi'])); ?></td>
                                            <td><?php echo $row['nama_customer']; ?></td>
                                            <td><?php echo $row['nama_petugas']; ?></td>
                                            <td><?php echo $row['nama_sales'] ? $row['nama_sales'] : '-'; ?></td>
                                            <td><?php echo formatRupiah($row['total_harga']); ?></td>
                                            <td>
                                                <?php 
                                                $status_class = '';
                                                switch ($row['status_pembayaran']) {
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
                                                    <?php echo ucfirst($row['status_pembayaran']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#transactionTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
            }
        });
    });
</script>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>