<?php
// Include file koneksi database
include 'konekke_local.php';

// Cek apakah tombol import telah diklik
if(isset($_POST["import"])){
    // Nama file sementara yang disimpan di server
    $file = $_FILES["fileImport"]["tmp_name"];
    
    // Nama file asli yang diunggah oleh pengguna
    $fileName = $_FILES["fileImport"]["name"];
    
    // Ekstensi file
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
    // Lokasi dan nama file sementara setelah diunggah
    $fileTmpName = $_FILES["fileImport"]["tmp_name"];
    
    // Lokasi penyimpanan file yang valid
    $targetDir = "uploads/";

    // Nama file setelah diunggah
    $targetFilePath = $targetDir . basename($fileName);
    
    // Cek apakah file yang diunggah merupakan file Excel atau CSV
    if($fileExt != "xls" && $fileExt != "xlsx" && $fileExt != "csv"){
        echo "Hanya file Excel atau CSV yang diizinkan.";
        exit();
    }

    // Pindahkan file dari lokasi sementara ke lokasi penyimpanan
    if(move_uploaded_file($fileTmpName, $targetFilePath)){
        // Menggunakan library PHPExcel untuk membaca file Excel
        require_once 'PHPExcel/PHPExcel.php';

        // Inisialisasi objek PHPExcel
        $objPHPExcel = PHPExcel_IOFactory::load($targetFilePath);

        // Ambil data dari sheet pertama (indeks 0)
        $sheet = $objPHPExcel->getSheet(0);

        // Hitung jumlah baris dengan data
        $highestRow = $sheet->getHighestDataRow();

        // Looping untuk membaca data karyawan dari setiap baris
        for($row = 2; $row <= $highestRow; $row++){
            // Ambil data dari setiap kolom
            $nik = $sheet->getCellByColumnAndRow(0, $row)->getValue();
            $sex = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $birthplace = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $birthdate = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $phone_number = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $address = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $email = $sheet->getCellByColumnAndRow(6, $row)->getValue();
            $jabatan = $sheet->getCellByColumnAndRow(7, $row)->getValue();
            $tempatkerja = $sheet->getCellByColumnAndRow(8, $row)->getValue();

            // Query untuk memasukkan data ke dalam database
            $query = "INSERT INTO karyawan (nik, sex, birthplace, birthdate, phone_number, address, email, jabatan, tempatkerja) VALUES ('$nik', '$sex', '$birthplace', '$birthdate', '$phone_number', '$address', '$email', '$jabatan', '$tempatkerja')";

            // Eksekusi query
            $result = $koneklocalhost->query($query);

            // Cek keberhasilan eksekusi query
            if($result === FALSE){
                echo "Gagal memasukkan data karyawan ke dalam database.";
                exit();
            }
        }

        // Hapus file yang diunggah setelah selesai proses impor
        unlink($targetFilePath);

        // Tampilkan pesan sukses
        echo "Impor data karyawan berhasil!";
    } else {
        // Jika gagal memindahkan file, tampilkan pesan error
        echo "Maaf, terjadi kesalahan saat mengunggah file.";
    }
}
?>
