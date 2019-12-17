<?php 
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');
if (!empty($email)){
if (!empty($password)){
	$host = "STUDMYSQL01";
	$dbusername = "dbi406102";
	$dbpassword = "123456789a";
	$dbname = "dbi406102";

	$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

	$queryw = mysqli_query($conn, "SELECT * From account Where Account_Email = '$email'");
	$answ = mysqli_fetch_array($queryw);

	if (mysqli_connect_error()){
	die('Connect Error ('. mysqli_connect_errno() .') '
	. mysqli_connect_error());
	}
	else if ($answ['Account_Email'] == $email) {
	$message = "Email Taken";
	echo "<script type='text/javascript'>alert('$message');
		location.replace('/index.php')</script>";
	}
	else if ($answ['Account_Email'] != $email) {
	$sql = "INSERT INTO account (Account_Email, Account_Pass_hash)
	values ('$email','$password')";
	if ($conn->query($sql)){
	$result = mysqli_query($conn, "SELECT * From account Where Account_Email = '$email' and Account_Pass_hash = '$password'");
	$row = mysqli_fetch_array($result);
	echo "New record is inserted sucessfully. ";
	echo "Welcome <b>".$row['Account_Email']."</b> with password: <b>".$row['Account_Pass_hash']."</b>!";
	// header("Location: /index.php");
	// exit(); 
	echo " Please go to <a href='/index.php'>the home page</a> if you're not getting redirected";
	}

	else{
	echo "Error: ". $sql ."
	". $conn->error;
	}
	$conn->close();
	}
}
else{
echo "Password should not be empty";
die();
}
}
else{
echo "Email should not be empty";
die();
}

?>