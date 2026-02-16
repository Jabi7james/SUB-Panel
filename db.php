<?php
$db = new PDO('sqlite:database.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ساخت جداول در اولین اجرا
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT,
    role TEXT DEFAULT 'user'
)");

$db->exec("CREATE TABLE IF NOT EXISTS links (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    title TEXT,
    url TEXT,
    FOREIGN KEY(user_id) REFERENCES users(id)
)");

// ساخت پسورد ادمین پیش‌فرض (یوزر: admin / پسورد: 123456)
// بعدا می‌توانید این را عوض کنید
$checkAdmin = $db->query("SELECT * FROM users WHERE username = 'admin'")->fetch();
if (!$checkAdmin) {
    $pass = password_hash('123456', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (username, password, role) VALUES ('admin', '$pass', 'admin')");
}
?>