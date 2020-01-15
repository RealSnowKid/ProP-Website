<!DOCTYPE html>
<?php

error_reporting(0);
$reg_errors = array();
$log_errors = array();
$ticket_errors = array();
require_once 'formActions.php';

if ($_SESSION['modal'] == '#tickets3') {
  echo "<script type='text/javascript'> 
  localStorage.setItem('openModal', '#tickets3');
  </script>";
  unset($_SESSION['modal']);
}

?>
<html>

<head>

  <title>Music Festival</title>
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


<!-- SCHEDULE -->
<?php
  //declerables
  $slots_array = array(1, 2, 3);
  $d1p1n = "";
  $d1p1t = "";
  $d1p1d = "";
  $d1p2n = "";
  $d1p2t = "";
  $d1p2d = "";
  $d1p3n = "";
  $d1p3t = "";
  $d1p3d = "";

  //connection D1
  $scheduleQueryD1 = "SELECT performerName, performerTime, performerDesc FROM schedule WHERE performerDay=1 AND performerSlot=? LIMIT 1";
  $statement = $conn->prepare($scheduleQueryD1);
  $statement->bind_param('i', $slot);


  foreach ($slots_array as $slot) {
    
    $statement->execute();
    $result1 = $statement->get_result();
    $assoc1 = $result1->fetch_assoc();
    
    switch ($slot) {
      case '1':
        $d1p1n = $assoc1['performerName'];
        $d1p1t = substr($assoc1['performerTime'], 0, 5);
        $d1p1d = $assoc1['performerDesc'];
        break;
      case '2':
        $d1p2n = $assoc1['performerName'];
        $d1p2t = substr($assoc1['performerTime'], 0, 5);
        $d1p2d = $assoc1['performerDesc'];
        break;
      case '3':
        $d1p3n = $assoc1['performerName'];
        $d1p3t = substr($assoc1['performerTime'], 0, 5);
        $d1p3d = $assoc1['performerDesc'];
        break;      
      default:
        echo "hello";
        break;
    }
  }
  $statement->close();

  //connection D2
  $scheduleQueryD2 = "SELECT performerName, performerTime, performerDesc FROM schedule WHERE performerDay=2 AND performerSlot=? LIMIT 1";
  $statement = $conn->prepare($scheduleQueryD2);
  $statement->bind_param('i', $slot);


  foreach ($slots_array as $slot) {
    
    $statement->execute();
    $result2 = $statement->get_result();
    $assoc2 = $result2->fetch_assoc();
    
    switch ($slot) {
      case '1':
        $d2p1n = $assoc2['performerName'];
        $d2p1t = substr($assoc2['performerTime'], 0, 5);
        $d2p1d = $assoc2['performerDesc'];
        break;
      case '2':
        $d2p2n = $assoc2['performerName'];
        $d2p2t = substr($assoc2['performerTime'], 0, 5);
        $d2p2d = $assoc2['performerDesc'];
        break;
      case '3':
        $d2p3n = $assoc2['performerName'];
        $d2p3t = substr($assoc2['performerTime'], 0, 5);
        $d2p3d = $assoc2['performerDesc'];
        break;      
      default:
        echo "hello";
        break;
    }
  }
  $statement->close();

  //connection D3
  $scheduleQueryD3 = "SELECT performerName, performerTime, performerDesc FROM schedule WHERE performerDay=3 AND performerSlot=? LIMIT 1";
  $statement = $conn->prepare($scheduleQueryD3);
  $statement->bind_param('i', $slot);


  foreach ($slots_array as $slot) {
    
    $statement->execute();
    $result3 = $statement->get_result();
    $assoc3 = $result3->fetch_assoc();
    
    switch ($slot) {
      case '1':
        $d3p1n = $assoc3['performerName'];
        $d3p1t = substr($assoc3['performerTime'], 0, 5);
        $d3p1d = $assoc3['performerDesc'];
        break;
      case '2':
        $d3p2n = $assoc3['performerName'];
        $d3p2t = substr($assoc3['performerTime'], 0, 5);
        $d3p2d = $assoc3['performerDesc'];
        break;
      case '3':
        $d3p3n = $assoc3['performerName'];
        $d3p3t = substr($assoc3['performerTime'], 0, 5);
        $d3p3d = $assoc3['performerDesc'];
        break;      
      default:
        echo "hello";
        break;
    }
  }
  $statement->close();

