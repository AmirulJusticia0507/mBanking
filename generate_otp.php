<?php
function generateOTP() {
    // Generate random 6-digit OTP
    $otp = rand(100000, 999999);
    return $otp;
}

// Generate new OTP
$new_otp = generateOTP();
echo $new_otp;
?>
