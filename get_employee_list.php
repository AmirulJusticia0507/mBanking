<?php
// Lakukan koneksi ke database
include 'konekke_local.php';

// Ambil status karyawan dari sesi
$status = $_SESSION['STATUS'];

// Query untuk mendapatkan data karyawan dari tabel users
if ($status == 'Admin') {
    $sql = "SELECT userid, fullname FROM users";
} else {
    // Jika status karyawan bukan Admin, hanya tampilkan nama karyawan sesuai dengan nama yang login
    $fullname = $_SESSION['fullname'];
    $sql = "SELECT userid, fullname FROM users WHERE fullname = '$fullname'";
}

$result = $koneklocalhost->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'id' => $row['userid'],
        'text' => $row['fullname']
    );
}

// Mengembalikan data dalam format JSON
echo json_encode($data);
?>
