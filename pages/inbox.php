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

//Inputting form data into database
if(isset($_POST['subject']) && isset($_POST['message']) && isset($_POST['to'])){

  //Accept POST variables, reassign for query
  $subject = validate($_POST['subject']);
  $message = validate($_POST['message']);
  $to = validate($_POST['to']);
  require('../php/connect.php');
   $query = "INSERT INTO messages (personfrom, personto, content, date, subject) VALUES ('$username', '$to', '$message', NOW(), '$subject')";
  $result = mysqli_query($link,$query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
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
          <a class="dropdown-item" href="social.php">Find Friends</a>
          <a class="dropdown-item" href="#">My Inbox</a>
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

<!-- send messages -->

<div class = "container" id="content">
    <form method="POST">
      
      <div class="row">

        <div class="col-sm-12">
          <div class="form-group">
            <label for="postTitle"> <h3>New message</h3> </label>
            <textarea class="form-control" name="to" maxlength=20 placeholder="To: (username)" rows="1"></textarea>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="subject" maxlength=100 placeholder="Subject (100 char max)" rows="1"></textarea>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="message" maxlength=1000 placeholder="Text (1000 char max)" rows="3" required></textarea>
          </div>
          <button type="submit">Send</button>
        </div>

        </div>
    </form>
  </div>










<!-- View messages -->
<div class= "container">
  <div class = "row">
    <h2> Messages </h2>
  </div>
  <?php
      require('../php/connect.php');
      $query = "SELECT personfrom, content, date, subject FROM messages WHERE personto='$username'";
      $result = mysqli_query($link, $query);
      if (!$result){
        die('Error: ' . mysqli_error($link));
      }

      if(mysqli_num_rows($result) == 0){
        ?> <div class = "row"> 
          You have no messages!
        </div>
      <?php 
      }else{
        echo mysqli_num_rows($result);
      while(list($personfrom, $message, $date, $subject) = mysqli_fetch_array($result)){
      ?>
  <div class = "row">
    <div class = "col-sm-3">
      From: <?php 
      require('../php/connect.php');

      $query = "SELECT firstname, lastname FROM users WHERE username='$personfrom'";
      $result = mysqli_query($link, $query);
      if (!$result){
        die('Error: ' . mysqli_error($link));
      }
      list($firstname, $lastname) = mysqli_fetch_array($result);

      echo $firstname . " " . $lastname ?>
    </div>
    <div class = "col-sm-2">
      <?php echo $subject; ?>
    </div>
    <div class = "col-sm-5" style="overflow-x:auto">
      <?php echo $message; ?>
    </div>
    <div class = "col-sm-2">
      <?php echo $date; ?>
    </div>
  </div>
<?php }} ?>
</div>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="../js/jquery-3.3.1.slim.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/scripts.js"></script>

  </body>

  <footer style = "position: relative;">
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
