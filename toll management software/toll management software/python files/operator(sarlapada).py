from tkinter import *
from tkinter import messagebox
from tkinter import ttk
import datetime
import hashlib
from PIL import ImageTk,Image
from config123 import connect_to_database
from datetime import  timedelta
from barcode import Code128
from barcode.writer import ImageWriter
from reportlab.pdfgen.canvas import Canvas
from reportlab.lib.pagesizes import A4
from reportlab.platypus import SimpleDocTemplate, Paragraph
from reportlab.lib.styles import getSampleStyleSheet
from reportlab.lib.units import cm
import win32api
import os
from threading import Thread
import socket




mydb,cursor=connect_to_database()

root=Tk()
root.state("zoomed")
root.title("Login Form")
#root.attributes("-fullscreen",True)

root.iconbitmap("images/kss.ico")

global background
background=ImageTk.PhotoImage(Image.open("images/login_page1.jpg").resize((root.winfo_screenwidth(),root.winfo_screenheight())))
background_label=Label(root,image=background)
background_label.place(relx=0,rely=0,relheight=1,relwidth=1)

header=Label(root,text="TollTax Management System",font=("Helvetica",20),bg="#2F2FA2",fg="white",anchor="w")
header.pack(fill=X)

date_today = str(datetime.datetime.now().strftime("%Y-%m-%d"))

time_cur=str(datetime.datetime.now().strftime("%I:%M:%S%p")).lower()

receipt_text = Text(root, height=10, width=40)

#getting ip of system
import socket
def get_ip():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        # doesn't even have to be reachable
        s.connect(('192.168.1.3', 1))
        IP = s.getsockname()[0]
    except:
        IP = '127.0.0.1'
    finally:
        s.close()
    return IP

ipaddr=get_ip()

def delete_file(receipt_no):
    os.remove(f"{receipt_no}.pdf")
    os.remove(f"{receipt_no}.png")

def print_receipt(matterp,date,v_type,j_type,direction,receipt_no,barcode_req=True):
    
    #month:no of days
    days_list=[31,28,31,30,31,30,31,31,30,31,30,31]
    no_of_days=0

    date=date.replace("-","")
    month=int(date[4:6])
    for i in range(month-1):
        if i==1:
            if int(date[:4])%4==0:
                no_of_days+=29
            else:
                no_of_days+=28
        else:            
            
            no_of_days+=days_list[i]
        
    
    no_of_days+=int(date[6:])
    if no_of_days<100:
        no_of_days="0"+str(no_of_days)



    v_type=v_type.lower()[0]
    
    j_type=j_type[0:2]
    direction=direction.lower()[0]

    time=datetime.datetime.now().strftime("%H%M")
    trans_no=str(receipt_no[1])+str(receipt_no[3:])

    code = str(no_of_days)+str(time)+str(v_type)+str(j_type)+str(direction)+trans_no
    bar=Code128(code,writer=ImageWriter())
    bar.save(receipt_no)

    #c = Canvas(f"{receipt_no}.pdf")
    #textobject = c.beginText(7*cm, 29.7 * cm - 1 * cm)
    #for line in matterp.splitlines(False):
    #    textobject.textLine(line.rstrip())
    #c.drawText(textobject)
    #if barcode_req:

    #    c.drawImage(f"{receipt_no}.png",7*cm,29.7*cm-1*cm-7.5*cm,width=200,height=50)
    c = Canvas(f"{receipt_no}.pdf")

    count_lines = 0
    c.setFont('Helvetica-Bold', 8)
    textobject_head = c.beginText(8*cm, 29.7 * cm - 1 * cm)
    for line in matterp.splitlines(False):
        if count_lines == 5:
            break
        textobject_head.textLine(line.rstrip())
        count_lines += 1

    c.drawText(textobject_head)

    c.setFont('Helvetica', 8)

    textobject_body = c.beginText(8*cm, 29.7*cm - 1*cm-1.7*cm)
    count_lines = 0
    for line in matterp.splitlines(False):
        if count_lines >= 5:

            textobject_body.textLine(line.rstrip())
        count_lines += 1
    c.drawText(textobject_body)

    if barcode_req:

        c.drawImage(f"{receipt_no}.png", 6.8*cm, 29.7 *cm-1*cm-6.7*cm, width=150, height=40)
    c.save()

    win32api.ShellExecute(0, "print", f"{receipt_no}.pdf", None, ".", 0)

    

    root.after(15000,lambda:delete_file(receipt_no))


def session_timeout():
    
    global timer
    
    cur_time = datetime.datetime.now().strftime("%H:%M:%S").split(":")
    t1 = datetime.time(23, 59, 59)
    t2 = datetime.time(7, 59, 59)
    t3 = datetime.time(15, 59, 59)

    t1_list=[int(str(t1).split(":")[0]),int(str(t1).split(":")[1]),int(str(t1).split(":")[2])]
    t2_list=[int(str(t2).split(":")[0]),int(str(t2).split(":")[1]),int(str(t2).split(":")[2])]
    t3_list=[int(str(t3).split(":")[0]),int(str(t3).split(":")[1]),int(str(t3).split(":")[2])]
    x_list = [int(cur_time[0]), int(cur_time[1]), int(cur_time[2])]
    
    #cur time
    x = datetime.time(int(cur_time[0]), int(cur_time[1]), int(cur_time[2]))

    
    dist=[1000,1000,1000]
    diff=100000
    if x<t2 :
        dist = []
        j = 0
        for i in t2_list:
            dist.append(i-x_list[j])
            j += 1
        diff=timedelta(days=0,seconds=dist[2],minutes=dist[1],hours=dist[0]).total_seconds()
    elif x>t2 and x<t3:
        dist = []
        j = 0
        for i in t3_list:
            dist.append(i-x_list[j])
            j += 1
        diff = timedelta(
            days=0, seconds=dist[2], minutes=dist[1], hours=dist[0]).total_seconds()
    elif x>t3 and x<t1:
        dist = []
        j = 0
        for i in t1_list:
            dist.append(i-x_list[j])
            j += 1
        diff = timedelta(
            days=0, seconds=dist[2], minutes=dist[1], hours=dist[0]).total_seconds()
    
    if int(diff)<=30 and int(diff)>1:
        #session_timer_label.config(text=f"Your Session will end in {diff} seconds",fg="red")
        timer.set(f"Your Session will end in {diff} seconds")
    if int(diff)==1:
        logout()
        
    root.after(1000,session_timeout)


def logout():
    widgets=root.winfo_children()
    for w in widgets:
        w.destroy()

    root.title("Login Form")
    global background
    background=ImageTk.PhotoImage(Image.open("images/login_page1.jpg").resize((root.winfo_screenwidth(),root.winfo_screenheight())))
    background_label=Label(root,image=background)
    background_label.place(relx=0,rely=0,relheight=1,relwidth=1)

    header=Label(root,text="TollTax Management System",font=("Helvetica",20),bg="#2F2FA2",fg="white",anchor="w")
    header.pack(fill=X)
    login_screen()