?>

  
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
        <li><a href="#start">HOME</a></li>
        <li><a href="#event">ABOUT</a></li>
       	<li><a href="#schedule">SCHEDULE</a></li>
<!--         <li><a href="#contact">CONTACT</a></li> -->
        <li><a href="#location">LOCATION</a></li>
        <?php if(isset($_SESSION['id'])): ?>
        <li><a href="profile.php">PROFILE</a></li>
        <?php else : ?>
        <li><a href="login.php">LOG IN</a></li>
        <?php endif ?>
       </ul>
    </div>
  </div>
</nav>



<!-- Carousel -->
<div id="start" class="carousel slide" data-ride="carousel">

    <ol class="carousel-indicators">
      <li data-target="#start" data-slide-to="0" class="active"></li>
      <li data-target="#start" data-slide-to="1"></li>
      <li data-target="#start" data-slide-to="2"></li>
    </ol>


    <div class="carousel-inner" role="listbox">
		<div class="item active">
			<img src="garrix_event.jpg" alt="Martin Garrix" width="1200">
				<div class="carousel-caption">
					<h3>MARTIN GARRIX</h3>
          			<p>Number 2 DJ in the Wrold</p>
        		</div>      
     	 </div>
    
    	<div class="item">
        	<img src="tiesto_event.jpg" alt="Tiesto" width="1200">
        		<div class="carousel-caption">
          			<h3>TIËSTO</h3>
          			<p>Number 8 DJ in the Wrold</p>
        		</div>      
		</div>

		<div class="item">
        	<img src="aoki_event.jpg" alt="Steve Aoki" width="1200">
        		<div class="carousel-caption">
          			<h3>STEVE AOKI</h3>
          			<p>Number 10 DJ in the Wrold</p>
       			</div>      
   		</div>

	</div>


    <a class="left carousel-control" href="#start" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#start" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<!-- Event Fields -->
<div id="event" class="container text-center">
  <h2>ABOUT THE EVENT</h2>
  <p><em>We make enjoying music easy!</em></p>
  <p>It's a new era of festivals with ours being the forerunner. We have prepared a star-studded lineup and almost entirely digital solution to your tipical festival routine. JIP is a leading tech company in the field and with their love for music not far behind, it was a no brainer to begin this festival, specially made for students. Bring your wallets because the food and drinks are going to be out of this world and don't worry if you forgot something, we have a stand that can loan you items to use during the event. In addition we have some rooms at the event, so be quick and grab them if you to stay inside the whole time and not miss on any of the action. So we welcome you for an entire weekend full of action fun and of course a lot of good music. </p>
  <br>
  <h3>HEADLINERS</h3>
  <br>
  <div class="row">
    <div class="col-sm-4">
      <p class="text-center"><strong>Martin Garrix</strong></p><br>
      <a href="#perf1" data-toggle="collapse">
        <img src="garrix.png" class="img-circle person" alt="Martin Garrix">
      </a>
      <div id="perf1" class="collapse">
        <p><span class="flag-icon flag-icon-nl"></span> Dutch DJ</p>
        <p>One of the leading DJs in the world.</p>
      </div>
    </div>
    <div class="col-sm-4">
      <p class="text-center"><strong>Tiësto</strong></p><br>
      <a href="#perf2" data-toggle="collapse">
        <img src="tiesto.png" class="img-circle person" alt="Tiësto">
      </a>
      <div id="perf2" class="collapse">
        <p><span class="flag-icon flag-icon-nl"></span> Dutch DJ</p>
        <p>A world-class performer and DJ, the "godfather" of EDM.</p>
      </div>
    </div>
    <div class="col-sm-4">
      <p class="text-center"><strong>Steve Aoki</strong></p><br>
      <a href="#perf3" data-toggle="collapse">
        <img src="aoki.png" class="img-circle person" alt="Steve Aoki">
      </a>
      <div id="perf3" class="collapse">
        <p><span class="flag-icon flag-icon-us"></span> American DJ</p>
        <p>One of the most comercially successful DJs in the world.</p>
      </div>
    </div>
  </div>
</div>

<!-- Schedule -->
<div id="schedule" class="bg-1">
  <div class="container">
    <h2 class="text-center">SCHEDULE</h2>
