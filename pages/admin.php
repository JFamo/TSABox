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

if(!isset($passmatch)){
  $passmatch=true;
}

require('../php/connect.php');
$query = "SELECT chapter FROM user_chapter_mapping WHERE username='$username'";
$resultChapter = mysqli_query($link, $query);
list($chapter) = mysqli_fetch_array($resultChapter);
$rank = $_SESSION['rank'];

//Change Rank to Officer
if(isset($_POST['users'])){
  $user = $_POST['users'];

  $query = "UPDATE ranks SET rank='officer' WHERE username='$user'";

  $result = mysqli_query($link, $query);

  if(!$result){
    die('Error: ' . mysqli_error($link));
  }
}

//Change Rank to Member
if(isset($_POST['officers'])){
  $user = $_POST['officers'];

  $query = "UPDATE ranks SET rank='member' WHERE username='$user'";

  $result = mysqli_query($link, $query);

  if(!$result){
    die('Error: ' . mysqli_error($link));
  }
}

//change password
if(isset($_POST['newPassword']) && isset($_POST['confirmPassword'])){
  require('../php/connect.php');


  $newPassword = $_POST['newPassword'];
  $confirmPassword = $_POST['confirmPassword'];

  if($newPassword==$confirmPassword){
    $query= "SELECT username FROM users WHERE username='$user'";
    $result = mysqli_query($link, $query);
    if (!$result){
      die('Error: ' . mysqli_error($link));
    }

    $count = mysqli_num_rows($result);
    if($count == 1){

      echo $newPassword;
      $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      echo $newPassword;
      $query3 = "UPDATE users SET password='$newPassword' WHERE username='$user'";
      $result3 = mysqli_query($link, $query3);
      if (!$result3){
        die('Error: ' . mysqli_error($link));
      }
    }
  }

  else{
    $passmatch=false;

  }
}

    //Delete A User

if(isset($_POST['del'])){
  $u=$_POST['del'];
  if($_POST['verify'] == 'Yes'){
    $query="SELECT balance FROM user_balance WHERE user='$u'";
    $result4=mysqli_query($link,$query);
    if(!$result4){
      die('Error: ' . mysqli_error($link));
    }
    list($bal)=mysqli_fetch_array($result4);


    $query = "SELECT chapter FROM user_chapter_mapping WHERE username='$u'";
    $resultChapter = mysqli_query($link, $query);
    if(!$resultChapter){
      die('Error: ' . mysqli_error($link));
    }
    list($chapter) = mysqli_fetch_array($resultChapter);


    $reason="User Deleted";

    $query = "INSERT INTO transactions (personto, description, amount, chapter, date) VALUES ('chapter','$reason','$bal','chapter', NOW())";

    $transfer=mysqli_query($link, $query);
    if(!$transfer){
      die('Error: ' . mysqli_error($link));
    }

    $query = "DELETE FROM ranks WHERE username='$u'";
    $d=mysqli_query($link,$query);
    if(!$d){
      die('Error: ' . mysqli_error($link));
    }
    $query = "DELETE FROM users WHERE username='$u'";
    $d=mysqli_query($link,$query);
    if(!$d){
      die('Error: ' . mysqli_error($link));
    }
    $query = "DELETE FROM user_chapter_mapping WHERE username='$u'";
    $d=mysqli_query($link,$query);
    if(!$d){
      die('Error: ' . mysqli_error($link));
    }
    $query = "DELETE FROM user_balance WHERE user='$u'";
    $d=mysqli_query($link,$query);
    if(!$d){
      die('Error: ' . mysqli_error($link));
    }
    $query = "DELETE FROM user_team_mapping WHERE username='$u'";
    $d=mysqli_query($link,$query);
    if(!$d){
      die('Error: ' . mysqli_error($link));
    }


  }

}


