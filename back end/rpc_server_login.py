#!/usr/bin/env python3
import mysql.connector
import mysql.connector.errors
import pika
import json

cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306',		
	database='back_end_database')
cursor = cnx.cursor(buffered = True)

def login_user(login_arguments):
	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306',		
	database='back_end_database')
	cursor = cnx.cursor(buffered = True)
	value_list = list()

	for value in login_arguments.values():
		value_list.append(value)
	value_string = str(value_list)
	a = value_string.strip("[")
	b = a.strip("]")
	c = b.replace("'", "")
	d = c.split(', ')
	
	print(d)
	loginquery = ("SELECT id FROM members WHERE email=%s AND password=%s;")
	cursor.execute(loginquery, d)
	if cursor.rowcount:
		print("true")
		true_or_false("true")
	else:
		print("false")
		true_or_false("false")
	cnx.commit()
	cursor.close()
	cnx.close()

credentials = pika.PlainCredentials('rabbitmq-service', 'Team666!')
parameters = pika.ConnectionParameters('10.0.0.7',
			5672,
			'/',
			credentials)
connection = pika.BlockingConnection(parameters)
channel = connection.channel()

channel.queue_declare(queue='login-queue', durable=True)

def on_request(ch, method, props, body):
	n = json.loads(body)	
	response = login_user(n)
	ch.basic_publish(exchange='Login-Exchange',
			routing_key='send-user-login',
			properties=pika.BasicProperties(correlation_id = \
							props.correlation_id),
			body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)
	
def true_or_false(message):
	credentials = pika.PlainCredentials('rabbitmq-service', 'Team666!')

	parameters = pika.ConnectionParameters('10.0.0.7', 
					5672,	
					'/',
					credentials)
										
	connection = pika.BlockingConnection(parameters)
	channel = connection.channel()
	channel.queue_declare(queue='login-queue', durable=True)
	channel.basic_publish(exchange='Login-Exchange', routing_key='send-user-login', body=message)
	print(" [x] Sent " + message)
	
	connection.close()

	
while True:	   
	try:
		channel.queue_purge(queue='login-queue')
		channel.basic_qos(prefetch_count=1)
		channel.basic_consume(queue='login-queue', on_message_callback=on_request)

		print(" [x] Awaiting login requests")
		channel.start_consuming()
	except AttributeError:	 
		pass
		continue
	except json.decoder.JSONDecodeError:
		pass
		continue
