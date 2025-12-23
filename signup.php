<?php
session_start();
require_once "db.php"; // ต้องเป็น PDO

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email     = trim($_POST['email'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $age_group = (int)($_POST['age_group'] ?? 0);
    $role      = 1; // user

    // ตรวจข้อมูลว่าง
    if ($email === '' || $username === '' || $password === '' || $age_group === 0) {
        $_SESSION['error'] = "กรอกข้อมูลให้ครบ";
        header("Location: signup.php");
        exit;
    }

    // ===== เช็ค username ซ้ำ =====
    $stmt = $conn->prepare(
        "SELECT id FROM employees WHERE username = :username LIMIT 1"
    );
    $stmt->execute([
        'username' => $username
    ]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username ถูกใช้แล้ว";
        header("Location: signup.php");
        exit;
    }

    // ===== hash password =====
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // ===== insert ข้อมูล =====
    $stmt = $conn->prepare(
        "INSERT INTO employees (email, username, password, age_group, role)
         VALUES (:email, :username, :password, :age_group, :role)"
    );

    $result = $stmt->execute([
        'email'     => $email,
        'username'  => $username,
        'password'  => $hash,
        'age_group' => $age_group,
        'role'      => $role
    ]);

    if ($result) {
        header("Location: home.php");
        exit;
    } else {
        $_SESSION['error'] = "สมัครไม่สำเร็จ";
        header("Location: signup.php");
        exit;
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
    <title>Bootstrap 5</title>
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    





</body>
</head>
<body>
    <div class="login-card">
        <form method="POST" action="signup.php">
            <h1 class="form-title">สมัครสมาชิก</h1>
    

            <div class="input-group">
                <input type="text" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="text" name="username" placeholder="User Name" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <select name="age_group" required>
                    <option selected disabled>เลือกช่วงอายุ...</option>
                    <option value="1" class ="AGE1">6-12 ปี</option>
                    <option value="2" class ="AGE1">13-16 ปี</option>
                    <option value="3" class ="AGE1">18 ปี ขึ้นไป</option>
                </select>
            </div>

            <button type="submit" class="btn-signin">Sign up</button>

            <p class="signup-text">หากไม่มีบัญชีมีแล้ว<a href="signin.html">เข้าสู่ระบบ</a></p>
        </form>
    </div>
</body>
</html> 


