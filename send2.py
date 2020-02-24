#!/usr/bin/env python3

import pika

credentials = pika.PlainCredentials('rabbitmq-test', 'test')
#will need to change IP
parameters = pika.ConnectionParameters('192.168.1.48', 
				5672,   
				'/',
                                credentials)
									
connection = pika.BlockingConnection(parameters)
channel = connection.channel()
channel.queue_declare(queue='Rishi')

channel.basic_publish(exchange='', routing_key='hello', body='Hello World! Sent from Rishi')

print(" [x] Sent 'Hello World from Rishi!'")
connection.close()
