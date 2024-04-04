<?php
// process_promise.php

session_start();
include 'konekke_local.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan dari form Promise
    $keteranganBayar = $_POST['keteranganBayar'];
    $janjiBayar = $_POST['janjiBayar'];
    $fotoKunjungan = $_FILES['fotoKunjungan']['name']; // Anda mungkin perlu memproses foto menggunakan fungsi upload yang sesuai

    // Ambil data lainnya dari sesi
    $usersid = $_SESSION['usersid'];
    $fullname = $_SESSION['fullname'];

    // Query untuk menyimpan data ke dalam tabel mandatory_bprs
    $insertQuery = "INSERT INTO db_kunjungan.mandatory_bprs (usersid, NAMA_NASABAH, keteranganbayar, janjibayar, kunjungan_path, created_at) 
                    VALUES ('$usersid', '$fullname', '$keteranganBayar', '$janjiBayar', '$fotoKunjungan', NOW())";

    // Eksekusi query
    if ($koneklocalhost->query($insertQuery)) {
        // Jika berhasil disimpan, kirim respons
        echo json_encode(['status' => 'success', 'message' => 'Promise submitted successfully']);
        header('Location: mandatory.php?page=mandatory');
        exit;
    } else {
        // Jika terjadi error, kirim respons error
        echo json_encode(['status' => 'error', 'message' => 'Error while submitting promise']);
    }

    // Tutup koneksi database
    $koneklocalhost->close();
} else {
    // Kirim respons error jika request bukan POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