<!--     <p class="text-center">Lorem ipsum we'll play you some music.<br> Remember to book your tickets!</p> -->

  	<div class="tab">
    		<button class="tablinks active" onclick="openDay(event, 'Friday')">Friday</button>
    		<button class="tablinks" onclick="openDay(event, 'Saturday')">Saturday</button>
    		<button class="tablinks" onclick="openDay(event, 'Sunday')">Sunday</button>
  	</div>

  	<div id="Friday" class="tabcontent" style="display: block;">
    		<h3 id="d1p1n"><?php echo $d1p1n; ?></h3>
    		<h5 id="d1p1t"><?php echo $d1p1t; ?> CET</h5>
    		<p id="d1p1d"><?php echo $d1p1d; ?></p>
    		<hr>
    		<h3 id="d1p2n"><?php echo $d1p2n; ?></h3>
    		<h5 id="d1p2t"><?php echo $d1p2t; ?> CET</h5>
    		<p id="d1p2d"><?php echo $d1p2d; ?></p>
    		<hr>
    		<h3 id="d1p3n"><?php echo $d1p3n; ?></h3>
    		<h5 id="d1p3t"><?php echo $d1p3t; ?> CET</h5>
    		<p id="d1p3d"><?php echo $d1p3d; ?></p>
        <hr>
  	</div>

  	<div id="Saturday" class="tabcontent">
        <h3 id="d1p1n"><?php echo $d2p1n; ?></h3>
        <h5 id="d1p1t"><?php echo $d2p1t; ?> CET</h5>
        <p id="d1p1d"><?php echo $d2p1d; ?></p>
        <hr>
        <h3 id="d1p2n"><?php echo $d2p2n; ?></h3>
        <h5 id="d1p2t"><?php echo $d2p2t; ?> CET</h5>
        <p id="d1p2d"><?php echo $d2p2d; ?></p>
        <hr>
        <h3 id="d1p3n"><?php echo $d2p3n; ?></h3>
        <h5 id="d1p3t"><?php echo $d2p3t; ?> CET</h5>
        <p id="d1p3d"><?php echo $d2p3d; ?></p>
        <hr>
  	</div>

  	<div id="Sunday" class="tabcontent">
        <h3 id="d1p1n"><?php echo $d3p1n; ?></h3>
        <h5 id="d1p1t"><?php echo $d3p1t; ?> CET</h5>
        <p id="d1p1d"><?php echo $d3p1d; ?></p>
        <hr>
        <h3 id="d1p2n"><?php echo $d3p2n; ?></h3>
        <h5 id="d1p2t"><?php echo $d3p2t; ?> CET</h5>
        <p id="d1p2d"><?php echo $d3p2d; ?></p>
        <hr>
        <h3 id="d1p3n"><?php echo $d3p3n; ?></h3>
        <h5 id="d1p3t"><?php echo $d3p3t; ?> CET</h5>
        <p id="d1p3d"><?php echo $d3p3d; ?></p>
        <hr>
  	</div>
    <div class="ticketsbutton">
      <?php if (isset($_SESSION['tickets'])): ?>
        <a class="btn" href="profile.php">View Your Tickets</a>
      <?php else: ?>
        <?php if (isset($_SESSION['id'])): ?>
          <button class="btn" data-toggle="modal" data-target="#tickets3">Buy Tickets</button>
        <?php else : ?>
          <button class="btn" data-toggle="modal" data-target="#tickets2">Buy Tickets</button>
        <?php endif ?>
      <?php endif ?>
    </div>
  </div>
  
</div>

