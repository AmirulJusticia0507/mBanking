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
    <title>Data Absensi Digital - BMT BENING SUCI</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Absensi BMT BENING SUCI</li>
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

                        <form id="absensiSearchForm">
                            <div class="row">
                                <div class="form-group col-md-4">
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
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggalAwal">Tanggal Awal:</label>
                                                <input type="date" class="form-control" id="tanggalAwal" name="tanggalAwal">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggalAkhir">Tanggal Akhir:</label>
                                                <input type="date" class="form-control" id="tanggalAkhir" name="tanggalAkhir">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary" id="absensiSearchForm"><i class='fa fa-search'></i> Cari</button>
                                        <button class="btn btn-info ms-2" onclick="refreshPage()"><i class='fa fa-sync'></i> Refresh</button>
                                        <button class="btn btn-success ms-2" onclick="exportToExcel()"><i class='fa fa-file-excel'></i> Download</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Tabel Data Absensi -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="absensiDataTable" class="display table table-bordered table-striped table-hover responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>NIK</th>
                                                <th>Nama Karyawan</th>
                                                <th>Tanggal</th>
                                                <th>Jam</th>
                                                <th>Foto Absensi</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Lakukan koneksi ke database
                                            include 'konekke_local.php';

                                            // Query untuk mendapatkan data absensi
                                            $query = "SELECT
                                            id,
                                            nik,
                                            fullname,
                                            tanggal,
                                            jam,
                                            keterangan,
                                            tempat,
                                            selfiephoto,
                                            absensi_created_date
                                          FROM
                                            db_bmt_beningsuci.absensidigital
                                            LEFT JOIN users ON absensidigital.userid = users.userid
                                            LEFT JOIN karyawan ON users.userid = karyawan.nik";
                                            $result = $koneklocalhost->query($query);
                                            $nomorUrutTerakhir = 1;
                                            // Loop melalui hasil query dan tampilkan baris data dalam tabel
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td style='text-align:center; width: 2px; font-size: 10pt; white-space: normal;'>" . $nomorUrutTerakhir . "</td>";
                                                echo "<td>" . $row['nik'] . "</td>";
                                                echo "<td>" . $row['fullname'] . "</td>";
                                                echo "<td>" . $row['tanggal'] . "</td>";
                                                echo "<td>" . $row['jam'] . "</td>";
                                                echo "<td>";
                                                echo $row['foto_absensi'] ? "<img src='" . $row['foto_absensi'] . "' height='100' />" : "Tidak ada foto";
                                                echo "</td>";
                                                echo "<td>" . $row['keterangan'] . "</td>";
                                                $nomorUrutTerakhir++;
                                                echo "</tr>";
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
            <!-- JavaScript -->
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
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
                    // Load data absensi menggunakan DataTables CDN
                    $('#absensiDataTable').DataTable({
                        responsive: true,
                        scrollX: true,
                        searching: false, // Matikan pencarian DataTables karena kita sudah punya form pencarian sendiri
                        lengthMenu: [10, 25, 50, 100, 500, 1000],
                        pageLength: 10,
                        dom: 'lBfrtip',
                        processing: true,
                        serverSide: true
                    });

                    // Submit form pencarian
                    $('#absensiSearchForm').on('submit', function (e) {
                        e.preventDefault(); // Mencegah pengiriman form secara default
                        $('#absensiDataTable').DataTable().ajax.reload(); // Reload data absensi
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
                    XLSX.writeFile(wb, "RekapDataAbsensi.xlsx");
                }
            </script>
</body>
</html>