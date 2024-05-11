<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['userid'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

$namalengkap = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Rekap Data - BMT BENING SUCI</title>
    <!-- Tambahkan link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan link AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Tambahkan link DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkbox.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- Sertakan CSS Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="uploadfoto.css"> -->
    <link rel="icon" href="img/visiting_new.png" type="image/png">
    <style>
        /* Tambahkan CSS agar tombol accordion terlihat dengan baik */
        .btn-link {
            text-decoration: none;
            color: #007bff;
            /* Warna teks tombol */
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .card-header {
            background-color: #f7f7f7;
            /* Warna latar belakang header card */
        }

        #notification {
            display: none;
            margin-top: 10px;
            /* Adjust this value based on your layout */
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
        }
    </style>
    <style>
        .myButtonCekSaldo {
            box-shadow: 3px 4px 0px 0px #899599;
            background: linear-gradient(to bottom, #ededed 5%, #bab1ba 100%);
            background-color: #ededed;
            border-radius: 15px;
            border: 1px solid #d6bcd6;
            display: inline-block;
            cursor: pointer;
            color: #3a8a9e;
            font-family: Arial;
            font-size: 17px;
            padding: 7px 25px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #e1e2ed;
        }

        .myButtonCekSaldo:hover {
            background: linear-gradient(to bottom, #bab1ba 5%, #ededed 100%);
            background-color: #bab1ba;
        }

        .myButtonCekSaldo:active {
            position: relative;
            top: 1px;
        }

        #imagePreview img {
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 5px;
            height: 150px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <?php include 'header.php'; ?>
        </nav>

        <?php include 'sidebar.php'; ?>

        <div class="content-wrapper">
            <!-- Konten Utama -->
            <main class="content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rekap Data Karyawan</li>
                    </ol>
                </nav>
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    $currentPage = basename($_SERVER['PHP_SELF']); // Mendapatkan halaman saat ini
                
                    switch ($page) {
                        case 'absensi':
                            if ($currentPage !== 'absensi.php') {
                                header("Location: absensi.php?page=absensi");
                                exit;
                            }
                            break;
                
                        case 'izin':
                            if ($currentPage !== 'izin.php') {
                                header("Location: izin.php?page=izin");
                                exit;
                            }
                            break;
                
                        case 'karyawan':
                            if ($currentPage !== 'karyawan.php') {
                                header("Location: karyawan.php?page=karyawan");
                                exit;
                            }
                            break;
                
                
                        case 'rekapdata.php':
                            if ($currentPage !== 'rekapdata.php.php') {
                                header("Location: rekapdata.php.php?page=rekapdata.php");
                                exit;
                            }
                            break;
                
                        // case 'uploadmandatory':
                        //     if ($currentPage !== 'uploadmandatory.php') {
                        //         header("Location: uploadmandatory.php?page=uploadmandatory");
                        //         exit;
                        //     }
                        //     break;
                        case 'dashboard':
                            if ($currentPage !== 'index.php') {
                                header("Location: index.php?page=dashboard");
                                exit;
                            }
                            break;
                
                        default:
                            // Handle cases for other pages or provide a default action
                            break;
                    }
                }
                ?>
<form id="rekapDataForm" action="rekapdata.php" method="post">
    <!-- Input untuk pencarian nama karyawan -->
    <div class="form-group">
        <label for="searchEmployee">Cari Nama Karyawan:</label>
        <select id="searchEmployee" class="form-control" style="width:100%;">
            <option value="">Pilih Nama Karyawan</option>
            <?php
            // Lakukan koneksi ke database
            include 'konekke_local.php';

            // Query untuk mendapatkan data karyawan dari tabel users
            $sql = "SELECT fullname FROM users";
            $result = $koneklocalhost->query($sql);

            // Loop melalui hasil query dan menambahkan nama karyawan sebagai opsi dropdown
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['fullname'] . "'>" . $row['fullname'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="row">
        <div class="col-md-3">
            <!-- Input tanggal awal -->
            <div class="form-group">
                <label for="tanggalAwal">Tanggal Awal:</label>
                <input type="date" class="form-control" id="tanggalAwal" name="tanggalAwal">
            </div>
        </div>
        <div class="col-md-3">
            <!-- Input tanggal akhir -->
            <div class="form-group">
                <label for="tanggalAkhir">Tanggal Akhir:</label>
                <input type="date" class="form-control" id="tanggalAkhir" name="tanggalAkhir">
            </div>
        </div>
    </div>
    <!-- Select box untuk memilih jenis rekap data -->
    <div class="form-group">
        <label for="rekapType">Rekap Data:</label>
        <select id="rekapType" class="form-control" style="width:100%;" name="rekapType">
            <option value="izin">Rekap Data Izin</option>
            <option value="cuti">Rekap Data Cuti</option>
        </select>
    </div>

    <!-- Tombol Submit -->
    <div class="row" align="right">
        <div class="col-md-4">
            <!-- Perbaiki type tombol menjadi "submit" -->
            <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-search"></i> Cari</button>
            <button class="btn btn-info" onclick="refreshPage()"><i class='fa fa-sync'></i> Refresh</button>
            <button class="btn btn-success" onclick="exportToExcel()"><i class='fa fa-file-excel'></i> Download</button>
        </div>
    </div>
</form>

<br><br>

<!-- Tabel untuk Menampilkan Rekap Data -->
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="rekapDataTable" class="display table table-bordered table-striped table-hover responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal</th>
                        <th>Jenis Izin/Cuti</th>
                        <th>Alasan</th>
                        <th>Status Approval</th>
                        <th>Approval</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lakukan koneksi ke database
                    include 'konekke_local.php';

                    // Periksa apakah data form telah dikirimkan
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                        // Periksa apakah elemen-elemen yang diperlukan dari $_POST telah diset
                        if (isset($_POST['tanggalAwal'], $_POST['tanggalAkhir'], $_POST['rekapType'])) {
                            // Ambil data dari form filter
                            $tanggalAwal = $_POST['tanggalAwal'];
                            $tanggalAkhir = $_POST['tanggalAkhir'];
                            $rekapType = $_POST['rekapType'];

                            // Query untuk mendapatkan data izin atau cuti sesuai dengan filter
                            if ($rekapType === 'izin') {
                                $sql = "SELECT id, 
                                        nik,
                                        tanggal, 
                                        jenis_izin AS jenis, 
                                        alasan, 
                                        status_approval, 
                                        '' AS approval, 
                                        '' AS action,
                                        fullname
                                        FROM db_bmt_beningsuci.izin 
                                        LEFT JOIN users ON izin.userid = users.userid
                                        LEFT JOIN karyawan ON users.userid = karyawan.userid
                                        WHERE tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";
                            } else if ($rekapType === 'cuti') {
                                $sql = "SELECT id, tanggal_mulai AS tanggal, tanggal_selesai AS jenis, alasan, status_approval, '' AS approval, '' AS action 
                                        FROM db_bmt_beningsuci.cuti 
                                        LEFT JOIN users ON izin.userid = users.userid
                                        LEFT JOIN karyawan ON users.userid = karyawan.userid
                                        WHERE tanggal_mulai BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";
                            }

                            $result = $koneklocalhost->query($sql);
                            $nomorUrutTerakhir = 1;
                            // Tampilkan data dalam bentuk baris tabel HTML
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td style='text-align:center; width: 2px; white-space: normal;'>" . $nomorUrutTerakhir . "</td>";
                                    echo "<td style='text-align:center; width: 2px; white-space: normal;'>" . $row['nik'] . "</td>";
                                    echo "<td>" . $row['fullname'] . "</td>";
                                    // echo "<td>" . $row['tanggal'] . "</td>";
                                    echo '<td style="text-align:center; width: 2px; white-space: normal;">' . date('d-m-Y', strtotime($row['tanggal'])) . '</td>';
                                    echo "<td>" . $row['jenis'] . "</td>";
                                    echo "<td>" . $row['alasan'] . "</td>";
                                    echo "<td>" . $row['status_approval'] . "</td>";
                                    echo "<td><button class='btn btn-success btn-approve' data-id='" . $row['id'] . "'>Approve</button></td>";
                                    echo "<td><button class='btn btn-danger btn-delete' data-id='" . $row['id'] . "'>Delete</button></td>";
                                    $nomorUrutTerakhir++;
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Tidak ada data yang ditemukan</td></tr>";
                            }
                        } else {
                            // Jika elemen-elemen belum diset, tampilkan pesan atau tindakan lainnya
                            echo "<tr><td colspan='8'>Mohon isi semua filter terlebih dahulu</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

            </main>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- Tambahkan Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>


    <script>
        $(document).ready(function () {
            // Tambahkan event click pada tombol pushmenu
            $('.nav-link[data-widget="pushmenu"]').on('click', function () {
                // Toggle class 'sidebar-collapse' pada elemen body
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#rekapDataTable  ').DataTable({
        responsive: true,
        scrollX: true,
        searching: true,
        lengthMenu: [10, 25, 50, 100, 500, 1000],
        pageLength: 10,
        dom: 'lBfrtip'
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Handle klik tombol approval
            $(document).on('click', '.btn-approve', function () {
                var id = $(this).data('id');
                var rekapType = $('#rekapType').val(); // Ambil nilai rekapType dari select box

                // Lakukan permintaan AJAX untuk menyimpan perubahan status approval
                $.ajax({
                    url: 'approve_request.php',
                    type: 'POST',
                    data: { id: id, type: rekapType },
                    success: function (response) {
                        if (response == 'success') {
                            // Jika berhasil, tambahkan kelas CSS untuk notifikasi warna hijau pada baris tabel yang bersangkutan
                            $(this).closest('tr').addClass('approved-row');

                            // Tampilkan notifikasi bahwa permintaan telah di-approve
                            // Anda dapat menggunakan toastr.js atau metode lain untuk menampilkan notifikasi
                            alert('Request has been approved!');
                        } else {
                            // Jika gagal, tampilkan pesan error
                            alert('Failed to approve request. Please try again later.');
                        }
                    }.bind(this) // Mengikat "this" agar merujuk ke tombol yang ditekan
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn-delete', function () {
                var id = $(this).data('id');

                // Lakukan permintaan AJAX untuk menghapus entri
                $.ajax({
                    url: 'delete_entry.php',
                    type: 'POST',
                    data: { id: id },
                    success: function (response) {
                        if (response == 'success') {
                            // Jika berhasil, hapus baris tabel yang terkait
                            $(this).closest('tr').remove();
                            alert('Entry has been deleted!');
                        } else {
                            // Jika gagal, tampilkan pesan error
                            alert('Failed to delete entry. Please try again later.');
                        }
                    }
                });
            });
        });
    </script>


    <script>
        function refreshPage() {
            location.reload(true);
        }
    </script>
    <script>
        function exportToExcel() {
            var table = document.getElementById("rekapDataTable");

            // Dapatkan daftar gambar dari elemen HTML
            var photoData = [];
            var photoContainers = document.querySelectorAll('.photo-container');
            photoContainers.forEach(function(container) {
                var photos = container.querySelectorAll('img');
                var photoPaths = Array.from(photos).map(function(photo) {
                    return photo.getAttribute('src');
                });
                photoData.push(photoPaths.join(', '));
            });

            // Dapatkan tanggal saat ini
            $currentDate = date("Y-m-d");

            // Nama judul website
            $websiteTitle = "BMT BENING SUCI";
            // Membuat objek worksheet
            var ws = XLSX.utils.table_to_sheet(table);

            // Menambahkan header berisi tanggal dan nama judul website
            XLSX.utils.sheet_add_aoa(ws, [
                ["Tanggal: " . $currentDate],
                ["Judul Website: " . $websiteTitle]
            ], { origin: 'A1' });

            // Membuat objek workbook
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            // Membuat file Excel dan mengunduhnya
            XLSX.writeFile(wb, "RekapDataIzin.xlsx");
        }
    </script>
</body>

</html>