import os
from multiprocessing import Process
import time

def f():
	while True:
		os.system('python3 testdb2.py')
		time.sleep(2)

def d():
	while True:
		os.system('python3 testdb2_register.py')
		time.sleep(2)

def c():
	while True:
		os.system('python3 api_store2.py')
		time.sleep(2)

if __name__ == '__main__':
	p1 = Process(target=f)
	p2 = Process(target=d)
	p3 = Process(target=c)
	p1.start()
	p2.start()
	p3.start()
	p1.join()
	p2.join()
	p3.join()
	