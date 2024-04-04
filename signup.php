<?php
include 'konekke_local.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Bersihkan input dari potensi risiko SQL injection
    $username = cleanInput($_POST['username']);
    $password = cleanInput($_POST['password']);
    $fullname = cleanInput($_POST['fullname']);
    $email = cleanInput($_POST['email']);
    $phoneNumber = cleanInput($_POST['phone_number']);
    $address = cleanInput($_POST['address']);
    // Generate OTP
    $otp = generateOTP(); // Fungsi untuk generate OTP

    // Hashing password dengan bcrypt dan salt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Query untuk menyimpan user baru ke database
    $query = "INSERT INTO users (Username, PASSWORD, FullName, Email, PhoneNumber, Address, AdditionalAuthInfo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneklocalhost->prepare($query);
    $stmt->bind_param("sssssss", $username, $hashedPassword, $fullname, $email, $phoneNumber, $address, $otp);
    
    if ($stmt->execute()) {
        header('Location: login.php');
        exit;
    } else {
        $error = "Error creating user account";
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

function generateOTP() {
    // Generate random 6-digit OTP
    $otp = rand(100000, 999999);
    return $otp;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up SuperApps Amirul Putra</title>
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

        .verified {
            background-color: #4CAF50; /* Warna latar belakang hijau untuk menunjukkan verifikasi berhasil */
            color: white; /* Warna teks putih untuk kontras */
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="col-md-6" align="center">
            <img src="img/visiting_new.png" alt="Image" class="img-fluid" style="width:100%">
        </div>
        <div class="text">Signup <span style="color:green">SuperApps</span></div>
        <form action="#" method="post" onsubmit="return validateForm()">
            <?php if (isset($error)) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="field">
                <input type="text" name="username" required style="width: 100%;">
                <span class="fas fa-user"></span>
                <label>Username</label>
            </div>
            <div class="field">
                <input type="password" name="password" required style="width: 100%;">
                <span class="fas fa-lock"></span>
                <label>Password</label>
            </div>
            <div class="field">
                <input type="text" name="fullname" required style="width: 100%;">
                <span class="fas fa-user"></span>
                <label>Fullname</label>
            </div>
            <div class="field">
                <input type="email" name="email" required style="width: 100%;">
                <span class="fas fa-envelope"></span>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="text" name="phone_number" required>
                <span class="fas fa-phone"></span>
                <label>Phone Number</label>
            </div>
            <div class="field">
                <input type="text" name="address" required>
                <span class="fas fa-map-marker-alt"></span>
                <label>Address</label>
            </div>
            <div class="field">
                <input type="text" name="manual_otp" id="manual_otp" placeholder="Enter OTP" style="width: 100%;">
                <span class="fas fa-key"></span>
                <label>Kode OTP</label>
            </div>
            <button type="button" id="verify_btn" onclick="verifyOTP()">Verify OTP</button>
            <div id="otp_timer">Kode OTP akan diperbarui dalam 2 menit</div>
            <button type="submit">Signup</button>
            <div class="sign-up">
                Already a member?
                <a href="login.php">Sign in now</a>
            </div>
        </form>
    </div>
    <script>
        function validateForm() {
            var username = document.forms["signupForm"]["username"].value;
            var password = document.forms["signupForm"]["password"].value;
            var fullname = document.forms["signupForm"]["fullname"].value;
            var email = document.forms["signupForm"]["email"].value;
            var phone_number = document.forms["signupForm"]["phone_number"].value;
            var additional_auth_info = document.forms["signupForm"]["additional_auth_info"].value;
            var address = document.forms["signupForm"]["address"].value;

            if (username == "" || password == "" || fullname == "" || email == "" || phone_number == "" || additional_auth_info == "" || address == "") {
                alert("Semua kolom harus diisi");
                return false;
            }
        }
    </script>
<script>
    var timerInterval; // variabel global untuk menyimpan interval timer

    function updateOTP() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("manual_otp").value = this.responseText;
            }
        };
        xhttp.open("GET", "generate_otp.php", true);
        xhttp.send();
    }

    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        timerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = "Kode OTP akan diperbarui dalam " + minutes + ":" + seconds;

            if (--timer < 0) {
                timer = duration;
                updateOTP();
            }
        }, 1000);
    }

    window.onload = function () {
        var twoMinutes = 60 * 2,
            display = document.querySelector('#otp_timer');
        startTimer(twoMinutes, display);
        updateOTP(); // Panggil fungsi updateOTP untuk menampilkan OTP pertama kali halaman dimuat
    };

    function verifyOTP() {
        var manualOTP = document.getElementById("manual_otp").value;
        // Lakukan verifikasi OTP seperti sebelumnya

        // Ganti teks tombol dan hentikan timer jika verifikasi berhasil
        if (verificationSuccess) { // Ganti kondisi ini dengan verifikasi yang sesuai
            clearInterval(timerInterval); // Hentikan timer
            var verifyBtn = document.getElementById("verify_btn");
            verifyBtn.textContent = "Verified";
            verifyBtn.style.backgroundColor = "lightblue"; // Ganti warna latar belakang tombol menjadi biru muda
            verifyBtn.disabled = true;
            document.getElementById("otp_timer").textContent = ""; // Hapus teks timer
        }
    }
</script>


</body>
</html>
