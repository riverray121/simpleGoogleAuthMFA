<?php
session_start();
require '../vendor/autoload.php';
require '../src/User.php';
require '../src/Auth.php';

$auth = new Auth();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $secret = $auth->register($username, $password);
        $_SESSION['username'] = $username;
        $message = 'Registration successful! Scan this QR code with Google Authenticator.';
        $qrCode = $auth->getQRCode($username);
    } elseif (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $code = $_POST['code'];
        if ($auth->login($username, $password, $code)) {
            $message = 'Login successful!';
        } else {
            $message = 'Invalid login credentials or TOTP code!';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MFA App</title>
</head>
<body>
    <h1>MFA App</h1>
    <h2>Register</h2>
    <form method="post">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit" name="register">Register</button>
    </form>

    <h2>Login</h2>
    <form method="post">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>TOTP Code: <input type="text" name="code" required></label><br>
        <button type="submit" name="login">Login</button>
    </form>

    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
        <?php if (isset($qrCode)) { ?>
            <img src="<?php echo $qrCode; ?>" alt="QR Code">
        <?php } ?>
    <?php } ?>
</body>
</html>
