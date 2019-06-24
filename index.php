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
    }
  }
  else{
    $fmsg = "Invalid Login Credentials";
  }
}

//Register new users
if(isset($_POST['register-username']) and isset($_POST['register-password']) and (isset($_POST['organization-name']) or isset($_POST['organization-code']))){
  $username = $_POST['register-username'];
  $password = $_POST['register-password'];
  $orgname = $_POST['organization-name'];
  $orgcode = $_POST['organization-code'];
  $orgaction = $_POST['organization-action'];
  $username = validate($username);
  $password = validate($password);
  $password = password_hash($password, PASSWORD_DEFAULT);
  
  require('php/connect.php');
  
  $query= "SELECT id FROM users WHERE username='$username'";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  $count = mysqli_num_rows($result);
  if($count == 0){
    //Check that specified organization code exists if joining
    $orgwithcodecount = 0;
    if($orgaction == "join"){
      $query= "SELECT id FROM organizations WHERE code='$orgcode'";
      $result = mysqli_query($link, $query);
      if (!$result){
        die('Error: ' . mysqli_error($link));
      }
      list($orgid) = mysqli_fetch_array($result);
      $orgwithcodecount = mysqli_num_rows($result);
    }
    if($orgwithcodecount == 1 || $orgaction == "create"){
      //User Creation
      $query2 = "INSERT INTO users (username, password, firstname, lastname) VALUES ('$username', '$password', '$firstname', '$lastname')";
      $result2 = mysqli_query($link, $query2);
      $userid = mysqli_insert_id($link);
      if (!$result2){
        die('Error: ' . mysqli_error($link));
      }
      //Organization Creation
      if($orgaction == "create"){
        //Generate organization code
        $newOrgCode = createOrgCode();
        while(!isCodeUnique($newOrgCode)){
          $newOrgCode = createOrgCode();
        }
        //Actually perform creation query
        $query2 = "INSERT INTO organizations (name, code) VALUES ('$orgname', '$newOrgCode')";
        $result2 = mysqli_query($link, $query2);
        $orgid = mysqli_insert_id($link);
        if (!$result2){
          die('Error: ' . mysqli_error($link));
        }
      }
      //Organization Join
      $query2 = "INSERT INTO user_organization_mapping (organization, user) VALUES ('$orgid', '$userid')";
      $result2 = mysqli_query($link, $query2);
      if (!$result2){
        die('Error: ' . mysqli_error($link));
      }
      //Add Rank for Owner
      if($orgaction == "create"){
        //Actually perform creation query
        $query2 = "INSERT INTO user_ranks (user, scope, rank) VALUES ('$userid', 'organization', 'owner')";
        $result2 = mysqli_query($link, $query2);
        if (!$result2){
          die('Error: ' . mysqli_error($link));
        }
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
                <img src="images/logoColor.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
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

        <form name="loginForm" method="POST" action="?">

            Enter your username: <br>
              <input class="input1 form-control" type="text" name="user" required/>
              <a href="#" style="font-size:10px; padding-bottom:5px;" data-container="body" data-toggle="popover" data-placement="top" data-content="Ask your adviser to lookup or reset your username from the 'My Chapter' page.">Forgot Your Username?</a>
              <br>
            Enter your password: <br>
              <input class="input1 form-control" type="password" name="pass" required/>
              <a href="#" style="font-size:10px; padding-bottom:5px;" data-container="body" data-html=true data-toggle="popover" data-placement="top" data-content='<form method="post" action="../php/send_reset.php">
              <p>Enter Email Address for Password Reset</p>
              Email:<input type="email" name="email" required>
              Username:<input type="text" name="username" required>
              <input class="btn btn-primary btn-sm" type="submit" name="submit_email">
              </form>'>Forgot Your Password?</a>
              <br></br>

          <input class="btn btn-primary btn-lg" type="submit" value="Login"/>

        </form>
        </div>
      </div>
    </div>
    </div>

  <br></br>
    OR

    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Create Account</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-4">
            <div class="md-form mb-5">
              <i class="fas fa-envelope prefix grey-text"></i>
              <input type="email" id="defaultForm-email" class="form-control validate">
              <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
            </div>

            <div class="md-form mb-4">
              <i class="fas fa-lock prefix grey-text"></i>
              <input type="password" id="defaultForm-pass" class="form-control validate">
              <label data-error="wrong" data-success="right" for="defaultForm-pass">Create password</label>
            </div>

            <div class="md-form mb-4">
              <i class="fas fa-lock prefix grey-text"></i>
              <input type="password" id="defaultForm-pass" class="form-control validate">
              <label data-error="wrong" data-success="right" for="defaultForm-pass">Confirm password</label>
            </div>

          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-default">Create an Account</button>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm">Create an Account</a>
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
