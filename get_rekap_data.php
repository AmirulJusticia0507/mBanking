<?php
// Lakukan koneklocalhost ke database
include 'konekke_local.php';

// Tangkap data dari permintaan AJAX
$tanggalAwal = $_POST['tanggalAwal'];
$tanggalAkhir = $_POST['tanggalAkhir'];
$rekapType = $_POST['rekapType'];
$employeeId = $_POST['employeeId'];

// Tentukan tabel berdasarkan jenis rekap data
if ($rekapType == 'izin') {
    $table = 'izin';
    $tanggalColumn = 'tanggal';
} else {
    $table = 'cuti';
    $tanggalColumn = 'tanggal_mulai'; // Sesuaikan dengan kolom yang tepat
}

// Query untuk mendapatkan data rekap berdasarkan tanggal
$sql = "SELECT * FROM $table WHERE userid = '$employeeId' AND $tanggalColumn BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";
$result = $koneklocalhost->query($sql);

// Generate tabel HTML untuk ditampilkan
$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Generate baris tabel untuk setiap entri data
        $output .= '<tr>';
        $output .= '<td>' . $row['userid'] . '</td>';
        $output .= '<td>' . $row['fullname'] . '</td>';
        $output .= '<td>' . $row['jenis_izin'] . '</td>';
        $output .= '<td>' . $row['tanggal'] . '</td>'; // Sesuaikan dengan kolom yang tepat
        $output .= '<td>' . $row['alasan'] . '</td>';
        $output .= '<td>' . $row['status_approval'] . '</td>';
        $output .= '<td><button class="btn btn-success btn-approve" data-id="' . $row['id'] . '">Approve</button></td>';
        $output .= '<td>' . $row['action'] . '</td>';
        // Tambahkan tombol action di sini jika diperlukan
        $output .= '</tr>';
    }
} else {
    // Tampilkan pesan jika tidak ada data yang ditemukan
    $output .= '<tr><td colspan="8">Tidak ada data ditemukan</td></tr>';
}

// Keluarkan hasil
echo $output;
?>