def edit_this_entry(receipt_no):
    
    
    edit_entry_button.grid_forget()
    

    receipt_number=receipt_no

    edit_entry_wd = Toplevel()
    edit_entry_wd.title("Edit This Entry")
    edit_entry_wd.state("zoomed")

    

    width, height = edit_entry_wd.winfo_screenwidth(),edit_entry_wd.winfo_screenheight()
    edit_entry_wd.geometry(f'{width}x{height}+-8+0')

    edit_entry_wd.config(bg="blue")

    print_button=Button(edit_entry_wd,text="Print Receipt")
    receipt_new_text = Text(edit_entry_wd, height=15, width=45)
    receipt_new_text.config(state=NORMAL)
    receipt_new_text.delete(1.0, END)
    receipt_new_text.pack_forget()

    monthly_or_sp_exemp_stop_message_new = Label(edit_entry_wd, text="Montly pass or Special Exemption Not Registered or is Expired  for this Vehicle",font=("Arial",15))
    
    def display_na_input_new(e):
        if vehicle_class_new.get()=="NA":
            toll_for_na_new_label.grid(row=4,column=0,pady=10)
            toll_for_na_new.grid(row=4,column=1,pady=10,padx=10)

        else:
            toll_for_na_new_label.grid_forget()
            toll_for_na_new.grid_forget()

    def exemption_new_type(e):
        if journey_type_new.get()=="Exemption":
           
            exemption_type_new_label.config(text="Exemption Type ")
            exemption_type_new_label.grid(row=3,column=0,pady=10)
        
            exemption_type_new_options.grid(row=3,column=1,pady=10) 
        else:
       

            exemption_type_new_label.grid_forget()
            exemption_type_new_options.grid_forget()
    def generate_receipt_new():
        
        toll_for_na_new_label.grid_forget()
        toll_for_na_new.grid_forget()
        exemption_type_new_label.grid_forget()
        exemption_type_new_options.grid_forget()
        monthly_or_sp_exemp_stop_message_new.pack_forget()

        car1.config(bg="yellowgreen")
        bus1.config(bg="black")
        truck1.config(bg="black")
        lcv1.config(bg="black")
        multi1.config(bg="black")
        na1.config(bg="black")

        if vehicle_number_new.get().replace(" ","")=="":
            return
        
        journey_type_var=journey_type_new.get()
        sql1 = f"select TOLL_AMOUNT from vehicle_type where VEHICLE_TYPE='{vehicle_class_new.get()}'"
        cursor.execute(sql1)
        toll = cursor.fetchall()
        toll = toll[0][0]
        

        if journey_type_new.get()=="Exemption":
            if exemption_type_new_options.get() == "Ambulance":
                journey_type_var = journey_type_new.get() + "[Amb]"
            elif exemption_type_new_options.get() == "Government":
                journey_type_var = journey_type_new.get() + "[Gov]"

            elif exemption_type_new_options.get() == "Police":
                journey_type_var = journey_type_new.get() + "[Police]"
            elif exemption_type_new_options.get() == "Press":
                journey_type_var = journey_type_new.get() + "[Press]"
            elif exemption_type_new_options.get() == "Defence":
                journey_type_var = journey_type_new.get() + "[Defence]"
            elif exemption_type_new_options.get() == "VIP":
                journey_type_var = journey_type_new.get() + "[VIP]"
            elif exemption_type_new_options.get() == "Other":
                journey_type_var = journey_type_new.get() + "[Other]"
            toll=0
        else:
            journey_type_var=journey_type_new.get()
        
        mon_stop = 0
        if journey_type_var=="Monthly Pass":
            sqlcheck=f"Select * from monthly_pass where VEHICLE_NUMBER='{vehicle_number_new.get()}' ORDER BY PASS_NO DESC LIMIT 1"
            cursor.execute(sqlcheck)
            mon_res=cursor.fetchall()
            
            if mon_res==[]:
                mon_stop=1
            else :
                if mon_res[0][9] < date_today:
                    mon_stop=1
                else : 
                    mon_stop=0
                    toll=0

        sp_stop = 0
        if journey_type_new.get()=="Special Exemption":
            sqlcheck = f"Select * from sp_exemption where VEHICLE_NUMBER='{vehicle_number_new.get()}'"
            cursor.execute(sqlcheck)
            sp_res=cursor.fetchall()
            
            if sp_res==[]:
                sp_stop=1
            else:
                sp_stop=0
                toll=0

        if vehicle_class_new.get()=="NA":
            toll=toll_for_na_new.get()


        if sp_stop==0 and mon_stop==0:
            sqlupdate = f"Update  vehicle_details set TIME='{time_cur}',V_TYPE='{vehicle_class_new.get()}',V_NUMBER='{vehicle_number_new.get()}',JOURNEY_TYPE='{journey_type_var}',TOLL='{toll}',CORRECT_TOLL='{toll}',OLD_V_TYPE='{vehicle_class_new.get()}' where RECEIPT_NO='{receipt_number}' "
            #excute sql command
            cursor.execute(sqlupdate)

            #commit changes
            mydb.commit()
            sqlretr=f"select DIRECTION,BOOTH_NO from vehicle_details where  RECEIPT_NO='{receipt_number}'"
            cursor.execute(sqlretr)
            res=cursor.fetchall()
            direct=res[0][0]
            booth_=res[0][1]
            valid_till = datetime.datetime.now() + timedelta(hours=12)
            valid_till = valid_till.strftime("%Y-%m-%d %I:%M:%S%p")
            valid_till = valid_till.lower()
            matter = f"""       Agroh Ratlam Tollway Pvt Ltd
        MDR ( 00.00 - 43.85)km
Sarlapada Toll Plaza Ratlam (at 26.50)
    Govt Notification No S.O. 1610 (E)
-----------------Toll Receipt-----------------
Receipt No        :  {receipt_number}
Transaction Date  :  {date_today}   {time_cur}
Booth             :  {booth_}
Operator ID       :  {id}
Vehicle Type      :  {vehicle_class_new.get()}
Vehicle Number    :  {vehicle_number_new.get()}
Journey Type      :  {journey_type_var}
Journey Direction :  {direct}
Trip Amt               :  {toll}
----------------Happy Journey---------------
Valid Till : {valid_till} """
            v_no_new = vehicle_number_new.get()
            matterp = f"""       Agroh Ratlam Tollway Pvt Ltd
        MDR ( 00.00 - 43.85)km
Sarlapada Toll Plaza Ratlam (at 26.50)
    Govt Notification No S.O. 1610 (E)
-----------------Toll Receipt-----------------
Receipt No        :  {receipt_number}
Trans Date : {date_today}-{time_cur}
Booth             :  {booth_}
Operator ID       :  {id}
Vehicle Type      :  {vehicle_class_new.get()}
Vehicle Number    :  {v_no_new}
Journey Type      :  {journey_type_var}
Journey Direction :  {direct}
Trip Amt               :  {toll}
----------------------Happy Journey-------------------
Valid Till : {valid_till} """

            print_button.config(command=lambda:print_receipt(matterp,date_today,vehicle_class_new.get(),v_no_new,journey_type_var,direct,f"{receipt_number}e"))
            
            print_button.pack(pady=10)
            
            receipt_new_text.insert(1.0, matter)
            receipt_new_text.config(state=DISABLED)
            receipt_new_text.pack(pady=10)
            vehicle_number_new.delete(0, END)
            submit_new_button.config(state=DISABLED)
        else:

            monthly_or_sp_exemp_stop_message_new.pack(pady=20)
        vehicle_class_new.set("CAR")
        journey_type_new.set("Single Journey")
    def vehicle_selection(e):
        if e.keysym == "F1":
            vehicle_class_new.set("CAR")
            car1.config(bg="yellowgreen")
            bus1.config(bg="black")
            truck1.config(bg="black")
            lcv1.config(bg="black")
            multi1.config(bg="black")
            na1.config(bg="black")

            toll_for_na_new_label.grid_forget()
            toll_for_na_new.grid_forget()

        elif e.keysym == "F2":
            vehicle_class_new.set("BUS")
            car1.config(bg="black")
            bus1.config(bg="yellowgreen")
            truck1.config(bg="black")
            lcv1.config(bg="black")
            multi1.config(bg="black")
            na1.config(bg="black")

            toll_for_na_new_label.grid_forget()
            toll_for_na_new.grid_forget()


        elif e.keysym == "F3":
            vehicle_class_new.set("TRUCK")
            car1.config(bg="black")
            bus1.config(bg="black")
            truck1.config(bg="yellowgreen")
            lcv1.config(bg="black")
            multi1.config(bg="black")
            na1.config(bg="black")

            toll_for_na_new_label.grid_forget()
            toll_for_na_new.grid_forget()

        elif e.keysym == "F4":
            vehicle_class_new.set("LCV")
            car1.config(bg="black")
            bus1.config(bg="black")
            truck1.config(bg="black")
            lcv1.config(bg="yellowgreen")
            multi1.config(bg="black")
            na1.config(bg="black")

            toll_for_na_new_label.grid_forget()
            toll_for_na_new.grid_forget()

        elif e.keysym == "F5":
            vehicle_class_new.set("M-EXLE")
            car1.config(bg="black")
            bus1.config(bg="black")
            truck1.config(bg="black")
            lcv1.config(bg="black")
            multi1.config(bg="yellowgreen")
            na1.config(bg="black")

            toll_for_na_new_label.grid_forget()
            toll_for_na_new.grid_forget()

        elif e.keysym == "F6":
            vehicle_class_new.set("NA")
            car1.config(bg="black")
            bus1.config(bg="black")
            truck1.config(bg="black")
            lcv1.config(bg="black")
            multi1.config(bg="black")
            na1.config(bg="yellowgreen")

            toll_for_na_new_label.grid(row=4, column=0, pady=10)
            toll_for_na_new.grid(row=4, column=1, pady=10, padx=10)





    image_frame=Frame(edit_entry_wd,bg="#EF005F")
    image_frame.pack(fill=X)

    #vehicle images
    global car_img1
    global car1
    car_img1 = ImageTk.PhotoImage(Image.open(
        "images/car.jpg").resize((100, 100)))
    car1 = Label(image_frame, image=car_img1, bg="yellowgreen", bd=7)
    car1.grid(row=0, column=0, padx=10)

    global bus_img1
    global bus1
    bus_img1 = ImageTk.PhotoImage(Image.open(
        "images/bus.jpg").resize((100, 100)))
    bus1 = Label(image_frame, image=bus_img1, bg="black", bd=7)
    bus1.grid(row=0, column=1, padx=10)

    global truck_img1
    global truck1
    truck_img1 = ImageTk.PhotoImage(Image.open(
        "images/truck.jpg").resize((100, 100)))
    truck1 = Label(image_frame, image=truck_img1, bg="black", bd=7)
    truck1.grid(row=0, column=2, padx=10)

    global lcv_img1
    global lcv1
    lcv_img1 = ImageTk.PhotoImage(Image.open(
        "images/lcv.jpg").resize((100, 100)))
    lcv1 = Label(image_frame, image=lcv_img1, bg="black", bd=7)
    lcv1.grid(row=0, column=3, padx=10)

    global multi_img1
    global multi1
    multi_img1 = ImageTk.PhotoImage(Image.open(
        "images/multi.jpg").resize((100, 100)))
    multi1 = Label(image_frame, image=multi_img1, bg="black", bd=7)
    multi1.grid(row=0, column=4, padx=10)

    global na_img1
    global na1
    na_img1 = ImageTk.PhotoImage(Image.open("images/na.jpg").resize((100, 100)))
    na1 = Label(image_frame, image=na_img1, bd=7, bg="black")
    na1.grid(row=0, column=5, padx=10)

    car_label = Label(image_frame, text="Car(F1)", font=("Arial", 15))
    car_label.grid(row=1, column=0, padx=10, pady=5)

    bus_label = Label(image_frame, text="Bus(F2)", font=("Arial", 15))
    bus_label.grid(row=1, column=1, padx=10, pady=5)

    truck_label = Label(image_frame, text="Truck(F3)", font=("Arial", 15))
    truck_label.grid(row=1, column=2, padx=10, pady=5)

    lcv_label = Label(image_frame, text="LCV(F4)", font=("Arial", 15))
    lcv_label.grid(row=1, column=3, padx=10, pady=5)

    multi_label = Label(image_frame, text="Multi Axel(F5)", font=("Arial", 15))
    multi_label.grid(row=1, column=4, padx=10, pady=5)

    na_label = Label(image_frame, text="NA(F6)", font=("Arial", 15))
    na_label.grid(row=1, column=5, padx=10, pady=5)


    edit_entry_wd.bind("<Key>",vehicle_selection)



    edit_entry_frame=Frame(edit_entry_wd)
    edit_entry_frame.pack(pady=10)
    vehicle_class_new_label=Label(edit_entry_frame,text="New Vehicle Type",font=("Helevetica",15,"bold"))
    vehicle_class_new_label.grid(row=0,column=0,pady=10)

    vehicle_class_new=ttk.Combobox(edit_entry_frame,values=(v_class_list),font=("Arial",15),state="readonly")
    vehicle_class_new.current(0)
    vehicle_class_new.grid(row=0,column=1,padx=10,pady=10)

    #binding vehicle_class_new for to display na input
    vehicle_class_new.bind("<<ComboboxSelected>>",display_na_input_new)

    vehicle_number_new_label = Label(edit_entry_frame, text="New Vehicle Number  ", font=("Arial", 15, "bold"))
    vehicle_number_new_label.grid(row=1, column=0,pady=10)

    vehicle_number_new = Entry(edit_entry_frame, font=("Arial", 15, "bold"),bd=4)
    vehicle_number_new.grid(row=1, column=1, pady=10, padx=10)
    vehicle_number_new.focus()

    #journey type dropdown

    journey_type_new_label=Label(edit_entry_frame,text="Journey Type  ",font=("Arial",15,"bold"))
    journey_type_new_label.grid(row=2, column=0)

    global journey_type_new
    journey_type_new=ttk.Combobox(edit_entry_frame,values=["Single Journey","Return Journey","Monthly Pass","Exemption","Special Exemption"],font=("Arial",15),state="readonly")


    journey_type_new.current(0)
    journey_type_new.grid(row=2, column=1, padx=10)

    journey_type_new.bind("<<ComboboxSelected>>",exemption_new_type)
    
    #exemption type label and dropdown
    global exemption_type_new_label
    global exemption_type_new_options
    exemption_type_new_label=Label(edit_entry_frame,text="",font=("Arial",15,"bold"))

    exemption_type_new_options=ttk.Combobox(edit_entry_frame,values=["Ambulance","Police","Defence","Press","Government","VIP","Other"],font=("Arial",15),state="readonly")
    exemption_type_new_options.current(0)

    global toll_for_na_new_label
    global toll_for_na_new
    toll_for_na_new_label=Label(edit_entry_frame,text="TOLL ",font=("Arial",15,"bold"))
    toll_for_na_new=Entry(edit_entry_frame,font=("Arial",15,"bold"),bd=3)


    #submit button

    submit_new_button=Button(edit_entry_frame,text="Submit",font=("Helvetica",15),command=generate_receipt_new)
    submit_new_button.grid(row=5,column=1,pady=10)


