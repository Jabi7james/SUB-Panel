<?php
session_start();
include 'db.php';
$req_user = $_GET['username'];

$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$req_user]);
$u = $stmt->fetch();
if (!$u) { die("<h2 style='color:white;text-align:center;margin-top:50px;'>کاربر یافت نشد</h2>"); }

// Login Logic
if (isset($_POST['user_pass'])) {
    if (password_verify($_POST['pass'], $u['password'])) {
        $_SESSION['user_auth_'.$req_user] = true;
    } else { $error = "رمز عبور اشتباه است"; }
}

// Show Login Form
if (!isset($_SESSION['user_auth_'.$req_user])) {
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود <?php echo $req_user; ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body>
    <main style="justify-content: center; display: flex; align-items: center;">
        <div class="glass-panel text-center" style="width: 90%; max-width: 400px;">
            <h3 class="mb-4 text-white">ورود: <?php echo $req_user; ?></h3>
            <form method="POST">
                <input type="password" name="pass" class="input-glass" placeholder="رمز عبور" required>
                <button name="user_pass" class="btn-custom btn-lg-custom btn-orange w-100 mt-3">ورود</button>
            </form>
        </div>
    </main>
</body>
</html>
<?php exit; } 

// *** تغییر مهم: اضافه کردن ORDER BY title ASC برای مرتب‌سازی الفبایی ***
$links = $db->prepare("SELECT * FROM links WHERE user_id = ? ORDER BY title ASC");
$links->execute([$u['id']]);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل <?php echo $u['username']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
</head>
<body>
    <main class="container pt-5">
        <h3 class="page-title">دریافت لینک سابسکریپشن</h3>
        
        <!-- باکس نام یوزر و خروج -->
        <div class="glass-panel d-flex justify-content-between align-items-center gap-2">
            <div class="name-badge flex-grow-1"><?php echo $u['username']; ?></div>
            <a href="../logout" class="btn-custom btn-lg-custom btn-red px-4">خروج</a>
        </div>

        <!-- حلقه برای نمایش هر لینک در یک باکس جداگانه -->
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?php foreach ($links->fetchAll() as $l): ?>
                
                <!-- شروع باکس جداگانه برای هر لینک -->
                <div class="glass-panel">
                    
                    <!-- نام لینک (ردیف بالا) -->
                    <div class="name-badge">
                        <?php echo $l['title']; ?>
                    </div>
                    
                    <!-- دکمه‌ها (ردیف پایین - کنار هم) -->
                    <div class="btn-row">
                        <button onclick="showQR('<?php echo $l['url']; ?>')" class="btn-custom btn-action btn-orange flex-fill">
                            QR Code <i class="fas fa-qrcode ms-2"></i>
                        </button>
                        <button onclick="copyTxt('<?php echo $l['url']; ?>')" class="btn-custom btn-action btn-orange flex-fill">
                            Copy <i class="fas fa-copy ms-2"></i>
                        </button>
                    </div>

                </div>
                <!-- پایان باکس -->

                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-box"><i class="fas fa-heart"></i><span>Made with Love</span></div>
        <div class="footer-box"><i class="fas fa-laptop"></i><span>Up to Date</span></div>
    </footer>

    <!-- QR Modal -->
    <div id="qrModal" onclick="this.style.display='none'" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:9999; align-items:center; justify-content:center;">
        <div class="bg-white p-4 rounded-4 text-center">
            <div id="qrcode"></div>
            <p class="mt-3 text-dark fw-bold">بستن</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
    function copyTxt(text) { navigator.clipboard.writeText(text); alert("Copied!"); }
    function showQR(text) {
        document.getElementById('qrcode').innerHTML = "";
        new QRCode(document.getElementById("qrcode"), { text: text, width: 250, height: 250 });
        document.getElementById('qrModal').style.display = 'flex';
    }
    </script>
</body>
</html>