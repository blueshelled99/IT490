sudo apt-get update
sudo apt-get install python3
sudo apt update
sudo apt-get install python3-pip
pip3 --version
sudo apt-get update 
pip3 install pika
sudo apt install vim
sudo apt-get install mysql-server
sudo netstat -tap | grep mysql
sudo sed -i "s/127.0.0.1/0.0.0.0/g" /etc/mysql/mysql.conf.d/mysqld.cnf
sudo systemctl restart mysql.service

###For SQL Setup###
#sudo mysql
#CREATE user 'backendtest' IDENTIFIED BY 'NOTweak$_@123!';
#GRANT ALL PRIVILEGES ON *.* TO 'backendtest';
#FLUSH PRIVILEGES;
#exit
#mysql -u backendtest 
#password: NOTweak$_@123!
#CREATE USER 'dmztest' IDENTIFIED BY 'YesStrong!321@_$';
#CREATE USER 'rabbitmqtest' IDENTIFIED BY 'Rabbitmq123!';
#GRANT ALL PRIVILEGES ON *.* TO 'dmztest';
#GRANT ALL PRIVILEGES ON *.* TO 'rabbitmqtest';
#FLUSH PRIVILEGES;
#CREATE DATABASE back_end_database;
#USE back_end_database;
#CREATE TABLE members(id INT AUTO_INCREMENT, firstname VARCHAR(20), lastname VARCHAR(20), email VARCHAR(20), password VARCHAR(20), PRIMARY KEY(id));
#CREATE TABLE search_history(id INT AUTO_INCREMENT, history VARCHAR(6000));
#GRANT ALL PRIVILEGES ON back_end_database.* to backendtest;