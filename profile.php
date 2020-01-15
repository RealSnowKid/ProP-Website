<!DOCTYPE html>
<?php

$accom_errors = array();
require_once 'formActions.php';
if (!isset($_SESSION['id'])) {
	header("Location: login.php");
	exit();
}


?>
<html>
<head>

  <title>Music Festival : Profile</title>
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

	<h1>Welcome <?php if (isset($_SESSION['email'])) {
		echo $_SESSION['email'];
		// echo "<br>";
		// echo $_SESSION['id'];
		// echo "<br>";
		// echo $_SESSION['tickets'];
		// echo "<br>";
		// echo $_SESSION['reservation'];
	} ?>,</h1>
	<h1>this is your profile page.</h1>
	<br>
	<a href="profile.php?logout=1" class="btn btn-block3">Log Out</a>
	<br>
	<hr>
	<h2>Reserved Tickets:</h2>
	<br>

	<?php
		$sql = "SELECT ticketHex FROM tickets WHERE visitorID=? LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('i', $_SESSION['id']);
		$stmt->execute();
		$result = $stmt->get_result();
		$string = $result->fetch_assoc();
		$ticketHex = $string['ticketHex'];

		switch (strlen ($ticketHex)) {
			case '10':
				$t1 = $ticketHex;
				echo "<ul class=\"list-group\">
					  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=1\">Cancel Ticket</a> </li>
					  </ul>";
				break;

			case '20':
				$t1 = substr($ticketHex, 0, 10);
				$t2 = substr($ticketHex, 10, 10);
				echo "<ul class=\"list-group\">
					  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=1\">Cancel Ticket</a> </li>
  					  <li class=\"list-group-item\">Ticket Code: " . $t2 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=2\">Cancel Ticket</a> </li>
					  </ul>";
				break;

			case '30':
				$t1 = substr($ticketHex, 0, 10);
				$t2 = substr($ticketHex, 10, 10);
				$t3 = substr($ticketHex, 20, 10);
				echo "<ul class=\"list-group\">
					  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=1\">Cancel Ticket</a> </li>
  					  <li class=\"list-group-item\">Ticket Code: " . $t2 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=2\">Cancel Ticket</a> </li>
					  <li class=\"list-group-item\">Ticket Code: " . $t3 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=3\">Cancel Ticket</a> </li>
					  </ul>";
				break;

			case '40':
				$t1 = substr($ticketHex, 0, 10);
				$t2 = substr($ticketHex, 10, 10);
				$t3 = substr($ticketHex, 20, 10);
				$t4 = substr($ticketHex, 30, 10);
				echo "<ul class=\"list-group\">
					  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=1\">Cancel Ticket</a> </li>
  					  <li class=\"list-group-item\">Ticket Code: " . $t2 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=2\">Cancel Ticket</a> </li>
					  <li class=\"list-group-item\">Ticket Code: " . $t3 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=3\">Cancel Ticket</a> </li>
					  <li class=\"list-group-item\">Ticket Code: " . $t4 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=4\">Cancel Ticket</a> </li>
					  </ul>";
				break;

			case '50':
				$t1 = substr($ticketHex, 0, 10);
				$t2 = substr($ticketHex, 10, 10);
				$t3 = substr($ticketHex, 20, 10);
				$t4 = substr($ticketHex, 30, 10);
				$t5 = substr($ticketHex, 40, 10);
				echo "<ul class=\"list-group\">
					  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=1\">Cancel Ticket</a> </li>
  					  <li class=\"list-group-item\">Ticket Code: " . $t2 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=2\">Cancel Ticket</a> </li>
					  <li class=\"list-group-item\">Ticket Code: " . $t3 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=3\">Cancel Ticket</a> </li>
					  <li class=\"list-group-item\">Ticket Code: " . $t4 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=4\">Cancel Ticket</a> </li>
					  <li class=\"list-group-item\">Ticket Code: " . $t5 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=5\">Cancel Ticket</a> </li>
					  </ul>";
				break;
			
			default:
				$id = $_SESSION['id'];
				unset($_SESSION['tickets']);

				$sql = "UPDATE accommodation SET Account_ID = NULL WHERE Account_ID = '".$id."'";

				if (!$conn->query($sql)) {
					echo "<script type='text/javascript'> 
				    window.alert('There is a big error. Please, contact someone!');
					</script>";
				}
				else {
					unset($_SESSION['reservation']);
				}

				echo "<h3>You have not yet purchased any tickets!</h3>";
				echo "<br>";
				echo "<a class=\"btn btn-block3\" href=\"index.php#schedule\">To Tickets</a>";
				break;
		}
	?>

	<hr>

	<?php if (isset($_SESSION['tickets'])): ?>
		<?php if(isset($_SESSION['reservation'])): ?>

			<h1 style="color:black">You Have Reserved Room Number: <?php echo $_SESSION['reservation']; ?>.</h1>
			<a class="btn btn-block3" href="profile.php?cancelres=<?php echo $_SESSION['reservation'] ?>">Cancel Reservation</a>

		<?php else: ?>

			<button class="btn btn-block2" data-toggle="modal" data-target="#accommodation">Reserve Accommodation</button>

		<?php endif ?>
	<?php endif ?>

	<div class="modal fade" id="accommodation" role="dialog">
	    <div class="modal-dialog">
	    
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">×</button>
	          <h4><span class="glyphicon glyphicon-bed"></span> Accommodation</h4>
	        </div>
	        <div class="modal-body">
	          <form role="form" method="POST" action="accommodation.php">
	            <div class="form-group">

	            <?php if(count($accom_errors) > 0): ?>
	              <div class="alert alert-danger">
	                <?php foreach ($ticket_errors as $error): ?>
	                  <li><?php echo $error; ?></li>
	                <?php endforeach; ?>
	              </div>
	            <?php endif; ?>

	              <label for="info">Select the Size of the Room You Want:</label>
	              <select name="accommodation">
	                <option value=2>2 person</option>
	                <option value=3>3 person</option>
	                <option value=4>4 person</option>
	                <option value=5>5 person</option>
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


</body>
</html>