if($rank == "adviser" || $rank == "admin") {

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
                <?php if($rank == "adviser" || $rank == "admin") { ?>
                  <li class="nav-item">
                    <a class="nav-link active" href="../pages/admin.php">
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
              <h1>Admin</h1>
              <small>Edit important chapter data</small><br>
              <small class="text-danger">Warning! Functions of this page should be used carefully and intentionally!</small>
              <?php if(!$passmatch){ ?>
              <div class="alert alert-danger">
                 Passwords do not match. Failed to reset.
              </div>
            <?php }?>

              <!-- Change User Rank -->
              <center>
                <div class = "container mt-5 contentcard">
                  <h3 style="border-bottom:2px solid #CF0C0C">Change Member Ranks</h3>
                  <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    <div class="row">

                      <div class="col-8">
                        <small>Select Member</small>
                        <!--Give each user as an option-->
                        <select id="users" name="users" class="form-control">
                          <?php

                          require('../php/connect.php');

                          $query="SELECT username FROM user_chapter_mapping WHERE chapter=$chapter ORDER BY username ASC";

                          $result = mysqli_query($link, $query);

                          if (!$result){
                            die('Error: ' . mysqli_error($link));
                          } 

                          while(list($user) = mysqli_fetch_array($result)){
                            $query = "SELECT firstname,lastname FROM users WHERE username='$user'";
                            $res=mysqli_query($link,$query);
                            if(!$res){
                              die('Error: ' . mysqli_error($link));

                            }
                            list($first,$last)=mysqli_fetch_array($res);

                            $query = "SELECT rank FROM ranks WHERE username='$user'";
                            $res2=mysqli_query($link,$query);
                            if(!$res2){
                              die('Error: ' . mysqli_error($link));
                            }

                            list($thisrank) = mysqli_fetch_array($res2);

                            if($thisrank == 'member'){
                              ?>

                              <option value="<?php echo $user; ?>"><?php echo $first . ' ' . $last; ?></option>

                              <?php
                            }
                          }


                          mysqli_close($link);

                          ?>
                        </select>
                      </div>
                      <div class="col-4">
                        <small></small><br>
                        <input type="submit" class="btn btn-primary" value="Make Officer">
                      </div>
                    </div>


                  </form>
                  <form method="post">
                    <div class="row">
                      <div class="col-8">
                        <small>Select Officer</small>
                        <!--Give each user as an option-->
                        <select id="officers" name="officers" class="form-control">


                          <?php

                          require('../php/connect.php');

                          $query="SELECT username FROM user_chapter_mapping WHERE chapter=$chapter ORDER BY username ASC";

                          $result = mysqli_query($link, $query);

                          if (!$result){
                            die('Error: ' . mysqli_error($link));
                          } 

                          while(list($user) = mysqli_fetch_array($result)){
                            $query = "SELECT firstname,lastname FROM users WHERE username='$user'";
                            $res=mysqli_query($link,$query);
                            if(!$res){
                              die('Error: ' . mysqli_error($link));

                            }
                            list($first,$last)=mysqli_fetch_array($res);

                            $query = "SELECT rank FROM ranks WHERE username='$user'";
                            $res2=mysqli_query($link,$query);
                            if(!$res2){
                              die('Error: ' . mysqli_error($link));
                            }

                            list($thisrank) = mysqli_fetch_array($res2);

                            if($thisrank == 'officer'){
                              ?>

                              <option value="<?php echo $user; ?>"><?php echo $first . ' ' . $last; ?></option>

                              <?php
                            }
                          }
                          mysqli_close($link);
                          ?>
                        </select>
                      </div>
                      <div class="col-4">   
                        <small></small><br>
                        <input type="submit" class="btn btn-primary" value="Make Member">
                      </div>
                    </div>
                  </form>
                </div>

                <center>
                  <div class = "container mt-5 contentcard">
                    <h3 style="border-bottom:2px solid #CF0C0C">Reset Member Password</h3>
                    <form method="post">
                      <div class="row">

                            <div class="col-sm-8">

                                <small>Select Member</small>
                                <!--Give each user as an option-->
                                <select id="officers" name="officers" class="form-control">

                                  <?php
                                  require('../php/connect.php');
                                  $query="SELECT username FROM user_chapter_mapping WHERE chapter=$chapter ORDER BY username ASC";
                                  $result = mysqli_query($link, $query);

                                  if (!$result){
                                    die('Error: ' . mysqli_error($link));
                                  } 

                                  while(list($user) = mysqli_fetch_array($result)){
                                    $query = "SELECT firstname,lastname FROM users WHERE username='$user'";
                                    $res=mysqli_query($link,$query);
                                    if(!$res){
                                      die('Error: ' . mysqli_error($link));
                                    }
                                    list($first,$last)=mysqli_fetch_array($res);              
                                    ?>
                                    <option value="<?php echo $user; ?>"><?php echo $first . ' ' . $last; ?></option>
                                    <?php
                                  }
                                  mysqli_close($link);
                                  ?>   
                                </select>

                            </div>
                            <div class="col-sm-4">
                              <small><br></small>
                              <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                      </div>
                      <div class="row">
                        <div class ="col-sm-6">
                          <small>New Password</small>
                          <input class="form-control" type="password" name="newPassword" maxlength=30  rows="1" required>
                        </div>
                        <div class ="col-sm-6">
                          <small>Confirm Password</small>
                          <input class="form-control" type="password" name="confirmPassword" maxlength=30  rows="1" required>
                        </div>
                      </div>
                    </form>   
                  </div>
                </center>

                        <center>
                          <div class = "container mt-5 contentcard">
                            <h3 style="border-bottom:2px solid #CF0C0C">Remove User</h3>
                            <form method="post" enctype="multipart/form-data">
                              <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                              <div class="row">

                                <div class="col-8">
                                  <small>Select Member</small>
                                  <!--Give each user as an option-->
                                  <select id="del" name="del" class="form-control">
                                    <?php

                                    require('../php/connect.php');

                                    $query="SELECT username FROM user_chapter_mapping WHERE chapter=$chapter ORDER BY username ASC";

                                    $result = mysqli_query($link, $query);

                                    if (!$result){
                                      die('Error: ' . mysqli_error($link));
                                    } 

                                    while(list($user) = mysqli_fetch_array($result)){
                                      $query = "SELECT firstname,lastname FROM users WHERE username='$user'";
                                      $res=mysqli_query($link,$query);
                                      if(!$res){
                                        die('Error: ' . mysqli_error($link));

                                      }
                                      list($first,$last)=mysqli_fetch_array($res);

                                      $query = "SELECT rank FROM ranks WHERE username='$user'";
                                      $res2=mysqli_query($link,$query);
                                      if(!$res2){
                                        die('Error: ' . mysqli_error($link));
                                      }

                                      list($thisrank) = mysqli_fetch_array($res2);

                                      if($user != $username){
                                        ?>

                                        <option value="<?php echo $user; ?>"><?php echo $first . ' ' . $last; ?></option>

                                        <?php
                                      }
                                    }


                                    mysqli_close($link);

                                    ?>
                                  </select>
                                </div>
                                <div class="col-4">
                                  <small>Verify Deletion</small>
                                  <select name="verify" class="form-control">
                                    <option>No</option>
                                    <option>Yes</option>
                                  </select>
                                </div>
                                <div class="col-4">
                                  <small></small><br>
                                  <input type="submit" class="btn btn-primary" value="Remove User">
                                </div>
                              </div></div>
                            </form>   
                          </center>

                          <!-- Optional JavaScript -->
                          <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                          <script src="../js/jquery-3.3.1.slim.min.js"></script>
                          <script src="../js/popper.min.js"></script>
                          <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
                          <script src="../js/scripts.js"></script>
                        </body>
                      </form>
                    </div>

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

                  <?php }

                  else{ ?>
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
                    <a class="dropdown-item" href="chapter.php">My Chapter</a>
                    <a class="dropdown-item" href="social.php">Find Friends</a>
                  </div>
                </li>
                <?php if($rank == "adviser" || $rank == "admin") { ?>
                  <li class="nav-item">
                    <a class="nav-link" href="../php/admin.php">
                      Admin
                    </a>
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
              <h1>Admin</h1>
              <small>Manage your chapter</small>

              <p>Only Admins and Advisers can view this page</p>
                 <footer style="position:bottom;">
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

                 <?php }



                   ?>
