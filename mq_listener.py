import pika
import MySQLdb

mqServer = '192.168.1.48'
sqlServer = 'localhost'
sqlPort = '3306'
sqlUser = 'root'
sqlPass = 'NOTweak$_@123!'
sqlDb = 'example3'
db = MySQLdb.connect(sqlServer, sqlUser, sqlPass, sqlDb)
cur = db.cursor()

def respond(rtn, identifier):
	conn = pika.BlockingConnection(pika.ConnectionParameters(mqServer))
	ch = conn.channel()
	ch.basic_publish(exchange='',routing_key=identifier, body=rtn)

def callback(ch, method, properties, body):
	identity = body.split()[0]
	q = body.split()[1]
	respond(cur.execute(q), identity)


if __name__ == "__main__":
	conn = pika.BlockingConnection(pika.ConnectionParameters(mqServer))
	ch = conn.channel()
	ch.basic_consume(callback, queue='sqlReq', no_ack=True)
	ch.start_consuming()

		

	
