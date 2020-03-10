import pika

credentials=pika.PlainCredentials('rabbitmq-test', 'test')
parameters=pika.ConnectionParameters('192.168.1.48', 
				5672,   
				'/',
                                credentials)

connection = pika.BlockingConnection(parameters)
channel = connection.channel()


channel.queue_declare(queue='DB_auth', durable=True)
channel.queue_declare(queue='user-test', durable=True)

user=''

def loginrequest(ch, method, props, body):			
	emailaddress = body.split(',')[0]
	password = body.split(',')[1]
	response = auth(emailaddress, password)
	
	if response=='true':
		global email
		email=emailaddress
	ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)

def registerrequest(ch, method, props, body):
	emailaddress = body.split(',')[0]
	password = body.split(',')[1]
	firstname = body.split(',')[2]
	lastname = body.split(',')[3]
	response = registeruser(emailaddress, password, firstname, lastname)
	ch.basic_publish(exchange='', routing_key=props.reply_to, properties=pika.BasicProperties(correlation_id = props.correlation_id), body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='DB_auth', on_message_callback=loginrequest)
print(" [x] Awaiting login requests")
channel.basic_consume(queue='user-test', on_message_callback=registerrequest)
print(" [x] Awaiting registration requests")	
channel.start_consuming()
