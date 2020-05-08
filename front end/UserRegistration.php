<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Registration</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="square">

<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;



class RpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            '10.0.0.7', //change ip address here
            5672,
            'rabbitmq-service',
            'Team666!'
        );

        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "register-queue",
            false,
            true,
            false,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            "",
            false,
            true,
            false,
            false,
            array(
                $this,
                'onResponse'
            )
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function call($n)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            //(string) $n,
	    $n,
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            )
        );
	$this->channel->basic_publish($msg, 'Registration-Exchange', 'send-user-registration');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    }
}

$options = [ 'salt' => 'seasalt_icecream123456' ];
$hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
	
#POST Data
$userSubmittal = array(
    "firstName" => $_POST['firstName'],
    "lastName" => $_POST['lastName'],
    "email" => $_POST['email'],
    "pass" => $hashed_password
);

$msgJSON = json_encode($userSubmittal);

$rpc = new RpcClient();
$response = $rpc->call($msgJSON);

echo $response;

if ($response == "true"){
	header("Location: index.html");
}

else{
	$_SESSION['logged_in'] = false;
	echo "Could not register user account. Please try registering again.";
}

?>
