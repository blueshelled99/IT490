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

$userSubmittal = array(
	"firstName" => $_POST['firstName'],
	"lastName" => $_POST['lastName'],
	"email" => $_POST['email'],
	"pass" => $_POST['pass']
);

$msgJson = json_encode($userSubmittal);



$connection = new AMQPStreamConnection('192.168.1.240', 5672, 'rabbitmq-test', 'test');

$channel = $connection->channel();

$channel -> queue_declare('user-test2', false, true, false, false);

$msg = new AMQPMessage($msgJson);

$channel->basic_publish($msg, '', 'user-test2');

echo "[x] Sent form data!\n";

$channel->close();
$connection->close();

?>
