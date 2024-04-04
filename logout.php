<?php
session_start();
include 'konekke_local.php';

if (isset($_SESSION['user_id'])) {
    // Ambil data mac dan ip dari session
    $macAddress = isset($_SESSION['mac']) ? $_SESSION['mac'] : '';
    $ipAddress = isset($_SESSION['ip']) ? $_SESSION['ip'] : '';

    // Update logout_date ke dalam tabel users_new
    date_default_timezone_set('Asia/Jakarta');
    $logoutDate = date('Y-m-d H:i:s');
    $userId = $_SESSION['user_id'];

    $updateQuery = "UPDATE users SET logout_date = ? WHERE UserID = ?";
    $updateStmt = $koneklocalhost->prepare($updateQuery);
    $updateStmt->bind_param("si", $logoutDate, $userId);
    $updateStmt->execute();

    // Hapus semua variabel sesi
    session_unset();

    // Hapus sesi
    session_destroy();

    // Periksa usersid
    if ($userId >= 1 && $userId <= 6) {
        // Jika usersid antara 1 dan 6, arahkan ke login.php
        header("Location: login.php");
    } elseif ($userId >= 13 && $userId <= 20) {
        // Jika usersid antara 13 dan 20, arahkan ke loginbisnis.php
        header("Location: loginbisnis.php");
    } else {
        // Jika usersid tidak sesuai, arahkan ke halaman login default
        header("Location: login.php");
    }

    exit; // Redirect ke halaman login yang sesuai
} else {
    // Jika tidak ada sesi, langsung redirect ke halaman login default
    header("Location: login.php");
}
?>
