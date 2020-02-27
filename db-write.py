#!/usr/bin/env python
import pika
import sys

#use this code if not sending from server and change ip address and credentials
credentials = pika.PlainCredentials('user', 'pwd') # change credentials and ip address
parameters = pika.ConnectionParameters('x.x.x.x', 
                                    5672,
                                    '/',
                                    credentials)

connection = pika.BlockingConnection(parameters)

channel = connection.channel()

channel.queue_declare(queue='db-access', durable=True)

message = ' '.join(sys.argv[1:]) or "this was sent from 'db-access'"
channel.basic_publish(
    exchange='',
    routing_key='db-access',
    body=message,
    properties=pika.BasicProperties(
        delivery_mode=2,  # make message persistent
    ))
print(" [x] Sent %r" % message)
connection.close()
