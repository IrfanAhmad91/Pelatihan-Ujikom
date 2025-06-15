<?php
require_once '../functions/functions.php';
redirectIfNotLoggedIn();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}
require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Cek apakah petugas mencoba menghapus dirinya sendiri
if ($id == $_SESSION['user_id']) {
    $_SESSION['error'] = 'Anda tidak dapat menghapus akun sendiri';
    header("Location: index.php");
    exit();
}

// Cek apakah petugas memiliki transaksi
$query_check = "SELECT COUNT(*) as total FROM transaction WHERE id_petugas = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['total'] > 0) {
    $_SESSION['error'] = 'Petugas tidak dapat dihapus karena memiliki transaksi terkait';
    header("Location: index.php");
    exit();
}

$query = "DELETE FROM petugas WHERE id_petugas = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Petugas berhasil dihapus';
} else {
    $_SESSION['error'] = 'Gagal menghapus petugas: ' . $conn->error;
}

$stmt->close();
$conn->close();
header("Location: index.php");
exit();
?>