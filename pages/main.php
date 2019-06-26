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

if(isset($_POST['select-event'])){

  $selectevent = addslashes($_POST['select-event']);
  $selectevent = validate($selectevent);

  require('../php/connect.php');
  $query = "SELECT id FROM teams WHERE event='$selectevent' AND id IN (SELECT team FROM user_team_mapping WHERE username='$username')";
  $result = mysqli_query($link,$query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  list($teamid) = mysqli_fetch_array($result);
  $_SESSION['team'] = $teamid;
  header('Location: event.php');
  mysqli_close($link);
}


//Acquiring user's full name 
require('../php/connect.php');
$query="SELECT firstname, lastname FROM users WHERE username='$username'";
$result = mysqli_query($link, $query);
if (!$result){
  die('Error: ' . mysqli_error($link));
}
list($first, $last) = mysqli_fetch_array($result);
$name = $first . " " . $last;


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
  <body style="background-image:url(../images/bg-logos.png); background-size:170px 140px;">
    <nav class="header bg-blue navbar navbar-expand-sm navbar-dark" style="min-height:95px; z-index: 1000;">
        <a class="navbar-brand" href="#">
          <div class="row">
            <div class="col nopadding">
                <img src="../images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
            </div>
        <a class="navbar-brand" href="#">TSABox</a>
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
            </li>
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
            </li>
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
            <li class="nav-item">
              <a class="nav-link" href="../php/logout.php">
                Logout
              </a>
            </li>
          </ul>
        </div>
    </nav>

    <div class="container px-5" id="content">
      <div class="row">
        <h1>Welcome, <?php 
          require('../php/connect.php');
          $query="SELECT firstname FROM users WHERE username='$username'";
          $result = mysqli_query($link, $query);
          if (!$result){
            die('Error: ' . mysqli_error($link));
          }
          list($firstname)=mysqli_fetch_array($result);
          echo ucfirst($firstname);
          ?>
        </h1>
      </div>
      <div class="row ">
        <div class="col-sm-6">
          <!-- My Events card -->
          <div class="contentcard mt-3">
              <h3 class="band-blue" align="left">My Events</h3>
                <?php

                  require('../php/connect.php');

                      $query="SELECT name, id FROM events WHERE id IN (SELECT event FROM teams WHERE id IN (SELECT team FROM user_team_mapping WHERE username='$username'))";
                      $result = mysqli_query($link, $query);
                      if (!$result){
                        die('Error: ' . mysqli_error($link));
                      }

                      if(mysqli_num_rows($result) == 0){
                        echo "Sign up for events on the <a href='selection.php'>Event Selection</a> page";
                      }
                      else{
                        ?>
                        <?php
                        while($resultArray = mysqli_fetch_array($result)){
                          ?>
                          <div class="row">
                          <div class="col-12" style="font-size:1.5rem;">
                            <?php

                            $eventname = $resultArray['name'];
                            $eventid = $resultArray['id'];
                            echo "<form method='POST'><input type='hidden' name='select-event' value='" . $eventid . "'><input class='nobtn' type='submit' value='" . $eventname . "'><p align='center'></form>";
                            ?>
                          </div> 
                        </div>
                      <?php
                      }
                    }
                  ?>
                  <a href='selection.php'>Event Selection</a>
            </div>
            <!-- My Balance card -->
          <div class="contentcard mt-5">   
            <h3 class="band-red"  align="left">My Balance</h3>
            <h2 align="left"> $<?php

              require('../php/connect.php');
              $query="SELECT balance FROM user_balance WHERE user='$username'";
              $result = mysqli_query($link, $query);
              if (!$result){
                die('Error: ' . mysqli_error($link));
              }
              list($balance)=mysqli_fetch_array($result);
              if(!$balance){
                $balance = 0.00;
              }
              echo number_format((float)$balance,2,'.','');

            ?>
            </h2>
          </div>
        </div>

        <!-- Recent Announcements card -->
        <div class="col-sm-6">
          
          <div class="contentcard mt-3">
            <h3 class="band-grey" align="left">Recent Announcements</h3>
            <!-- Retrieving announcements from database -->
            <?php
            require('../php/connect.php');

            $query="SELECT * FROM announcements WHERE username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username='$username')) ORDER BY date DESC LIMIT 3";
            $result = mysqli_query($link, $query);
            if (!$result){
              die('Error: ' . mysqli_error($link));
            }

            if(mysqli_num_rows($result) == 0){
              ?>
              <div class="container" id="content">
                <?php echo "There are no announcements! <br>"; ?>
              </div>
              <?php
            }
            else{
              while($resultArray = mysqli_fetch_array($result)){
                $id = $resultArray['id'];
                $title = $resultArray['title'];
                $content = $resultArray['content'];
                $username = $resultArray['username'];
                $date = $resultArray['date'];
                ?>              
            
            <!-- Displaying announcements -->
            <div class="d-flex" style="padding-top: .5rem; text-align: left;"> 
              <h2> <?php
                echo $title;
                ?> </h2>
            </div>
            <div class="d-flex" style="text-align: left;">
              <?php
              echo $content;
              ?>
            </div>
            <div class="d-flex" style="padding-top: 0.5rem; text-align: left;"> 
              <small> <?php
                echo " - " . $name . " on " . $date;
                ?> </small>
            </div>
            <?php 
          }
        } ?>        

          </div>
        
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
  </body>

  <footer style="position: relative;">
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

<?php 

?>
