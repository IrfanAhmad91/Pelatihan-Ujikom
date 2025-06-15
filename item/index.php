<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Produk';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../config/database.php';

$query = "SELECT * FROM item ORDER BY nama_item ASC";
$result = $conn->query($query);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Produk</li>
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
                            <h3 class="card-title">Daftar Produk</h3>
                            <div class="card-tools">
                                <a href="create.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            
                            <div class="table-responsive">
                                <table id="itemTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['nama_item']; ?></td>
                                            <td><?php echo $row['kategori']; ?></td>
                                            <td><?php echo formatRupiah($row['harga_beli']); ?></td>
                                            <td><?php echo formatRupiah($row['harga_jual']); ?></td>
                                            <td><?php echo $row['stok']; ?></td>
                                            <td><?php echo $row['satuan']; ?></td>
                                            <td>
                                                <a href="edit.php?id=<?php echo $row['id_item']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete.php?id=<?php echo $row['id_item']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
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
        $('#itemTable').DataTable({
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