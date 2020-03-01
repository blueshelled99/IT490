import pyodbc
import pika
import time

credentials=pika.PlainCredentials('rabbitmq-test', 'test')
parameters=pika.ConnectionParameters('192.168.1.48', 
				5672,   
				'/',
                                credentials)
									
connection = pika.BlockingConnection(parameters)
channel = connection.channel()
channel.queue_declare(queue='user-test')


databaseconnection = {
 'server' : 'localhost',
 'database' : 'back_end_database',
 'username' : 'backendtest',
 'password' : 'NOTweak$_@123!',
 'connection' : 'no'
 }

connectionstring = 'DRIVER={{ODBC Driver 17 for SQL Server}};SERVER={server};PORT=3306; DATABASE={database};UID={username};PWD={password};Trusted_connection={connection};)'.format(**databaseconnection)

connection = pyodbc.connect(connectionstring)

cursor = connection.cursor()



def login(emailaddress, password):
	authquery="SELECT password FROM DB_auth WHERE email='" + emailaddress + "';"
	with cursor.execute(authquery):
		row=cursor.fetchone()
	try: 
		passwd=row[0]
		if password==passwd:				
			return "true"
		else:					
			return "false"
		
	except TypeError:		
		return "false"
		

def registeruser(emailaddress, password, firstname, lastname):
	
	addcredentialsquery="SELECT COUNT(*) FROM DB_add;"
	with cursor.execute(addcredentialsquery):
		row=cursor.fetchone()
	count=row[0]+1
	
	
	registerquery="INSERT INTO DB_add VALUES (" + str(count) + ",'" + emailaddress +"','"  +password + "','" + firstname + "','" + lastname + "';"
	try:
		cursor.execute(registerquery)
		connection.commit()
		return "true"
	except pyodbc.IntegrityError:					
		return "false"
