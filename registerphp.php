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


$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$email = $_POST['email'];
$pass = $_POST['password'];

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
            '192.168.1.48',
            5672,
            'rabbitmq-test',
            'test'
        );
        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "user-test", //this should be the queue we are using
            false,
            true, //message is durable if this is true
            false,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            '',
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
            $n,
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue//, remember to add , here if we need delivery mode
                //'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                //i think there should be a delivery mode persistent 
            )
        );
        $this->channel->basic_publish($msg, '', 'user-test'); //i think the second and third parameter have to be swapped here
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    }
}

$options = [ 'salt' => 'Nature415SpudFrozen' ];
$hashed_password = password_hash($pass, PASSWORD_BCRYPT, $options);
    
$rpc = new RpcClient();
$response = $rpc->call("$email,$hashed_password,$fname,$lname");
echo $response;
if ($response == "true"){
    header("Location: index.html");
	
}

?>


</div>
</body>
</html>