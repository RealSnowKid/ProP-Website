<?php

	require_once 'vendor/autoload.php';
	require_once 'dbCredentials.php';

	// Create the Transport
	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
	  ->setUsername(EMAIL)
	  ->setPassword(PASSWORD)
	;

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);



	function sendEmail($userEmail)
	{
		global $mailer;
		// Create a message
		$message = (new Swift_Message('TEST'))
		  ->setFrom(EMAIL)
		  ->setTo($userEmail)
		  ->setBody('LMAO GET REKT SON')
		  ;


		// Send the message
		$result = $mailer->send($message);

	}


?>