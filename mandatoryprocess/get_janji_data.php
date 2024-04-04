<?php
// get_janji_data.php

session_start();
include 'konekke_local.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan dari tombol View Janji
    $recordIdToView = $_POST['recordId']; // Sesuaikan dengan cara Anda menangani ID record

    // Query untuk mengambil data dari tabel mandatory_bprs
    $selectQuery = "SELECT keterangan, tanggalbayar, mandatory_path 
                    FROM db_kunjungan.mandatory_bprs 
                    WHERE usersid = '{$_SESSION['usersid']}' AND mandatory_id = '$recordIdToView'";

    // Eksekusi query
    $result = $koneklocalhost->query($selectQuery);

    // Ambil data
    $data = $result->fetch_assoc();

    // Kirim data sebagai respons JSON
    echo json_encode($data);

    // Tutup koneksi database
    $koneklocalhost->close();
} else {
    // Kirim respons error jika request bukan POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
