<?php

$email = $_POST['email2'];
$password = $_POST['password2'];

$host = "STUDMYSQL01";
$dbusername = "dbi406102";
$dbpassword = "123456789a";
$dbname = "dbi406102";
// Create connection
$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

$email = stripcslashes($email);
$password = stripcslashes($password);
$email = mysqli_real_escape_string($conn, $email);
$password = mysqli_real_escape_string($conn, $password);

$result = mysqli_query($conn, "SELECT * From account Where Account_Email = '$email' and Account_Pass_hash = '$password'")
			or die('Failed to query db'.mysql_error());

$row = mysqli_fetch_array($result);
if ($row['Account_Email'] == $email && $row['Account_Pass_hash'] == $password) {
	echo "Login successful! Welcome ".$row['Account_Email']." with password: ".$row['Account_Pass_hash']."!";
	echo "<br>";
	echo "Please go to <a href='/index.php'>the home page</a> if you're not getting redirected";
} else {
	echo "Failed to login!";
}


?>