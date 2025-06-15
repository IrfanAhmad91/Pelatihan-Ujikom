<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
$page_title = 'Edit Transaksi';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../config/database.php';

// Ambil data untuk dropdown
$query_customer = "SELECT id_customer, nama_customer FROM customer ORDER BY nama_customer";
$query_sales = "SELECT id_sales, nama_sales FROM sales ORDER BY nama_sales";
$query_item = "SELECT id_item, nama_item, harga_jual, stok FROM item WHERE stok > 0 ORDER BY nama_item";

$result_customer = $conn->query($query_customer);
$result_sales = $conn->query($query_sales);
$result_item = $conn->query($query_item);

$status_options = ['lunas', 'cicilan', 'belum lunas'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_customer = sanitizeInput($_POST['id_customer']);
    $id_sales = sanitizeInput($_POST['id_sales']);
    $tanggal = sanitizeInput($_POST['tanggal']);
    $status = sanitizeInput($_POST['status']);
    $catatan = sanitizeInput($_POST['catatan']);
    $items = $_POST['items'];
    
    // Hitung total harga
    $total_harga = 0;
    foreach ($items as $item) {
        $total_harga += $item['harga'] * $item['jumlah'];
    }
    
    // Mulai transaksi
    $conn->begin_transaction();
    
    try {
        // Insert transaksi
        $query = "INSERT INTO transaction (id_customer, id_petugas, id_sales, tanggal_transaksi, total_harga, status_pembayaran, catatan) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiisdss", $id_customer, $_SESSION['user_id'], $id_sales, $tanggal, $total_harga, $status, $catatan);
        $stmt->execute();
        $id_transaksi = $stmt->insert_id;
        $stmt->close();
        
        // Insert detail transaksi dan update stok
        foreach ($items as $item) {
            $id_item = $item['id_item'];
            $jumlah = $item['jumlah'];
            $harga = $item['harga'];
            
            // Insert detail
            $query_detail = "INSERT INTO detail_transaction (id_transaksi, id_item, jumlah, harga_satuan, subtotal) 
                             VALUES (?, ?, ?, ?, ?)";
            $stmt_detail = $conn->prepare($query_detail);
            $subtotal = $harga * $jumlah;
            $stmt_detail->bind_param("iiidd", $id_transaksi, $id_item, $jumlah, $harga, $subtotal);
            $stmt_detail->execute();
            $stmt_detail->close();
            
            // Update stok
            $query_update = "UPDATE item SET stok = stok - ? WHERE id_item = ?";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bind_param("ii", $jumlah, $id_item);
            $stmt_update->execute();
            $stmt_update->close();
        }
        
        $conn->commit();
        $_SESSION['success'] = 'Transaksi berhasil ditambahkan';
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $error = 'Gagal menambahkan transaksi: ' . $e->getMessage();
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
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
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Transaksi</h3>
                        </div>
                        <form action="create.php" method="POST" id="transactionForm">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal Transaksi</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status Pembayaran</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="">Pilih Status</option>
                                                <?php foreach ($status_options as $option): ?>
                                                    <option value="<?php echo $option; ?>"><?php echo ucfirst($option); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_customer">Customer</label>
                                            <select class="form-control" id="id_customer" name="id_customer" required>
                                                <option value="">Pilih Customer</option>
                                                <?php while ($row = $result_customer->fetch_assoc()): ?>
                                                    <option value="<?php echo $row['id_customer']; ?>"><?php echo $row['nama_customer']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_sales">Sales</label>
                                            <select class="form-control" id="id_sales" name="id_sales">
                                                <option value="">Pilih Sales (Opsional)</option>
                                                <?php while ($row = $result_sales->fetch_assoc()): ?>
                                                    <option value="<?php echo $row['id_sales']; ?>"><?php echo $row['nama_sales']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                                </div>
                                
                                <hr>
                                
                                <h5>Item Pembelian</h5>
                                <div class="row mb-3">
                                    <div class="col-md-5">
                                        <label>Produk</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Harga</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Jumlah</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Subtotal</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label>Aksi</label>
                                    </div>
                                </div>
                                
                                <div id="itemContainer">
                                    <!-- Item akan ditambahkan di sini melalui JavaScript -->
                                </div>
                                
                                <button type="button" id="addItemBtn" class="btn btn-sm btn-secondary mt-2">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Harga</label>
                                            <input type="text" class="form-control" id="totalHarga" value="Rp 0" readonly>
                                            <input type="hidden" id="totalHargaHidden" name="total_harga" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
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
    $(document).ready(function() {
        // Data item untuk dropdown
        const items = [
            <?php while ($row = $result_item->fetch_assoc()): ?>
            {
                id: <?php echo $row['id_item']; ?>,
                nama: '<?php echo $row['nama_item']; ?>',
                harga: <?php echo $row['harga_jual']; ?>,
                stok: <?php echo $row['stok']; ?>
            },
            <?php endwhile; ?>
        ];
        
        // Tambah item baru
        $('#addItemBtn').click(function() {
            addItemRow();
        });
        
        // Fungsi untuk menambah baris item
        function addItemRow(selectedItemId = '', quantity = 1) {
            const itemId = 'item_' + Date.now();
            const itemRow = `
                <div class="row mb-2 item-row" id="${itemId}">
                    <div class="col-md-5">
                        <select class="form-control item-select" name="items[${itemId}][id_item]" required>
                            <option value="">Pilih Produk</option>
                            ${items.map(item => `
                                <option value="${item.id}" 
                                    data-harga="${item.harga}" 
                                    data-stok="${item.stok}"
                                    ${selectedItemId == item.id ? 'selected' : ''}>
                                    ${item.nama} (Stok: ${item.stok})
                                </option>
                            `).join('')}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control item-price" name="items[${itemId}][harga]" readonly>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control item-quantity" name="items[${itemId}][jumlah]" min="1" value="${quantity}" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control item-subtotal" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger remove-item" data-item="${itemId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            $('#itemContainer').append(itemRow);
            
            // Jika ada item yang dipilih, set harga dan hitung subtotal
            if (selectedItemId) {
                const selectedItem = items.find(item => item.id == selectedItemId);
                $(`#${itemId} .item-price`).val(formatCurrency(selectedItem.harga));
                calculateSubtotal(itemId);
            }
            
            // Event listeners untuk item yang baru ditambahkan
            $(`#${itemId} .item-select`).change(function() {
                const selectedOption = $(this).find('option:selected');
                const harga = selectedOption.data('harga') || 0;
                const stok = selectedOption.data('stok') || 0;
                
                $(this).closest('.item-row').find('.item-price').val(formatCurrency(harga));
                $(this).closest('.item-row').find('.item-quantity').attr('max', stok);
                
                if (harga > 0) {
                    calculateSubtotal(itemId);
                }
            });
            
            $(`#${itemId} .item-quantity`).on('input', function() {
                calculateSubtotal(itemId);
            });
            
            $(`#${itemId} .remove-item`).click(function() {
                $(this).closest('.item-row').remove();
                calculateTotal();
            });
        }
        
        // Fungsi untuk menghitung subtotal per item
        function calculateSubtotal(itemId) {
            const row = $(`#${itemId}`);
            const price = parseFloat(row.find('.item-price').val().replace(/[^0-9]/g, '')) || 0;
            const quantity = parseInt(row.find('.item-quantity').val()) || 0;
            const subtotal = price * quantity;
            
            row.find('.item-subtotal').val(formatCurrency(subtotal));
            calculateTotal();
        }
        
        // Fungsi untuk menghitung total harga
        function calculateTotal() {
            let total = 0;
            
            $('.item-row').each(function() {
                const subtotal = parseFloat($(this).find('.item-subtotal').val().replace(/[^0-9]/g, '')) || 0;
                total += subtotal;
            });
            
            $('#totalHarga').val(formatCurrency(total));
            $('#totalHargaHidden').val(total);
        }
        
        // Fungsi untuk format mata uang
        function formatCurrency(amount) {
            return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        // Tambahkan satu baris item saat pertama kali load
        addItemRow();
    });
</script>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>