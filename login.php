<?php
session_start();

$admin_user = 'admin';
$admin_pass = '1234';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yummy Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: #f0f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background: #fff;
      padding: 40px 50px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
      font-size: 28px;
      font-weight: 600;
    }

    .input-field {
      width: 100%;
      padding: 15px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      color: #333;
      background-color: #f8f8f8;
      transition: all 0.3s ease-in-out;
    }

    .input-field:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 10px rgba(76, 175, 80, 0.3);
    }

    .login-button {
      background: #4CAF50;
      color: white;
      padding: 15px;
      width: 100%;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease-in-out;
    }

    .login-button:hover {
      background: #45a049;
    }

    .error-message {
      color: red;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Admin Login</h2>
    <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="username" class="input-field" placeholder="Username" required><br>
      <input type="password" name="password" class="input-field" placeholder="Password" required><br>
      <input type="submit" class="login-button" value="Login">
    </form>
  </div>

</body>
</html>

