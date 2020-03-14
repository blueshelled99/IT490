<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="square">

<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$options = [ 'salt' => 'NaturalSpudPhenomenom' ];
$hashed_password = password_hash($_POST['pass'], PASSWORD_BCRYPT, $options);
    
$userSubmittal = array(
    "email" => $_POST['email'],
    "password" => $hashed_password
);

$msgJson = json_encode($userSubmittal);

$connection = new AMQPStreamConnection('192.168.1.240', 5672, 'rabbitmq-test', 'test');

$channel = $connection->channel();

$channel -> queue_declare('user-test2', false, true, false, false);

$msg = new AMQPMessage($msgJson);

$channel->basic_publish($msg, '', 'user-test2');

echo $msg;
    
if $msg == "true"){
        $_SESSION['logged in'] = true;
    header("Location: user_home.php")
}
    
else{
    $_SESSION['logged in'] = false;
    header("Location: index.html");
    echo "Wrong credentials. Please try again"
}

$channel->close();
$connection->close();

?>
