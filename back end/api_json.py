#api key for openfec
#r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN

#https://api.open.fec.gov/v1/candidates/search/?sort_nulls_last=false&sort=name&api_key=DEMO_KEY&sort_null_only=false&page=1&sort_hide_null=false&per_page=20&name=Trump

import urllib.request, json, pika, mysql.connector, mysql.connector.errors

def store(url):
	response = urllib.request.urlopen(url)

	data = json.loads(response.read())
	
	value_list = list()
	for value in data.values():
		value_list.append(value)
	x = value_list[1][0].values()
	y = list(x)
	z = y[1]
	print (z)
	name = str(z)
	

	cnx = mysql.connector.connect(user='backendtest', password='NOTweak$_@123!', host='localhost', port='3306', 	
		database='back_end_database')
	cursor = cnx.cursor(buffered = True)

	historyquery = ("INSERT INTO search_history (id, history) VALUES (id, '" + name + "');")

	cursor.execute(historyquery)
	if cursor.rowcount:
		print("true")
			
	else:
		print("false")

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

channel.queue_declare(queue='url-queue', durable=True)

def send_url(ch, method, props, body):
	response = store(body)
	ch.basic_publish(exchange='',
			routing_key='',
			properties=pika.BasicProperties(correlation_id = \
							props.correlation_id),
			body=str(response))
	ch.basic_ack(delivery_tag=method.delivery_tag)
channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='url-queue', on_message_callback=send_url)
print(" [x] Awaiting for front end to search")
channel.start_consuming()



