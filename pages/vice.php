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
        <a class="navbar-brand" href="index.html">
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
          <a class="dropdown-item active" href="#">Vice President</a>
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
  <h1>Vice President</h1>
  <small>A comprehensive chapter membership overview</small>

  <?php if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ ?>
  <div class="row" style="padding-top:1rem; padding-bottom:1rem;">
    <div class="col-sm-12"> 
      <div class="contentcard" id="canvasSection" style="height:300px;">
        <h3 style="border-bottom:2px solid #CF0C0C">Membership Summary</h3>
        <script src="../js/Chart.js"></script>
          <div id="canvasDiv">
            <canvas id="gradeChart" style="max-width:40%; float:left;"></canvas>
            <canvas id="rankChart" style="max-width:40%; float:right;"></canvas>
            </div>
        <script>
          var ctx = document.getElementById('gradeChart').getContext('2d');
          var chart = new Chart(ctx, {
              // The type of chart we want to create
              type: 'doughnut',
              // The data for our dataset
              data: {
                  labels: ["Freshmen", "Sophomores", "Juniors", "Seniors"],
                  datasets: [{
                      label: "My First dataset",
                      backgroundColor: ['rgb(28, 115, 255)','rgb(165,70,87)','rgb(238,227,171)','rgb(115,186,155)'],
                      data: [<?php
                require("../php/connect.php");
                //get number of 9th graders
                $query="SELECT COUNT(username) FROM users WHERE grade='9' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers . ", ";
                //get number of 10th graders
                $query="SELECT COUNT(username) FROM users WHERE grade='10' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers . ", ";
                //get number of 11th graders
                $query="SELECT COUNT(username) FROM users WHERE grade='11' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers . ", ";
                //get number of 12th graders
                $query="SELECT COUNT(username) FROM users WHERE grade='12' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers;
                mysqli_close($link);
                ?>],
                  }]
              },
              // Configuration options go here
              options: {
                circumference: 2 * Math.PI,
                cutoutPercentage: 75
              }
          });
          var ctx = document.getElementById('rankChart').getContext('2d');
          var chart = new Chart(ctx, {
              // The type of chart we want to create
              type: 'doughnut',
              // The data for our dataset
              data: {
                  labels: ["Advisers", "Officers", "Members"],
                  datasets: [{
                      label: "My First dataset",
                      backgroundColor: ['rgb(28, 115, 255)','rgb(165,70,87)','rgb(238,227,171)'],
                      data: [<?php
                require("../php/connect.php");
                //get number of advisers
                $query="SELECT COUNT(username) FROM ranks WHERE rank='adviser' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers . ", ";
                //get number of officers
                $query="SELECT COUNT(username) FROM ranks WHERE rank='officer' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers . ", ";
                //get number of members
                $query="SELECT COUNT(username) FROM ranks WHERE rank='member' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username = '$username'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($numUsers) = mysqli_fetch_array($result);
                echo $numUsers;
                ?>],
                  }]
              },
              // Configuration options go here
              options: {
                circumference: 2 * Math.PI,
                cutoutPercentage: 75
              }
          });
        </script>
      </div>
      <br>
      <div>
      <h3 style="border-bottom:2px solid #CF0C0C">Members and Events</h3>
        <table class="minutesTable">
        <tr>
          <th>
            <b style="float:left;">Name</b>
          </th>
          <th>
            <b style="float:left;">Grade</b>
          </th>
          <th>
            <b style="float:left;"># of Events</b>
          </th>
        </tr>

        <?php
        require('../php/connect.php');
        $query="SELECT firstname, lastname, username, grade FROM users WHERE username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username='$username')) ORDER BY lastname ASC";
        $result = mysqli_query($link, $query);
        if (!$result){
          die('Error: ' . mysqli_error($link));
        }
        while(list($firstname, $lastname, $user, $grade) = mysqli_fetch_array($result)){
            ?>

          <tr>
            <td><p class="" style="float:left;"><?php echo $firstname." ".$lastname ?></p></td>
            <td><p class="" style="float:left;"><?php echo $grade ?></p></td>
            <?php $query2="SELECT COUNT(username) FROM user_team_mapping WHERE username='$user'";
            $result2 = mysqli_query($link, $query2);
            if(!$result2){
              die('Error: ' . mysqli_error($link));
            }
            list($eventcount) = mysqli_fetch_array($result2);
            ?>
            <td><p style="float:left; <?php if($eventcount < 3 || $eventcount > 6){ echo'color:red;'; } ?>"><?php echo $eventcount ?></p></td>
            <td>
              <button class="nobtn" data-toggle="collapse" href="#events-<?php echo $user; ?>" aria-expanded="false">&#9660;</button>
            </td>
          </tr>
          <tr class="collapse out" id="events-<?php echo $user; ?>">
            <td style="padding-left:1rem;" colspan="4">
                <div class="card"><?php 
                $query3="SELECT name FROM events WHERE id IN (SELECT event FROM teams WHERE id IN (SELECT team FROM user_team_mapping WHERE username='$user'))";
                $result3 = mysqli_query($link, $query3);
                if(!$result3){
                  die('Error: ' . mysqli_error($link));
                }
                while(list($eventname) = mysqli_fetch_array($result3)){
                  echo $eventname . "<br>";
                }
                ?></div>
            </td>
          </tr>

          <?php
          }
          mysqli_close($link);
          ?>

        </table>
        </div>
      </div>
    </div>

<?php }else{ ?>

  <p>Only officers can view this page!</p>

<?php } ?>

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
  </body>

  <footer style="position:relative;">
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
