<?php

session_start();

require 'db.php';
// require_once 'emailer.php';

// Array for errors and storing email in case of mistake by user
$reg_errors = array();
$log_errors = array();
$accom_errors = array();
$ticket_errors = array();
$email = "";
$log_email = "";

// TEST EMAIL
// if (isset($_GET['sendemail'])) {
// 	// mail('prop2020mail@gmail.com', 'TEST', 'LMAO REKT.');
// 	sendEmail('playpaladinsstrike@gmail.com');
// }

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
			//Enter data into customer table
			$sql = "INSERT INTO customer (customerID) VALUES (?)";
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
				$reg_errors['db_error'] = "Database error: failed to register" . $stmt->error();;
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets1'); 
				</script>";
			}
		}
		else{
			$reg_errors['db_error'] = "Database error: failed to register" . $stmt->error();;
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
			$stmt->close();
			//Enter data into customer table
			$sql = "INSERT INTO customer (customerID) VALUES ($user_id)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $user_id);
			if ($stmt->execute()) {
				//Login the user
				$_SESSION['id'] = $user_id;
				$_SESSION['email'] = $email;
				unset($_SESSION['tickets']);
				unset($_SESSION['reservation']);
				header("Location: profile.php");
				exit();
			}
			else{
				$reg_errors['db_error'] = "Database error: failed to register " . $stmt->error();
			}
		}
		else{
			$reg_errors['db_error'] = "Database error: failed to register " . $stmt->error();
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
			$location = "index.php#schedule";

			$_SESSION['id'] = $id;
			$_SESSION['email'] = $user['Account_Email'];
			$_SESSION['modal'] = '#tickets3';
			

			$sql2 = "SELECT customerID FROM tickets WHERE customerID = '".$id."' LIMIT 1";

			if ($result2 = $conn->query($sql2)) {
				
			    if ($result2->num_rows > 0) {
    			    $_SESSION['tickets'] = 1;
        			unset($_SESSION['modal']);
	    			$location = "profile.php";
			    }
			    else {
					unset($_SESSION['tickets']);
					$_SESSION['modal'] = '#tickets3';
					$location = "index.php#schedule";
			    }

			    $result2->close();

			}
			else {
				unset($_SESSION['tickets']);
			}

			$query = "SELECT Accommodation_ID, customerID FROM accommodation WHERE customerID = '".$id."' LIMIT 1";

			if ($result2 = $conn->query($query)) {

				if ($result2->num_rows > 0) {
				    $obj = $result2->fetch_object();
				    $accomID = $obj->Accommodation_ID;
				    $_SESSION['reservation'] = $accomID;
				}
				else {
					unset($_SESSION['reservation']);
				}

			    $result2->close();
			}
			else {
				unset($_SESSION['reservation']);
			}

			header("Location: " . $location);
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
		$stmt->close();

		if (password_verify($password, $user['Account_Pass_hash'])) {
			$id = $user['Account_ID'];

			$_SESSION['id'] = $id;
			$_SESSION['email'] = $user['Account_Email'];
			

			$sql2 = "SELECT customerID FROM tickets WHERE customerID = '".$id."' LIMIT 1";

			if ($result2 = $conn->query($sql2)) {
				
			    if ($result2->num_rows > 0) {
    			    $_SESSION['tickets'] = 1;
			    }
			    else {
					unset($_SESSION['tickets']);
			    }

			    $result2->close();

			}
			else {
				unset($_SESSION['tickets']);
			}

			$query = "SELECT Accommodation_ID, customerID FROM accommodation WHERE customerID = '".$id."' LIMIT 1";

			if ($result2 = $conn->query($query)) {

				if ($result2->num_rows > 0) {
				    $obj = $result2->fetch_object();
				    $accomID = $obj->Accommodation_ID;
				    $_SESSION['reservation'] = $accomID;
				}
				else {
					unset($_SESSION['reservation']);
				}

			    $result2->close();
			}
			else {
				unset($_SESSION['reservation']);
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
            $email = $_SESSION['email'];

    		$sql = "UPDATE customer SET email=?, balance= -23, ticketHex=? WHERE customerID=?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ssi', $email, $hex, $id);

			if ($stmt->execute()) {

				$_SESSION['tickets'] = 1;
				echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");						
			    	</script>";
				exit();

			}
			else{

					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

            break;
        case '2':

            $hex = bin2hex(random_bytes(5));
            $hex2 = bin2hex(random_bytes(5));
            $id = $_SESSION['id'];
            $email = $_SESSION['email'];

    		$sql = "UPDATE customer SET email=?, balance= -23, ticketHex=? WHERE customerID=?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ssi', $email, $hex, $id);

			if ($stmt->execute()) {

				$sql3 = "INSERT INTO customer (email, balance, ticketHex) VALUES (?, -23, ?)";
				$stmt3 = $conn->prepare($sql3);
				$stmt3->bind_param('ss', $email, $hex2);

				if ($stmt3->execute()) {

					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");						
			    	</script>";
					exit();				
				}
				else {
					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

			}
			else{

					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

            break;
        case '3':

            $hex = bin2hex(random_bytes(5));
            $hex2 = bin2hex(random_bytes(5));
            $hex3 = bin2hex(random_bytes(5));
            $id = $_SESSION['id'];
            $email = $_SESSION['email'];

    		$sql = "UPDATE customer SET email=?, balance= -23, ticketHex=? WHERE customerID=?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ssi', $email, $hex, $id);

			if ($stmt->execute()) {

				$sql3 = "INSERT INTO customer (email, balance, ticketHex) VALUES (?, -23, ?), (?, -23, ?)";
				$stmt3 = $conn->prepare($sql3);
				$stmt3->bind_param('ssss', $email, $hex2, $email, $hex3);

				if ($stmt3->execute()) {

					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");						
			    	</script>";
					exit();				
				}
				else {
					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

			}
			else{

					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

            break;
        case '4':

            $hex = bin2hex(random_bytes(5));
            $hex2 = bin2hex(random_bytes(5));
            $hex3 = bin2hex(random_bytes(5));
            $hex4 = bin2hex(random_bytes(5));
            $id = $_SESSION['id'];
            $email = $_SESSION['email'];

    		$sql = "UPDATE customer SET email=?, balance= -23, ticketHex=? WHERE customerID=?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ssi', $email, $hex, $id);

			if ($stmt->execute()) {

				$sql3 = "INSERT INTO customer (email, balance, ticketHex) VALUES (?, -23, ?), (?, -23, ?), (?, -23, ?)";
				$stmt3 = $conn->prepare($sql3);
				$stmt3->bind_param('ssssss', $email, $hex2, $email, $hex3, $email, $hex4);

				if ($stmt3->execute()) {

					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");						
			    	</script>";
					exit();				
				}
				else {
					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

			}
			else{

					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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


        	break;
        case '5':

            $hex = bin2hex(random_bytes(5));
            $hex2 = bin2hex(random_bytes(5));
            $hex3 = bin2hex(random_bytes(5));
            $hex4 = bin2hex(random_bytes(5));
            $hex5 = bin2hex(random_bytes(5));
            $id = $_SESSION['id'];
            $email = $_SESSION['email'];

    		$sql = "UPDATE customer SET email=?, balance= -23, ticketHex=? WHERE customerID=?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ssi', $email, $hex, $id);

			if ($stmt->execute()) {

				$sql3 = "INSERT INTO customer (email, balance, ticketHex) VALUES (?, -23, ?), (?, -23, ?), (?, -23, ?), (?, -23, ?)";
				$stmt3 = $conn->prepare($sql3);
				$stmt3->bind_param('ssssssss', $email, $hex2, $email, $hex3, $email, $hex4, $email, $hex5);

				if ($stmt3->execute()) {

					$_SESSION['tickets'] = 1;
					echo "<script type='text/javascript'>
				    window.alert('Tickets Reserved!');
    			   	window.location.replace(\"profile.php\");						
			    	</script>";
					exit();				
				}
				else {
					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
					else{
						// $ticket_errors['error'] = "There was an error processing your tickets.";
						$ticket_errors['error'] = $conn->error;
						echo "<script type='text/javascript'> 
						localStorage.setItem('openModal', '#tickets3'); 
						</script>";
					}
				}

			}
			else{

					$sql2 = "UPDATE customer SET email = NULL, balance = 0, ticketHex = NULL WHERE customerID=?";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('i', $id);

					if (!$stmt2->execute()) {
						$ticket_errors['error'] = "There is a big error. Please, contact someone!";
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

        	break;
        default:
				$ticket_errors['error'] = "There was an error processing your tickets.";
				echo "<script type='text/javascript'> 
				localStorage.setItem('openModal', '#tickets3'); 
				</script>";
        		break;
    }
}

// CANCEL TICKET
if (isset($_GET['cancelticket'])) {
	$ticketHex = $_GET['cancelticket'];
	$email = $_SESSION['email'];


	$sql = "UPDATE customer SET email=NULL, balance=0, ticketHex=NULL WHERE ticketHex=?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $ticketHex);

	if ($stmt->execute()) {
		$stmt->close();
		echo "<script type='text/javascript'>
	    window.alert('Ticket Cancelled!');
	   	window.location.replace(\"profile.php\");
	    </script>";
	}
	else {
		echo "error: " . $stmt->error;	
	}
}

// ACCOMMODATION
if (isset($_POST['accommodation'])) {
	switch ($_POST['accommodation']) {

		case '2':

			function fetchAccom(mysqli $conn){
				$data = [];

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 2 AND customerEmail IS NULL AND Accommodation_Status = 0";

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

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 3 AND customerEmail IS NULL AND Accommodation_Status = 0";

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

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 4 AND customerEmail IS NULL AND Accommodation_Status = 0";

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

				$sql = "SELECT Accommodation_ID FROM accommodation WHERE Accommodation_Max_People = 5 AND customerEmail IS NULL AND Accommodation_Status = 0";

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '1' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '1'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '2' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '2'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '3' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '3'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '4' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '4'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '5' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '5'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '6' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '6'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '7' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '7'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '8' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '8'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '9' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '9'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '10' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '10'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '11' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '11'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '12' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '12'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '13' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '13'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query1 = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '14' AND customerEmail IS NOT NULL ";
			$result1 = $conn->query($query1);
			$row_cnt = $result1->num_rows;

			if ($row_cnt > 0) {
					echo "<script type='text/javascript'>
				    window.alert('Sorry, the Room is Reserved. Please try another!');
					window.location.replace(\"profile.php\");
				    </script>";
			}
			else{
				$query = "UPDATE accommodation SET customerEmail = ? WHERE Accommodation_ID = '14'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '1' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '1' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '2' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '2' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '3' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '3' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '4' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '4' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '5' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '5' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '6' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '6' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

		case '7':

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '7' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '7' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '8' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '8' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '9' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '9' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '10' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '10' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '11' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '11' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '12' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '12' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '13' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '13' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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

			$email = $_SESSION['email'];
			
			$query = "SELECT customerEmail FROM accommodation WHERE Accommodation_ID = '14' AND customerEmail IS NOT NULL ";
			$result = $conn->query($query);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0) {
		
			    $query = "UPDATE accommodation SET customerEmail = NULL WHERE Accommodation_ID = '14' AND customerEmail = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('s', $email);

				if ($stmt->execute()) {
					unset($_SESSION['reservation']);
					echo "<script type='text/javascript'>
				    window.alert('Reservation Successfully Cancelled!');
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