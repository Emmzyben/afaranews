<?php
session_start();

// Check if admin is not logged in, then redirect to admin.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin.php');
    exit;
}

$servername = 'localhost';
$username = "root";
$password = "";
$database = "afaranew";

// Create a database connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    // File upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mpeg');

    if (in_array($fileType, $allowedTypes)) {
        // Upload file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Insert data into database
            $sql = "INSERT INTO posts (title, content, author, category, file, post_date) VALUES ('$title', '$content', '$author', '$category', '$targetFile', NOW())";

            if (mysqli_query($connection, $sql)) {
                echo "Post created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($connection);
            }
        } else {
            echo "Error uploading file";
        }
    } else {
        echo "File type not allowed";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="shortcut icon" href="images/logo.jpg">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reg.css">
    <style>
        main{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        body{
            background-color: #00EBC7;
        }
        #content{
    margin: 10px;
    border: 1px solid rgb(228, 223, 223);
    width: 300px;
    border-radius: 10px;
}
select{
    margin: 10px;
    border: 1px solid rgb(228, 223, 223);
    width: 300px;
    border-radius: 10px; 
    padding: 10px;
}
    </style>
</head>

<body >
<header id="header">
    <div class="div1">
      <img src="images/logo.png">
    </div>
    <div class="div2">
    <ul>
    <li style="border:1px solid white;padding:10px"><a href="adminlogout.php">Log Out </a></li>
    
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
<div id="f1"><h2>Add post</h2></div>
<div id="f2">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" required></textarea><br><br>
        
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" required><br><br>
        
        <label for="category">Category:</label><br>
        <select name="category" id="">
            <option value="">select</option>
            <option value="politics">Politics</option>
            <option value="business">Business</option>
            <option value="community">Community</option>
            <option value="sports">Sport</option>
        </select><br>
        
        <label for="file">File:</label><br>
        <input type="file" id="file" name="file"><br><br>
        
        <input type="submit" value="Submit" id="subm">
    </form>
</div>
   </div>
   
</main>
   


</body>
</html>
