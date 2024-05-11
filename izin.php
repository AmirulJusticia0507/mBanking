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
    <title>Data Izin - BMT BENING SUCI</title>
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
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Izin</li>
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

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#izin" data-bs-toggle="tab">Form Izin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#cuti" data-bs-toggle="tab">Form Cuti</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="izin">
                                <form action="simpan_izin.php" method="POST"><br>
                                    <div class="col mb-3">
                                        <label for="nama_karyawan">Nama Karyawan:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?php echo $namalengkap; ?>" readonly>
                                        </div>
                                    </div>
                                    <?php
                                    date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu menjadi Asia/Jakarta

                                    $now = date('Y-m-d H:i:s'); // Ambil tanggal dan jam saat ini dengan format Y-m-d H:i:s

                                    list($date, $time) = explode(' ', $now); // Pisahkan tanggal dan jam

                                    // Ubah format tanggal menjadi dd-mm-yyyy
                                    $dateFormatted = date('d-m-Y', strtotime($date));
                                    // Ubah format jam menjadi HH:ii
                                    // $timeFormatted = date('H:i', strtotime($time));
                                    ?>
                                    <!-- <div class="mb-3">
                                        <label for="tanggal" class="form-label">Tanggal:</label>
                                        <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo $dateFormatted; ?>" readonly>
                                    </div> -->
                                    <div class="col mb-3">
                                        <label for="tanggal">Tanggal : </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="date" class="form-control" name="tanggal" id="tanggal" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis_izin" class="form-label">Jenis Izin:</label>
                                        <select class="form-select" id="jenis_izin" name="jenis_izin" required>
                                            <option value="">Pilih Jenis Izin</option>
                                            <option value="Izin Sakit">Izin Sakit</option>
                                            <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                                            <!-- Tambahkan jenis izin lainnya jika perlu -->
                                        </select>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="alasan" class="form-label">Alasan:</label>
                                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                                    </div> -->
                                    <div class="col mb-3">
                                        <label for="alasan">Alasan Izin:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-clipboard-list"></i></span>
                                            <textarea name="alasan" id="alasan" cols="10" rows="10" placeholder="Alasan izin...." class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3" align="center">
                                        <button type="submit" class="btn btn-info">Ajukan Izin</button>
                                        <button type="reset" class="btn btn-danger"><i class="fa fa-power-off"> Reset</i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="cuti">
                                <form action="simpan_cuti.php" method="POST"><br>
                                    <div class="col mb-3">
                                        <label for="nama_karyawan">Nama Karyawan:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?php echo $namalengkap; ?>" readonly>
                                        </div>
                                    </div>
                                    <?php
                                    // Atur zona waktu menjadi Asia/Jakarta
                                    date_default_timezone_set('Asia/Jakarta');

                                    // Ambil tanggal hari ini dengan format Y-m-d
                                    $today = date('Y-m-d');
                                    ?>

                                    <!-- <div class="mb-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai:</label>
                                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" min="<?php echo $today; ?>" required>
                                    </div> -->
                                    <div class="col mb-3">
                                        <label for="tanggal_mulai">Tanggal Mulai: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai:</label>
                                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" min="<?php echo $today; ?>" required>
                                    </div> -->
                                    <div class="col mb-3">
                                        <label for="tanggal_selesai">Tanggal Selesai: </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="alasan" class="form-label">Alasan:</label>
                                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                                    </div> -->
                                    <div class="col mb-3">
                                        <label for="alasan">Alasan Cuti:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-clipboard-list"></i></span>
                                            <textarea name="alasan" id="alasan" cols="10" rows="10" placeholder="Alasan Cuti...." class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3" align="center">
                                        <button type="submit" class="btn btn-info">Ajukan Cuti</button>
                                        <button type="reset" class="btn btn-danger"><i class="fa fa-power-off"> Reset</i></button>
                                    </div>
                                </form>
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
            <script type="text/javascript" charset="utf8"
                src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
            <!-- Tambahkan Select2 -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
            <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script> -->
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
                    $('#accountsTable').DataTable({
                        responsive: true,
                        scrollX: true,
                        searching: true,
                        lengthMenu: [10, 25, 50, 100, 500, 1000],
                        pageLength: 10,
                        dom: 'lBfrtip'
                    });
                });
            </script>
</body>
</html>