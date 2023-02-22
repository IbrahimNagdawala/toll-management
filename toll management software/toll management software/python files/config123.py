import mysql.connector

def connect_to_database():
    

    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="tolltax")


    cursor = mydb.cursor()
    return mydb,cursor
