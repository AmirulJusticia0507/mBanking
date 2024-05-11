<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['userid'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Data Karyawan - BMT BENING SUCI</title>
    <!-- Tambahkan link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan link AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Tambahkan link DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkbox.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="checkbox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/venobox@1.9.3/dist/venobox/venobox.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
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
                        <li class="breadcrumb-item active" aria-current="page">Form Data Karyawan</li>
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
                        include 'konekke_local.php';

                        function executeQuery($query)
                        {
                            global $koneklocalhost;
                            if ($koneklocalhost->query($query) === TRUE) {
                                return true;
                            } else {
                                echo "Error: " . $query . "<br>" . $koneklocalhost->error;
                                return false;
                            }
                        }

                        function uploadPhoto($file)
                        {
                            $targetDir = "uploads/photokaryawan/";
                            $fileName = basename($file["name"]);
                            $targetFilePath = $targetDir . $fileName;
                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                            $fileSize = $file["size"];

                            // Check file size
                            if ($fileSize > 10 * 1024 * 1024) {
                                echo "File terlalu besar. Harap unggah file yang lebih kecil.";
                                return false;
                            }

                            // Allow certain file formats
                            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                            if (!in_array($fileType, $allowedTypes)) {
                                echo "Hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
                                return false;
                            }

                            // Rename file if it already exists
                            $counter = 1;
                            while (file_exists($targetFilePath)) {
                                $fileName = pathinfo($fileName, PATHINFO_FILENAME) . "_" . $counter . "." . $fileType;
                                $targetFilePath = $targetDir . $fileName;
                                $counter++;
                            }

                            // Upload file to server
                            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                                return $fileName;
                            } else {
                                echo "Maaf, terjadi kesalahan saat mengunggah file.";
                                return false;
                            }
                        }

                        $action = isset($_GET['action']) ? $_GET['action'] : 'input';

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST['create'])) {
                                // $userid = $_POST['userid'];
                                $nik = $_POST['nik'];
                                $sex = $_POST['sex'];
                                $birthplace = $_POST['birthplace'];
                                $birthdate = $_POST['birthdate'];
                                $phone_number = $_POST['phone_number'];
                                $address = $_POST['address'];
                                $email = $_POST['email'];
                                $jabatan = $_POST['jabatan'];
                                $tempatkerja = $_POST['tempatkerja'];

                                // Validasi jika ada file yang diunggah
                                if (isset($_FILES['photokaryawan']) && $_FILES['photokaryawan']['error'] === UPLOAD_ERR_OK) {
                                    $photokaryawan = uploadPhoto($_FILES['photokaryawan']);
                                    if ($photokaryawan !== false) {
                                        // $query = "INSERT INTO karyawan (userid, nik, sex, birthplace, birthdate, phone_number, address, email, jabatan, tempatkerja, photokaryawan) VALUES ('$userid', '$nik', '$sex', '$birthplace', '$birthdate', '$phone_number', '$address', '$email', '$jabatan', '$tempatkerja', '$photokaryawan')";
                                        $query = "INSERT INTO karyawan (nik, sex, birthplace, birthdate, phone_number, address, email, jabatan, tempatkerja, photokaryawan) VALUES ('$nik', '$sex', '$birthplace', '$birthdate', '$phone_number', '$address', '$email', '$jabatan', '$tempatkerja', '$photokaryawan')";
                                        executeQuery($query);
                                    }
                                }
                            }

                            if (isset($_POST['update'])) {
                                $nik = $_POST['nik'];
                                $sex = $_POST['sex'];
                                $birthplace = $_POST['birthplace'];
                                $birthdate = $_POST['birthdate'];
                                $phone_number = $_POST['phone_number'];
                                $address = $_POST['address'];
                                $email = $_POST['email'];
                                $jabatan = $_POST['jabatan'];
                                $tempatkerja = $_POST['tempatkerja'];

                                // Validasi jika ada file yang diunggah
                                if (isset($_FILES['photokaryawan']) && $_FILES['photokaryawan']['error'] === UPLOAD_ERR_OK) {
                                    $photokaryawan = uploadPhoto($_FILES['photokaryawan']);
                                    if ($photokaryawan !== false) {
                                        $query = "UPDATE karyawan SET nik='$nik', sex='$sex', birthplace='$birthplace', birthdate='$birthdate', phone_number='$phone_number', address='$address', email='$email', jabatan='$jabatan', tempatkerja='$tempatkerja', photokaryawan='$photokaryawan' WHERE nik=$nik";
                                        executeQuery($query);
                                    }
                                }
                            }                                

                            if (isset($_POST['delete'])) {
                                $nik = $_POST['nik'];
                                $query = "DELETE FROM karyawan WHERE nik=$nik";
                                executeQuery($query);
                            }
                        }

                        // Mendapatkan data jika ID tersedia
                        if (isset($_GET['nik'])) {
                            $nik = $_GET['nik'];
                            // Ubah query SQL untuk menyesuaikan dengan penggunaan kolom id
                            $sql = "SELECT * FROM karyawan WHERE nik = $nik";
                            $result = $koneklocalhost->query($sql);
                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                            } else {
                                echo "Data tidak ditemukan.";
                            }
                        }

                        ?>
                            <div class="card">
                                <div class="card-header">
                                    Form Karyawan
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="nik" value="<?php echo isset($_GET['nik']) ? $_GET['nik'] : ''; ?>">
                                        <div class="form-group">
                                            <label for="fullname">Nama Lengkap:</label>
                                            <input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo $namalengkap; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="nik">NIK:</label>
                                            <input type="text" name="nik" id="nik" class="form-control" value="<?php echo isset($_GET['nik']) ? $row['nik'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="sex">Jenis Kelamin:</label>
                                            <select name="sex" id="sex" class="form-control">
                                                <option value="Male" <?php echo (isset($_GET['nik']) && $row['sex'] == 'Male') ? 'selected' : ''; ?>>Pria</option>
                                                <option value="Female" <?php echo (isset($_GET['nik']) && $row['sex'] == 'Female') ? 'selected' : ''; ?>>Wanita</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="birthplace">Tempat Lahir:</label>
                                            <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo isset($_GET['nik']) ? $row['birthplace'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="birthdate">Tanggal Lahir:</label>
                                            <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo isset($_GET['nik']) ? $row['birthdate'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number">No. Hp:</label>
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo isset($_GET['nik']) ? $row['phone_number'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_GET['nik']) ? $row['email'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="jabatan">Jabatan:</label>
                                            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo isset($_GET['nik']) ? $row['jabatan'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="tempatkerja">Tempat Kerja:</label>
                                            <input type="text" class="form-control" id="tempatkerja" name="tempatkerja" value="<?php echo isset($_GET['nik']) ? $row['tempatkerja'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Alamat:</label>
                                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($_GET['nik']) ? $row['address'] : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="photokaryawan">Foto Karyawan:</label>
                                            <input type="file" class="form-control" id="photokaryawan" name="photokaryawan">
                                            <!-- Pratinjau foto -->
                                            <?php if (isset($_GET['nik']) && !empty($row['photokaryawan'])) : ?>
                                                <img src="uploads/photokaryawan/<?php echo $row['photokaryawan']; ?>" alt="Foto Karyawan" style="max-width: 100px; max-height: 100px;">
                                            <?php endif; ?>
                                        </div>
                                        <div align="center">
                                            <?php if ($action == 'input') : ?>
                                                <button type="submit" name="create" class="btn btn-primary">Create</button>
                                            <?php else : ?>
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            <?php endif; ?>
                                            <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Tampilkan Data Karyawan -->
                            <div class="card">
                                <div class="card-header">
                                    Data Karyawan
                                </div><br>
                                <!-- Form Upload Excel/CSV -->
                                <!-- <div class="card">
                                    <div class="card-header">
                                        Import Data Karyawan dari Excel atau CSV
                                    </div>
                                    <div class="card-body">
                                        <form action="import_karyawan.php" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="fileImport">Pilih File:</label>
                                                <input type="file" class="form-control" id="fileImport" name="fileImport">
                                                <small class="form-text text-muted">Hanya file Excel atau CSV yang diizinkan.</small>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="import">Import</button>
                                        </form>
                                    </div>
                                </div><br><br> -->

                                <div class="card-body">
                                    <table class="display table table-bordered table-striped table-hover responsive nowrap" style="width:100%" id="karyawanTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Tempat Lahir</th>
                                                <th>Tanggal Lahir</th>
                                                <th>No Hp</th>
                                                <th>Alamat</th>
                                                <th>Foto</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Ambil data dari database
                                            $sql = "SELECT * FROM karyawan";
                                            $result = $koneklocalhost->query($sql);
                                            $nomorUrutTerakhir = 1;
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td style='text-align:center; width: 2px; font-size: 10pt; white-space: normal;'>" . $nomorUrutTerakhir . "</td>";
                                                    echo "<td>" . $row["nik"] . "</td>";
                                                    echo "<td>" . $row["sex"] . "</td>";
                                                    echo "<td>" . $row["birthplace"] . "</td>";
                                                    echo '<td style="text-align:center; white-space: normal;">' . date('d-m-Y', strtotime($row['birthdate'])) . '</td>';
                                                    echo "<td>" . $row["phone_number"] . "</td>";
                                                    echo "<td>" . $row["address"] . "</td>";
                                                    echo "<td>";
                                                    if (!empty($row['photokaryawan'])) {
                                                        $photoPath = "uploads/photokaryawan/{$row['photokaryawan']}";
                                                        echo "<a href='{$photoPath}' data-fancybox='gallery'>";
                                                        echo "<img src='{$photoPath}' alt='Foto Karyawan' style='max-width: 100px; max-height: 100px;'>";
                                                        echo "</a>";
                                                    } else {
                                                        echo "Tidak ada foto";
                                                    }
                                                    echo "</td>";
                                                    echo "<td nowrap>
                                                            <a href='?action=edit&nik=" . $row["nik"] . "' class='btn btn-info'><i class='fas fa-edit'></i> Edit</a> 
                                                            <a href='?action=delete&nik=" . $row["nik"] . "' class='btn btn-danger'><i class='fas fa-trash'></i> Delete</a></td>";
                                                    $nomorUrutTerakhir++;
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "0 results";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/venobox@1.9.3/dist/venobox/venobox.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
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
                    $('#karyawanTable').DataTable({
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
                    $('.venobox').venobox();
                });
            </script>
                <script>
                    function toggleThumbnailDetails(photoId) {
                        var thumbnail = document.getElementById('thumbnail_' + photoId);
                        var details = document.getElementById('details_' + photoId);

                        if (details.style.display === 'none') {
                            details.style.display = 'block';
                            thumbnail.style.display = 'none';
                        } else {
                            details.style.display = 'none';
                            thumbnail.style.display = 'block';
                        }
                    }
                </script>
</body>
</html>