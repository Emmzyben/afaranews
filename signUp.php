
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

$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'username',
        'email',
        'password'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errorMessage .= "Field '$field' is missing or empty. ";
        }
    }

    // Check if username and email are unique
    $checkExistingQuery = $con->prepare('SELECT COUNT(*) FROM Users WHERE username = ? OR email = ?');
    $checkExistingQuery->bind_param('ss', $_POST['username'], $_POST['email']);
    $checkExistingQuery->execute();
    $checkExistingQuery->bind_result($existingAccountsCount);
    $checkExistingQuery->fetch();
    $checkExistingQuery->close();

    if ($existingAccountsCount > 0) {
        $errorMessage .= "An account with the same username or email already exists.";
    }

    if ($errorMessage === '') {
        // Hash the password
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user data into the Users table
        $stmt = $con->prepare('INSERT INTO Users (username, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $_POST['username'], $_POST['email'], $hashedPassword);
        
        if ($stmt->execute()) {
            $successMessage = "Registration successful!";
        } else {
            $errorMessage .= 'Registration failed, please try again';
        }

        $stmt->close();
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="reg.css">
    <link rel="shortcut icon" href="images/logo.jpg">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header id="header">
        <div class="div1">
          <img src="images/logo.png">
        </div>
        <div class="div2">
          <ul>
            <li><a href="index.php">Home </a></li>
            <li class="nav-container">
                <span id="hoverer">News Category</span> 
                 <ul id="dropdown">
                  <li><a href="politics.php">Politics</a></li> 
                  <li><a href="business.php">Business</a></li> 
                  <li><a href="sports.php">Sports</a></li>
                  <li><a href="community.php">Community</a></li>
                 </ul>
               </li>
            <li><a href="login.php" >Sign In</a></li>
            <li><a id="contactbtn" href="signUp.php">Sign Up</a></li>
          </ul>
        </div>
      </header>
      

      <aside> 
        <div>
          <img src="images/logo.png" alt="" >
      </div> 
          <div onclick="openNav()" >
              <div class="container" onclick="myFunction(this)" id="sideNav">
                  <div class="bar1"></div>
                  <div class="bar2"></div>
                  <div class="bar3"></div>
                </div>
              </div>
      </aside>
        
    <nav style="z-index: 1;">
        <div id="mySidenav" class="sidenav">
            <img src="images/logo.png" alt="">
            <a href="index.php">Home</a>
            <a class="dropdown-item" onclick="toggleDropdown()" >
                News Category +
                   <div class="sub-menu1" style="display: none;transition: 0.5s;background-color: #171718;
                   color: #fff;">
                  <a href="politics.php">Politics</a>
                  <a href="business.php">Business</a>
                  <a href="sports.php">Sports</a>
                  <a href="community.php">Community</a>
                   </div>
                 </a>
              
                 <script>
                   function toggleDropdown() {
                     const subMenu = document.querySelector('.sub-menu1');
                     subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
                   }
                 </script>
          <a href="login.php" >Sign In</a>
            <a href="signUp.php">Sign UP</a>
        </div>
        <script>
        
    function myFunction(x) {
        x.classList.toggle("change");
      }
    
      var open = false;
    
    function openNav() {
        var sideNav = document.getElementById("mySidenav");
        
        if (sideNav.style.width === "0px" || sideNav.style.width === "") {
            sideNav.style.width = "250px";
            open = true;
        } else {
            sideNav.style.width = "0";
            open = false;
        }
    }
        </script>
    </nav>
    <main>
      <div id="form">
   <div id="f1"><h2>Sign Up</h2></div>
   <div id="f2">
     <form action="" method="post">
      <input type="text" name="username" id="" placeholder="Name">
       <input type="email" name="email" id="" placeholder="Email"><br>
       <input type="password" name="password" id="" placeholder="Password"><br>
       <input type="submit" name="" id="subm" value="Sign Up">
       <p>Already a Member? <a href="login.php">Sign-in now</a></p>
       <?php
        if ($errorMessage !== '') {
            echo '<div style="color: red;text-align:center">' . $errorMessage . '</div>';
        }

        if ($successMessage !== '') {
            echo '<div style="color: #08b197;text-align:center">' . $successMessage . '</div>';
        }
    ?>
     </form>
   </div>
      </div>
       </main>

    <script src="script.js"></script>
</body>
</html>