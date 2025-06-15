<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}
$page_title = 'Petugas';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../config/database.php';

$query = "SELECT * FROM petugas ORDER BY nama_petugas ASC";
$result = $conn->query($query);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Petugas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Petugas</li>
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
                            <h3 class="card-title">Daftar Petugas</h3>
                            <div class="card-tools">
                                <a href="create.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Petugas
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            
                            <div class="table-responsive">
                                <table id="petugasTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Level</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['nama_petugas']; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo ucfirst($row['level']); ?></td>
                                            <td>
                                                <a href="edit.php?id=<?php echo $row['id_petugas']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($row['id_petugas'] != $_SESSION['user_id']): ?>
                                                <a href="delete.php?id=<?php echo $row['id_petugas']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php endif; ?>
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
        $('#petugasTable').DataTable({
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