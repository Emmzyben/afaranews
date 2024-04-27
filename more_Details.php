<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root'; // Replace with your database username
$DATABASE_PASS = '';      // Replace with your database password
$DATABASE_NAME = 'afaranew';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment']) && !empty($_POST['comment'])) {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $post_id = $_POST['post_id'];
            $commenter_id = $_SESSION['user_id'];
            $comment_text = $_POST['comment'];

            $stmt = $con->prepare('INSERT INTO Comments (post_id, commenter_id, comment_text) VALUES (?, ?, ?)');
            $stmt->bind_param('iis', $post_id, $commenter_id, $comment_text);

            if ($stmt->execute()) {
                $successMessage = "Comment added successfully!";
            } else {
                $errorMessage = 'Failed to add comment';
            }

            $stmt->close();
        } else {
            $errorMessage = 'You need to log in to comment.';
            header("location: login.php");
            exit;
        }
    }
}

if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch post details
    $stmt = $con->prepare('SELECT * FROM Posts WHERE post_id = ?');
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();

    // Fetch comments for this post along with commenter's username
    $stmt = $con->prepare('SELECT Comments.*, Users.username FROM Comments INNER JOIN Users ON Comments.commenter_id = Users.user_id WHERE post_id = ?');
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $comments = $stmt->get_result();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>post details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
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
    <?php

    // Check if the user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // If logged in, show the username and Sign Out link
        echo '<li><a href="#">  Welcome ' . $_SESSION['username'] . '!</a></li>';
        echo '<li><a href="logout.php">Sign Out</a></li>';
    } else {
        // If not logged in, show Sign In and Sign Up links
        echo '<li><a href="login.php">Sign In</a></li>';
        echo '<li><a id="contactbtn" href="signUp.php">Sign Up</a></li>';
    }
    ?>
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
    <?php if (!empty($post['file'])): ?>
        <?php $fileExtension = pathinfo($post['file'], PATHINFO_EXTENSION); ?>
        <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
            <img src="<?php echo $post['file']; ?>" alt="Post Image" style="width:300px">
        <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mpeg'])): ?>
            <video controls style="max-width: 100%;">
                <source src="<?php echo $post['file']; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        <?php endif; ?>
    <?php endif; ?>
    <h1><?php echo $post['title']; ?></h1>
    <p>by <?php echo $post['author']; ?></p>
    <p>Date: <?php echo $post['post_date']; ?></p>
    <p><?php echo nl2br($post['content']); ?></p>

    

    <h2>Comments</h2>
    <?php if ($comments->num_rows > 0): ?>
        <ul>
            <?php while ($comment = $comments->fetch_assoc()): ?>
                <li>
                    <p><b><?php echo $comment['username']; ?>:</b> <?php echo $comment['comment_text']; ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>


    <h2>Add Comment</h2>
    <form action="" method="post">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <textarea name="comment" rows="4" cols="50" placeholder="Enter your comment"></textarea><br>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <input type="submit" value="Add Comment">
        <?php else: ?>
            <p>You need to <a href="login.php">log in</a> to add a comment.</p>
        <?php endif; ?>
    </form>
    </main>
    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
    <div id="inbox">
        <h2>Get the best viral stories straight into your inbox!</h2>
        <a href="signUp.php" id="submit">SIGN UP NOW!</a>
    </div>
    <?php endif; ?>

    <div id="bg">
    <footer>
      <div>
       <h3>ABOUT US</h3> 
       <hr>
<p>Afara News and Afara TV are online News medium with unbiased reporting.</p>

<p>Discussions are no hold barred.</p>

<p>You have a right to be heard because we bridge the information divide.</p>

<p>Afaranews is owned by <b>HIBERNATION DIGITAL ACADEMY</b></p>

 
      </div>
      <div>
        <h3>FIND US ON FACEBOOK</h3>
        <hr>
        <div style="width: 100%;height: 100%;"></div>
      </div>
      <div>
        <h3>ADVERTISE WITH US</h3>
        <hr>
        <p>Want to expose your Business to the world?</p>
         <p>Advertise your business on our platform at reduced price</p>
         <p>Fill the form to contact us now :</p>
         <form action="" id="form">
          <input type="text" name="name" id="" placeholder="Enter Name"><br>
          <input type="email" name="email" id="" placeholder="Email"><br>
          <input type="phone" name="phone" id="" placeholder="Phone number"><br>
          <input type="submit" name="" id="submit">
         </form>
      </div>
     
    </footer>
   
      <div id="last">
        <a href="#" ><i class="fa fa-facebook-official" style="font-size:24px;color:#ff1b00"></i> </a>
        <a  href="#" ><i class="fa fa-twitter-square" style="font-size:24px;color:#ff1b00"></i></a>
        <a  href="#" ><i class="fa fa-instagram" style="font-size:24px;color:#ff1b00"></i></a>
        <a href="#" ><i class="fa fa-linkedin-square" style="font-size:24px;color:#ff1b00"></i></a>
     </div>

  </div>
    <script src="script.js"></script>
</body>
</html>