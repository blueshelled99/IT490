#!/usr/bin/env python3
import pika
import sys

credentials = pika.PlainCredentials('rabbitmq-test', 'test')
connection = pika.BlockingConnection(
    pika.ConnectionParameters('192.168.1.48',
			5672,
			'/',
			credentials))

channel = connection.channel()

channel.queue_declare(queue='Rishi', durable=True)

message = ' '.join(sys.argv[1:]) or "Hello World! Sent from Rishi"
channel.basic_publish(
    exchange='',
    routing_key='task_queue',
    body=message,
    properties=pika.BasicProperties(
        delivery_mode=2,  # make message persistent
    ))
print(" [x] Sent %r" % message)
connection.close()
