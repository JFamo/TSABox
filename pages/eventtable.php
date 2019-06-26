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

require('../php/connect.php');

$query = "SELECT chapter FROM user_chapter_mapping WHERE username='$username'";
$result = mysqli_query($link, $query);
if (!$result){
  die('Error: ' . mysqli_error($link));
}
list($chapter) = mysqli_fetch_array($result);

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
    <nav class="header bg-blue navbar navbar-expand-lg navbar-dark" style="min-height:95px; z-index: 1000;">
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
      <li class="nav-item">
        <a class="nav-link" href="../php/logout.php">
          Logout
        </a>
      </li>
    </ul>
  </div>
</nav>

    <div class="container" id="content">
      <h1>Event Grid View</h1>
      <small>See your chapter's events and teams</small><br>
      <a href="selection.php">Back to Event Selection</a>

      <div class="row" style="padding-top:1rem; padding-bottom:1rem;">
        <div class="col-sm-12">
          <table id="eventTable">
            <tbody>
            <?php

              require('../php/connect.php');

              $query="SELECT event, min, max, teams FROM limits WHERE event IN (SELECT id FROM events WHERE level IN (SELECT level FROM chapters WHERE id='$chapter'))";
              $result = mysqli_query($link, $query);
              if (!$result){
                die('Error: ' . mysqli_error($link));
              }
              if(mysqli_num_rows($result) == 0){
                echo "Could not find any events!";
              }
              else{
                //Iterate every event at my level
                while($resultArray = mysqli_fetch_array($result)){
                  $event = $resultArray['event'];
                  $teams = $resultArray['teams'];
                  $min = $resultArray['min'];
                  $max = $resultArray['max'];

                  //Get excess qualifier teams
                  $query2="SELECT count FROM extrateams WHERE event='$event' AND chapter='$chapter'";
                  $result2 = mysqli_query($link, $query2);
                  if (!$result2){
                    die('Error: ' . mysqli_error($link));
                  }
                  list($excessteams) = mysqli_fetch_array($result2);
                  $teams = $teams + $excessteams;

                  //Display event name row
                  //Get event name
                  $query2="SELECT name FROM events WHERE id='$event'";
                  $result2 = mysqli_query($link, $query2);
                  if (!$result2){
                    die('Error: ' . mysqli_error($link));
                  }
                  list($eventname) = mysqli_fetch_array($result2);
                  echo "<tr class='evt-namerow'><td><b>".$eventname."</b></td></tr>";

                  if($teams == 0){
                    echo "<td colspan='6'>You have no teams for this event!</td>";
                  }
                  for($team = 1; $team <= $teams; $team++){

                      echo "<tr class='evt-teamrow'>";

                      //Get my team ID
                      $query2="SELECT id FROM teams WHERE chapter='$chapter' AND event='$event' AND number='$team'";
                      $result2 = mysqli_query($link, $query2);
                      if (!$result2){
                        die('Error: ' . mysqli_error($link));
                      }
                      list($teamid) = mysqli_fetch_array($result2);

                      //Get team members
                      $queryT="SELECT firstname, lastname FROM users WHERE username IN (SELECT username FROM user_team_mapping WHERE team='$teamid')";
                      $resultT = mysqli_query($link, $queryT);
                      if (!$resultT){
                        die('Error: ' . mysqli_error($link));
                      }

                      for($q = 1; $q <= $max; $q++){
                        if($q <= $min){
                          $bck = "#6b7fdb";
                        }
                        else{
                          $bck = "#d6d6d6";
                        }
                        $row = mysqli_fetch_row($resultT);
                        echo "<td class='evt-slot' style='background-color:" . $bck . ";'>" . $row[0] . " " . $row[1] . "</td>";
                      }

                      echo "</tr>";
                  }
                }
              }
            ?>
            </tbody>
          </table>
         
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
