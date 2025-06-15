<?php
require_once 'functions/functions.php';
redirectIfNotLoggedIn();
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Hitung total data
$query_customer = "SELECT COUNT(*) as total FROM customer";
$query_sales = "SELECT COUNT(*) as total FROM sales";
$query_item = "SELECT COUNT(*) as total FROM item";
$query_transaction = "SELECT COUNT(*) as total FROM transaction";

$result_customer = $conn->query($query_customer);
$result_sales = $conn->query($query_sales);
$result_item = $conn->query($query_item);
$result_transaction = $conn->query($query_transaction);

$total_customer = $result_customer->fetch_assoc()['total'];
$total_sales = $result_sales->fetch_assoc()['total'];
$total_item = $result_item->fetch_assoc()['total'];
$total_transaction = $result_transaction->fetch_assoc()['total'];

// Ambil 5 transaksi terbaru
$query_recent = "SELECT t.id_transaksi, c.nama_customer, t.tanggal_transaksi, t.total_harga 
                 FROM transaction t 
                 JOIN customer c ON t.id_customer = c.id_customer 
                 ORDER BY t.tanggal_transaksi DESC LIMIT 5";
$result_recent = $conn->query($query_recent);

// Ambil data untuk chart
$query_chart = "SELECT DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan, 
                SUM(total_harga) as total 
                FROM transaction 
                WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY bulan 
                ORDER BY bulan";
$result_chart = $conn->query($query_chart);

$chart_labels = [];
$chart_data = [];

while ($row = $result_chart->fetch_assoc()) {
    $chart_labels[] = $row['bulan'];
    $chart_data[] = $row['total'];
}

$conn->close();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $total_customer; ?></h3>
                            <p>Customer</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="customer/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $total_sales; ?></h3>
                            <p>Sales</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <a href="sales/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $total_item; ?></h3>
                            <p>Produk</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <a href="item/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $total_transaction; ?></h3>
                            <p>Transaksi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="transaction/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <section class="col-lg-7 connectedSortable">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Penjualan 6 Bulan Terakhir
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" height="250"></canvas>
                        </div>
                    </div>
                </section>

                <section class="col-lg-5 connectedSortable">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-1"></i>
                                Transaksi Terbaru
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_recent->fetch_assoc()): ?>
                                        <tr>
                                            <td>#<?php echo $row['id_transaksi']; ?></td>
                                            <td><?php echo $row['nama_customer']; ?></td>
                                            <td><?php echo date('d M Y', strtotime($row['tanggal_transaksi'])); ?></td>
                                            <td><?php echo formatRupiah($row['total_harga']); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesChart = document.getElementById('salesChart').getContext('2d');
    const myChart = new Chart(salesChart, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [{
                label: 'Total Penjualan',
                data: <?php echo json_encode($chart_data); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>