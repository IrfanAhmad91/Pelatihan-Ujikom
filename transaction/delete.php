<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_transaksi = $_GET['id'];

// Mulai transaksi
$conn->begin_transaction();

try {
    // Ambil detail transaksi untuk mengembalikan stok
    $query_detail = "SELECT id_item, jumlah FROM detail_transaction WHERE id_transaksi = ?";
    $stmt_detail = $conn->prepare($query_detail);
    $stmt_detail->bind_param("i", $id_transaksi);
    $stmt_detail->execute();
    $result_detail = $stmt_detail->get_result();
    $detail_items = $result_detail->fetch_all(MYSQLI_ASSOC);
    $stmt_detail->close();
    
    // Kembalikan stok item
    foreach ($detail_items as $item) {
        $query_restore = "UPDATE item SET stok = stok + ? WHERE id_item = ?";
        $stmt_restore = $conn->prepare($query_restore);
        $stmt_restore->bind_param("ii", $item['jumlah'], $item['id_item']);
        $stmt_restore->execute();
        $stmt_restore->close();
    }
    
    // Hapus detail transaksi
    $query_delete_detail = "DELETE FROM detail_transaction WHERE id_transaksi = ?";
    $stmt_delete_detail = $conn->prepare($query_delete_detail);
    $stmt_delete_detail->bind_param("i", $id_transaksi);
    $stmt_delete_detail->execute();
    $stmt_delete_detail->close();
    
    // Hapus transaksi
    $query_delete = "DELETE FROM transaction WHERE id_transaksi = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("i", $id_transaksi);
    $stmt_delete->execute();
    $stmt_delete->close();
    
    $conn->commit();
    $_SESSION['success'] = 'Transaksi berhasil dihapus';
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = 'Gagal menghapus transaksi: ' . $e->getMessage();
}

$conn->close();
header("Location: index.php");
exit();
?>