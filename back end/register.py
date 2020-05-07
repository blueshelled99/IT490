import mysql.connector
import mysql.connector.errors
import pika
import json

credentials = pika.PlainCredentials('rabbitmq-service', 'Team666!')
parameters = pika.ConnectionParameters('10.0.0.7',
			5672,
			'/',
			credentials)
connection = pika.BlockingConnection(parameters)
channel = connection.channel()

#channel.queue_declare(queue='user-test', durable=True)

def auth(n):
	mydb = mysql.connector.connect(
	  host="localhost",
	  user="backendtest",
	  passwd="NOTweak$_@123!",
	  database="back_end_database"
	)

	print(mydb)
	
	value_list = list()

	for value in n.values():
		value_list.append(value)
	value_string = str(value_list)
	a = value_string.strip("[")
	b = a.strip("]")
	c = b.replace("'", "")
	d = c.split(', ')
	
	print(d)
	
	mycursor = mydb.cursor(buffered=True)
	
	
	sql = "INSERT INTO members (id, firstname, lastname, email, password) VALUES (id, %s, %s, %s, %s);"
	mycursor.execute(sql, d)

	mydb.commit()

	print(mycursor.rowcount, "record inserted.")
	if mycursor.rowcount:
		return "true"
	else:
		return "false"




def login_request(ch, method, props, body):
	n = json.loads(body)
	print (n)
	print(" [.] auth(%s)" % n)
	response = auth(n)
	ch.basic_publish(exchange='Registration-Exchange',
			routing_key='send-user-registration',
			properties=pika.BasicProperties(correlation_id = \
							props.correlation_id),
			body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)
	connection.close()



channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='register-queue', on_message_callback=login_request)

print(" [x] Awaiting registration requests")
channel.start_consuming()
