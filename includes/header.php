<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi Pegawai | <?php echo $page_title ?? 'Dashboard'; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="dashboard.php" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i> <?php echo $_SESSION['nama']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->