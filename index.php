<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

// Sambungkan ke database
include 'konekke_local.php';

// Kueri untuk mengambil informasi pengguna
$userID = $_SESSION['user_id'];
$userQuery = "SELECT Username FROM users WHERE UserID = ?";
$userStmt = $koneklocalhost->prepare($userQuery);
$userStmt->bind_param("i", $userID);
$userStmt->execute();
$userResult = $userStmt->get_result();

// Periksa apakah pengguna ditemukan
if ($userResult->num_rows === 0) {
    // Jika tidak ditemukan, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil data pengguna
$userData = $userResult->fetch_assoc();
$userStmt->close();

// Kueri untuk mengambil informasi saldo rekening
$accountQuery = "SELECT AccountID, AccountType, Balance FROM accounts WHERE UserID = ?";
$accountStmt = $koneklocalhost->prepare($accountQuery);
$accountStmt->bind_param("i", $userID);
$accountStmt->execute();
$accountResult = $accountStmt->get_result();

// Ambil data saldo rekening
$accounts = [];
while ($row = $accountResult->fetch_assoc()) {
    $accounts[] = $row;
}
$accountStmt->close();

// Kueri untuk mengambil informasi transaksi
$transactionQuery = "SELECT TransactionID, SourceAccountID, DestinationAccountID, Amount, TransactionType, TransactionDate, Description FROM transactions WHERE SourceAccountID IN (SELECT AccountID FROM accounts WHERE UserID = ?)";
$transactionStmt = $koneklocalhost->prepare($transactionQuery);
$transactionStmt->bind_param("i", $userID);
$transactionStmt->execute();
$transactionResult = $transactionStmt->get_result();

// Ambil data transaksi
$transactions = [];
while ($row = $transactionResult->fetch_assoc()) {
    $transactions[] = $row;
}
$transactionStmt->close();

// Tutup koneksi database
$koneklocalhost->close();
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
                        <li class="breadcrumb-item active" aria-current="page">Form Kunjungan</li>
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

                <h2>Welcome, <?php echo $userData['Username']; ?></h2>
                <!-- Tampilkan saldo rekening utama -->
                <?php foreach ($accounts as $account): ?>
                    <?php if ($account['AccountType'] == 'Utama'): ?>
                        <p>Saldo Rekening Utama: <?php echo $account['Balance']; ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Tampilkan rekening yang dimiliki -->
                <h3>Rekening yang Anda Miliki:</h3>
                <ul>
                    <?php foreach ($accounts as $account): ?>
                        <li><?php echo $account['AccountType']; ?>: <?php echo $account['Balance']; ?></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Tampilkan tombol aksi -->
                <div>
                    <button onclick="location.href='transfer.php'">Transfer</button>
                    <button onclick="location.href='qris.php'">QRIS</button>
                    <button onclick="location.href='mutasi.php'">Mutasi</button>
                    <button onclick="location.href='aktivitas_payment.php'">Aktivitas Payment</button>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Tambahkan Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>

    
    <script>
        $(document).ready(function() {
            // Tambahkan event click pada tombol pushmenu
            $('.nav-link[data-widget="pushmenu"]').on('click', function() {
                // Toggle class 'sidebar-collapse' pada elemen body
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
</body>
</html>