<!-- Registration Modal -->
  <div class="modal fade" id="tickets1" role="dialog">
      <div class="modal-dialog">
      
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4><span class="glyphicon glyphicon-lock"></span> Tickets</h4>
          </div>
          <div class="modal-body">
            <form role="form" action="index.php" method="POST">
              <div class="form-group2">
                  <h3>Please fill in this form to create an account.</h3>
              </div>

              <?php if(count($reg_errors) > 0): ?>
              <div class="alert alert-danger">
                <?php foreach ($reg_errors as $error): ?>
                  <li><?php echo $error; ?></li>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

              <div class="form-group2">
                  <label for="email">Email</label>
                  <input type="text" placeholder="Enter Email" name="email" id="email" value="<?php echo $email; ?>" >
              </div>
              <div class="form-group2">
                  <label for="password">Password</label>
                  <input type="password" placeholder="Enter Password" name="password" id="password" >
              </div>
              <div class="form-group2">
                  <label for="repeatpw">Repeat Password</label>
                  <input type="password" placeholder="Repeat Password" name="repeatpw" id="repeatpw" >
              </div>
              <hr>
              <button class="btn btn-block" type="submit" name="register-btn">Register
                <span class="glyphicon glyphicon-ok"></span>
              </button>
            </form>
          </div>
          <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
            <span class="glyphicon glyphicon-remove"></span> Cancel
          </button>
          <p>Already have an account? Click <a data-dismiss="modal" data-toggle="modal" data-target="#tickets2">here</a>.</p>
          </div>
        </div>
      </div>
  </div>

<!-- Login Modal -->
  <div class="modal fade" id="tickets2" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Tickets</h4>
        </div>
        <div class="modal-body">
          <form role="form" action="index.php" method="POST">
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
                <label for="email2">Email</label>
                <br>
          <input type="text" placeholder="Enter Email" name="email2" value="<?php echo $log_email; ?>" >
      </div>
      <div class="form-group2">
          <label for="password2">Password</label>
          <br>
          <input type="password" placeholder="Enter Password" name="password2" >
      </div>
        <hr>
            <button class="btn btn-block" type="submit" name="login-btn">Log In
              <span class="glyphicon glyphicon-ok"></span>
          </button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
            <span class="glyphicon glyphicon-remove"></span> Cancel
          </button>
          <p>Don't have an account? Click <a data-dismiss="modal" data-toggle="modal" data-target="#tickets1">here</a>.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Tickets -->
  <div class="modal fade" id="tickets3" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Tickets</h4>
        </div>
        <div class="modal-body">
          <form role="form" method="POST" action="index.php">
            <div class="form-group">

            <?php if(count($ticket_errors) > 0): ?>
              <div class="alert alert-danger">
                <?php foreach ($ticket_errors as $error): ?>
                  <li><?php echo $error; ?></li>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

              <label for="info"><span class="glyphicon glyphicon-shopping-cart"></span> General Admission, $23 per person</label>
              <select name="ticketamount">
                <option value=1>1</option>
                <option value=2>2</option>
                <option value=3>3</option>
                <option value=4>4</option>
                <option value=5>5</option>
              </select>
            </div>
              <button class="btn btn-block">Confirm 
                <span class="glyphicon glyphicon-ok"></span>
              </button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
            <span class="glyphicon glyphicon-remove"></span> Cancel
          </button>
        </div>
      </div>
    </div>
  </div>


<!-- <div id="contact" class="container">
  <h2 class="text-center">CONTACT</h2>

  <div class="row">
    <div class="col-md-4">
      <p>Want to get in touch? Drop a line.</p>
      <p><span class="glyphicon glyphicon-map-marker"></span>Eindhoven, NL</p>
      <p><span class="glyphicon glyphicon-phone"></span>Phone: +31 99999999</p>
      <p><span class="glyphicon glyphicon-envelope"></span>Email: mail@mail.com</p>
    </div>
    <div class="col-md-8">
      <div class="row">
        <div class="col-sm-6 form-group">
          <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
        </div>
        <div class="col-sm-6 form-group">
          <input class="form-control" id="email3" name="email" placeholder="Email" type="email" required>
        </div>
      </div>
      <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5"></textarea>
      <br>
      <div class="row">
        <div class="col-md-12 form-group">
          <button class="btn pull-right" type="submit">Send</button>
        </div>
      </div>
    </div>
  </div>
</div>
 -->

<div id="location" class="container2">
    <h2 class="text-center">LOCATION</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1045.3805444465693!2d5.4800590215280245!3d51.45176748423797!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6d921bb500e25%3A0x9add605999df4464!2sFontys%20Hogescholen%20R1%2C%20Rachelsmolen%201%2C%20Eindhoven!5e0!3m2!1snl!2snl!4v1578322920154!5m2!1snl!2snl" class="googleMap"></iframe>
</div>

<footer class="text-center">
  <a class="up-arrow" href="#start" data-toggle="tooltip" title="TO TOP">
    <span class="glyphicon glyphicon-chevron-up"></span>
  </a>
</footer>


</body>
</html>
