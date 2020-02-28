sudo apt-get update
sudo apt-get install python3
sudo apt update
sudo apt install python3-pip
pip3 --version
sudo apt-get update 
sudo apt-get install python-pika
sudo apt install vim
sudo apt-get install mysql-server
sudo netstat -tap | grep mysql
sed -i "s/127.0.0.1/0.0.0.0/g" /etc/mysql/mysql.conf.d/mysqld.cnf
sudo systemctl restart mysql.service
sudo mysql -u root password NOTweak$_@123!
sudo mysql -u root
UPDATE user set user = 'backendtest' WHERE user = 'root';
GRANT ALL PRIVILEGES ON *.* TO 'backendtest';
FLUSH PRIVILEGES;
exit
sudo mysql -u backendtest password NOTweak$_@123!
sudo mysql -u backendtest -p NOTweak$_@123!
CREATE USER 'dmztest' IDENTIFIED BY 'YesStrong!321@_$';
CREATE USER 'rabbitmqtest' IDENTIFIED BY 'Rabbitmq123!';
GRANT ALL PRIVILEGES ON *.* TO 'dmztest';
GRANT ALL PRIVILEGES ON *.* TO 'rabbitmqtest';
FLUSH PRIVILEGES;
CREATE DATABASE back_end_database;
USE back_end_database;
CREATE TABLE DB_members(username VARCHAR(20), password VARCHAR(20));
CREATE TABLE DB_add(username VARCHAR(20), password VARCHAR(20), firstname VARCHAR(20), lastname VARCHAR(20));
