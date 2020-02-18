#!/usr/bin/env python3

import pika
#credentials obfuscated
credentials = pika.PlainCredentials('rabbitmq-server', 'ObfuscatedPassword')
#ip address obfuscated. change 'xxx.xxx.xxx.xxx' to ip of the rabbitmq-server
parameters = pika.ConnectionParameters('xxx.xxx.xxx.xxx',
                                    5672,
                                    '/',
                                    credentials)
connection = pika.BlockingConnection(parameters)
channel = connection.channel()
channel.queue_declare(queue='hello')

channel.basic_publish(exchange='', routing_key='hello', body='Hello World!')

print(" [x] Sent 'Hello World!'")

connection.close()
