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
    $query2 = "SELECT password, firstname, lastname FROM users WHERE username='$username'";
    $result2 = mysqli_query($link, $query2);
    if (!$result2){
      die('Error: ' . mysqli_error($link));
    }
    list($passwordValue, $firstnameValue, $lastnameValue) = mysqli_fetch_array($result2);
    if(password_verify($password, $passwordValue)){
      $_SESSION['username'] = $username;
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
      $query2 = "INSERT INTO users (username, password, firstname, lastname) VALUES ('$username', '$password', '$firstname', '$lastname')";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i|Ubuntu:400,400i,700,700i&display=swap" rel="stylesheet">
    <title>TSABox</title>
  </head>
  <body>
    <nav class="header bg-blue navbar navbar-expand-lg navbar-dark" style="min-height:95px; z-index: 1000;">
        <a class="navbar-brand" href="index.html">
          <div class="row">
            <div class="col nopadding">
                <img src="images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
            </div>
            <div class="col nopadding">
                <p>TSABOX</p>
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

    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal">Login</button>
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

  <br>
  <?php echo $fmsg; ?>

    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#registerModal">Register</button>
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
  </body>




  <footer>
    <div class="bg-blue color-white py-3">
        <center>
        <p>
          For more information, visit <a href="pages/about.html" style="color:white;">The About Page</a>.
        </p>
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
