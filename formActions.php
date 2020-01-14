<?php

session_start();

require 'db.php';

// Array for errors and storing email in case of mistake by user
$reg_errors = array();
$log_errors = array();
$accom_errors = array();
$ticket_errors = array();
$email = "";
$log_email = "";

// REGISTER
if (isset($_POST['register-btn'])){
	$email = $_POST['email'];
	$password = $_POST['password'];
	$repeatpw = $_POST['repeatpw'];
	$re = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/m';


	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$reg_errors['email'] = "Email is not valid";
		echo "<script type='text/javascript'> 
		localStorage.setItem('openModal', '#tickets1'); 
		</script>";
	}
	if (empty($email)){
		$reg_errors['email'] = "Email is required";
		echo "<script type='text/javascript'>
		localStorage.setItem('openModal', '#tickets1'); 
		</script>";
	}
	if (empty($password)){
		$reg_errors['password'] = "Password is required";
		echo "<script type='text/javascript'> 
		localStorage.setItem('openModal', '#tickets1'); 
		</script>";
	}
	elseif (!preg_match($re, $password)) {
		$reg_errors['password'] = "Password must atlest be 8 characters long and include: <br> - One Uppercase Letter <br> - One Lowercase letter <br> - 1 Number or 1 Special Character ";
		echo "<script type='text/javascript'> 
		localStorage.setItem('openModal', '#tickets1'); 
		</script>";
	}
	if ($password !== $repeatpw) {
		$reg_errors['password'] = "The two passwords don't match";
		echo "<script type='text/javascript'> 
		localStorage.setItem('openModal', '#tickets1'); 
		</script>";
	}

	$emailQuery = "SELECT * FROM account WHERE Account_Email=? LIMIT 1";
	$stmt = $conn->prepare($emailQuery);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$userCount = $result->num_rows;
	$stmt->close();

	if ($userCount > 0) {
		$reg_errors['email'] = "Email already exists.";
		echo "<script type='text/javascript'> 
		localStorage.setItem('openModal', '#tickets1'); 
		</script>";
	}

	if (count($reg_errors) === 0) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO account (Account_Email, Account_Pass_hash) VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ss', $email, $password);

		if($stmt->execute()){
			$user_id = $conn->insert_id;
			//Enter data into visitor table
			$sql = "INSERT INTO visitor (visitorID) VALUES (?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $user_id);
			if ($stmt->execute()) {
				//Login the user
				$_SESSION['id'] = $user_id;
				$_SESSION['email'] = $email;
				$_SESSION['modal'] = '#tickets3';
				header("Location: /index.php#schedule");
				exit();
			}
			else{
				$reg_errors['db_error'] = "Database error: failed to register";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets1'); 
				</script>";
			}
		}
		else{
			$reg_errors['db_error'] = "Database error: failed to register";
			echo "<script type='text/javascript'> 
			localStorage.setItem('openModal', '#tickets1'); 
			</script>";
		}
	}
}

// REGISTER PAGE
if (isset($_POST['register-btn2'])){
	$email = $_POST['email'];
	$password = $_POST['password'];
	$repeatpw = $_POST['repeatpw'];
	$re = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/m';


	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$reg_errors['email'] = "Email is not valid";

	}
	if (empty($email)){
		$reg_errors['email'] = "Email is required";

	}
	if (empty($password)){
		$reg_errors['password'] = "Password is required";
	}
	elseif (!preg_match($re, $password)) {
		$reg_errors['password'] = "Password must atlest be 8 characters long and include: <br> - One Uppercase Letter <br> - One Lowercase letter <br> - 1 Number or 1 Special Character ";
	}
	if ($password !== $repeatpw) {
		$reg_errors['password'] = "The two passwords don't match";
	}

	$emailQuery = "SELECT * FROM account WHERE Account_Email=? LIMIT 1";
	$stmt = $conn->prepare($emailQuery);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$userCount = $result->num_rows;
	$stmt->close();

	if ($userCount > 0) {
		$reg_errors['email'] = "Email already exists.";
	}

	if (count($reg_errors) === 0) {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO account (Account_Email, Account_Pass_hash) VALUES (?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ss', $email, $password);

		if($stmt->execute()){
			$user_id = $conn->insert_id;
			//Enter data into visitor table
			$sql = "INSERT INTO visitor (visitorID) VALUES (?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $user_id);
			if ($stmt->execute()) {
				//Login the user
				$_SESSION['id'] = $user_id;
				$_SESSION['email'] = $email;
				header("Location: profile.php");
				exit();
			}
			else{
				$reg_errors['db_error'] = "Database error: failed to register";;
			}
		}
		else{
			$reg_errors['db_error'] = "Database error: failed to register";
		}
	}
}

