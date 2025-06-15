<?php
// Tambahkan ini di bagian atas file untuk menentukan base URL
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$app_path = dirname($_SERVER['SCRIPT_NAME']);
$full_base_url = $base_url . $app_path;

// Function to check if user is admin (assuming this function is defined elsewhere)
if (!function_exists('isAdmin')) {
    function isAdmin() {
        // Placeholder for your actual isAdmin logic, e.g.:
        return isset($_SESSION['level']) && $_SESSION['level'] === 'admin';
    }
}
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo $full_base_url; ?>/dashboard.php" class="brand-link">
        <span class="brand-text font-weight-light">Koperasi Pegawai</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle img-circle elevation-2"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION['nama']; ?></a>
                <small class="text-success"><?php echo ucfirst($_SESSION['level']); ?></small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/customer/index.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customer</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/sales/index.php" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Sales</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/item/index.php" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Produk</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/transaction/index.php" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/petugas/index.php" class="nav-link">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Petugas</p>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?php echo $full_base_url; ?>/logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>