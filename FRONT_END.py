#THIS IS A BOOTSTRAP SCRIPT UPON RUNNING IT WILL PRE CONFIGURE YOUR MACHINE TO FRONT END SPECIFICATIONS

#HOW TO INSTALL PYTHON

sudo apt-get update
sudo apt-get install python3

#HOW TO INSTALL PIP
sudo apt update
sudo apt install python3-pip

#verify
pip3 --version


#HOW TO INSTALL PIKA
sudo apt-get update 
sudo apt-get install python-pika

#HOW TO INSTAll VI EDITOR
sudo apt-install vim

#HOW TO INSTALL FLASK AND PRINT HELLO WORLD ON PORT 5000
pip install flask

#HOW TO INSTALL APACHE SERVER
sudo apt-get update
sudo apt-get install apache2

#VERIFY YOU HAVE INSTALLED APACHE SERVER
http://local.server.ip

#HELLO WORLD CODE
from flask import Flask
#CREATE APP OBJECT
app = Flask(__name__)
#ROUTE HELLO TO DISPLAY HELLO WORLD
@app.route('/')
def hello():
    return "Hello World!"
if (__name__ = "__main__"):
    app.run(host='0.0.0.0', port=5000)


