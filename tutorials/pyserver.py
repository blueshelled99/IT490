#!/usr/bin/env python

#this is for testing login.php 

import pika

credentials = pika.PlainCredentials('rabbitmq-test', 'test')
parameters = pika.ConnectionParameters('192.168.1.50',
				       5672,
				       '/',
				       credentials)

connection = pika.BlockingConnection(parameters)

channel = connection.channel()

channel.queue_declare(queue='login-queue', durable = True)


def auth(n):
    return "true"


def on_request(ch, method, props, body):
    n = body

    print(" [.] auth(%s)" % n)
    response = auth(n)

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \
                                                         props.correlation_id),
                     body=str(response))
    ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='login-queue', on_message_callback=on_request)

print(" [x] Awaiting RPC requests")
channel.start_consuming()
