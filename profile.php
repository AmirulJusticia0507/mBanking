<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['user_id'])) {
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
    <title>SuperApps Amirul Putra</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Profile Account</li>
                    </ol>
                </nav>
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    $currentPage = basename($_SERVER['PHP_SELF']); // Mendapatkan halaman saat ini
                
                    switch ($page) {
                        // case 'logkunjungan':
                        //     if ($currentPage !== 'logkunjungan.php') {
                        //         header("Location: logkunjungan.php?page=logkunjungan");
                        //         exit;
                        //     }
                        //     break;
                
                        // case 'kunjungan':
                        //     if ($currentPage !== 'kunjungan.php') {
                        //         header("Location: kunjungan.php?page=kunjungan");
                        //         exit;
                        //     }
                        //     break;
                
                        // case 'laporangrafik':
                        //     if ($currentPage !== 'laporangrafik.php') {
                        //         header("Location: laporangrafik.php?page=laporangrafik");
                        //         exit;
                        //     }
                        //     break;
                
                
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
                        <?php
                        // Include file koneksi database
                        include 'konekke_local.php';

                        // Function untuk membuat salt random
                        function generateSalt() {
                            return bin2hex(random_bytes(16));
                        }

                        // Function untuk melakukan hashing password dengan salt
                        function hashPassword($password, $salt) {
                            return hash('sha256', $password . $salt);
                        }

                        // Function untuk menambahkan user baru ke database
                        function addUser($username, $password, $fullname, $birthplace, $birthdate, $sex, $photo) {
                            global $koneklocalhost;

                            // Generate salt baru
                            $salt = generateSalt();
                            // Hash password dengan salt
                            $hashedPassword = hashPassword($password, $salt);

                            // Prepared statement untuk mencegah SQL injection
                            $stmt = $koneklocalhost->prepare("INSERT INTO users (username, password, salt, fullname, birthplace, birthdate, sex, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("ssssssss", $username, $hashedPassword, $salt, $fullname, $birthplace, $birthdate, $sex, $photo);
                            $stmt->execute();
                            $stmt->close();
                        }

                        // Function untuk mengubah password user di database
                        function updateUserPassword($id, $password) {
                            global $koneklocalhost;

                            // Generate salt baru
                            $salt = generateSalt();
                            // Hash password baru dengan salt
                            $hashedPassword = hashPassword($password, $salt);

                            // Prepared statement untuk mencegah SQL injection
                            $stmt = $koneklocalhost->prepare("UPDATE users SET password = ?, salt = ? WHERE id = ?");
                            $stmt->bind_param("ssi", $hashedPassword, $salt, $id);
                            $stmt->execute();
                            $stmt->close();
                        }

                        // Function untuk mengambil data user dari database
                        function getUser($id) {
                            global $koneklocalhost;

                            // Prepared statement untuk mencegah SQL injection
                            $stmt = $koneklocalhost->prepare("SELECT * FROM users WHERE id = ?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $stmt->close();

                            return $row;
                        }

                        // Function untuk menampilkan semua data user
                        function displayUsers() {
                            global $koneklocalhost;

                            // Prepared statement untuk mencegah SQL injection
                            $stmt = $koneklocalhost->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Menampilkan data user ke dalam tabel DataTables
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['fullname'] . "</td>";
                                    echo "<td>" . $row['birthplace'] . "</td>";
                                    echo "<td>" . $row['birthdate'] . "</td>";
                                    echo "<td>" . $row['sex'] . "</td>";
                                    echo "<td>" . $row['photo'] . "</td>";
                                    echo "<td>" . $row['created_at'] . "</td>";
                                    echo "<td>" . $row['updated_at'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>";
                                    echo "<button type='button' class='btn btn-primary btn-sm editUserBtn' data-toggle='modal' data-target='#editUserModal' data-id='" . $row['id'] . "'>Edit</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                        ?>

                        <!-- Form untuk menambahkan dan mengubah data user -->
                        <div class="card">
                            <div class="card-body">
                                <fieldset>
                                    <legend>Information :</legend>
                                    <form id="userForm" action="config/entryaccounts.php" method="post">
                                        <input type="hidden" name="action" id="action" value="add">
                                        <input type="hidden" name="id" id="id">
                                        <div class="row">
                                            <div class="col md-3">
                                                <div class="form-group">
                                                    <label for="username">Username:</label>
                                                    <input type="text" class="form-control" id="username" name="username">
                                                </div>
                                            </div>
                                            <div class="col md-3">
                                                <div class="form-group">
                                                    <label for="password">Password:</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                                            <i class="far fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fullname">Fullname:</label>
                                            <input type="text" class="form-control" id="fullname" name="fullname">
                                        </div>
                                        <div class="row">
                                            <div class="col md-3">
                                                <div class="form-group">
                                                    <label for="birthplace">Birthplace:</label>
                                                    <input type="text" class="form-control" id="birthplace" name="birthplace">
                                                </div>
                                            </div>
                                            <div class="col md-3">
                                                <div class="form-group">
                                                    <label for="birthdate">Birthdate:</label>
                                                    <input type="date" class="form-control" id="birthdate" name="birthdate">
                                                </div>
                                            </div>
                                            <div class="col md-3">
                                                <div class="form-group">
                                                    <label for="sex">Sex:</label>
                                                    <select class="form-control" id="sex" name="sex">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="photo">Photo:</label>
                                            <input type="file" class="form-control-file" id="photo" name="photo">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </fieldset>
                            </div>
                        </div>

                        <!-- Tabel untuk menampilkan data user -->
                        <div class="card">
                            <div class="card-body">
                                <table id="userTable" class="display table table-bordered table-striped table-hover responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Fullname</th>
                                            <th>Birthplace</th>
                                            <th>Birthdate</th>
                                            <th>Sex</th>
                                            <th>Photo</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data user akan ditampilkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal untuk mengedit data user -->
                        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form untuk mengubah password user -->
                                        <form id="editPasswordForm">
                                            <div class="form-group">
                                                <label for="newPassword">New Password:</label>
                                                <input type="password" class="form-control" id="newPassword" name="newPassword">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Change Password</button>
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
            <script type="text/javascript" charset="utf8"
                src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
                    $('#userTable').DataTable();

                    // Event handler untuk tombol Edit pada setiap baris tabel
                    $('.editUserBtn').click(function () {
                        var id = $(this).data('id');

                        // Ambil data user berdasarkan ID
                        $.ajax({
                            url: 'config/getUser.php',
                            type: 'POST',
                            data: { id: id },
                            success: function (response) {
                                var user = JSON.parse(response);

                                // Isi form edit dengan data user
                                $('#id').val(user.id);
                                $('#newPassword').val('');
                            }
                        });
                    });

                    // Event handler untuk form submit
                    $('#editPasswordForm').submit(function (e) {
                        e.preventDefault(); // Mencegah form submit secara default

                        // Ambil nilai input
                        var id = $('#id').val();
                        var newPassword = $('#newPassword').val();

                        // Kirim data ke server untuk mengubah password
                        $.ajax({
                            url: 'config/updateUserPassword.php',
                            type: 'POST',
                            data: {
                                id: id,
                                newPassword: newPassword
                            },
                            success: function (response) {
                                // Tampilkan pesan sukses atau error
                                if (response === 'success') {
                                    alert('Password updated successfully');
                                } else {
                                    alert('Failed to update password');
                                }
                            }
                        });
                    });
                });
            </script>
            <script>
                document.getElementById('togglePassword').addEventListener('click', function() {
                    var passwordInput = document.getElementById('password');
                    var icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('far', 'fa-eye');
                        icon.classList.add('fas', 'fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fas', 'fa-eye-slash');
                        icon.classList.add('far', 'fa-eye');
                    }
                });
            </script>

    </body>
</html>