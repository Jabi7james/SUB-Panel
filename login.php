<?php
session_start();
include 'db.php';

// اگر قبلا ادمین لاگین کرده، مستقیم برود به پنل
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: admin");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // فقط به دنبال ادمین بگرد
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $user['username'];
        header("Location: admin");
        exit;
    } else {
        $error = "نام کاربری یا رمز عبور اشتباه است";
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود مدیریت</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body>
    <main>
        <div class="glass-panel text-center">
            <h2 class="mb-4 fw-bold text-white">ورود به پنل مدیریت</h2>
            
            <?php if($error): ?>
                <div class="alert alert-danger p-2 mb-3 rounded-3"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="username" class="input-glass" placeholder="نام کاربری" required>
                <input type="password" name="password" class="input-glass" placeholder="رمز عبور" required>
                <button type="submit" class="btn-custom btn-orange mt-3">ورود</button>
            </form>
        </div>
    </main>
</body>
</html>