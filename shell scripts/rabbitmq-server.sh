sudo apt-get -y update
sudo apt-get install python3
sudo apt-get install python3-pip
pip3 install pika
wget -O- https://packages.erlang-solutions.com/ubuntu/erlang_solutions.asc | sudo apt-key add -
echo "deb https://packages.erlang-solutions.com/ubuntu bionic contrib" | sudo tee /etc/apt/sources.list.d/rabbitmq.list
sudo apt update
sudo apt -y install erlang
wget -O- https://dl.bintray.com/rabbitmq/Keys/rabbitmq-release-signing-key.asc | sudo apt-key add -
wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | sudo apt-key add -
echo "deb https://dl.bintray.com/rabbitmq/debian $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/rabbitmq.list
sudo apt update
sudo apt -y install rabbitmq-server
sudo systemctl enable rabbitmq-server
sudo rabbitmq-plugins enable rabbitmq_management
sudo ufw allow proto tcp from any to any port 5672,15672
rabbitmqctl add_user admin admin
rabbitmqctl set_user_tags admin administrator
