<?php
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

// Modify the SQL query to retrieve posts with the category "Business" sorted by the latest post
$sql = "SELECT * FROM posts WHERE category = 'sports' ORDER BY post_date DESC";

// Execute the query
$result = mysqli_query($connection, $sql);

// Check if there are results
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports news</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
    <link rel="shortcut icon" href="images/logo.jpg">
    <link rel="stylesheet" href="style.css">
    <style>
    .post-container {
            display: flex;
            flex-direction: column;
            align-items: left;
            justify-content: center;
         margin-left:-20px;
        }
        #cool{
          padding: 10px;
          padding-right:20px;
          color: black;
        } 
        #cooler{
          margin-bottom: 30px;
        }
        #color{
          color:#012A6A;
        }
 #img {
           width: 500px;
           height: 300px;
            max-width: 500px;
            max-height: 300px;
            margin-top:10px;
        }
        ul li{
  list-style-type: none;
}
#topper{
  margin-top: 20px;
  background-color:  #0e0e88;
  color: white;
  padding: 10px;
  font-weight: bold;
  font-size:17px;
   margin-left:40px;
   margin-right:40px;
    text-align:center;
}
        .newIMG{
             width:70px;
                margin-top:-40px;
                margin-left:10px;
                }
@media screen and (max-width:800px) {
  #img{
    width:100%;
    margin-left:-10px;
  }
        #topper{
margin-left:0;
   margin-right:0;
   width:auto;
}
  
}
</style>
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
    session_start();

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
    <div class="post-container"> 
        <ul style="display: flex; flex-wrap: wrap;">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <li id='cool' style='flex: 1; margin: 10px;'>
               <?php if (!empty($row['file'])): ?>
    <?php $fileExtension = pathinfo($row['file'], PATHINFO_EXTENSION); ?>
    <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
        <img src='<?php echo $row['file']; ?>' alt='Post Image' id='img' style='max-width: 100px; max-height: 100px;'>
    <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mpeg'])): ?>
        <video controls style="max-width: 100px; max-height: 100px;">
            <source src="<?php echo $row['file']; ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php endif; ?>
<?php endif; ?>

                    <div id='cooler'>
                      <h3><b><?php echo $row['category']; ?></b></h3>
                        <h1 id='color'><b><?php echo $row['title']; ?></b></h1>
                        <p>by <?php echo $row['author']; ?></p>
                    </div>
                    <p>Posted on: <?php echo $row['post_date']; ?></p>
                    <p><a href="more_Details.php?post_id=<?php echo $row['post_id']; ?>">Read more</a></p>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>


<?php
// Close the database connection
mysqli_close($connection);
?>

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