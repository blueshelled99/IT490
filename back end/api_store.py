#!/usr/bin/env python3

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

channel.queue_declare(queue='api-queue', durable = True)


def auth(n):
	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', database='back_end_database')
	cursor = cnx.cursor(buffered = True)
	value_list = list()
	for value in n.values():
		value_list.append(value)
	value_list.reverse()
	value_string = str(value_list)
	a = value_string.strip("[")
	b = a.strip("]")
	c = b.replace("'", "")
	d = c.split(', ')
	authquery=("UPDATE members SET history = %s WHERE email = %s;")
	cursor.execute(authquery, d)
	if cursor.rowcount:
		return("true")
	else:
		return("false")
	cursor.close()
	cnx.commit()
	cnx.close()


def on_request(ch, method, props, body):
	n = json.loads(body)

	print(n)
	auth(n)



channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='api-queue', on_message_callback=on_request)

print(" [x] Awaiting api URLs")
channel.start_consuming()