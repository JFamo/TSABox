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
        <h1>About
        </h1>
      </div>
      <div class="row ">
        <div class="col-12">
          <div class="contentcard mt-3">
              <p><span class="text-primary">TSA Box</span> is the original work of Team T1285, created for the 2019 national Technology Student Association conference in National Harbor, MD. It was built using HTML, CSS, JavaScript, PHP, and MySQL on a WAMP stack.</p>
              <p>The following open-source libraries were used, and are documented with the project's portfolio. All material is Public Attribution 3.0 Share-Alike, Royalty-Free, Creative Commons 0, or better.</p>
              <p><a href="https://getbootstrap.com/docs/4.0/getting-started/introduction/">Bootstrap</a>
              <br>
              <a href="https://www.chartjs.org/">Chart.js</a>
              <br>
              <a href="https://popper.js.org/">Popper.js</a>
              <br>
              <a href="https://jquery.com/">jQuery</a>
              </p>
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

  <footer>
    <div class="bg-blue color-white py-3">
        <center>
        <p>
          Made by Team T1285, 2018-2019, All Rights Reserved
        </p>
        </center>
    </div>
  </footer>

</html>

<?php 

?>
