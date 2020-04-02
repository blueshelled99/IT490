<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Registration</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="square">




<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//adding code for encryption here maybe
$options = [ 'salt' => 'seasalt_icecream123456' ];
$hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
	
$userSubmittal = array(
	"firstName" => $_POST['firstName'],
	"lastName" => $_POST['lastName'],
	"email" => $_POST['email'],
	"pass" => $hashed_password //comment this and uncomment the next line to get everything back to normal
	//"pass" => $_POST['password']
);

$msgJson = json_encode($userSubmittal);



$connection = new AMQPStreamConnection('10.0.0.7', 5672, 'rabbitmq-service', 'Team666!'); //change ip address

$channel = $connection->channel();

$channel -> queue_declare('register-queue', false, true, false, false);

$msg = new AMQPMessage($msgJson);

$channel->basic_publish($msg, '', 'register-queue');

echo "[x] Sent form data!\n";

$channel->close();
$connection->close();

?>
