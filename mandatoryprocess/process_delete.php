<?php
// process_delete.php

session_start();
include 'konekke_local.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan dari form Delete
    $recordIdToDelete = $_POST['recordId']; // Sesuaikan dengan cara Anda menangani ID record

    // Query untuk menghapus data di dalam tabel mandatory_bprs
    $deleteQuery = "DELETE FROM db_kunjungan.mandatory_bprs WHERE usersid = '{$_SESSION['usersid']}' AND mandatory_id = '$recordIdToDelete'";

    // Eksekusi query
    if ($koneklocalhost->query($deleteQuery)) {
        // Jika berhasil dihapus, kirim respons
        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully']);
        header('Location: mandatory.php?page=mandatory');
        exit;
    } else {
        // Jika terjadi error, kirim respons error
        echo json_encode(['status' => 'error', 'message' => 'Error while deleting record']);
    }

    // Tutup koneksi database
    $koneklocalhost->close();
} else {
    // Kirim respons error jika request bukan POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
