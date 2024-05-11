<?php
include 'konekke_local.php';

// Periksa apakah pengguna telah terautentikasi
session_start();
if (!isset($_SESSION['userid'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

$namalengkap = $_SESSION['fullname']; // Mengambil fullname dari session

// Pastikan session 'userid' sudah diset saat login
$userid = $_SESSION['userid'];

// Query untuk mengambil data karyawan berdasarkan 'userid'
$sql = "SELECT * FROM karyawan WHERE userid = '$userid'";
$result = $koneklocalhost->query($sql);

if ($result->num_rows > 0) {
    // Ambil data karyawan
    $row = $result->fetch_assoc();
    // $namalengkap = $row['fullname'];
    $nik = $row['nik'];
    $jabatan = $row['jabatan'];
    $tempatkerja = $row['tempatkerja'];
    $fotoKaryawan = $row['photokaryawan'];
    // Potong NIK untuk menampilkan tiga digit awal
    $nikTigaDigitAwal = substr($nik, 0, 3) . str_repeat('X', strlen($nik) - 3);
} else {
    // Jika data karyawan tidak ditemukan
    $namalengkap = "Data tidak ditemukan";
    $nikTigaDigitAwal = "";
    $jabatan = "";
    $tempatkerja = "";
    $fotoKaryawan = "";
}

$koneklocalhost->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - BMT BENING SUCI</title>
    <!-- Tambahkan link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan link AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Tambahkan link DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkbox.css">
    <!-- Sertakan CSS Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="uploadfoto.css"> -->
    <link rel="icon" href="img/visiting_new.png" type="image/png">
    <style>
        /* Tambahkan CSS agar tombol accordion terlihat dengan baik */
        .btn-link {
            text-decoration: none;
            color: #007bff; /* Warna teks tombol */
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .card-header {
            background-color: #f7f7f7; /* Warna latar belakang header card */
        }

        #notification {
            display: none;
            margin-top: 10px; /* Adjust this value based on your layout */
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
        }
    </style>
    <style>
        .myButtonCekSaldo {
            box-shadow: 3px 4px 0px 0px #899599;
            background:linear-gradient(to bottom, #ededed 5%, #bab1ba 100%);
            background-color:#ededed;
            border-radius:15px;
            border:1px solid #d6bcd6;
            display:inline-block;
            cursor:pointer;
            color:#3a8a9e;
            font-family:Arial;
            font-size:17px;
            padding:7px 25px;
            text-decoration:none;
            text-shadow:0px 1px 0px #e1e2ed;
        }
        .myButtonCekSaldo:hover {
            background:linear-gradient(to bottom, #bab1ba 5%, #ededed 100%);
            background-color:#bab1ba;
        }
        .myButtonCekSaldo:active {
            position:relative;
            top:1px;
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
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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

                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card bg-light d-flex flex-fill">
                        <div class="card-header text-muted border-bottom-0">
                            <?php echo $jabatan; ?>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="lead"><b><?php echo $namalengkap; ?></b></h2>
                                    <p class="text-muted text-sm"><b>Job as: </b><b><?php echo $jabatan; ?></b></p>
                                    <ul class="ml-4 mb-0 fa-ul text-muted">
                                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: <b><?php echo $tempatkerja; ?></b></li><br>
                                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                            <div class="input-group mb-3">
                                                <label for="nik">NIK : </label>
                                                &emsp;<input type="text" class="form-control" id="nik" value="<?php echo $nikTigaDigitAwal; ?>" readonly>
                                                <button class="btn btn-outline-secondary" type="button" id="eye-toggle"><i class="fas fa-eye"></i></button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-3 text-center">
                                    <img src="uploads/photokaryawan/<?php echo $fotoKaryawan; ?>" alt="user-avatar" class="img-circle img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div><br><br>

                <div class="row" align="center">
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-user fa-3x"></i>
                                <h5 class="card-title">Karyawan</h5>
                                <p class="card-text">Kelola data karyawan</p>
                                <button onclick="location.href='karyawan.php?page=karyawan'" class="btn btn-secondary">Karyawan</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-camera fa-3x"></i>
                                <h5 class="card-title">Absen</h5>
                                <p class="card-text">Absensi</p>
                                <button onclick="location.href='absensi.php?page=absensi'" class="btn btn-danger">Data Kehadiran</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-3x"></i>
                                <h5 class="card-title">History</h5>
                                <p class="card-text">Rekap data karyawan</p>
                                <button onclick="location.href='rekapdata.php?page=rekapdata'" class="btn btn-success">Rekap Data</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-3x"></i>
                                <h5 class="card-title">Cuti/Izin</h5>
                                <p class="card-text">Data Cuti/Izin karyawan</p>
                                <button onclick="location.href='izin.php?page=izin'" class="btn btn-info">Izin</button>
                            </div>
                        </div>
                    </div>
                </div><hr><br>

            </main>
        </div>
    </div>
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Tambahkan Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function() {
            // Tambahkan event click pada tombol pushmenu
            $('.nav-link[data-widget="pushmenu"]').on('click', function() {
                // Toggle class 'sidebar-collapse' pada elemen body
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#eye-toggle').click(function() {
                var input = $('#nik');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    // Menampilkan seluruh NIK saat tombol mata diklik
                    input.val('<?php echo $nik; ?>');
                } else {
                    input.attr('type', 'password');
                    // Menyembunyikan digit setelah tiga digit awal saat tombol mata diklik
                    input.val('<?php echo $nikTigaDigitAwal; ?>' + 'XXX');
                }
            });
        });
    </script>
</body>
</html>