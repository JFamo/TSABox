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
  //Media categories
  $digital = 1;
  $display = 1; 
  $engineering = 1;
  $presentation = 1;
  //Skill Categories
  $art = 1;
  $tech = 1; 
  $material = 1; 
  $design = 1;
  $speaking = 1; 
  $audiovisual = 1;
  $research = 1;

  //Assigning the user point values
  if(isset($_POST['question1']) && isset($_POST['question2']))
  {
    $digital = ($_POST['question1'] + $_POST['question2']);
  }
  if(isset($_POST['question3']) && isset($_POST['question4']))
  {
    $display = validate($_POST['question3'] + $_POST['question4']);
  }
  if(isset($_POST['question5']) && isset($_POST['question6']))
  {
    $engineering = validate($_POST['question5'] + $_POST['question6']);
  }
  if(isset($_POST['question7']) && isset($_POST['question8']))
  {
    $presentation = validate($_POST['question7'] + $_POST['question8']);
  }

  if(isset($_POST['question9']) && isset($_POST['question10']))
  {
    $art = validate($_POST['question9'] + $_POST['question10']);
  }
  if(isset($_POST['question11']) && isset($_POST['question12']))
  {
    $tech = validate($_POST['question11'] + $_POST['question12']);
  }
  if(isset($_POST['question13']) && isset($_POST['question14']))
  {
    $material = validate($_POST['question13'] + $_POST['question14']);
  }
  if(isset($_POST['question15']) && isset($_POST['question16']))
  {
    $design = validate($_POST['question15'] + $_POST['question16']);
  }
  if(isset($_POST['question17']) && isset($_POST['question18']))
  {
    $speaking = validate($_POST['question17'] + $_POST['question18']);
  }
  if(isset($_POST['question19']) && isset($_POST['question20']))
  {
    $audiovisual = validate($_POST['question19'] + $_POST['question20']);
  }
  if(isset($_POST['question21']) && isset($_POST['question22']))
  {
    $research = validate($_POST['question21'] + $_POST['question22']);
  }

  $mediaTotal = $digital + $display + $engineering + $presentation;
  $skillTotal = $art + $tech + $material + $design + $speaking + $audiovisual + $research;


  //test
  echo $digital, $display, $engineering, $presentation, $art, $tech, $material, $design, $speaking, $audiovisual, $research;

  //Media category percentages
  $mediaPercentages = array($digital, $display, $engineering, $presentation);  
  foreach($mediaPercentages as $i => $value)
  {
    if ($mediaTotal == 0) {$mediaPercentages[$i] = 0;}
    else {$mediaPercentages[$i] = ($mediaPercentages[$i] / $mediaTotal) * 100;}    
  }
  print_r($mediaPercentages);

//Skill category percentages
  $skillPercentages = array($art, $tech, $material, $design, $speaking, $audiovisual, $research);
  foreach($skillPercentages as $i => $value)
  {
    if ($skillTotal == 0) {$skillPercentages[$i] = 0;}
    else {$skillPercentages[$i] = ($skillPercentages[$i] / $skillTotal) * 100;}
  }

//Comparing total difference in percentages from table values to user values
  require('../php/connect.php');
  $query="SELECT event, digital, display, engineering, presentation FROM quizcategories";
  $result = mysqli_query($link, $query);
  if (!$result){
    die('Error: ' . mysqli_error($link));
  }

  $index = 0;
  while(list($event, $digital, $display, $engineering, $presentation) = mysqli_fetch_array($result)){
    $compat[$index] = ($digital / $mediaPercentages[0]) + ($display / $mediaPercentages[1]) + ($engineering / $mediaPercentages[2]) + ($presentation / $mediaPercentages[3]);
    echo "CMPTI" . $compat[$index] . "<br>";
    echo $digital;
    $name[$index] = $event;
    $index++;
  }
    print_r($compat);

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
        <a class="navbar-brand" href="#">
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

<!-- Title -->
<div class="container" id="content">
  <h1> Event Interest Quiz </h1>
  <small> Learn which events you may be interested in </small>
</div>