def recent_entries():
    recent_entry_wd = Toplevel()
    recent_entry_wd.title("Recent Entries")

    

    width, height = recent_entry_wd.winfo_screenwidth(),recent_entry_wd.winfo_screenheight()
    recent_entry_wd.geometry(f'{width}x{height}+0+0')

    recent_entry_wd.config(bg="blue")

    sql = f"Select RECEIPT_NO,DATE,TIME,EMPLOYEE_ID,NAME,V_TYPE,V_NUMBER,JOURNEY_TYPE,BOOTH_NO,TOLL from vehicle_details Where EMPLOYEE_ID='{id}' and DATE='{cur_date}'ORDER BY SNO DESC LIMIT 20 "
    cursor.execute(sql)
    result = cursor.fetchall()

    label = Label(recent_entry_wd, text="Recent Entries",
                  font=("Helvetica", 20), bg="black", fg="orange")
    label.pack(pady=(20, 10))

    #back_button=Button(recent_entry_wd,text="Go Back",command=go_back,font=("Arial",15))
    #back_button.pack(pady=10, anchor="w")

    my_frame = Frame(recent_entry_wd, bg="blue")
    my_frame.pack(fill=Y)

    scrollbar = Scrollbar(my_frame)
    scrollbar.pack(fill=Y, side=RIGHT)

    my_tree = ttk.Treeview(my_frame, height=25)
    my_tree.config(yscrollcommand=scrollbar.set)

    scrollbar.config(command=my_tree.yview)

    #columns of treeview
    my_tree["columns"] = ("Receipt No", "Date", "Time", "ID", "Name",
                            "Vehicle Type", "Vehicle Number", "Journey Type", "Booth", "Toll")

    #create Headings
    columns = ("Receipt No", "Date", "Time", "ID", "Name",
                  "Vehicle Type", "Vehicle Number", "Journey Type", "Booth", "Toll")
    for head in columns:
        my_tree.heading(head, text=head)

    my_tree.column("#0", width=0)
    # format tree columns
    for head in columns:
        my_tree.column(head, width=140, anchor=CENTER)

    #insert entries
    i = 0
    for entry in result:
        my_tree.insert(parent='', index='end',
                        iid=i, text="", values=entry)
        i += 1
    my_tree.pack(fill=BOTH)

    

