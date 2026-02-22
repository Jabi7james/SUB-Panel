<?php
session_start();
include 'db.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: admin");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body>
    <main style="justify-content: center; display: flex; align-items: center; min-height: 100vh;">
        <div class="glass-panel text-center" style="width: 90%; max-width: 400px;">
            <h3 class="mb-4 fw-bold text-white">ورود به پنل مدیریت</h3>
            
            <?php if($error): ?>
                <div class="alert alert-danger py-2 mb-3 rounded-3" style="font-size: 0.9rem;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="input-glass" placeholder="نام کاربری" required>
                </div>
                
                <div class="password-wrapper mb-3">
                    <input type="password" name="password" id="adminPass" class="input-glass" placeholder="رمز عبور" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('adminPass', this)"></i>
                </div>

                <button type="submit" class="btn-custom btn-lg-custom btn-orange w-100 mt-2">ورود</button>
            </form>
        </div>
    </main>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
