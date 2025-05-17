<html>
<head>
    <title>Generate OTP</title>
</head>
<body>
    <h1>Generate OTP</h1>
    <h3>Your Secret: <?php echo $secret; ?></h3>
    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code" />
    <form action="<?php echo site_url('welcome/validate'); ?>" method="post">
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" id="otp" required>
        <button type="submit">Validate</button>
    </form>
</body>
</html>