def generate_receipt(e):

    
    toll_for_na_label.grid_forget()
    toll_for_na.grid_forget()
    exemption_type_options.grid_forget()
    exemption_type_label.grid_forget()
    print_button.grid_forget()

    car.config(bg="yellowgreen")
    bus.config(bg="black")
    truck.config(bg="black")
    lcv.config(bg="black")
    multi.config(bg="black")
    na.config(bg="black")

    

    bar_entry_message.pack_forget()
    edit_entry_button.grid_forget()
    monthly_or_sp_exemp_stop_message.pack_forget()
    receipt_text.config(state=NORMAL)
    receipt_text.delete(1.0, END)
    receipt_text.grid_forget()
    if vehicle_number.get().replace(" ", "") == "":
        return
    if len(vehicle_number.get().replace(" ",""))>10:

        code = vehicle_number.get().replace(" ", "")
        global dateqr,timeqr,v_typeqr,j_typeqr,dirqr,old_receipt_no
        dateqr = code[:3]
        timeqr = code[3:7]
        v_typeqr = code[7]
        
        j_typeqr = code[8:10]
        dirqr = code[10]
        old_receipt_no=code[11:]
        old_receipt_no="L"+str(old_receipt_no[0])+"-" + str(old_receipt_no[1:])

        time_now = str(datetime.datetime.now().strftime("%H%M"))
        date_now = str(datetime.datetime.now().strftime("%Y%m%d"))

        days_list = False

        is_leap=False

        if int(date_now[:4])%4==0:
            is_leap=True
        
        if is_leap:
            days_list=[31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
        else:
            days_list = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
        
        month=0
        d=0
        for i in range(12):
            d+=days_list[i]
            if d > int(dateqr):
                month=i
                break
        
        days_left=int(dateqr) - sum(days_list[:month])
        if days_left<10:
            days_left="0" + str(days_left)

        month=int(month)+1
        if month<10:
            month="0"+ str(month)

        dateqr=date_now[2:4] + str(month) +  str(days_left)

        


        date_now = date_now[2:]
        diff = False
        allow_to_go = False
        if dateqr == date_now:
            diff = int(time_now[:2]) - int(timeqr[:2])
        else:
            if dateqr[:4] == date_now[:4]:
                if int(dateqr[4:6]) + 1 == int(date_now[4:6]):
                    diff = 24 - int(timeqr[:2]) + int(time_now[:2])

        if diff>=0:
            if diff <= 11:
                allow_to_go = True




        def bar_generate_receipt(e):

            

            
            global bar_print_btn,bar_receipt_text
            bar_receipt_text.pack_forget()
            bar_print_btn.pack_forget()

            global v_typeqr,j_typeqr,dirqr,old_receipt_no,bar_sub_btn

            if bar_v_no.get().replace(" ", "") == "":
                return

            bar_sub_btn.config(state=DISABLED)

            

            lane = "L"+str(booth)
            sql1 = f"select SNO from booth_sno where BOOTH='{lane}'"
            cursor.execute(sql1)
            x = cursor.fetchall()
            x = x[0][0]
            x += 1
            sql1 = f"Update booth_sno set SNO='{x}' where BOOTH='{lane}'"
            cursor.execute(sql1)
            new_receipt_no = lane+"-"+str(x)

            date_now = str(datetime.datetime.now().strftime("%Y-%m-%d"))
            time_now = str(datetime.datetime.now().strftime("%I:%M:%S:%p")).lower()
            shift_now=cur_shift
            v_type_dict={'c':'CAR','l':'LCV','b':'BUS','t':'TRUCK','m':'M-EXLE','n':'NA'}
            v_typeqr=v_type_dict[v_typeqr]

            v_noqr=bar_v_no.get()

            j_type_dict={'Si':'Single Journey','Re':'Return Journey','Ex':'Exemption','Sp':'Special Exemption','Mo':'Monthly Pass'}
            j_typeqr=j_type_dict[j_typeqr]

            dir_dict={'u':'Up','d':'Down'}
            dirqr=dir_dict[dirqr]

            boothqr=booth
            idqr=id
            nameqr=name

            sql=f"INSERT INTO barcode_entries (DATE,TIME,ID,NAME,V_TYPE,V_NUMBER,J_TYPE,DIRECTION,BOOTH,OLD_RECEIPT_NO,NEW_RECEIPT_NO,SHIFT) values ('{date_now}','{time_now}','{idqr}','{nameqr}','{v_typeqr}','{v_noqr}','{j_typeqr}','{dirqr}','{boothqr}','{old_receipt_no}','{new_receipt_no}','{shift_now}')"
            cursor.execute(sql)
            mydb.commit()

            matter = f"""       Agroh Ratlam Tollway Pvt Ltd
        MDR ( 00.00 - 43.85)km
Sarlapada Toll Plaza Ratlam (at 26.50)
    Govt Notification No S.O. 1610 (E)
-----------------Toll Receipt-----------------
Receipt No        :  {new_receipt_no}
Trans Date : {date_now}-{time_now}
Booth             :  {booth}
Operator ID       :  {id}
Vehicle Type      :  {v_typeqr}
Vehicle Number    :  {bar_v_no.get()}
Journey Type      :  {j_typeqr}
Journey Direction :  {dirqr}
Trip Amt               :  0
-----------------Happy Journey---------------"""

            bar_receipt_text.insert(0.0,matter)
            bar_receipt_text.config(state=DISABLED)
            bar_receipt_text.pack(pady=10)
            
            bar_print_btn.config(command=lambda:print_receipt(matter,date_now,v_typeqr,j_typeqr,dirqr,new_receipt_no,barcode_req=False))
            bar_print_btn.pack(pady=5)

            vehicle_number.delete(0, END)




        if not allow_to_go:
            bar_entry_message.pack(pady=10)
            return


        bar_window=Toplevel(root)
        bar_window.config(bg="teal")
        bar_window.state("zoomed")

        bar_heading=Label(bar_window,text="Barcode Entry",font=("Arial",35))
        bar_heading.pack(pady=20)

        bar_frame=Frame(bar_window,bg="orange")
        bar_frame.pack(pady=40)

        bar_label=Label(bar_frame,text="Vehicle Number",font=("Arial",25))
        bar_label.grid(row=0,column=0,pady=20)
        
        global bar_v_no
        bar_v_no=Entry(bar_frame,font=("Arial",20))
        bar_v_no.focus()
        bar_v_no.grid(row=0,column=1,pady=20,padx=10)

        global bar_sub_btn
        bar_sub_btn=Button(bar_frame,text="Submit",command=lambda:bar_generate_receipt(1),font=("Arial",20))

        bar_sub_btn.grid(row=1,column=0,columnspan=2,pady=20)


        global bar_receipt_text
        bar_receipt_text=Text(bar_window,height=15,width=45)
        
        global bar_print_btn
        bar_print_btn=Button(bar_window,text="Print",font=("Arial",20))

        #bind sub btn with Return key
        bar_window.bind("<Return>",bar_generate_receipt)
        


        

        

            




        


        





        


    else:
        journey_type_var=journey_type.get()
        
        mon_stop=0
        tolle="NA"
        if journey_type_var == "Monthly Pass":
            
            sqlcheck = f"Select * from monthly_pass where VEHICLE_NUMBER='{vehicle_number.get()}' ORDER BY PASS_NO DESC LIMIT 1"
            cursor.execute(sqlcheck)
            mon_res = cursor.fetchall()
            if mon_res == []:
                mon_stop = 1
                
            else:
                if mon_res[0][9] < date_today:
                    mon_stop = 1
                else:
                    mon_stop = 0
                    tolle = 0
        sp_stop = 0
        if journey_type.get() == "Special Exemption":
            sqlcheck = f"Select * from sp_exemption where VEHICLE_NUMBER='{vehicle_number.get()}'"
            cursor.execute(sqlcheck)
            sp_res = cursor.fetchall()
            if sp_res == []:
                sp_stop = 1
            else:
                sp_stop = 0
                tolle = 0

        if sp_stop == 0 and mon_stop == 0:
            lane="L"+str(booth)
            sql1 = f"select SNO from booth_sno where BOOTH='{lane}'"
            cursor.execute(sql1)
            x = cursor.fetchall()
            x = x[0][0]
            x += 1
            sql1 = f"Update booth_sno set SNO='{x}' where BOOTH='{lane}'"
            cursor.execute(sql1)
            receipt_no = lane+"-"+str(x)
            import client_photo_clicker
            client_photo_clicker.click_photo(receipt_no)
            sql1 = f"select TOLL_AMOUNT from vehicle_type where VEHICLE_TYPE='{vehicle_class.get()}'"
            cursor.execute(sql1)
            toll = cursor.fetchall()
            if tolle!="NA":
                toll=tolle
            else:
                toll = toll[0][0]
            v_image = "vehicle_images/" + receipt_no+".jpg"
            global direction
            direction=""
            
            if booth==1:
                direction="Up"
            elif booth==2:
                direction="Up"
            elif booth==3:
                direction="Down"
            elif booth==4:
                direction="Down"

            shift = cur_shift
            correct_toll = toll
            validated = 0
            validated_by = "NULL"
            old_v_type = vehicle_class.get()
            if journey_type.get() == "Exemption":
                if exemption_type_options.get()=="Ambulance":
                    journey_type_var=journey_type.get() + "[Amb]"
                elif exemption_type_options.get()=="Government":
                    journey_type_var=journey_type.get() + "[Gov]"

                elif exemption_type_options.get() == "Police":
                    journey_type_var = journey_type.get() + "[Police]"
                elif exemption_type_options.get() == "Press":
                    journey_type_var = journey_type.get() + "[Press]"
                elif exemption_type_options.get()=="Defence":
                    journey_type_var=journey_type.get() + "[Defence]"
                elif exemption_type_options.get()=="VIP":
                    journey_type_var=journey_type.get() + "[VIP]"
                elif exemption_type_options.get()=="Other":
                    journey_type_var=journey_type.get() + "[Other]"
                toll = 0
            else:
                journey_type_var = journey_type.get()
            mon_stop = 0

            if vehicle_class.get() == "NA":
                toll = toll_for_na.get()

            sqlfinal = f"INSERT INTO vehicle_details (DATE, TIME, EMPLOYEE_ID, NAME, V_TYPE, V_NUMBER, JOURNEY_TYPE, TOLL, IMAGE, SHIFT, BOOTH_NO, RECEIPT_NO, CORRECT_TOLL, VALIDATED, DIRECTION, VALIDATED_BY, OLD_V_TYPE) VALUES('{cur_date}', '{cur_time}', {id}, '{name}', '{vehicle_class.get()}', '{vehicle_number.get()}', '{journey_type_var}', {toll}, '{v_image}', '{shift}', {booth}, '{receipt_no}', '{toll}', '{validated}', '{direction}', '{validated_by}', '{vehicle_class.get()}')"
            #excute sql command
            cursor.execute(sqlfinal)
            #commit changes
            mydb.commit()
            valid_till = datetime.datetime.now() + timedelta(hours=12)
            valid_till = valid_till.strftime("%Y-%m-%d %I:%M:%S%p")
            valid_till = valid_till.lower()
            matter = f"""       Agroh Ratlam Tollway Pvt Ltd
        MDR ( 00.00 - 43.85)km
Sarlapada Toll Plaza Ratlam (at 26.50)
    Govt Notification No S.O. 1610 (E)
-----------------Toll Receipt-----------------
Receipt No        :  {receipt_no}
Transaction Date  :  {date_today}   {time_cur}
Booth             :  {booth}
Operator ID       :  {id}
Vehicle Type      :  {vehicle_class.get()}
Vehicle Number    :  {vehicle_number.get()}
Journey Type      :  {journey_type_var}
Journey Direction :  {direction}
Trip Amt              :  {toll}
-----------------Happy Journey---------------
Valid Till : {valid_till} """


            v_no=vehicle_number.get()
            receipt_text.insert(1.0, matter)
            receipt_text.config(state=DISABLED)
            receipt_text.grid(row=6,column=0,columnspan=2,pady=10)
            vehicle_number.delete(0, END)
            matterp = f"""       Agroh Ratlam Tollway Pvt Ltd
        MDR ( 00.00 - 43.85)km
Sarlapada Toll Plaza Ratlam (at 26.50)
    Govt Notification No S.O. 1610 (E)
-----------------Toll Receipt-----------------
Receipt No        :  {receipt_no}
Trans Date : {date_today}-{time_cur}
Booth             :  {booth}
Operator ID       :  {id}
Vehicle Type      :  {vehicle_class.get()}
Vehicle Number    :  {v_no}
Journey Type      :  {journey_type_var}
Journey Direction :  {direction}
Trip Amt              :  {toll}
---------------------Happy Journey-------------------
Valid Till : {valid_till} """
            print_button.config(command=lambda:print_receipt(matterp,date_today,vehicle_class.get(),journey_type_var,direction,receipt_no))
            print_button.grid(row=7,column=0,pady=10)
        
       

            edit_entry_button.config(
                command=lambda: edit_this_entry(receipt_no))

            edit_entry_button.grid(pady=10,row=7,column=1)
        else:
            monthly_or_sp_exemp_stop_message.pack(pady=20)
    vehicle_class.set("CAR")
    journey_type.set("Single Journey")
    
def live_clock_and_date():
    global time_now
    global date_now
    global cur_time
    global cur_date
    global date_today
    global time_cur

    time_now = str(datetime.datetime.now().strftime("%I:%M:%S %p")).lower()
    date_now = datetime.datetime.now().strftime("%d-%m-%Y")

    #for global update of date and time to be used in other parts of programs
    
    cur_time = datetime.datetime.now()
    cur_date = cur_time.strftime("%Y-%m-%d")
    cur_time = str(cur_time.strftime("%I:%M:%S%p")).lower()
    date_today = datetime.datetime.now().strftime("%Y-%m-%d")
    time_cur=cur_time
    global live_clock_date_label

    live_clock_date_label.config(text=f"{date_now}\n{time_now}")

    live_clock_date_label.after(1000, live_clock_and_date)


def exemption_type(e):

    if journey_type.get() == "Exemption":

        exemption_type_label.config(text="Exemption Type ")
        exemption_type_label.grid(row=3, column=0,pady=(10,0))

        exemption_type_options.grid(row=3, column=1,pady=(10,0))
    else:

        exemption_type_label.grid_forget()
        exemption_type_options.grid_forget()



def display_na_input(e):
    global toll_for_na
    global toll_for_na_label
    if vehicle_class.get() == "NA":
        toll_for_na_label.grid(row=4, column=0)
        toll_for_na.grid(row=4, column=1, padx=7)
    else:
        toll_for_na.grid_forget()
        toll_for_na_label.grid_forget()


def staff_dashboard(name_, username_, id_, cur_shift_, booth_=2):

    def vehicle_selection(e):
        if e.keysym=="F1":
            vehicle_class.set("CAR")
            car.config(bg="yellowgreen")
            bus.config(bg="black")
            truck.config(bg="black")
            lcv.config(bg="black")
            multi.config(bg="black")
            na.config(bg="black")


            toll_for_na.grid_forget()
            toll_for_na_label.grid_forget()
        elif e.keysym=="F2":
            vehicle_class.set("BUS")
            car.config(bg="black")
            bus.config(bg="yellowgreen")
            truck.config(bg="black")
            lcv.config(bg="black")
            multi.config(bg="black")
            na.config(bg="black")

            toll_for_na.grid_forget()
            toll_for_na_label.grid_forget()
        elif e.keysym=="F3":
            vehicle_class.set("TRUCK")
            car.config(bg="black")
            bus.config(bg="black")
            truck.config(bg="yellowgreen")
            lcv.config(bg="black")
            multi.config(bg="black")
            na.config(bg="black")

            toll_for_na.grid_forget()
            toll_for_na_label.grid_forget()
        elif e.keysym=="F4":
            vehicle_class.set("LCV")
            car.config(bg="black")
            bus.config(bg="black")
            truck.config(bg="black")
            lcv.config(bg="yellowgreen")
            multi.config(bg="black")
            na.config(bg="black")

            toll_for_na.grid_forget()
            toll_for_na_label.grid_forget()

        elif e.keysym == "F5":
            vehicle_class.set("M-EXLE")
            car.config(bg="black")
            bus.config(bg="black")
            truck.config(bg="black")
            lcv.config(bg="black")
            multi.config(bg="yellowgreen")
            na.config(bg="black")

            toll_for_na.grid_forget()
            toll_for_na_label.grid_forget()
        elif e.keysym == "F6":
            vehicle_class.set("NA")
            car.config(bg="black")
            bus.config(bg="black")
            truck.config(bg="black")
            lcv.config(bg="black")
            multi.config(bg="black")
            na.config(bg="yellowgreen")

            toll_for_na_label.grid(row=4, column=0, pady=10)
            toll_for_na.grid(row=4, column=1, padx=7, pady=10)


    root.unbind("<Return>")
    root.bind("<Return>",generate_receipt)

    root.bind("<Key>",vehicle_selection)

    global name,username,id,cur_shift,booth
    name=name_
    username=username_
    id=id_
    cur_shift=cur_shift_
    booth=booth_

    widgets=root.winfo_children()
    for w in widgets:
        w.destroy()

    root.title("Dashboard")
    
    header=Label(root,text="TollTax Management System",font=("Helvetica",20),bg="#2F2FA2",fg="white",anchor="w")
    header.pack(fill=X)

    global main_frame
    main_frame=Frame(root)
    main_frame.pack(fill=BOTH,expand=1,anchor=NW)

    

    

    global dashboard_img
    dashboard_img=ImageTk.PhotoImage(Image.open("images/dash.jpg").resize((root.winfo_screenwidth(),root.winfo_screenheight())))

   

    label=Label(main_frame,image=dashboard_img)
    label.place(relx=0, rely=0, relheight=1, relwidth=1)
    global frame_1
    frame_1 = Frame(main_frame, bg="#ff4917")
    frame_1.pack(anchor=N, fill=X)
    
    

    #live clock and date
    global live_clock_date_label
    live_clock_date_label=Label(frame_1,text="",font=("Arial",15),bg="black",fg="yellowgreen")
    live_clock_date_label.pack(side=LEFT)
    live_clock_and_date()

    #logout button
    global logout_img
    logout_img=ImageTk.PhotoImage(Image.open("images/logout.png").resize((50,50)))
    logout_btn=Button(frame_1,image=logout_img,borderwidth=0,command=logout)
    logout_btn.pack(side=RIGHT,padx=10)

    #operator details
    
    operator_label=Label(frame_1,text=f"Name:{name}    \nID:{id}\nBooth No:{booth}",font=("Helvetica",14),bg="#680000",fg="white")
    operator_label.pack(side=RIGHT)

    

    #recent entries button
    recent_entries_button=Button(frame_1,text="Recent Entries",command=recent_entries,font=("Helvetica",15))
    recent_entries_button.pack(side=LEFT,padx=10)

   

    global session_timer_label
    global timer
    timer = StringVar()
    session_timer_label = Label(frame_1, textvariable=timer, bg="#ff4917",fg="white",font=("Arial",20,"bold"))
    session_timer_label.pack(side=LEFT)

    

    image_frame=Frame(main_frame,bg="#ff4917")
    image_frame.pack(fill=X)
    
    
    #vehicle images
    global car_img
    global car
    car_img=ImageTk.PhotoImage(Image.open("images/car.jpg").resize((80,80)))
    car=Label(image_frame,image=car_img,bg="yellowgreen",bd=7)
    car.grid(row=0,column=0,padx=10)

    global bus_img
    global bus
    bus_img = ImageTk.PhotoImage(Image.open(
        "images/bus.jpg").resize((80, 80)))
    bus = Label(image_frame, image=bus_img,bg="black",bd=7)
    bus.grid(row=0,column=1,padx=10)

    global truck_img
    global truck
    truck_img=ImageTk.PhotoImage(Image.open("images/truck.jpg").resize((80,80)))
    truck=Label(image_frame,image=truck_img,bg="black",bd=7)
    truck.grid(row=0, column=2, padx=10)

    global lcv_img
    global lcv
    lcv_img=ImageTk.PhotoImage(Image.open("images/lcv.jpg").resize((80,80)))
    lcv=Label(image_frame,image=lcv_img,bg="black",bd=7)
    lcv.grid(row=0,column=3,padx=10)

    global multi_img
    global multi
    multi_img=ImageTk.PhotoImage(Image.open("images/multi.jpg").resize((80,80)))
    multi=Label(image_frame,image=multi_img,bg="black",bd=7)
    multi.grid(row=0,column=4,padx=10)

    global na_img
    global na
    na_img=ImageTk.PhotoImage(Image.open("images/na.jpg").resize((80,80)))
    na=Label(image_frame,image=na_img,bd=7,bg="black")
    na.grid(row=0,column=5,padx=10)

    car_label=Label(image_frame,text="Car(F1)",font=("Arial",15))
    car_label.grid(row=1,column=0,padx=10,pady=5)

    bus_label=Label(image_frame,text="Bus(F2)",font=("Arial",15))
    bus_label.grid(row=1,column=1,padx=10,pady=5)

    truck_label=Label(image_frame,text="Truck(F3)",font=("Arial",15))
    truck_label.grid(row=1,column=2,padx=10,pady=5)

    lcv_label=Label(image_frame,text="LCV(F4)",font=("Arial",15))
    lcv_label.grid(row=1,column=3,padx=10,pady=5)

    multi_label=Label(image_frame,text="Multi Axel(F5)",font=("Arial",15))
    multi_label.grid(row=1,column=4,padx=10,pady=5)

    na_label = Label(image_frame, text="NA(F6)", font=("Arial", 15))
    na_label.grid(row=1, column=5, padx=10, pady=5)

    

    global cur_time
    global cur_date
    cur_time=datetime.datetime.now()
    cur_date=cur_time.strftime("%Y-%m-%d")
    cur_time=str(cur_time.strftime("%I:%M:%S%p")).lower()

    #getting vehicle classes from database
    cursor.execute("Select VEHICLE_TYPE from vehicle_type")
    v_class = cursor.fetchall()

    #making list of vehicle classe
    global v_class_list
    v_class_list = []
    for x in v_class:
        v_class_list.append(x[0])
    

    #vehicle entry frame
    global vehicle_frame
    vehicle_frame = Frame(main_frame,bg="#4913DC")
    vehicle_frame.pack(pady=(20,0))

    #vehicle class dropdown
    vehicle_class_label = Label(vehicle_frame, text="Vehicle Type ", font=(
        "Arial", 15, "bold"), bg="#4913DC",fg="white")
    vehicle_class_label.grid(row=0,column=0)

    global vehicle_class
    vehicle_class=ttk.Combobox(vehicle_frame,values=v_class_list,font=("Arial",15),state="readonly")

    vehicle_class.current(0)
    vehicle_class.grid(row=0, column=1,padx=7)
    #virtual event binding for NA detection
    vehicle_class.bind("<<ComboboxSelected>>",display_na_input)

    #vehicle number
    global vehicle_number
    vehicle_number_label = Label(vehicle_frame, text="Vehicle Number  ", font=(
        "Arial", 15, "bold"), bg="#4913DC",fg="white")
    vehicle_number_label.grid(row=1, column=0,pady=10)

    vehicle_number = Entry(vehicle_frame, font=("Arial", 15, "bold"),bd=4)
    vehicle_number.grid(row=1, column=1, pady=10, padx=7)
    vehicle_number.focus()

    #journey type dropdown

    journey_type_label = Label(vehicle_frame, text="Journey Type  ", font=(
        "Arial", 15, "bold"), bg="#4913DC",fg="white")
    journey_type_label.grid(row=2, column=0)

    global journey_type
    journey_type=ttk.Combobox(vehicle_frame,values=["Single Journey","Return Journey","Monthly Pass","Exemption","Special Exemption"],font=("Arial",15),state="readonly")


    journey_type.current(0)
    journey_type.grid(row=2, column=1, padx=7)

    journey_type.bind("<<ComboboxSelected>>",exemption_type)
    
    #exemption type label and dropdown
    global exemption_type_label
    global exemption_type_options
    exemption_type_label=Label(vehicle_frame,text="",font=("Arial",15,"bold"),bg="#99CCFF")

    exemption_type_options=ttk.Combobox(vehicle_frame,values=["Ambulance","Police","Defence","Press","Government","VIP","Other"],font=("Arial",15),state="readonly")
    exemption_type_options.current(0)

    #toll For NA 
    global toll_for_na_label
    global toll_for_na
    toll_for_na_label=Label(vehicle_frame,text="TOLL ",font=("Arial",15,"bold"),bg="#99CCFF")
    toll_for_na=Entry(vehicle_frame,font=("Arial",15,"bold"))

    #submit button

    submit_button=Button(vehicle_frame,text="Submit",font=("Helvetica",15),command=lambda : generate_receipt(1))
    submit_button.grid(row=5,column=1,pady=10)

    global edit_entry_button,monthly_or_sp_exemp_stop_message
    monthly_or_sp_exemp_stop_message=Label(main_frame,font=("Arial",15),text="Montly pass or Special Exemption Not Registered or is Expired  for this Vehicle")
    edit_entry_button=Button(vehicle_frame,text="Edit This Entry",font=("Arial",15))



    global receipt_text
    receipt_text = Text(vehicle_frame, height=15, width=45)

    global print_button
    print_button=Button(vehicle_frame,text="Print Receipt",font=("Arial",15))

    global bar_entry_message
    bar_entry_message=Label(main_frame,text="Bar code Not Valid",font=("Arial",20))

def hashfunc(x):
    x = hashlib.sha256(x.encode())
    return x.hexdigest()


def shift_running():

    shift = False
    cur_time = datetime.datetime.now().strftime("%H:%M:%S").split(":")
    t1 = datetime.time(00, 00, 00)
    t2 = datetime.time(8, 00, 00)
    t3 = datetime.time(16, 00, 00)
    t4 = datetime.time(23, 59, 59)
    #cur time
    x = datetime.time(int(cur_time[0]), int(cur_time[1]), int(cur_time[2]))

    if t1 <= x < t2:
        shift = "shift 1"
    elif t2 <= x < t3:
        shift = "shift 2"
    elif t3 <= x < t4:
        shift = "shift 3"

    return shift
def check_login(e):
    
    boo_message.pack_forget()
    login_flag = 0

    acknowlegde = False

    user = str(username.get())
    passwd = hashfunc(password.get())

    if not username.get():
        acknowlegde = "Please Enter username"
        messagebox.showwarning("Wrong Login Attempt",acknowlegde)
    elif not password.get():
        acknowlegde = "Please Enter password"
        messagebox.showwarning("Wrong Login Attempt", acknowlegde)
    else:
        sql = f"SELECT PASSWORD,ID,NAME,TYPE FROM users WHERE USERNAME = '{user}' "
        cursor.execute(sql)
        res = cursor.fetchall()
        if res:

            if res[0][0] == passwd:
                login_flag = 1
                if res[0][3] == "staff":
                    
                    shift = shift_running()
                    
                    boo_no=0

                    if ipaddr=="192.168.1.110":
                        boo_no=1
                    elif ipaddr=="192.168.1.111":
                        boo_no=2
                    elif ipaddr=="192.168.1.112":
                        boo_no=3
                    elif ipaddr=="192.168.1.113":
                        boo_no=4
                    else:
                        boo_no=0
                    if boo_no:
                        staff_dashboard(res[0][2], user, res[0][1], shift, booth_=boo_no)
                    else:
                        booth_undefined="Booth Undefined"
                        boo_message.config(text=booth_undefined)
                        boo_message.pack(pady=10)
                #elif res[0][3] == "administrator":
                    #login_window.destroy()
                    #import admin

                #elif res[0][3] == "pass manager":
                    #login_window.destroy()
                    #import pass_manager

            else:

                login_flag = 0
                acknowlegde = "Incorrect Password or Username"
                username.delete(0, END)
                password.delete(0, END)
                messagebox.showwarning("Wrong Login Attempt",acknowlegde)
                #acknowlegde_label.config(text=acknowlegde)
                
        else:
            acknowlegde = "Incorrect Password or Username"
            username.delete(0, END)
            password.delete(0, END)
            messagebox.showwarning("Wrong Login Attempt",acknowlegde)
            #acknowlegde_label.config(text=acknowlegde)



def login_screen():
    def clear_widget(e):
       if username.get()=="Username" or password=="Password":
            username.delete(0,END)

            password.delete(0,END)
            password.config(show="*")

    
    root.unbind("<Return>")
    bgcolor = "#8516ae"
    global login_img
   

    

    #frame=Frame(root)
    #frame.pack(pady=10)
    
    
    
    global username,password
    #username_label=Label(frame,text="Username : ",font=("Arial",24),fg="black",bg=bgcolor)
    #username_label.grid(row=0,column=0,pady=(30,10),padx=10)
    
    username = Entry(root, font=("Helvetica", 30), bd=3, bg="white",justify="center")
    username.insert(0,"Username")
    username.pack(pady=(300,10))

    

    #password_label=Label(frame,text="Password : ",font=("Arial",24),fg="black",bg=bgcolor)
    #password_label.grid(row=1,column=0,pady=(10,10),padx=10)

    password=Entry(root,font=("Helvetica",30),bd=3, bg="white",justify="center")
    password.insert(0,"Password")
    
    password.pack(pady=10)

    username.bind("<Button-1>",clear_widget)
    password.bind("<Button-1>",clear_widget)

    
    login_button=Button(root,text="Login",command=lambda:check_login(1),bg="#009FD8",font=("Arial",20),width=20)
    login_button.pack(pady=20)
    #authentication

    #acknowlegde_label=Label(root,text="",bg=bgcolor,font=("Helvetica",20))
    #acknowlegde_label.pack(pady=20)


    #booth message
    global boo_message
    boo_message=Label(root,font=("Arial",15))

    #bind login button with Enter Key
    root.bind("<Return>",check_login)

t1=Thread(target=login_screen).start()
#login_screen()
session_timeout()   





root.mainloop()
