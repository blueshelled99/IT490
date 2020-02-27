#!/usr/bin/env python
import pika
import time

#change credentials and ip address if not using server
credentials = pika.PlainCredentials('user', 'pwd') #change credentials and ip address here
parameters = pika.ConnectionParameters('x.x.x.x',
                                    5672,
                                    '/',
                                    credentials)

connection = pika.BlockingConnection(parameters)

channel = connection.channel()

channel.queue_declare(queue='db-access', durable=True)
print(' [*] Waiting for messages. To exit press CTRL+C')


def callback(ch, method, properties, body):
    print(" [x] Received %r" % body)
    time.sleep(body.count(b'.'))
    print(" [x] Done")
    ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='db-access', on_message_callback=callback)

channel.start_consuming()
