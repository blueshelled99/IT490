# IT490: OpenFecData.com: Charts from Queries

- [Abstract] (https://gitbub.com/blueshelled99/IT490#Abstract)
- [General Setup] (https://gitbub.com/blueshelled99/IT490#General-Setup)
- [Initial Setup: Shell Scripts] (https://gitbub.com/blueshelled99/IT490#Initial-Setup:-Shell-Scripts)
- [RabbitMQ] (https://gitbub.com/blueshelled99/IT490#RabbitMQ)
- [Front End] (https://gitbub.com/blueshelled99/IT490#Front-End)
- [Back End] (https://gitbub.com/blueshelled99/IT490#Back-End)

## Abstract

OpenFecData.com will be used as a way to visualize json data provided by the OpenFec API and hopefully create charts using the Google Charts API. Users will create accounts and login to use our service. 

## General Setup

At the beginning of the semester we worked on virtual machines and we had a dedicated virtual machine to act as a single server. In total we had a RabbitMQ server, Front-End Server, Back-End/Database Server. Each server can be started up by making a new Ubuntu VM and running the respective shell scripts. 

## Initial Setup: Shell Scripts

Make sure to read/run the shell scripts to have all the dependencies needed to run the files.
Read the comments as sometimes you may need to type out the command as well or change a line of code to suit your environment.

for rabbitmq machine just run the rabbitmq-server.sh shell script
for front-end machine run the php.sh and front-end.sh(if this shell script then use the other one that says to change line 12) shell scripts
for the back-end machine just run the back-end.sh shell script

## RabbitMQ

Make sure to add a new user as to not use the default credentials for the rabbitMQ server. Also remember to set up exchanges in the future for rabbitMQ.

## Front End

A lot of the code used for RabbitMQ to interact with the Front-end was taken from the php tutorials from the RabbitMQ documentation. 

## Back End

Similarly to the Front End, a lot of the code that we used to read from the queues and write back can be found in the python tutorials from the RabbitMQ documentation. 
