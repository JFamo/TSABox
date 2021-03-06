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

//Function to randomly generate a unique chapter code
function createChapterCode(){
  return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
}

//Function to check if chapter code is unique
function isCodeUnique($code){
  require('php/connect.php');
  $query= "SELECT id FROM chapters WHERE code='$code'";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  $count = mysqli_num_rows($result);
  if($count == 0){
    return true;
  }
  else{
    return false;
  }
}

session_start();


//Handle user login
$fmsg="";
if(isset($_POST['login-username']) and isset($_POST['login-password'])){
  $username = $_POST['login-username'];
  $password = $_POST['login-password'];
  $username = validate($username);
  $password = validate($password);
  
  require('php/connect.php');
  
  $query= "SELECT username FROM users WHERE username='$username'";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  $count = mysqli_num_rows($result);
  if($count == 1){
    $query2 = "SELECT username, password, firstname, lastname FROM users WHERE username='$username'";
    $result2 = mysqli_query($link, $query2);
    if (!$result2){
      die('Error: ' . mysqli_error($link));
    }
    list($usernameValue, $passwordValue, $firstnameValue, $lastnameValue) = mysqli_fetch_array($result2);
    if(password_verify($password, $passwordValue)){
      $_SESSION['username'] = $usernameValue;
      $_SESSION['firstname'] = $firstnameValue;
      $_SESSION['lastname'] = $lastnameValue;

      $query3= "SELECT rank FROM ranks WHERE username='$username'";
      $result3 = mysqli_query($link, $query3);
      if (!$result3){
        die('Error: ' . mysqli_error($link));
      }
      list($userrank) = mysqli_fetch_array($result3);
      $_SESSION['rank'] = $userrank;
    }
    else{
      $fmsg = "Invalid Password";
    }
  }
  else{
    $fmsg = "Invalid Username";
  }
}

//Register new users
if(isset($_POST['register-username']) and isset($_POST['register-password']) and (isset($_POST['register-code']))){

  $username = $_POST['register-username'];
  $password = $_POST['register-password'];
  $firstname = $_POST['register-firstname'];
  $lastname = $_POST['register-lastname'];
  $orgcode = $_POST['register-code'];
  $grade = $_POST['register-grade'];

  $username = validate($username);
  $password = validate($password);
  $password = password_hash($password, PASSWORD_DEFAULT);
  
  require('php/connect.php');
  
  $query= "SELECT username FROM users WHERE username='$username'";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  $count = mysqli_num_rows($result);
  if($count == 0){
    //Check that specified organization code exists if joining
    $orgwithcodecount = 0;
    $query= "SELECT id FROM chapters WHERE code='$orgcode'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }
    list($orgid) = mysqli_fetch_array($result);
    $orgwithcodecount = mysqli_num_rows($result);

    if($orgwithcodecount == 1){
      //User Creation
      $query2 = "INSERT INTO users (username, password, firstname, lastname, grade) VALUES ('$username', '$password', '$firstname', '$lastname', '$grade')";
      $result2 = mysqli_query($link, $query2);
      if (!$result2){
        die('Error: ' . mysqli_error($link));
      }
      //Organization Join
      $query2 = "INSERT INTO user_chapter_mapping (username, chapter) VALUES ('$username', '$orgid')";
      $result2 = mysqli_query($link, $query2);
      if (!$result2){
        die('Error: ' . mysqli_error($link));
      }
      //Set Rank
      $query2 = "INSERT INTO ranks (username, rank) VALUES ('$username', 'member')";
      $result2 = mysqli_query($link, $query2);
      if (!$result2){
        die('Error: ' . mysqli_error($link));
      }
      //Set Hold
      $query2 = "INSERT INTO holds (username, status) VALUES ('$username', 'hold')";
      $result2 = mysqli_query($link, $query2);
      if (!$result2){
        die('Error: ' . mysqli_error($link));
      }
      $fmsg = "Successfully Registered!";
    }
    else{
      $fmsg = "Invalid Organization Code!";
    }
  }
  else{
    $fmsg = "This Username is Taken!";
  }
}

//Authenticate session and force redirect on real session
if(isset($_SESSION['username'])){
  header('Location: pages/main.php');
}else{
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>TSABox</title>
  </head>
  <body>
    <nav class="header bg-blue navbar navbar-expand-lg navbar-dark" style="min-height:95px; z-index: 1000;">
        <a class="navbar-brand" href="index.html">
          <div class="row">
            <div class="col">
                <img src="images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
            </div>
            <div class="col">
                <p style="margin-bottom: 0;">TSABOX</p>
            </div>
          </div>
        </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav" style="justify-content: flex-end;">
        <ul class="navbar-nav">
        </ul>
      </div>
    </nav>

    <div class="container py-5">
      <div class="row">
        <div class="col-12">
          <center>
          <h2>Manage your chapter<br><span class="text-primary">Effectively</span> and <span class="text-primary">Successfully</span> with</h2>
          <br>
          <h1>TSA Box</h1>
          <br>
          <h6 style="text-decoration: underline;;">A comprehensive suite of chapter management tools</h6></center>
        </div>
      </div>
      <div class="row pt-5">
        <div class="col-12">
          <center>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal">Login</button>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#registerModal">Register</button>
            <br><br>
            <?php echo "<b class='text-danger'>".$fmsg."</b>"; ?>
          </center>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="loginModal" role="dialog">
      <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Login</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form method="POST" class="pt-4">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="login-username">Username</label>
                  <input type="text" class="form-control" id="login-username" name="login-username" placeholder="Username">
                </div>
                <div class="form-group col-md-6">
                  <label for="login-password">Password</label>
                  <input type="password" class="form-control" id="login-password" name="login-password" placeholder="Password">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Log In</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="registerModal" role="dialog">
      <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Register</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form method="POST" class="pt-4">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="register-username">Username</label>
                  <input type="text" class="form-control" id="register-username" name="register-username" placeholder="Username">
                </div>
                <div class="form-group col-md-6">
                  <label for="register-password">Password</label>
                  <input type="password" class="form-control" id="register-password" name="register-password" placeholder="Password">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="register-firstname">First Name</label>
                  <input type="text" class="form-control" id="register-firstname" name="register-firstname" placeholder="John">
                </div>
                <div class="form-group col-md-6">
                  <label for="register-lastname">Last Name</label>
                  <input type="text" class="form-control" id="register-lastname" name="register-lastname" placeholder="Doe">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="register-grade">Grade</label>
                  <select class="form-control" id="register-grade" name="register-grade">
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="register-code">Chapter Code</label>
                  <input type="text" class="form-control" id="register-code" name="register-code" placeholder="Code">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Create Account</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
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
}
?>
