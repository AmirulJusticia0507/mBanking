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
    <title>Data Kehadiran - BMT BENING SUCI</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Data Kehadiran</li>
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

                    <?php
                    date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu menjadi Asia/Jakarta

                    $now = date('Y-m-d H:i:s'); // Ambil tanggal dan jam saat ini dengan format Y-m-d H:i:s

                    list($date, $time) = explode(' ', $now); // Pisahkan tanggal dan jam

                    // Ubah format tanggal menjadi dd-mm-yyyy
                    $dateFormatted = date('d-m-Y', strtotime($date));
                    // Ubah format jam menjadi HH:ii
                    $timeFormatted = date('H:i', strtotime($time));
                    ?>

                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card">
                                <div class="card-header">
                                    Form Input Absensi
                                </div>
                                <div class="card-body">
                                    <form action="simpan_data_absensidigital.php" method="POST" enctype="multipart/form-data">
                                        <div class="col mb-3">
                                            <label for="nama_karyawan">Nama Karyawan:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?php echo $namalengkap; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo $dateFormatted; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jam" class="form-label">Jam:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                <input type="text" class="form-control" id="jam" name="jam" value="<?php echo $timeFormatted; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="keterangan" class="form-label">Keterangan:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                                    <select class="form-control" id="keterangan" name="keterangan" required>
                                                        <option value="">Pilih Keterangan</option>
                                                        <option value="Absen Pagi">Absen Pagi</option>
                                                        <option value="Absen Dinas Luar">Absen Dinas Luar</option>
                                                        <option value="Absen Pulang">Absen Pulang</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col md-3" id="dimanaForm" style="display: none;">
                                                <label for="tempat">Dimana :</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <input type="text" name="tempat" id="tempat" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="selfiephoto" class="form-label">Upload Photo:</label>
                                            <div class="position-relative">
                                                <div class="input-group">
                                                    <input type="file" class="form-control" id="selfiephoto" name="selfiephoto" >
                                                    <label class="input-group-text" for="selfiephoto"><i class="fas fa-upload"></i></label>
                                                </div>
                                                <div id="imagePreview" class="position-absolute top-50 start-50 translate-middle">
                                                    <img id="previewImg" style="max-width: 400px; max-height: 400px; display: none;">
                                                    <button type="button" class="btn btn-danger position-absolute top-0 end-0" id="cancelUpload" style="display: none;"><i class="fas fa-times"></i></button>
                                                </div>
                                            </div>
                                        </div><br><br><br><br><br><br>
                                        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']; ?>">
                                        <div class="mb-3" align="center">
                                            <button type="submit" class="btn btn-info"><i class="fa fa-floppy-disk"></i> Simpan Absensi</button>
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
            // Tampilkan preview foto saat memilih foto
            $("#selfiephoto").change(function () {
                previewImage(this);
            });

            // Fungsi untuk menampilkan preview foto
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewImg').attr('src', e.target.result).show();
                        $('#cancelUpload').show();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Tombol X untuk membatalkan unggahan foto
            $('#cancelUpload').click(function () {
                $('#selfiephoto').val('');
                $('#previewImg').hide();
                $(this).hide();
            });
        });
    </script>
    <script>
        document.getElementById('keterangan').addEventListener('change', function() {
            var dimanaForm = document.getElementById('dimanaForm');
            if (this.value === 'Absen Dinas Luar') {
                dimanaForm.style.display = 'block';
            } else {
                dimanaForm.style.display = 'none';
            }
        });
    </script>
</body>
</html>