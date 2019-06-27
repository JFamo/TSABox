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

if(isset($_POST['uploadFile']) && $_FILES['userfile']['size'] > 0){
  //file details
  $fileName = $_FILES['userfile']['name'];
  $tmpName = $_FILES['userfile']['tmp_name'];
  $fileSize = $_FILES['userfile']['size'];
  $fileType = $_FILES['userfile']['type'];
  //file data manipulation
  $fp = fopen($tmpName, 'r');
  $content = fread($fp, filesize($tmpName));
  $content = addslashes($content);
  fclose($fp);
  if(!get_magic_quotes_gpc()){
    $fileName = addslashes($fileName);
  }
  require('../php/connect.php');
  $query = "SELECT chapter FROM user_chapter_mapping WHERE username='$username'";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  list($chapter) = mysqli_fetch_array($result);
  $query = "INSERT INTO rules (name, size, type, content, chapter) VALUES ('$fileName', '$fileSize', '$fileType', '$content', '$chapter')";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  
  mysqli_close($link);
}
//file deletion
if(isset($_POST['deleteFileID'])){
  //file details
  $fileid = $_POST['deleteFileID'];
  $filename = $_POST['deleteFileName'];
  if($rank == "admin" || $rank == "adviser"){
    require('../php/connect.php');
    $query = "DELETE FROM rules WHERE id = '$fileid'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
    mysqli_close($link);
  }
  else{
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
                  <a class="dropdown-item active" href="rules.php">Rules</a>
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

 <div class="container" id="content">
  <h1>Rules</h1>
  <small>Download national TSA event rules</small>

  <div class="row" style="padding-top:1rem; padding-bottom:1rem;">
    <div class="col-sm-12"> 
        <?php if($rank == "admin" || $rank == "adviser"){ ?>
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
              <div class="form-control">
                <div class="col-12">
                  <input style="font-size:16px;" name="userfile" type="file" id="userfile">
                </div>
                <div class="col-12">
                <input name="uploadFile" type="submit" class="btn btn-primary" id="uploadFile" value="Upload">
                </div>
              </div>
            </form>
        <?php } ?>
        <br>
          <table class="minutesTable">

          <?php
          require('../php/connect.php');
          $query="SELECT id, name, size FROM rules WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username='$username') ORDER BY name ASC";
          $result = mysqli_query($link, $query);
          if (!$result){
            die('Error: ' . mysqli_error($link));
          }
            while(list($id, $name, $size) = mysqli_fetch_array($result)){
                  ?>
                <tr>
                  <td><a class="text-primary" href="../php/download_rules.php?id=<?php echo "".$id ?>" style="float:left;"><?php echo "".$name ?></a></td>
                      <td><p style="float:left;"><?php echo round(($size / 1024) , 2) ?>KB</p></td>
                  <?php 
                    if($rank == "admin" || $rank == "adviser"){
                  ?>
                  <td>
                    <form method="post" id="deleteFileForm">
                      <input name="deleteFileID" type="hidden" value="<?php echo $id ?>">
                      <input name="deleteFileName" type="hidden" value="<?php echo $name ?>">
                      <input style="padding:0 0 0 0;" type="submit" class="close btn btn-link" value="&times";>
                    </form>
                  </td>
                  <?php
                    }
                  ?>
                </tr>
                <?php
                
              }
            
              
          mysqli_close($link);
          ?>

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
