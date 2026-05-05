<?php
require_once 'config/database.php';

// ========== validasi ID dari GET ==========
if (!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: index.php?error=ID tidak ditemukan");
    exit();
}

$id = $_GET['id'];

// ========== cek keberadan data ==========
$check_sql = "SELECT id_kategori FROM kategori WHERE id_kategori = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows == 0){
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}
$check_stmt->close();

// ========== delete data ==========
$sql = "DELETE FROM kategori WHERE id_kategori = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        // ========== redirect dengan pesan ==========
        header("Location: index.php?success=hapus");
        exit();
    } else {
        header("Location: index.php?error=Gagal menghapus data");
        exit();
    }
} else {
    header("Location: index.php?error=Terjadi kesalahan saat menghapus data");
    exit();
}

$stmt->close();
$conn->close();
?>