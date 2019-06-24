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

function getChapterBalance()
{
  $returnValue = 0;
  $chapter = 1; //$_SESSION['chapter'];
  require('../php/connect.php');
  $transQ = "SELECT chapter, amount FROM chapter_transactions WHERE chapter='$chapter'";
  $transR = mysqli_query($link, $transQ);
  if (!$transR){
    die('Error: ' . mysqli_error($link));
  }
  while($row = mysqli_fetch_array($transR)){
      $returnValue += $row['amount'];
  }

  return $returnValue;
}

session_start();
$rank = "adviser";
$username = $_SESSION['username'];
/*
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];
*/

//handling transactions
if(isset($_POST['amount'])){

  //variables assignment
  $personfrom = $_POST['personfrom'];
  $personto = $_POST['personto'];
  $amount = $_POST['amount'];
  $description = addslashes($_POST['description']);

  require('../php/connect.php');

  

  //make the transaction
  $query = "INSERT INTO transactions (personto, personfrom, description, amount, date, chapter) VALUES ('$personto', '$personfrom', '$description', '$amount', now(), 1)";

  $result = mysqli_query($link, $query);

  if (!$result){
    die('Error: ' . mysqli_error($link));
  }

  //update balances
  if($personto != "expense" && $personto != "chapter"){

    $query2 = "UPDATE users SET balance=balance+'$amount' WHERE id='$personto' AND chapter='$chapter'";

    $result2 = mysqli_query($link, $query2);

    if (!$result2){
      die('Error: ' . mysqli_error($link));
    }

  }
  if($personfrom != "income" && $personfrom != "chapter"){

    $query3 = "UPDATE users SET balance=balance-'$amount' WHERE id='$personfrom' AND chapter='$chapter'";

    $result3 = mysqli_query($link, $query3);

    if (!$result3){
      die('Error: ' . mysqli_error($link));
    }

  }
  /*
  $activityForm = "Transacted " . $amount . " from " . $personfrom . " to " . $personto;
    $sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$fullname', '$activityForm', now(), '$chapter')";

    if (!mysqli_query($link, $sql)){
      die('Error: ' . mysqli_error($link));
    }
  */
  mysqli_close($link);

  $fmsg =  "Transaction of ".$amount." Completed Successfully!";

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
        <a class="navbar-brand" href="index.html">
          <div class="row">
            <div class="col nopadding">
                <img src="../images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
            </div>
  <a class="navbar-brand" href="#">TSABox</a>
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
      <li class="nav-item">
        <a class="nav-link" href="../php/logout.php">
          Logout
        </a>
      </li>
    </ul>
  </div>
</nav>
<?php if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ ?>
  <div class="container">

    <div class="row">
      <div class = "col-sm-12">
        <p>Chapter balance: $<?php echo number_format((float)getChapterBalance(), 2, '.', '') ?>
        </p>
        <p>Individual Balances: </p>
      </div>
    </div>

    <?php

          //User Balances

          require('../php/connect.php');

          $query="SELECT * FROM user_balance WHERE chapter=1"; //chapter=$chapter

          $result = mysqli_query($link, $query);

          if (!$result){
            die('Error: ' . mysqli_error($link));
          }   

          if(mysqli_num_rows($result) == 0){
            echo "No Transactions Found!<br>";
          }
          else{
            while(list($user, $amount) = mysqli_fetch_array($result)){
              ?>
              <div class="row">
                <div class = "col-sm-9">
                <p><?php echo "User : ".$user ?></p>
              </div>
                <div class = "col-sm-3">
                <p><?php echo "$".$amount ?></p>
              </div>
            </div>
              
              <?php
            }
          }
        }
          ?>
              
    </div>
  </div>

      <!-- transaction stuff -->

        <div class="adminDataSection">
          <p class="userDashSectionHeader" style="padding-left:0px;">Transact</p>
          <form method="post" enctype="multipart/form-data" class="fileForm">
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
            <div class="form-row">
              <div class="col-4">
                <small>$ Amount</small>
                <input name="amount" type="number" id="amount" value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : '' ?>">
              </div>
              <div class="col-4">
                  <small>From</small><br>
              <!--Give each user as an option-->
              <select id="personfrom" name="personfrom">
                <option value="income">Income</option>
                <option value="chapter">Chapter</option>
                <?php

                require('../php/connect.php');

                $query="SELECT username FROM user_chapter_mapping WHERE chapter=1 ORDER BY username ASC";

                $result = mysqli_query($link, $query);

                if (!$result){
                  die('Error: ' . mysqli_error($link));
                } 

                while(list($username) = mysqli_fetch_array($result)){
                  //if($personrank != "admin"){ ???
                  ?>

                  <option><?php echo $username ?></option>
                  
                  <?php
                  }
                //}
                    
                mysqli_close($link);

                ?>
              </select>
              </div>
              <div class="col-4">
                <small>To</small><br>
                <!--Give each user as an option-->
              <select id="personto" name="personto">
                <option value="expense">Expense</option>
                <option value="chapter">Chapter</option>
                <?php

                require('../php/connect.php');

                $query="SELECT username FROM user_chapter_mapping WHERE chapter=1 ORDER BY username ASC";

                $result = mysqli_query($link, $query);

                if (!$result){
                  die('Error: ' . mysqli_error($link));
                } 

                while(list($username) = mysqli_fetch_array($result)){
                  //if($personrank != "admin"){ ???
                  ?>

                  <option><?php echo $username ?></option>
                  
                  <?php
                  }
                //}
                    
                mysqli_close($link);

                ?>
              </select>
              </div>
            </div>
            <div class="form-row">
                <div class="col-8">
                  <small>Description</small>
                  <input name="description" style="width:100%;" type="text" id="description" value="<?php echo isset($_POST['description']) ? $_POST['description'] : '' ?>">
                </div>
                <div class="col-4">
                  <input name="transact" type="submit" class="btn btn-primary" id="transact" value="Transact">
                </div>
              </div>
          </form>
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
          For more information, visit <a href="about.php" style="color:white;">The About Page</a>.
        </p>
        <p>
          Made by Team T1285, 2018-2019, All Rights Reserved
        </p>
        </center>
    </div>
  </footer>

</html>