<?php

require 'dbcredentials.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
	die('Database Error: ' . $conn->connect_error);
}


// try{
// 	$conn = new PDO('mysql:host=studmysql01.fhict.local;dbname=dbi406102', DB_USER, DB_PASS);
// }
// catch (PDOException $e) {
//     echo $e->getMessage();
// }



?>