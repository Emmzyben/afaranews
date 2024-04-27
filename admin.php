<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'afaranew';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $Password = $_POST['password'];

    $stmt = $con->prepare('SELECT user_id, username, password FROM adminUser WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $password);
        $stmt->fetch();

        if ($Password === $password) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {
        echo 'Incorrect username and/or password!';
    }

    $stmt->close();
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="reg.css">
    <link rel="shortcut icon" href="images/logo.jpg">
    <link rel="stylesheet" href="style.css">
    <style>
      body{
        background-color: #00EBC7;
      }
    </style>
</head>
<body>
  
    <main>
   <div id="form">
<div id="f1"><h2>Admin Login</h2></div>
<div id="f2">
  <form action="" method="post">
    <input type="text" name="username" id="" placeholder="Username"><br>
    <input type="password" name="password" id="" placeholder="Password"><br>
    <input type="submit" name="" id="subm" value="Login"><br>
    <a href="index.php">Go to Home page</a>
  </form>
</div>
   </div>
    </main>
 

  
    <script src="script.js"></script>
</body>
</html>