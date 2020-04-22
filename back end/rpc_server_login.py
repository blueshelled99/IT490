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

channel.queue_declare(queue='login-queue-response', durable = True)


def auth(n):
	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', database='back_end_database')
	cursor = cnx.cursor(buffered = True)
	value_list = list()
	for value in n.values():
		value_list.append(value)
	value_string = str(value_list)
	a = value_string.strip("[")
	b = a.strip("]")
	c = b.replace("'", "")
	d = c.split(', ')
	authquery=("SELECT id FROM members WHERE email=%s AND password=%s;")
	cursor.execute(authquery, d)
	if cursor.rowcount:
		return "true"
	else:
		return "false"
	cursor.close()
	cnx.commit()
	cnx.close()


def on_request(ch, method, props, body):
    n = json.loads(body)

    print(n)
    response = auth(n)
    print(" [x] Sent " + response)
    ch.basic_publish(exchange='Login-Exchange',
                     routing_key='login-response',
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='login-queue', on_message_callback=on_request)

print(" [x] Awaiting login requests")
channel.start_consuming()

