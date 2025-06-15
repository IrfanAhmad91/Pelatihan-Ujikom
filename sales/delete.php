<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();


// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Cek apakah sales memiliki transaksi
$query_check = "SELECT COUNT(*) as total FROM transaction WHERE id_sales = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['total'] > 0) {
    $_SESSION['error'] = 'Sales tidak dapat dihapus karena memiliki transaksi terkait';
    header("Location: index.php");
    exit();
}

$query = "DELETE FROM sales WHERE id_sales = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Sales berhasil dihapus';
} else {
    $_SESSION['error'] = 'Gagal menghapus sales: ' . $conn->error;
}

$stmt->close();
$conn->close();
header("Location: index.php");
exit();
?>