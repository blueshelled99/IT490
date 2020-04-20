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

//encrypt user creds
$options = [ 'salt' => 'seasalt_icecream123456' ];
$hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

#POST Data	
$userSubmittal = array(
	"firstName" => $_POST['firstName'],
	"lastName" => $_POST['lastName'],
	"email" => $_POST['email'],
	"pass" => $hashed_password
);

#encode POST data to JSON
$msgJson = json_encode($userSubmittal);

#AMQP Connection
$connection = new AMQPStreamConnection('10.0.0.7', 5672, 'rabbitmq-service', 'Team666!'); //change ip address
$channel = $connection->channel();

#RabbitMQ Message data as JSON
$msg = new AMQPMessage($msgJson);

#Declare Exchange and routing key
$channel->basic_publish($msg, 'Registration-Exchange', 'send-user-registration');

#Echo result
echo "Account was registered. Return to previous page to login.";

#Close connection
$channel->close();
$connection->close();

?>
