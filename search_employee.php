<?php
// Sertakan file koneksi
include 'koneksi_local.php';

// Ambil data pencarian dari request GET
$searchTerm = $_GET['term'];

// Query untuk mencari nama karyawan berdasarkan inputan pencarian
$query = "SELECT fullname FROM karyawan WHERE fullname LIKE '%".$searchTerm."%'";
$result = $koneklocalhost->query($query);

// Format data hasil query menjadi array untuk Select2
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'id' => $row['fullname'],
        'text' => $row['fullname']
    );
}

// Kembalikan hasil dalam format JSON
echo json_encode($data);
?>
