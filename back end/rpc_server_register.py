#!/usr/bin/env python3
import mysql.connector
import mysql.connector.errors
import pika
import json

cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', 	
	database='back_end_database')
cursor = cnx.cursor(buffered = True)

def register_user(registration_arguments):
	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', 	
	database='back_end_database')
	cursor = cnx.cursor(buffered = True)
	value_list = list()

	for value in registration_arguments.values():
		value_list.append(value)
	value_string = str(value_list)
	a = value_string.strip("[")
	b = a.strip("]")
	c = b.replace("'", "")
	d = c.split(', ')
	
	print(d)
	addcredentialsquery = ("INSERT INTO members (id, firstname, lastname, email, password) VALUES (id, %s, %s, %s, %s);")
	cursor.execute(addcredentialsquery, d)
	if cursor.rowcount:
		print("true")
		true_or_false("true")
	else:
		print("false")
		true_or_false("false")
	cnx.commit()
	cursor.close()
	cnx.close()



credentials = pika.PlainCredentials('rabbitmq-test', 'test')
parameters = pika.ConnectionParameters('192.168.1.48,
			5672,
			'/',
			credentials)
connection = pika.BlockingConnection(parameters)
channel = connection.channel()

channel.queue_declare(queue='register-queue', durable=True)

def register_request(ch, method, props, body):
	n = json.loads(body)	
	response = register_user(n)
	ch.basic_publish(exchange='',
			routing_key='',
			properties=pika.BasicProperties(correlation_id = \
							props.correlation_id),
			body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)

def true_or_false(message):
	credentials = pika.PlainCredentials('rabbitmq-test', 'test')

	parameters = pika.ConnectionParameters('192.168.1.48', 
					5672,   
					'/',
		                        credentials)
										
	connection = pika.BlockingConnection(parameters)
	channel = connection.channel()
	channel.queue_declare(queue='register-queue', durable=True)

	channel.basic_publish(exchange='', routing_key='', body=message)

	print(" [x] Sent " + message)
	connection.close()

channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='register-queue', on_message_callback=register_request)
print(" [x] Awaiting registration requests")
channel.start_consuming()
