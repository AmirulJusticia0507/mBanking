<?php
// Fungsi untuk mendapatkan MAC address berdasarkan IP address
function getMacAddress($ipAddress) {
    $output = shell_exec("arp -a " . escapeshellarg($ipAddress));
    $pattern = "/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/";
    if (preg_match($pattern, $output, $matches)) {
        return $matches[0];
    } else {
        return false;
    }
}

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=index.php');
    exit;
}

include 'konekke_local.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Bersihkan input dari potensi risiko SQL injection
    $usernameOrFullname = cleanInput($_POST['usernameOrFullname']);
    $password = cleanInput($_POST['password']);

    // Dapatkan IP address pengguna
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Dapatkan MAC address berdasarkan IP address
    $macAddress = getMacAddress($ipAddress);

    // Dapatkan informasi perangkat (Device) yang digunakan
    $device = $_SERVER['HTTP_USER_AGENT'];

    // Query untuk mencari user berdasarkan username atau fullname
    $query = "SELECT UserID, username, password, fullname FROM users WHERE username = ? OR fullname = ?";
    $stmt = $koneklocalhost->prepare($query);
    $stmt->bind_param("ss", $usernameOrFullname, $usernameOrFullname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Memeriksa kecocokan password dengan hash yang disimpan
        if (password_verify($password, $row['password'])) {
            // Simpan informasi perangkat, MAC address, dan IP address ke dalam session untuk digunakan nanti
            $_SESSION['device'] = $device;
            $_SESSION['mac'] = $macAddress;
            $_SESSION['ip'] = $ipAddress;

            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['UserID'] = $row['UserID'];

            // Simpan tanggal login ke dalam database
            date_default_timezone_set('Asia/Jakarta');
            $loginDate = date('Y-m-d H:i:s');
            $userId = $row['UserID'];

            $updateQuery = "UPDATE users SET login_date = ?, device = ? WHERE UserID = ?";
            $updateStmt = $koneklocalhost->prepare($updateQuery);
            $updateStmt->bind_param("ssi", $loginDate, $device, $userId);
            $updateStmt->execute();

            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}

function cleanInput($input)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Hapus script
        '@<[\/\!]*?[^<>]*?>@si',            // Hapus tag HTML
        '@<style[^>]*?>.*?</style>@siU',    // Hapus style tag
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Hapus komentar
    );
    $output = preg_replace($search, '', $input);
    return $output;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes">
    <title>Login SuperApps Amirul Putra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="icon" href="img/visiting_new.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
        *{
        margin: 0;
        padding: 0;
        /* user-select: none; */
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        }
        html,body{
        height: 100%;
        }
        body{
        display: grid;
        place-items: center;
        background: #dde1e7;
        text-align: center;
        }
        .content{
        width: 330px;
        padding: 40px 30px;
        background: #dde1e7;
        border-radius: 10px;
        box-shadow: -3px -3px 7px #ffffff73,
                    2px 2px 5px rgba(94,104,121,0.288);
        }
        .content .text{
        font-size: 33px;
        font-weight: 600;
        margin-bottom: 35px;
        color: #595959;
        }
        .field{
        height: 50px;
        width: 100%;
        display: flex;
        position: relative;
        }
        .field:nth-child(2){
        margin-top: 20px;
        }
        .field input{
        height: 100%;
        width: 100%;
        padding-left: 45px;
        outline: none;
        border: none;
        font-size: 18px;
        background: #dde1e7;
        color: #595959;
        border-radius: 25px;
        box-shadow: inset 2px 2px 5px #BABECC,
                    inset -5px -5px 10px #ffffff73;
        }
        .field input:focus{
        box-shadow: inset 1px 1px 2px #BABECC,
                    inset -1px -1px 2px #ffffff73;
        }
        .field span{
        position: absolute;
        color: #595959;
        width: 50px;
        line-height: 50px;
        }
        .field label{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 45px;
        pointer-events: none;
        color: #666666;
        }
        .field input:valid ~ label{
        opacity: 0;
        }
        .forgot-pass{
        text-align: left;
        margin: 10px 0 10px 5px;
        }
        .forgot-pass a{
        font-size: 16px;
        color: #3498db;
        text-decoration: none;
        }
        .forgot-pass:hover a{
        text-decoration: underline;
        }
        button{
        margin: 15px 0;
        width: 100%;
        height: 50px;
        font-size: 18px;
        line-height: 50px;
        font-weight: 600;
        background: #dde1e7;
        border-radius: 25px;
        border: none;
        outline: none;
        cursor: pointer;
        color: #595959;
        box-shadow: 2px 2px 5px #BABECC,
                    -5px -5px 10px #ffffff73;
        }
        button:focus{
        color: #3498db;
        box-shadow: inset 2px 2px 5px #BABECC,
                    inset -5px -5px 10px #ffffff73;
        }
        .sign-up{
        margin: 10px 0;
        color: #595959;
        font-size: 16px;
        }
        .sign-up a{
        color: #3498db;
        text-decoration: none;
        }
        .sign-up a:hover{
        text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="col-md-6" align="center">
            <img src="img/visiting_new.png" alt="Image" class="img-fluid" style="width:100%">
        </div>
        <!-- <div class="text">Login <span style="color:green">E-Visit</span> <span style="color:#3498db;"><b>Remidial</b></span></div> -->
        <div class="text">Login <span style="color:green">SuperApps</span></div>
        <form action="#" method="post">
            <?php if (isset($error)) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="field">
                <input type="text" name="usernameOrFullname" required>
                <span class="fas fa-user"></span>
                <label>Username or Fullname</label>
            </div>
            <div class="field">
                <input type="password" name="password" required>
                <span class="fas fa-lock"></span>
                <label>Password</label>
            </div>
            <div class="forgot-pass">
                <a href="forgot_password.php?page=forgot_password">Forgot Password?</a>
            </div>
            <button type="submit">Sign in</button>
            <div class="sign-up">
                Not a member?
                <a href="signup.php?page=signup">Signup now</a>
            </div>
        </form>
    </div>
</body>
</html>
