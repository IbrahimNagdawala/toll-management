# !/usr/bin/python

import socket   # importing socket module
import sys      # importing sys module
import os
import wget
# shell_exec("/usr/bin/wget http://admin:aidpl12345@192.168.1.65/ISAPI/Streaming/channels/1/picture -O /var/www/html/TOLLTAX/vehicle_images/$booth_sno.jpeg")


def banner():
	print("\n \n")
	print("######################################")
	print("######################################")
	print("created  by : Ibrahim Nagdawala ")
	print("######################################")
	print("######################################")
	print("\n \n")

banner()








while True:
		
	host ="192.168.1.3"
	port = 5234	
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	s.bind((host, port))
	s.listen()
	conn, addr = s.accept()
	data = conn.recv(5000).decode("utf-8")
	camera_url=""
	if data[1]=="1":
			camera_url = "http://admin:aidpl12345@192.168.1.55/ISAPI/Streaming/channels/1/picture"
	elif data[1]=="2":
			camera_url = "http://admin:aidpl12345@192.168.1.56/ISAPI/Streaming/channels/1/picture"
	elif data[1]=="3":
    		camera_url = "http://admin:aidpl12345@192.168.1.57/ISAPI/Streaming/channels/1/picture" 
	elif data[1]=="4":
    		camera_url = "http://admin:aidpl12345@192.168.1.58/ISAPI/Streaming/channels/1/picture"
	try:
		wget.download(camera_url, f"c:/xampp/htdocs/dashboard/vehicle_images/{data}.jpg") 
	except:
		pass   
	conn.close()


sys.exit()