// LOGIN MODAL
if (isset($_POST['login-btn'])){
	$log_email = $_POST['email2'];
	$password = $_POST['password2'];

	if (empty($log_email)){
		$log_errors['email'] = "Email is required";
		echo "<script type='text/javascript'>
		localStorage.setItem('openModal', '#tickets2'); 
		</script>";
	}
	if (empty($password)){
		$log_errors['password'] = "Password is required";
		echo "<script type='text/javascript'> 
		localStorage.setItem('openModal', '#tickets2'); 
		</script>";
	}

	if (count($log_errors) === 0) {
		$sql = "SELECT * FROM account WHERE Account_email=? LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $log_email);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();

		if (password_verify($password, $user['Account_Pass_hash'])) {

			$id = $user['Account_ID'];

			$_SESSION['id'] = $id;
			$_SESSION['email'] = $user['Account_Email'];
			$_SESSION['modal'] = '#tickets3';

			$sql2 = "SELECT visitorID FROM tickets WHERE visitorID = ?";
			$stmt2 = $conn->prepare($sql2);
			$stmt2->bind_param('i', $id);
			$stmt2->execute();
			$rws = $stmt2->num_rows();

			if ($rws > 0) {
				$_SESSION['tickets'] = 1;
			}
			else {
				unset($_SESSION['tickets']);
			}

			$sql3 = "SELECT Account_ID, Accommodation_ID FROM accommodation WHERE Account_ID = ?";
			$stmt3 = $conn->prepare($sql3);
			$stmt3->bind_param('i', $id);
			$stmt3->execute();
			$rws2 = $stmt3->num_rows();
			$result2 = $stmt3->get_result();
			$accom = $result2->fetch_assoc();

			if ($rws2 > 0) {
				$_SESSION['reservation'] = $accom['Accommodation_ID'];
			}
			else {
				unset($_SESSION['reservation']);
			}

			header("Location: index.php#schedule");
			exit();
		}
		else{
			$log_errors['login_fail'] = "Wrong Credentials";
			echo "<script type='text/javascript'> 
			localStorage.setItem('openModal', '#tickets2'); 
			</script>";
		}
	}
}


// LOGIN PAGE
if (isset($_POST['login-btn2'])){
	$log_email = $_POST['email'];
	$password = $_POST['password'];

	if (empty($log_email)){
		$log_errors['email'] = "Email is required";
	}
	if (empty($password)){
		$log_errors['password'] = "Password is required";
	}

	if (count($log_errors) === 0) {
		$sql = "SELECT * FROM account WHERE Account_email=? LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $log_email);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();

		if (password_verify($password, $user['Account_Pass_hash'])) {
			$id = $user['Account_ID'];

			$_SESSION['id'] = $id;
			$_SESSION['email'] = $user['Account_Email'];

			$sql2 = "SELECT visitorID FROM tickets WHERE visitorID = ? LIMIT 1";
			$stmt2 = $conn->prepare($sql2);
			$stmt2->bind_param('i', $id);
			$stmt2->execute();
			$rws = $stmt2->num_rows();
			$stmt2->close();

			if ($rws > 0) {
				$_SESSION['tickets'] = 1;
			}
			else {
				unset($_SESSION['tickets']);
			}


			$query = "SELECT Accommodation_ID, Account_ID FROM accommodation WHERE Account_ID = '".$id."' LIMIT 1";

			if ($result2 = $conn->query($query)) {

			    while ($obj = $result2->fetch_object()) {
			        $accomID = $obj->Accommodation_ID;
			    }
			    $_SESSION['reservation'] = $accomID;

			    $result2->close();
			}

			header("Location: profile.php");
			exit();
		}
		else{
			$log_errors['login_fail'] = "Wrong Credentials";
		}
	}
}


// LOGOUT
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['id']);
	unset($_SESSION['email']);
	unset($_SESSION['modal']);
	unset($_SESSION['tickets']);
	unset($_SESSION['reservation']);
	header("Location: /index.php");
	exit();
}

