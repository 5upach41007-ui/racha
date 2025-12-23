<?php
session_start();
require_once 'db.php';

$error = "";

// ===== เมื่อ submit ฟอร์ม =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "กรุณากรอก Username และ Password";
    } else {

        // ดึงข้อมูลผู้ใช้
        $stmt = $conn->prepare(
            "SELECT id, username, password, role 
             FROM employees 
             WHERE username = :username 
             LIMIT 1"
        );
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // ตรวจรหัสผ่าน
            if (password_verify($password, $user['password'])) {

                // เก็บ session
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];

                // แยกสิทธิ์
                if ($user['role'] == 1) {
                    header("Location: home.php");
                } else {
                    header("Location: admin.php");
                }
                exit;

            } else {
                $error = "รหัสผ่านไม่ถูกต้อง";
            }

        } else {
            $error = "ไม่พบบัญชีผู้ใช้";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Football Training</title>
    <link rel="stylesheet" href="signin.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-card">
        <form method="POST" action="signin.php">
            <h1 class="form-title">เข้าสู่ระบบ</h1>
            
            <div class="input-group">
                <input type="text" name="username" placeholder="User Name" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit"  name="login" class="btn-signin">Sign In</button>

            <p class="signup-text">หากยังไม่มีบัญชี <a href="signup.html">สมัครสมาชิก</a></p>
        </form>
    </div>
</body>
</html>
