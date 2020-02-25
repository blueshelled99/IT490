
Learn more or give us feedback
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


#HOW TO INSTALL AND SET UP MYSQL
sudo apt-get install mysql-server
sudo netstat -tap | grep mysql
sudo systemctl restart mysql.service
mysql -u root -p NOTweak$_@123!
CREATE DATABASE example3;
INSERT INTO numbers VALUES (1, 'One!');
INSERT INTO numbers VALUES (2, 'Two!');
INSERT INTO numbers VALUES (3, 'Three!');

#VIEW YOUR TABLE
SELECT * FROM numbers;

#CREATE USERS
CREATE USER 'dmztest' IDENTIFIED BY 'YesStrong!321@_$';
CREATE USER 'rabbitmqtest' IDENTIFIED BY 'Rabbitmq123!';


