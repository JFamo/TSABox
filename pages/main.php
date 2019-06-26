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
$team = $_SESSION['team'];

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
      <li class="nav-item">
        <a class="nav-link" href="../php/logout.php">
          Logout
        </a>
      </li>
    </ul>
  </div>
</nav>
</li>
</li>

<div class="row" style="padding:1rem">
  <div class="col-sm-6"> 
    <div class="contentcard">
      <h2 style="border-bottom:2px solid #CF0C0C" align="left">Event Overview</h2>
        <?php

          require('../php/connect.php');

              $query="SELECT name, id FROM events WHERE id IN (SELECT event FROM teams WHERE id IN (SELECT team FROM user_team_mapping WHERE username='$username'))";
              $result = mysqli_query($link, $query);
              if (!$result){
                die('Error: ' . mysqli_error($link));
              }

              if(mysqli_num_rows($result) == 0){
                echo "You are not in any events!";
              }
              else{
                ?>
                <div class="row" style="padding:1rem;">
                <?php
                $eventnumber = 1;
                while($resultArray = mysqli_fetch_array($result)){
                  ?>
                <div class="col-sm-4" style="font-size:1.5rem; padding:1rem;">
                  <?php

                  $eventname = $resultArray['name'];
                  $eventid = $resultArray['id'];
                  echo "<form method='POST'><input type='hidden' name='select-event' value='" . $eventid . "'><input class='nobtn' type='submit' value='" . $eventname . "'><p style='border-bottom:2px solid #CF0C0C' align='center'></form></p>";
                  ?>

                  <script src="../js/Chart.js"></script>
                <canvas id="chartjs-4" class="chartjs" width="200" height="100" style="display: block; height: 100px; width: 200px;"></canvas>

                <script>new Chart(document.getElementById("chartjs-4"),{"type":"doughnut","data":{"labels":["In Progress","Complete","Backlog"],"datasets":[{"label":"Event Weight","data":[<?php
                require('../php/connect.php');
                $query2 = "SELECT SUM(weight) FROM tasks WHERE team='$team' AND status='progress'";
                $result2 = mysqli_query($link,$query2);
                if (!$result2){
                  die('Error: ' . mysqli_error($link));
                }
                list($progress_weight) = mysqli_fetch_array($result2);
                echo $progress_weight;
                ?>,<?php
                require('../php/connect.php');
                $query2 = "SELECT SUM(weight) FROM tasks WHERE team='$team' AND status='complete'";
                $result2 = mysqli_query($link,$query2);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($progress_weight) = mysqli_fetch_array($result2);
                echo $progress_weight;
                ?>,<?php
                require('../php/connect.php');
                $query2 = "SELECT SUM(weight) FROM tasks WHERE team='$team' AND status='backlog'";
                $result2 = mysqli_query($link,$query2);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($progress_weight) = mysqli_fetch_array($result2);
                echo $progress_weight;
                ?>],"backgroundColor":["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"]}]}});</script>

                <?php 
                mysqli_close($link);
                ?>

              </div>  
              <?php
                  if($eventnumber == 3){
                    ?>
                  </div>
                  <?php
                    echo "<div class='row' style='padding-left:1rem'>";
                  }
                $eventnumber += 1;
                }
              }
            ?>
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

  <footer>
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
