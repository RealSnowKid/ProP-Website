<!DOCTYPE html>
<?php

$reg_errors = array();
$log_errors = array();
require_once 'formActions.php';

?>
<html>
<head>

  <title>Music Festival : Log In</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link href="style.css" rel="stylesheet" type="text/css">
  <link href="./flags_css/flag-icon.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type = "text/javascript" src = "functions.js" ></script>

  
</head>

<body id="top" data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a href="index.php#start"><img class="rounded float-left" src="dj_set_2.png" height="50" width="50"></a><a class="navbar-brand" href="index.php#start">LIT MUSIC FESTIVAL</a>
    </div>


    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="index.php#start">HOME</a></li>
        <li><a href="index.php#event">ABOUT</a></li>
       	<li><a href="index.php#schedule">SCHEDULE</a></li>
<!--         <li><a href="index.php#contact">CONTACT</a></li> -->
        <li><a href="index.php#location">LOCATION</a></li>
        <?php if(isset($_SESSION['id'])): ?>
        <li><a href="profile.php">PROFILE</a></li>
        <?php else : ?>
        <li><a href="login.php">LOG IN</a></li>
        <?php endif ?>
       </ul>
    </div>
  </div>
</nav>

<div class="container text-center">
  <img src="dj_set.png" alt="DJ Set" width="200" height="200">
  <hr class="solid">
  <h2>LOG IN</h2>
  <form role="form" action="login.php" method="POST">
    <div class="form-group2">
      <h3>Please fill in this form to log into your account.</h3>
    </div>

    <?php if(count($log_errors) > 0): ?>
      <div class="alert alert-danger">
        <?php foreach ($log_errors as $error): ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="form-group2">
    <label for="email">Email</label>
    <br>
    <input type="text" placeholder="Enter Email" name="email" value="<?php echo $log_email; ?>" >
    </div>
    <div class="form-group2">
    <label for="password">Password</label>
    <br>
    <input type="password" placeholder="Enter Password" name="password" >
    </div>
    <hr class="solid">
    <button class="btn btn-block" type="submit" name="login-btn2">Log In
    <span class="glyphicon glyphicon-ok"></span>
    </button>
  </form>

  <p>Don't have an account? Click <a href="register.php">here</a>.</p>

  

</div>

</body>
</html>