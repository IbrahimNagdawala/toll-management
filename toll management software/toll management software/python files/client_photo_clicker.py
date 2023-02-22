
import socket   # importing socket module
import sys      # importing sys module

target_ip="192.168.1.3"
target_port=5234

def click_photo(image):
	client=socket.socket(socket.AF_INET,socket.SOCK_STREAM)

	# connecting with the server

	client.connect((target_ip,target_port))


	# sending and recieving data

	#while True:

	mess= bytes(image, 'utf-8')
	client.sendall(mess)
	'''data=client.recv(1024).decode("utf-8")
	if  not data:
		break
	elif data=="bye":
		break
	elif data=="":
		break
	else:
		print ("\n message recieved from server \n")
		print ("message :  %s" % data)'''


	client.close()





