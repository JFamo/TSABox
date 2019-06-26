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

$pwmsg="";
$rank = $_SESSION['rank'];
if(isset($_GET['user'])){
  $username = $_GET['user'];
}else{
  $username = $_SESSION['username'];
}

function getImage($Username){
  require('../php/connect.php');
    $query = "SELECT * FROM profilepictures WHERE username='$Username'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }

    while($image = mysqli_fetch_array($result)){

      echo '
            <tr>
                <td>
                    <img src="data:image;base64,'.base64_encode($image['content']).'" height="200" width="200" class="img-thumnail" />
                </td>
            <tr>
            ';
    }
}


//Inputting form data into database
if(isset($_POST['postTitle']) && isset($_POST['postText'])){

  //Accept POST variables, reassign for query
  $postTitle = validate($_POST['postTitle']);
  $postText = validate($_POST['postText']);

  require('../php/connect.php');
   $query = "INSERT INTO posts (title, content, username, date) VALUES ('$postTitle', '$postText', '$username', NOW())";
  $result = mysqli_query($link,$query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
}


if(isset($_POST['newBio'])){
  $newBio = validate($_POST['newBio']);

  require('../php/connect.php');

  $query = "SELECT content FROM bio WHERE username='$username'";
  $result = mysqli_query($link, $query);

  if (!$result){
      die('Error: ' . mysqli_error($link));
  }
  $bio = mysqli_num_rows($result);
  if($bio==0){
      $query1 = "INSERT INTO `bio` (`username`, `content`) VALUES ('$username', '$newBio')";
      $result2 = mysqli_query($link, $query1);

      if (!$result2){
      die('Error: ' . mysqli_error($link));
      }
  }else if($bio==1){
    $query2 = "UPDATE bio SET content='$newBio' WHERE username='$username'";

    $result2 = mysqli_query($link, $query2);

    if (!$result2){
      die('Error: ' . mysqli_error($link));
    
      }
  }
}
if(isset($_POST['uploadFile']) && $_FILES['userfile']['size'] > 0){
  require('../php/connect.php');

  $query1 = "SELECT size FROM profilepictures WHERE username='$username'";
  $result1 = mysqli_query($link, $query1);
  if (!$result1){
    die('Error: ' . mysqli_error($link));
  }
  list($thing1) = mysqli_fetch_array($result1);
  if($thing1==""){



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
    $query = "INSERT INTO profilepictures (name, size, type, content, date, username) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now(), '$username')";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
    mysqli_close($link);
  }else{

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
    $query = "UPDATE profilepictures SET name='$fileName', size='$fileSize', type='$fileType', content='$content', date=now() WHERE username='$username'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
    mysqli_close($link);
  }
}
//change password
if(isset($_POST['currentPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmPassword'])){
    require('../php/connect.php');
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if($newPassword==$confirmPassword){
      $query= "SELECT username FROM users WHERE username='$username'";
      $result = mysqli_query($link, $query);
      if (!$result){
        die('Error: ' . mysqli_error($link));
      }
      $count = mysqli_num_rows($result);
      if($count == 1){
        $query2 = "SELECT password FROM users WHERE username='$username'";
        $result2 = mysqli_query($link, $query2);
        if (!$result2){
          die('Error: ' . mysqli_error($link));
        }
        list($passwordValue) = mysqli_fetch_array($result2);
        if(password_verify($currentPassword, $passwordValue)){
          echo $newPassword;
          $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
          echo $newPassword;
          $query3 = "UPDATE users SET password='$newPassword' WHERE username='$username'";
          $result3 = mysqli_query($link, $query3);
          if (!$result3){
            die('Error: ' . mysqli_error($link));
          }
        }
      }
    }
}
if(isset($_POST['newEmail'])){
    require('../php/connect.php');
    $newEmail =$_POST['newEmail'];
    $query = "UPDATE users SET email='$newEmail' WHERE username='$username'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
  
}

if(isset($_POST['newFirstName']) && isset($_POST['newLastName'])){
    require('../php/connect.php');
    $newFirstName =$_POST['newFirstName'];
    $newLastName =$_POST['newLastName'];
    $query = "UPDATE users SET firstname='$newFirstName', lastname='$newLastName' WHERE username='$username'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
  
}

if(isset($_POST['newGrade'])){
    require('../php/connect.php');
    $newGrade =$_POST['newGrade'];
    $query = "UPDATE users SET grade='$newGrade' WHERE username='$username'";
    $result = mysqli_query($link, $query);
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
          <a class="dropdown-item" href="inbox.php">My Inbox</a>
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

<!-- Title -->
<div class="container" id="content">
  <div class = "row">
    <div class = "col-sm-4">
      <!-- profile picture -->
      <?php 
        getimage($username);
      ?>
  
    </div>
    <div class = "col-sm-8">
      <h1> <?php echo $username ?>'s Profile  </h1>
    </div>
  </div>
  <div class = "row">
    <p> <?php
    require('../php/connect.php');

    $query = "SELECT firstname, lastname, grade FROM users WHERE username = '$username'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
    list($firstname, $lastname, $grade) = mysqli_fetch_array($result);


    $query = "SELECT name FROM chapters WHERE id=(SELECT chapter FROM user_chapter_mapping WHERE username='$username')";
    $result = mysqli_query($link, $query);
    list($chapter) = mysqli_fetch_array($result);
    echo $firstname . " " . $lastname . " is in grade " . $grade . " at " . $chapter;
    ?>
    </p>
  </div>
  <div class = "row">
    <p>
      <?php 
        require('../php/connect.php');
        $query = "SELECT content FROM bio WHERE username='$username'";
        $result = mysqli_query($link, $query);
        list($bio) = mysqli_fetch_array($result);
        if($bio!=""){
          echo "Bio: " . $bio;
        }
      ?>
    </p>
  </div>
  <div class = "row">
    <p>
      <?php 
        require('../php/connect.php');
        $query = "SELECT email FROM users WHERE username='$username'";
        $result = mysqli_query($link, $query);
        list($email) = mysqli_fetch_array($result);
        if($email!=""){
          echo "Email: " . $email;
        }
      ?>
    </p>

  </div>
</div>




<?php if($username == $_SESSION['username']){ 
  ?>
<!-- Change Boi -->
  <div class = "container">
  <div class="row" style="padding-top:1rem; padding-bottom:1rem;">
    <div class="col-sm-12"> 
          <div class="contentcard">
            <h3 style="border-bottom:2px solid #CF0C0C">Upload</h3>
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
              <div class="form-control" style="border:0;">
                <div class="row py-3">
                  <div class="col-sm-6">
                    <input style="font-size:16px;" name="userfile" type="file" id="userfile" accept="image/png, image/jpeg">
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <input name="uploadFile" type="submit" class="btn btn-primary" id="uploadFile" value="Upload">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>





  </div>
  <div class = "container" id="content">
    <form method="POST">
      
      <div class="row" style="padding-top:1rem; padding-bottom: 1rem;">

        <div class="col-sm-12">
          <div class="form-group">
            <label for="bio"> <h3>Change Bio</h3> </label>
            <textarea class="form-control" name="newBio" maxlength=200 placeholder="New Bio (200 char max)" rows="1"></textarea>
          </div>
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>

        </div>
    </form>
  </div>



  <!-- Creating new posts -->
  <div class = "container" id="content">
    <form method="POST">
      
      <div class="row" style="padding-top:1rem; padding-bottom: 1rem;">

        <div class="col-sm-12">
          <div class="form-group">
            <label for="postTitle"> <h3>New post</h3> </label>
            <textarea class="form-control" name="postTitle" maxlength=100 placeholder="Title (100 char max)" rows="1"></textarea>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="postText" maxlength=1000 placeholder="Text (1000 char max)" rows="3" required></textarea>
          </div>
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
        </div>
    </form>
  </div>
<!-- Retrieving posts from database -->
  <?php }
    require('../php/connect.php');
    $query="SELECT * FROM posts WHERE username='$username' ORDER BY date DESC";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }

    if(mysqli_num_rows($result) == 0){
      ?>
      <div class="container" id="content">
        <?php echo "There are no posts! <br>"; ?>
      </div>
      <?php
    }
    else{
      while($resultArray = mysqli_fetch_array($result)){

        $title = $resultArray['title'];
        $content = $resultArray['content'];
        $username = $resultArray['username'];
        $date = $resultArray['date'];
        ?>

        <!-- Displaying retrieved posts-->
        <div class="container" id="content">
          <div class="row" style="padding-top: 1rem; padding-bottom: 1rem; overflow: auto;">
            <div class="col-sm-12">              
                <div class="d-flex"> 
                    <h2> <?php
                    echo $title;
                    ?> </h2>
                </div>
                <div class="d-flex">
                  <?php
                    echo $content;
                  ?>
                </div>
                <div class="d-flex" style="padding-top: 0.5rem;"> 
                  <small> <?php
                  echo " - " . $username . " on " . $date;
                  ?> </small>
                </div>               
            </div>
          </div>
        </div>

        <?php
        }
    }    
  mysqli_close($link);
  ?>

<?php if($username==$_SESSION['username']){ ?>
<div class ="container">
  <form method="POST">
    <!-- change password -->
    <div class = "row">
      <h3> Change Password </h3>
    </div>
    <div class = "row">
      <div class="col-sm-3">
          <p>Current Password</p>
      </div>
      <div class="col-sm-9">
          <input type="password" name="currentPassword" maxlength=30  rows="1" required>
      </div>
      </div>
    <div class = "row">
        <div class="col-sm-3">
          <p>New Password</p>
        </div>
        <div class ="col-sm-9">
              <input type="password" name="newPassword" maxlength=30  rows="1" required>
          </div>
    </div>
    <div class = "row">
        <div class="col-sm-3">
          <p>Confirm Password</p>
        </div>
          <div class ="col-sm-9">
            <input type="password" name="confirmPassword" maxlength=30  rows="1" required>
          </div>
    </div>
    <div class="row">
      <?php echo $pwmsg; ?>
    </div>
    <div class="row">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>


<br>


<div class ="container" style="padding-top">
  <!-- change email -->
  <form method="POST">
  <div class = "row">
    <h3> Update Email </h3>
  </div>
  <div class = "row">
        <div class="col-sm-3">
            <p>New Email</p>
        </div>
        <div class="col-sm-9">
          
            <input name="newEmail" maxlength=30  rows="1" required>
            </div>
            
            
      
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<div class ="container" style="padding-top">
  <!-- change name -->
  <form method="POST">
    <div class = "row">
      <h3> Change Name </h3>
    </div>
    <div class = "row">
      <div class="col-sm-3">
        <p>First Name</p>
      </div>
      <div class="col-sm-9">
        <input name="newFirstName" maxlength=30  rows="1" required>
      </div>
    </div>
    <div class = "row">
      <div class="col-sm-3">
        <p>Last Name</p>
      </div>
      <div class="col-sm-9">
        <input name="newLastName" maxlength=30  rows="1" required>
      </div>
    </div>
        <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<div class ="container" style="padding-bottom">
  <!-- change grade -->
  <form method="POST">
  <div class = "row">
    <h3> Update Grade </h3>
  </div>
  <div class = "row">
        <div class="col-sm-3">
            <p>New Grade</p>
        </div>
        <div class="col-sm-9">
          
            <input name="newGrade" maxlength=30  rows="1" required>
            </div>
            
            
      
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php } ?>
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
