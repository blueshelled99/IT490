<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
        $this->channel->basic_publish($msg, '', 'user-test');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    }
}

$options = [ 'salt' => 'Nature415SpudFrozen' ];
$hashed_password = password_hash($pass, PASSWORD_BCRYPT, $options);

$rpc = new RpcClient();
#$response = $rpc->call("$user,$pass");
$response = $rpc->call("$email,$hashed_password");

echo $response;
#echo ' [.] Got ', $response, "\n";

if ($response == "true"){
	$_SESSION['logged_in'] = true;
    header("Location: main_menu.php"); 
}
else{
	$_SESSION['logged_in'] = false;
	header("Location: index.html");
	echo "Wrong username or password. Please try again.";
}

?>