// TICKET GENERATION
if (isset($_POST['ticketamount'])) {
	switch ($_POST['ticketamount']) {
        case '1':

            $hex = bin2hex(random_bytes(5));
            $id = $_SESSION['id'];

    		$sql = "INSERT INTO tickets (visitorID, ticketHex) VALUES (?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('is', $id, $hex);

			if ($stmt->execute()) {

				$sql2 = "UPDATE visitor SET Visitor_Balance = 23 * (-1) WHERE visitorID = ?";
				
				$stmt2 = $conn->prepare($sql2);
				$stmt2->bind_param('i', $id);

				if (!$stmt2->execute()) {

					$sql3 = "DELETE FROM tickets WHERE visitorID = ?";
					$stmt3 = $conn->prepare($sql3);
					$stmt3->bind_param('i', $id);

					if (!$stmt3->execute()) {
						$ticket_errors['error'] = "Big OOF.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
					else{
						$ticket_errors['error'] = "There was an error processing your tickets.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}

				}
				else{
					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");
				    </script>";
					exit();
				}
			}
			else{
				$ticket_errors['error'] = "There was an error processing your tickets.";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets3'); 
				</script>";
			}

            break;
        case '2':

            $hex = bin2hex(random_bytes(10));
            $id = $_SESSION['id'];


    		$sql = "INSERT INTO tickets (visitorID, ticketHex) VALUES (?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('is', $id, $hex);

			if ($stmt->execute()) {

				$sql2 = "UPDATE visitor SET Visitor_Balance = 23 * (-2) WHERE visitorID = ?";
				
				$stmt2 = $conn->prepare($sql2);
				$stmt2->bind_param('i', $id);

				if (!$stmt2->execute()) {

					$sql3 = "DELETE FROM tickets WHERE visitorID = ?";
					$stmt3 = $conn->prepare($sql3);
					$stmt3->bind_param('i', $id);

					if (!$stmt3->execute()) {
						$ticket_errors['error'] = "Big OOF.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
					else{
						$ticket_errors['error'] = "There was an error processing your tickets.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}

				}
				else{
					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");
				    </script>";
					exit();
				}
			}
			else{
				$ticket_errors['error'] = "There was an error processing your tickets.";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets3'); 
				</script>";
			}

            break;
        case '3':

            $hex = bin2hex(random_bytes(15));
            $id = $_SESSION['id'];


    		$sql = "INSERT INTO tickets (visitorID, ticketHex) VALUES (?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('is', $id, $hex);

			if ($stmt->execute()) {
				$sql2 = "UPDATE visitor SET Visitor_Balance = 23 * (-3) WHERE visitorID = ?";
				
				$stmt2 = $conn->prepare($sql2);
				$stmt2->bind_param('i', $id);

				if (!$stmt2->execute()) {

					$sql3 = "DELETE FROM tickets WHERE visitorID = ?";
					$stmt3 = $conn->prepare($sql3);
					$stmt3->bind_param('i', $id);

					if (!$stmt3->execute()) {
						$ticket_errors['error'] = "Big OOF.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
					else{
						$ticket_errors['error'] = "There was an error processing your tickets.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}

				}
				else{
					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");
				    </script>";
					exit();
				}
			}
			else{
				$ticket_errors['error'] = "There was an error processing your tickets.";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets3'); 
				</script>";
			}

            break;
        case '4':

            $hex = bin2hex(random_bytes(20));
            $id = $_SESSION['id'];


    		$sql = "INSERT INTO tickets (visitorID, ticketHex) VALUES (?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('is', $id, $hex);

			if ($stmt->execute()) {
				$sql2 = "UPDATE visitor SET Visitor_Balance = 23 * (-4) WHERE visitorID = ?";
				
				$stmt2 = $conn->prepare($sql2);
				$stmt2->bind_param('i', $id);

				if (!$stmt2->execute()) {

					$sql3 = "DELETE FROM tickets WHERE visitorID = ?";
					$stmt3 = $conn->prepare($sql3);
					$stmt3->bind_param('i', $id);

					if (!$stmt3->execute()) {
						$ticket_errors['error'] = "Big OOF.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
					else{
						$ticket_errors['error'] = "There was an error processing your tickets.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}

				}
				else{
					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");
				    </script>";
					exit();
				}
			}
			else{
				$ticket_errors['error'] = "There was an error processing your tickets.";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets3'); 
				</script>";
			}

        	break;
        case '5':

            $hex = bin2hex(random_bytes(25));
            $id = $_SESSION['id'];


    		$sql = "INSERT INTO tickets (visitorID, ticketHex) VALUES (?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('is', $id, $hex);

			if ($stmt->execute()) {
				$sql2 = "UPDATE visitor SET Visitor_Balance = 23 * (-5) WHERE visitorID = ?";
				
				$stmt2 = $conn->prepare($sql2);
				$stmt2->bind_param('i', $id);

				if (!$stmt2->execute()) {

					$sql3 = "DELETE FROM tickets WHERE visitorID = ?";
					$stmt3 = $conn->prepare($sql3);
					$stmt3->bind_param('i', $id);

					if (!$stmt3->execute()) {
						$ticket_errors['error'] = "Big OOF.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
					else{
						$ticket_errors['error'] = "There was an error processing your tickets.";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}

				}
				else{
					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");
				    </script>";
					exit();
				}
			}
			else{
				$ticket_errors['error'] = "There was an error processing your tickets.";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets3'); 
				</script>";
			}

        	break;
        default:
        	echo "hello?!?!?";
        		break;
    }
}


// ACCOMMODATION
if (isset($_POST['accommodation'])) {
	switch ($_POST['accommodation']) {

		case '2':

			function fetchAccom(mysqli $conn){
				$data = [];

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 2 AND Account_ID IS NULL AND Accommodation_Status = 0";

				$results = $conn->query($sql);

				if ($results->num_rows > 0) {
					while ($row = $results->fetch_assoc()) {
						$data[] = $row;
					}
				}
				return $data;
			}

			break;

		case '3':
			function fetchAccom(mysqli $conn){
				$data = [];

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 3 AND Account_ID IS NULL AND Accommodation_Status = 0";

				$results = $conn->query($sql);

				if ($results->num_rows > 0) {
					while ($row = $results->fetch_assoc()) {
						$data[] = $row;
					}
				}
				return $data;
			}
			break;

		case '4':
			function fetchAccom(mysqli $conn){
				$data = [];

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 4 AND Account_ID IS NULL AND Accommodation_Status = 0";

				$results = $conn->query($sql);

				if ($results->num_rows > 0) {
					while ($row = $results->fetch_assoc()) {
						$data[] = $row;
					}
				}
				return $data;
			}
			break;

		case '5':
			function fetchAccom(mysqli $conn){
				$data = [];

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 5 AND Account_ID IS NULL AND Accommodation_Status = 0";

				$results = $conn->query($sql);

				if ($results->num_rows > 0) {
					while ($row = $results->fetch_assoc()) {
						$data[] = $row;
					}
				}
				return $data;
			}
			break;	

		default:
			echo "hello?!?!?";
			break;
	}
}




// THE TWILIGHT ZONE BELOW, DON'T GO UNLESS REALLY NECESSARY

// RESERVE ACCOMMODATION
if (isset($_GET['reserve'])) {
	switch ($_GET['reserve']) {

		case '1':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '1' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '1'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}

			break;

		case '2':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '2' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '2'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 2;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}

			break;

		case '3':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '3' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '3'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 3;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '4':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '4' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '4'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 4;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '5':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '5' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '5'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 5;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '6':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '6' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '6'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 6;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '7':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '7' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '7'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 7;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '8':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '8' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '8'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 8;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '9':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '9' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '9'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 9;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '10':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '10' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '10'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 10;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '11':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '11' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '11'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 11;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '12':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '12' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '12'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 12;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		case '13':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '13' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '13'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 13;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;
			
		case '14':

			$id = $_SESSION['id'];
			
			$query1 = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '14' AND Account_ID IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET Account_ID = ? WHERE Accommodation_ID = '14'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					$_SESSION['reservation'] = 14;
					echo "<script type='text/javascript'>
				    window.alert('Reservation Complete!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Completing the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
			}
 
			break;

		default:
			echo "hello?!?!?";
			break;
	}
}


// CANCEL RESERVATION
if (isset($_GET['cancelres'])) {
	switch ($_GET['cancelres']) {

		case '1':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '1' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '1' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '2':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '2' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '2' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '3':

			
			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '3' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '3' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

 
			break;

		case '4':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '4' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '4' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '5':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '5' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '5' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '6':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '6' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '6' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again! 2');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '7':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '7' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '7' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '8':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '8' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '8' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '9':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '9' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '9' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '10':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '10' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '10' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}
 
			break;

		case '11':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '11' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '11' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '12':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '12' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '12' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		case '13':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '13' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '13' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;
			
		case '14':

			$id = $_SESSION['id'];
			
			$query = "SELECT Account_ID FROM accommodation WHERE Accommodation_ID = '14' AND Account_ID IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET Account_ID = NULL WHERE Accommodation_ID = '14' AND Account_ID = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('i', $id);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Canceled!');
					window.location.replace(\"profile.php\");
				    </script>";
				}
				else {
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";
				}

			}
			else{
					echo "<script type='text/javascript'>
				    window.alert('Error Canceling the Reservation. Please try again!');
					window.location.replace(\"profile.php\");
				    </script>";		
			}

			break;

		default:
			echo "hello?!?!?";
			break;
	}

}

?>