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

//get permission settings
require('../php/connect.php');

//post scores
if(isset($_POST['scoreValue'])){

  //variables assignment
  $testNum = $_POST['testNumber'];
  $scoreVal = $_POST['scoreValue'];
  $myName = addslashes($username);

  require('../php/connect.php');

  $query = "INSERT INTO scores (username, test, score) VALUES ('$myName', '$testNum', '$scoreVal')";

  $result = mysqli_query($link,$query);

  if (!$result){
    die('Error: ' . mysqli_error($link));
  }

  mysqli_close($link);
}
//clear scores

  
if(isset($_POST['clearScores']) && ($rank == "admin" || $rank == "adviser")){

  

  $query = "TRUNCATE TABLE scores";

  $result = mysqli_query($link,$query);

  if (!$result){
    die('Error: ' . mysqli_error($link));
  }

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
                <a class="dropdown-item active" href="parliamentarian.php">Parliamentarian</a>
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
          <a class="dropdown-item" href="social.php">Find Friends</a>          
          <a class="dropdown-item" href="inbox.php">My Inbox</a>
                  </div>
                </li>
                <?php if($rank == "adviser" || $rank == "admin") { ?>
                  <li class="nav-item">
                    <a class="nav-link" href="../pages/admin.php">
                      Admin
                    </a>
                  </li>
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
  <h1> Parliamentarian </h1>
  <small> The Parliamentarian page contains links to parliamentary procedure study guides and practice tests, and allows for the automatic generation of a randomized practice Chapter Team test </small>

<div class="row" style="padding-top:1rem; padding-bottom:1rem;">
      <div class="col-sm-12">
        <div class="contentcard">
          <div class="row" style="width:97.5%;">

<div class="col-sm-9" id="content" style="padding:0 0 0 0;">
          <div class="adminDataSection" style="margin-bottom:15px; width:97.5%; padding-left:5%; padding-right:5%; padding-bottom: 2.5%"><center>
          <p class="userDashSectionHeader" style="padding-left:0;">Practice Test</p>
          <form id="createForm">
            <div class="form-group">
              <label for="numQuestions">Number of Questions</label>
              <input style="width:200px;" type="number" class="form-control" id="numQuestions" aria-describedby="numberHelp" value="10">
            </div>
            <div class="form-group">
              <label for="difficulty">Difficulty Level</label>
              <select style="width:200px;" class="form-control" id="difficulty">
                <option value="beginner">Beginner</option>
                <option value="simple">Simple</option>
                <option value="average">Average</option>
                <option value="challenging">Challenging</option>
                <option value="chapter">Chapter Team (50 questions)</option>
      
              </select>
            </div>
          </form>
          <form id="scoreForm" style="display:none;" method="post">
            <input type="number" id="scoreValue" name="scoreValue">
            <input type="number" id="testNumber" name="testNumber">
          </form>
          <button id="generateButton" class="btn btn-primary" onclick="generate()">Generate Test</button>
          </center>
          </div>
          <div class="adminDataSection" style="margin-bottom:15px; width:97.5%; padding-left:5%; padding-right:5%; padding-top:2.5%; padding-bottom: 2.5%"><center>
          <p class="userDashSectionHeader" style="padding-left:0; padding-bottom:0; margin-bottom:0;">High Scores</p>
          <p style="font-size:12px; padding-top:0; margin-top:0;">Scores are taken from the 50-question chapter team test</p>
            <ul class="list-group list-group-flush">
              <?php

                $counter = 1;

                require('../php/connect.php');

                $query = "SELECT username, MAX(score) FROM scores WHERE test='100' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username='$username')) GROUP BY username ORDER BY MAX(score) DESC LIMIT 10";

                $result = mysqli_query($link,$query);

                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }

                while(list($thisname, $thisscore) = mysqli_fetch_array($result)){

                  echo '<li class="list-group-item">';
                  if($counter == 1){
                    echo '<img src="../imgs/ribbon-first.png" width="20px" height="20px" />';
                  }
                  else if($counter == 2){
                    echo '<img src="../imgs/ribbon-second.png" width="20px" height="20px" />';
                  }
                  else if($counter == 3){
                    echo '<img src="../imgs/ribbon-third.png" width="20px" height="20px" />';
                  }

                  $query = "SELECT firstname,lastname FROM users WHERE username='$thisname'";

                  $res=mysqli_query($link,$query);
                  if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                  list($first, $last)=mysqli_fetch_array($res);

                  echo $first . ' ' . $last . ' - ' . $thisscore . '</li>';

                  $counter += 1;
                }

                mysqli_close($link);

              ?>
            </ul>
          <?php 
          if($rank == "admin" || $rank == "adviser") { ?>
            <br>
            <form method="post">
              <input type="submit" name="clearScores" class="btn btn-danger" value="Clear Scores"/>

            </form>

          <?php 
          }  ?>
          </center></div>
        </div>
        <div class="col-sm-3" style="padding:0 0 0 0;">
          <div class="adminDataSection" style="padding-bottom: 15px;">
          <center>
          <p class="userDashSectionHeader" style="padding-right:0;">Resources</p>
          <!--PARLI PRO-->
          <b><p class="bodyTextType1" style="padding-left:0;">Helpful Guides</p></b>
          <a href="https://docs.google.com/presentation/d/19JnTf9YjODwRgyt2N4jIxER_rYEQZaEyjZtwk_zvyRs/edit?usp=sharing">State Guide</a><br>
          <a href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&ved=2ahUKEwjvuf_9mIXjAhUwT98KHQVQAyoQFjAAegQIAxAC&url=https%3A%2F%2Ftsaweb.org%2Fdocs%2Fdefault-source%2Ftoolkits%2Fparliamentary_procedure_basics-(3).pptx%3Fsfvrsn%3Debcb6b1c_0&usg=AOvVaw0nUHwwuTkN4wLsegZxATe4">National Beginner Guide</a><br>
          <a href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=2&cad=rja&uact=8&ved=2ahUKEwjvuf_9mIXjAhUwT98KHQVQAyoQFjABegQIABAC&url=https%3A%2F%2Ftsaweb.org%2Fdocs%2Fdefault-source%2Ftoolkits%2Fparliamentary_procedure_advanced-(1).pptx%3Fsfvrsn%3D1276260e_0&usg=AOvVaw06_fnebd5QAhTegeeQbPd_">National Advanced Guide</a><br>
  
          <br><b><p class="bodyTextType1">Practice Tests</p></b>
          <a href="http://www.300questions.org/" target="_blank">300 Questions</a><br>
          <a href="https://drive.google.com/file/d/0B0djtG22WOS_aEhsVWZLT0xocDg/view?usp=sharing" target="_blank">Dunbar Tests</a><br>
          </center>
          </div>
        </div>


        </div>
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
    <script src="../js/parli.js"></script>

    <iframe name="hideframe" style="display:none;"></iframe>

  </body>

  <footer style = "position:relative">
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
