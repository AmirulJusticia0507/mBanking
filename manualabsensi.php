<?php
// Mulai sesi
// session_start();

// // Periksa apakah pengguna telah terautentikasi
// if (!isset($_SESSION['user_id'])) {
//     // Jika tidak ada sesi pengguna, alihkan ke halaman login
//     header('Location: login.php');
//     exit;
// }
// $namalengkap = $_SESSION['fullname'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Form Manual Data Absensi - BMT BENING SUCI</title>
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
    <style>
        /* Style untuk peta */
        #map {
            height: 400px;
            width: 100%;
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
                        <li class="breadcrumb-item active" aria-current="page">Form Manual Absensi</li>
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
                
                
                        // case 'mandatory':
                        //     if ($currentPage !== 'mandatory.php') {
                        //         header("Location: mandatory.php?page=mandatory");
                        //         exit;
                        //     }
                        //     break;
                
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
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Form Create -->
                            <div class="card">
                                <div class="card-header">
                                    Form Manual Absensi
                                </div>
                                <div class="card-body">
                                <div id="notification"></div>
                                    <form action="proses_absensi_manual.php" method="post">
                                        <div class="form-group">
                                            <label for="userid">Nama karyawan</label>
                                            <select class="form-control" id="userid" name="userid">
                                                <?php
                                                include 'konekke_local.php';
                                                // Query untuk mendapatkan data karyawan
                                                $query = "SELECT userid, fullname FROM db_bmt_beningsuci.karyawan";
                                                $result = mysqli_query($koneklocalhost, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<option value='" . $row['userid'] . "'>" . $row['fullname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jam">Pilih Jam</label>
                                            <input type="time" class="form-control" id="jam" name="jam" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan:</label>
                                            <select class="form-control" id="keterangan" name="keterangan" required>
                                                <option value="">Pilih Keterangan</option>
                                                <option value="Absen Masuk">Absen Masuk</option>
                                                <option value="Absen Istirahat">Absen Istirahat</option>
                                                <option value="Absen Pulang">Absen Pulang</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="info_lokasi">Info Lokasi:</label>
                                            <input type="text" class="form-control" id="info_lokasi" name="info_lokasi" required>
                                            <input type="hidden" id="latitude" name="latitude">
                                            <input type="hidden" id="longitude" name="longitude"><br>
                                            <button type="button" class="btn btn-info" onclick="getLocation()">Dapatkan Lokasi</button>
                                        </div>

                                        <!-- Menampilkan peta Google Maps -->
                                        <div id="map"></div><br><br>
                                        <div align="center">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Submit</button>
                                            <button type="reset" class="btn btn-danger"><i class="fa fa-power-off"> Reset</i></button>
                                        </div>
                                    </form>
                                </div>
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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
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
        // Fungsi untuk mendapatkan informasi lokasi pengguna
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Fungsi untuk menampilkan posisi pengguna
        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            var locationInfo = "Latitude: " + latitude + ", Longitude: " + longitude;

            // Mengisi nilai latitude dan longitude ke dalam input
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;

            // Mengisi nilai info lokasi dengan informasi latitude dan longitude
            document.getElementById('info_lokasi').value = locationInfo;

            // Memanggil fungsi untuk menampilkan peta dengan posisi pengguna
            showMap(latitude, longitude);
        }

        // Fungsi untuk menampilkan peta dengan posisi pengguna
        function showMap(latitude, longitude) {
            var mapCanvas = document.getElementById("map");
            var mapOptions = {
                center: new google.maps.LatLng(latitude, longitude),
                zoom: 15
            };
            var map = new google.maps.Map(mapCanvas, mapOptions);

            // Menambahkan marker ke peta
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(latitude, longitude),
                map: map
            });
        }
    </script>
    <script>
        // Ambil parameter dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');

        // Tampilkan notifikasi sesuai hasil penyimpanan data
        if (success) {
            // Notifikasi jika penyimpanan berhasil
            $("#notification").html('<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
        } else if (error) {
            // Notifikasi jika terjadi kesalahan
            $("#notification").html('<div class="alert alert-danger" role="alert">Gagal menyimpan data!</div>');
        }
    </script>

</body>
</html>