<?php
//Basic function to sanitize input data
function validate($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = str_replace('\\', '', $data);
  $data = str_replace('/', '', $data);
  $data = str_replace("'", '', $data);
  $data = str_replace(";", '', $data);
  $data = str_replace("(", '', $data);
  $data = str_replace(")", '', $data);
  return $data;
}

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
require('../php/connect.php');
$query = "SELECT chapter FROM user_chapter_mapping WHERE username='$username'";
$resultChapter = mysqli_query($link, $query);
list($chapter) = mysqli_fetch_array($resultChapter);
$rank = $_SESSION['rank'];




?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../bootstrap-4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/styles.css">

  <title>TSABox</title>
</head>

<body>    
  <!-- Nav Bar -->
  <nav class="header bg-blue navbar navbar-expand-sm navbar-dark" style="min-height:95px; z-index: 1000;">
    <a class="navbar-brand" href="main.php">
      <div class="row">
        <div class="col nopadding">
          <img src="../images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
        </div>
        <a class="navbar-brand" href="main.php">TSABox</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                OfficerBox
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="president.php">President</a>
                <a class="dropdown-item" href="vice.php">Vice President</a>
                <a class="dropdown-item" href="secretary.php">Secretary</a>
                <a class="dropdown-item" href="treasurer.php">Treasurer</a>
                <a class="dropdown-item" href="reporter.php">Reporter</a>
                <a class="dropdown-item" href="parliamentarian.php">Parliamentarian</a>
              </div>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  EventBox
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="myevents.php">My Events</a>
                  <a class="dropdown-item" href="rules.php">Rules</a>
                  <a class="dropdown-item" href="selection.php">Event Selection</a>
                  <a class="dropdown-item" href="quiz.php">Interest Quiz</a>
                </div>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    SocialBox
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="profile.php">My Profile</a>
                    <a class="dropdown-item" href="chapter.php">My Chapter</a>
                    <a class="dropdown-item" href="social.php">Find Friends</a>
                  </div>
                </li>
                <?php if($rank == "adviser" || $rank == "admin") { ?>
                <li class="nav-item">
                  <a class="nav-link" href="../php/admin.php">
                    Admin
                  </a>
                <?php } ?>
                <li class="nav-item">
                  <a class="nav-link" href="../php/logout.php">
                    Logout
                  </a>
                </li>
              </ul>
            </div>
          </nav>

          <!-- Title -->
          <div class="container" id="content">
    <h1>Admin</h1>
    <small>Manage your chapter</small>

    <!-- Make User Officer -->
    <center>
    <div class = "container mt-5 contentcard">
      <h3 style="border-bottom:2px solid #CF0C0C">Transact</h3>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
        <div class="form-row">
          
          <div class="col-4">
            <small>User</small>
            <!--Give each user as an option-->
            <select id="username" name="username" class="form-control">
            
              <?php

              require('../php/connect.php');

              $query="SELECT username FROM user_chapter_mapping WHERE chapter=$chapter ORDER BY username ASC";

              $result = mysqli_query($link, $query);

              if (!$result){
                die('Error: ' . mysqli_error($link));
              } 

              while(list($user) = mysqli_fetch_array($result)){
                $query = "SELECT firstname,lastname FROM users WHERE username='$user'";
                $res=mysqli_query($link,$query);
                if(!$res){
                  die('Error: ' . mysqli_error($link));

                }
                list($first,$last)=mysqli_fetch_array($res);

                $query = "SELECT rank FROM ranks WHERE username='$user'";
                $r=mysqli_query($query);
                if(!$r){
                  die('Error: ' . mysqli_error($link));
                }

                list($rank) = mysqli_fetch_array($r);

                if($rank == 'member'){
                ?>

                <option><?php echo $first . ' ' . $last; ?></option>

                <?php
              }

              mysqli_close($link);

              ?>
            </select>
          </div>
          
        </div>
        <div class="form-row">
          <div class="col-4">
            <small></small><br>
            <input name="transact" type="submit" class="btn btn-primary" id="transact" value="Transact" class="form-control">
          </div>
        </div>
      </form>
    </div>

    
          </div>
          
        </div>
        </div>
      </center>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../js/jquery-3.3.1.slim.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
        <script src="../js/scripts.js"></script>
      </body>

      <footer <?php if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ ?>style="position:relative;"<?php } ?>>
        <div class="bg-blue color-white py-3">
          <center>
            <p>
              For more information, visit <a href="about.php" style="color:white;">The About Page</a>.
            </p>
            <p>
              Made by Team T1285, 2018-2019, All Rights Reserved
            </p>
          </center>
        </div>
      </footer>

      </html>
