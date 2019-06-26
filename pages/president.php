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

require('../php/connect.php');

$query = "SELECT chapter FROM user_chapter_mapping WHERE username='$username'";
$result = mysqli_query($link, $query);
if (!$result){
  die('Error: ' . mysqli_error($link));
}
list($chapter) = mysqli_fetch_array($result);

//Handler to create a new goal
if(isset($_POST['task-name'])){

  $taskname = validate($_POST['task-name']);
  $taskdesc = validate($_POST['task-description']);
  $taskofficer = validate($_POST['task-officer']);

  require('../php/connect.php');
  $query = "INSERT INTO officergoals (name, description, chapter, creator, officer) VALUES ('$taskname', '$taskdesc', '$chapter', '$username', '$taskofficer')";
  $result = mysqli_query($link,$query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  mysqli_close($link);

}

if(isset($_POST['task-delete'])){

  $taskdelete = validate($_POST['task-delete']);

  require('../php/connect.php');
  $query = "DELETE FROM officergoals WHERE id='$taskdelete'";
  $result = mysqli_query($link,$query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }
  mysqli_close($link);

}

//Handler to complete a task
if(isset($_POST['task-complete'])){

  require('../php/connect.php');

  $taskid = $_POST['task-complete'];

  $sql = "UPDATE officergoals SET status='complete' WHERE id='$taskid'";
  if (!mysqli_query($link, $sql)){
    die('Error: ' . mysqli_error($link));
  }
  $sql = "UPDATE officergoals SET creator='$username' WHERE id='$taskid'";
  if (!mysqli_query($link, $sql)){
    die('Error: ' . mysqli_error($link));
  }
  $sql = "UPDATE officergoals SET date=NOW() WHERE id='$taskid'";
  if (!mysqli_query($link, $sql)){
    die('Error: ' . mysqli_error($link));
  }

  mysqli_close($link);

}

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

  //get poster
  $poster = $username;

  require('../php/connect.php');

  $query = "INSERT INTO officerfiles (name, size, type, content, date, poster, chapter) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now(), '$poster', '$chapter')";

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

  require('../php/connect.php');

  $query = "DELETE FROM officerfiles WHERE id = '$fileid'";

  $result = mysqli_query($link, $query);

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
                <a class="dropdown-item active" href="president.php">President</a>
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
            <h1>
              President
            </h1>
            <small>Manage your officer team's plan of work</small><br>

           <?php if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ ?>

            <div class="row pt-5">
              <div class="col-sm-12">
                <h3 class="band-blue">The Officer Team</h3>
                <?php

                require('../php/connect.php');

                $query="SELECT firstname, lastname FROM users WHERE username IN (SELECT username FROM ranks WHERE rank='officer' AND username IN (SELECT username FROM user_chapter_mapping WHERE chapter='$chapter'))";
                $result = mysqli_query($link, $query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }

                while($resultArray = mysqli_fetch_array($result)){

                  $firstname = $resultArray['firstname'];
                  $lastname = $resultArray['lastname'];

                  echo "<p>". $firstname . " " . $lastname . "</p>";
                }
                ?>
              </div>
            </div>

            <div class="row pt-5">
              <div class="col-sm-12">
                <h3 class="band-red">Tasks in Progress</h3>

                <div class="d-flex justify-content-start flex-wrap">
                  <?php

                  require('../php/connect.php');

                  $query="SELECT id, name, description, creator, date, officer FROM officergoals WHERE chapter='$chapter' AND status='progress'";
                  $result = mysqli_query($link, $query);
                  if (!$result){
                    die('Error: ' . mysqli_error($link));
                  }

                  if(mysqli_num_rows($result) == 0){
                    echo "No Tasks in Progress";
                  }
                  else{
                    while($resultArray = mysqli_fetch_array($result)){

                      $taskname = $resultArray['name'];
                      $taskid = $resultArray['id'];
                      $taskdesc = $resultArray['description'];
                      $taskcreator = $resultArray['creator'];
                      $taskdate = $resultArray['date'];
                      $taskofficer = $resultArray['officer'];

                      $query2="SELECT firstname, lastname FROM users WHERE username='$taskcreator'";
                      $result2 = mysqli_query($link, $query2);
                      if (!$result2){
                        die('Error: ' . mysqli_error($link));
                      }
                      list($firstname,$lastname) = mysqli_fetch_array($result2);

                      ?>

                      <div class="taskcard">
                        <div class="row">
                          <div class="col-12">
                            <h5><?php echo $taskname; ?></h5>
                            <h6><?php echo $taskofficer; ?></h6>
                          </div>
                        </div>
                        <hr>
                        <p><?php echo $taskdesc; ?></p>
                        <small>Created <?php echo $taskdate; ?> by <?php echo $firstname . " " . $lastname; ?></small>
                        <hr>
                        <div class="row">
                          <div class="col-sm-6">
                            <form method="post">
                              <input type="hidden" name="task-complete" value="<?php echo $taskid; ?>">
                              <input type="submit" value="Complete" class="btn btn-link">
                            </form>
                          </div>
                          <div class="col-sm-6">
                            <form method="post">
                              <input type="hidden" name="task-delete" value="<?php echo $taskid; ?>">
                              <input type="submit" value="Delete" class="btn btn-danger">
                            </form>
                          </div>
                        </div>
                      </div>

                      <?php
                    }
                  }
                  ?>
                </div>

                <br>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal">
                  Create Task
                </button>

              </div>
            </div>

            <div class="row pt-5">
              <div class="col-sm-12">
                <h3 class="band-blue">Complete Tasks</h3>

                <div class="d-flex justify-content-start flex-wrap">
                  <?php

                  require('../php/connect.php');

                  $query="SELECT id, name, description, creator, date, officer FROM officergoals WHERE chapter='$chapter' AND status='complete'";
                  $result = mysqli_query($link, $query);
                  if (!$result){
                    die('Error: ' . mysqli_error($link));
                  }

                  if(mysqli_num_rows($result) == 0){
                    echo "No Completed Tasks";
                  }
                  else{
                    while($resultArray = mysqli_fetch_array($result)){

                      $taskname = $resultArray['name'];
                      $taskid = $resultArray['id'];
                      $taskdesc = $resultArray['description'];
                      $taskcreator = $resultArray['creator'];
                      $taskdate = $resultArray['date'];
                      $taskofficer = $resultArray['officer'];

                      $query2="SELECT firstname, lastname FROM users WHERE username='$taskcreator'";
                      $result2 = mysqli_query($link, $query2);
                      if (!$result2){
                        die('Error: ' . mysqli_error($link));
                      }
                      list($firstname,$lastname) = mysqli_fetch_array($result2);

                      ?>

                      <div class="taskcard">
                        <div class="row">
                          <div class="col-12">
                            <h5><?php echo $taskname; ?></h5>
                            <h6><?php echo $taskofficer; ?></h6>
                          </div>
                        </div>
                        <hr>
                        <p><?php echo $taskdesc; ?></p>
                        <small>Completed <?php echo $taskdate; ?> by <?php echo $firstname . " " . $lastname; ?></small>
                        <hr>
                        <form method="post">
                          <input type="hidden" name="task-delete" value="<?php echo $taskid; ?>">
                          <input type="submit" value="Delete" class="btn btn-danger">
                        </form>
                      </div>

                      <?php
                    }
                  }
                  ?>
                </div><br>
              </div>
            </div>

            <div class="row pt-5">
              <div class="col-sm-12">
                <h3 class="band-red">Files</h3>
                <form class="pb-3" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                  <div class="form-control">
                    <h5>Upload Files</h5>
                    <div class="row">
                    <div class="col-8">
                      <input style="font-size:16px;" name="userfile" type="file" id="userfile">
                    </div>
                    <div class="col-4">
                      <input name="uploadFile" type="submit" class="btn btn-primary" id="uploadFile" value="Upload">
                    </div>
                    </div>
                  </div>
                </form>

                <table style="width:80%;">
                  <h5>Download Files</h5>

                  <?php
                  require('../php/connect.php');
                  $query="SELECT id, name, date, poster FROM officerfiles WHERE chapter IN (SELECT chapter FROM user_chapter_mapping WHERE username='$username')";
                  $result = mysqli_query($link, $query);
                  if (!$result){
                    die('Error: ' . mysqli_error($link));
                  }
                  $doMemberSkip = 0;
                  if(mysqli_num_rows($result) == 0){
                    echo "No Files Found!<br>";
                  }
                  else{
                    while(list($id, $name, $date, $poster) = mysqli_fetch_array($result)){
                        ?>
                        <tr>
                          <td><a class="text-primary" href="../php/download_officerfiles.php?id=<?php echo "".$id ?>" style="float:left;"><?php echo "".$name ?></a></td>
                          <td><p style="float:right;"><?php echo "".$date ?></p></td>
                          <?php
                          $query3="SELECT firstname, lastname FROM users WHERE username='$poster'";
                          $result3 = mysqli_query($link, $query3);
                          if (!$result3){
                            die('Error: ' . mysqli_error($link));
                          }
                          list($firstname,$lastname) = mysqli_fetch_array($result3);
                          ?>
                          <td><p style="float:right;"><?php echo "".$firstname." ".$lastname ?></p></td>
                          <td>
                            <form method="post" id="deleteFileForm">
                              <input name="deleteFileID" type="hidden" value="<?php echo $id ?>">
                              <input name="deleteFileName" type="hidden" value="<?php echo $name ?>">
                              <input style="padding:0 0 0 0;" type="submit" class="close btn btn-link" value="&times";>
                            </form>
                          </td>
                        </tr>
                        <?php
                    }
                  }

                  mysqli_close($link);
                  ?>

                </table>
              </div>
            </div>

            <div class="row pt-5">
              <div class="col-sm-12">
                <h3 class="band-blue">Progress Summary</h3>
                <script src="../js/Chart.js"></script>
                <canvas id="chartjs-4" class="chartjs" width="962" height="481" style="display: block; height: 385px; width: 770px;"></canvas>

                <script>new Chart(document.getElementById("chartjs-4"),{"type":"doughnut","data":{"labels":["In Progress","Complete"],"datasets":[{"label":"Tasks","data":[<?php
                require('../php/connect.php');
                $query = "SELECT COUNT(id) FROM officergoals WHERE chapter='$chapter' AND status='progress'";
                $result = mysqli_query($link,$query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($progress_weight) = mysqli_fetch_array($result);
                echo $progress_weight;
                ?>,<?php
                require('../php/connect.php');
                $query = "SELECT COUNT(id) FROM officergoals WHERE chapter='$chapter' AND status='complete'";
                $result = mysqli_query($link,$query);
                if (!$result){
                  die('Error: ' . mysqli_error($link));
                }
                list($progress_weight) = mysqli_fetch_array($result);
                echo $progress_weight;
                ?>],"backgroundColor":["rgb(255, 99, 132)","rgb(54, 162, 235)"]}]}});</script>

                <?php 
                mysqli_close($link);
                ?>

              </div>
            </div>

          </div>

          <!-- Task Modal -->
          <div class="modal fade" id="taskModal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Create Task</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <form method="POST" class="pt-4">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="task-name">Task Name</label>
                        <input type="text" maxlength="50" class="form-control" id="task-name" name="task-name" placeholder="Name" required>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="task-name">Task Description</label>
                        <textarea class="form-control" maxlength="500" id="task-description" name="task-description" rows="3"></textarea>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="task-name">Assigned Officer</label>
                        <select name="task-officer" class="form-control">
                          <option value="President">President</option>
                          <option value="Vice President">Vice President</option>
                          <option value="Secretary">Secretary</option>
                          <option value="Treasurer">Treasurer</option>
                          <option value="Reporter">Reporter</option>
                          <option value="Sergeant at Arms">Sergeant at Arms</option>
                          <option value="Parliamentarian">Parliamentarian</option>
                          <option value="Historian">Historian</option>
                          <option value="Committee Chair">Committee Chair</option>
                          <option value="Other/All">Other/All</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                  </form>
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

        <footer <?php if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ ?>style="position:relative;"<?php } ?>>
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
