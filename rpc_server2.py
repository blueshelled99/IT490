#!/usr/bin/env python
import mysql.connector
import mysql.connector.errors
import pika

cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', 	
	database='back_end_database')
cursor = cnx.cursor(buffered = True)

def login_user(login_arguments):
	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', 	
	database='back_end_database')
	cursor = cnx.cursor(buffered = True)
	args = login_arguments[:-1:]
	args2 = args[0: 0:] + args[1 + 1::]
	args3 = args2.split(', ')
	print(args3)
	authquery=("SELECT id FROM DB_add WHERE email=%s AND password=%s;")
	cursor.execute(authquery, args3)
	for (id) in cursor:
		print("{} is the matching id of this user and password.".format(id))
	cursor.close()
	cnx.commit()
	cnx.close()
	

def register_user(registration_arguments):
	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', 	
	database='back_end_database')
	cursor = cnx.cursor(buffered = True)
	args = registration_arguments[:-1:]
	args2 = args[0: 0:] + args[1 + 1::]
	args3 = args2.split(', ')
	print (args3)
	addcredentialsquery = ("INSERT INTO DB_add (email, password, firstname, lastname, id) VALUES (%s, %s, %s, %s, id);")
	cursor.execute(addcredentialsquery, args3)
	cnx.commit()
	cursor.close()
	cnx.close()



credentials = pika.PlainCredentials('rabbitmq-test', 'test')
parameters = pika.ConnectionParameters('192.168.1.48',
			5672,
			'/',
			credentials)
connection = pika.BlockingConnection(parameters)
channel = connection.channel()

channel.queue_declare(queue='user-test', durable=True)

def register_request(ch, method, props, body):
	n = str(body)	
	response = register_user(n)
	ch.basic_publish(exchange='',
			routing_key=props.reply_to,
			properties=pika.BasicProperties(correlation_id = \
							props.correlation_id),
			body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)

def login_request(ch, method, props, body):
	n = str(body)	
	response = login_user(n)
	ch.basic_publish(exchange='',
			routing_key=props.reply_to,
			properties=pika.BasicProperties(correlation_id = \
							props.correlation_id),
			body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_qos(prefetch_count=1)
#on_message_callback calls the function. Login_request is login and register_request is for registration
channel.basic_consume(queue='user-test', on_message_callback=register_request)
print(" [x] Awaiting registration/login requests")
channel.start_consuming()
