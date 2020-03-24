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
	authquery=("SELECT id FROM members WHERE email=%s AND password=%s;")
	cursor.execute(authquery, d)
	for (id) in cursor:
		print("{} is the matching id of this user and password.".format(id))
	if cursor.rowcount:
		print("true")
		true_or_false("true")
	else:
		print("false")
		true_or_false("false")
	cursor.close()
	cnx.commit()
	cnx.close()
	

credentials = pika.PlainCredentials('rabbitmq-test', 'test')
parameters = pika.ConnectionParameters('192.168.1.48',
			5672,
			'/',
			credentials)
connection = pika.BlockingConnection(parameters)
channel = connection.channel()

channel.queue_declare(queue='login-queue', durable=True)


def login_request(ch, method, props, body):
	n = json.loads(body)	
	response = login_user(n)
	ch.basic_publish(exchange='',
			routing_key=props.reply_to,
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
	channel.queue_declare(queue='login-queue', durable=True)

	channel.basic_publish(exchange='', routing_key='', body=message)

	print(" [x] Sent " + message)
	connection.close()

channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='login-queue', on_message_callback=login_request)
print(" [x] Awaiting login requests")
channel.start_consuming()