<!-- Questions -->
<form method="POST">
  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I like working with computer applications. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question1" id="q1sagree" value="6">
            <label class="form-check-label" for="q1sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question1" id="q1agree" value="4">
            <label class="form-check-label" for="q1agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question1" id="q1neutral" value="2" required>
            <label class="form-check-label" for="q1neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question1" id="q1disagree" value="1">
            <label class="form-check-label" for="q1disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question1" id="q1sdisagree" value="0">
            <label class="form-check-label" for="q1sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I prefer working or designing digitally than physically. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question2" id="q2sagree" value="6">
            <label class="form-check-label" for="q2sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question2" id="q2agree" value="4">
            <label class="form-check-label" for="q2agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question2" id="q2neutral" value="2" required>
            <label class="form-check-label" for="q2neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question2" id="q2disagree" value="1">
            <label class="form-check-label" for="q2disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question2" id="q2sdisagree" value="0">
            <label class="form-check-label" for="q2sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> Arranging findings on a poster or display is appealing. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question3" id="q3sagree" value="6">
            <label class="form-check-label" for="q3sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question3" id="q3agree" value="4">
            <label class="form-check-label" for="q3agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question3" id="q3neutral" value="2" required>
            <label class="form-check-label" for="q3neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question3" id="q3disagree" value="1">
            <label class="form-check-label" for="q3disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question3" id="q3sdisagree" value="0">
            <label class="form-check-label" for="q3sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> Decorating and designing a poster or display sounds fun, compared to talking about it. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question4" id="q4sagree" value="6">
            <label class="form-check-label" for="q4sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question4" id="q4agree" value="4">
            <label class="form-check-label" for="q4agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question4" id="q4neutral" value="2" required>
            <label class="form-check-label" for="q4neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question4" id="q4disagree" value="1">
            <label class="form-check-label" for="q4disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question4" id="q4sdisagree" value="0">
            <label class="form-check-label" for="q4sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> Designing and building a model car sounds very appealing. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question5" id="q5sagree" value="6">
            <label class="form-check-label" for="q5sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question5" id="q5agree" value="4">
            <label class="form-check-label" for="q5agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question5" id="q5neutral" value="2" required>
            <label class="form-check-label" for="q5neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question5" id="q5disagree" value="1">
            <label class="form-check-label" for="q5disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question5" id="q5sdisagree" value="0">
            <label class="form-check-label" for="q5sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I consider wood-working and/or construction a specialty of mine. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question6" id="q6sagree" value="6">
            <label class="form-check-label" for="q6sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question6" id="q6agree" value="4">
            <label class="form-check-label" for="q6agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question6" id="q6neutral" value="2" required>
            <label class="form-check-label" for="q6neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question6" id="q6disagree" value="1">
            <label class="form-check-label" for="q6disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question6" id="q6sdisagree" value="0">
            <label class="form-check-label" for="q6sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I prefer talking about subjects rather than displaying information on them. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question7" id="q7sagree" value="6">
            <label class="form-check-label" for="q7sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question7" id="q7agree" value="4">
            <label class="form-check-label" for="q7agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question7" id="q7neutral" value="2" required>
            <label class="form-check-label" for="q7neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question7" id="q7disagree" value="1">
            <label class="form-check-label" for="q7disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question7" id="q7sdisagree" value="0">
            <label class="form-check-label" for="q7sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I like working on-the-spot more than preparing projects. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question8" id="q8sagree" value="6">
            <label class="form-check-label" for="q8sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question8" id="q8agree" value="4">
            <label class="form-check-label" for="q8agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question8" id="q8neutral" value="2" required>
            <label class="form-check-label" for="q8neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question8" id="q8disagree" value="1">
            <label class="form-check-label" for="q8disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question8" id="q8sdisagree" value="0">
            <label class="form-check-label" for="q8sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I am confident in my artistic ability. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question9" id="q9sagree" value="6">
            <label class="form-check-label" for="q9sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question9" id="q9agree" value="4">
            <label class="form-check-label" for="q9agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question9" id="q9neutral" value="2" required>
            <label class="form-check-label" for="q9neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question9" id="q9disagree" value="1">
            <label class="form-check-label" for="q9disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question9" id="q9sdisagree" value="0">
            <label class="form-check-label" for="q9sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I am interested in artistic concepts, including music. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question10" id="q10sagree" value="6">
            <label class="form-check-label" for="q10sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question10" id="q10agree" value="4">
            <label class="form-check-label" for="q10agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question10" id="q10neutral" value="2" required>
            <label class="form-check-label" for="q10neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question10" id="q10disagree" value="1">
            <label class="form-check-label" for="q10disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question10" id="q10sdisagree" value="0">
            <label class="form-check-label" for="q10sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I like programming or web developing. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question11" id="q11sagree" value="6">
            <label class="form-check-label" for="q11sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question11" id="q11agree" value="4">
            <label class="form-check-label" for="q11agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question11" id="q11neutral" value="2" required>
            <label class="form-check-label" for="q11neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question11" id="q11disagree" value="1">
            <label class="form-check-label" for="q11disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question11" id="q11sdisagree" value="0">
            <label class="form-check-label" for="q11sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> Making software, including video games, sounds appealing. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question12" id="q12sagree" value="6">
            <label class="form-check-label" for="q12sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question12" id="q12agree" value="4">
            <label class="form-check-label" for="q12agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question12" id="q12neutral" value="2" required>
            <label class="form-check-label" for="q12neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question12" id="q12disagree" value="1">
            <label class="form-check-label" for="q12disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question12" id="q12sdisagree" value="0">
            <label class="form-check-label" for="q12sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I would love building bridges, cars, or planes using wood. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question13" id="q13sagree" value="6">
            <label class="form-check-label" for="q13sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question13" id="q13agree" value="4">
            <label class="form-check-label" for="q13agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question13" id="q13neutral" value="2" required>
            <label class="form-check-label" for="q13neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question13" id="q13disagree" value="1">
            <label class="form-check-label" for="q13disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question13" id="q13sdisagree" value="0">
            <label class="form-check-label" for="q13sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I am good at constructing objects with wood or plastic using respective tools or machines. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question14" id="q14sagree" value="6">
            <label class="form-check-label" for="q14sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question14" id="q14agree" value="4">
            <label class="form-check-label" for="q14agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question14" id="q14neutral" value="2" required>
            <label class="form-check-label" for="q14neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question14" id="q14disagree" value="1">
            <label class="form-check-label" for="q14disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question14" id="q14sdisagree" value="0">
            <label class="form-check-label" for="q14sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I like designing architecture using CAD software. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question15" id="q15sagree" value="6">
            <label class="form-check-label" for="q15sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question15" id="q15agree" value="4">
            <label class="form-check-label" for="q15agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question15" id="q15neutral" value="2" required>
            <label class="form-check-label" for="q15neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question15" id="q15disagree" value="1">
            <label class="form-check-label" for="q15disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question15" id="q15sdisagree" value="0">
            <label class="form-check-label" for="q15sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I enjoy designing objects in applications like Solidworks. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question16" id="q16sagree" value="6">
            <label class="form-check-label" for="q16sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question16" id="q16agree" value="4">
            <label class="form-check-label" for="q16agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question16" id="q16neutral" value="2" required>
            <label class="form-check-label" for="q16neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question16" id="q16disagree" value="1">
            <label class="form-check-label" for="q16disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question16" id="q16sdisagree" value="0">
            <label class="form-check-label" for="q16sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I consider myself a good public speaker. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question17" id="q17sagree" value="6">
            <label class="form-check-label" for="q17sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question17" id="q17agree" value="4">
            <label class="form-check-label" for="q17agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question17" id="q17neutral" value="2" required>
            <label class="form-check-label" for="q17neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question17" id="q17disagree" value="1">
            <label class="form-check-label" for="q17disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question17" id="q17sdisagree" value="0">
            <label class="form-check-label" for="q17sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I’m not nervous speaking or presenting information. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question18" id="q18sagree" value="6">
            <label class="form-check-label" for="q18sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question18" id="q18agree" value="4">
            <label class="form-check-label" for="q18agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question18" id="q18neutral" value="2" required>
            <label class="form-check-label" for="q18neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question18" id="q18disagree" value="1">
            <label class="form-check-label" for="q18disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question18" id="q18sdisagree" value="0">
            <label class="form-check-label" for="q18sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I love using the Adobe Suite or similar tools (Photoshop, Illustrator, Premier, etc.). </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question19" id="q19sagree" value="6">
            <label class="form-check-label" for="q19sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question19" id="q19agree" value="4">
            <label class="form-check-label" for="q19agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question19" id="q19neutral" value="2" required>
            <label class="form-check-label" for="q19neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question19" id="q19disagree" value="1">
            <label class="form-check-label" for="q19disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question19" id="q19sdisagree" value="0">
            <label class="form-check-label" for="q19sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I’d consider photo and video editing a specialty of mine. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question20" id="q20sagree" value="6">
            <label class="form-check-label" for="q20sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question20" id="q20agree" value="4">
            <label class="form-check-label" for="q20agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question20" id="q20neutral" value="2" required>
            <label class="form-check-label" for="q20neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question20" id="q20disagree" value="1">
            <label class="form-check-label" for="q20disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question20" id="q20sdisagree" value="0">
            <label class="form-check-label" for="q20sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I can properly research topics to create innovative solutions for any problem. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question21" id="q21sagree" value="6">
            <label class="form-check-label" for="q21sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question21" id="q21agree" value="4">
            <label class="form-check-label" for="q21agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question21" id="q21neutral" value="2" required>
            <label class="form-check-label" for="q21neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question21" id="q21disagree" value="1">
            <label class="form-check-label" for="q21disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question21" id="q21sdisagree" value="0">
            <label class="form-check-label" for="q21sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <div class="container" id="content">
    <div class="row" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
      <div class="col-sm-12">
        <div class="contentcard">

          <p style="font-size: 1.5rem;"> I like knowing subjects I deal with in depth and do not hesitate to learn more. </p>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question22" id="q22sagree" value="6">
            <label class="form-check-label" for="q22sagree">Strongly Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question22" id="q22agree" value="4">
            <label class="form-check-label" for="q22agree">Agree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question22" id="q22neutral" value="2" required>
            <label class="form-check-label" for="q22neutral">Neutral</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question22" id="q22disagree" value="1">
            <label class="form-check-label" for="q22disagree">Disagree</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="question22" id="q22sdisagree" value="0">
            <label class="form-check-label" for="q22sdisagree">Strongly Disagree</label>
          </div>
        </div>     
      </div>
    </div>
  </div>

  <!-- Button -->
  <div class="container" id="content">
    <div class="row justify-content-center">    
      <button type="submit" class="btn btn-primary">Submit</button>    
    </div>
  </div>
</form>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
  </body>

  <footer style="position: relative;">
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
