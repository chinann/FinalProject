#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Wed Jun 15 01:52:25 2016

@author: Administrator
"""
import psycopg2
import cgi
import cgitb; cgitb.enable()
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    username = ""
    password = ""
    fname = ""
    lname = ""
    addr = ""
    email = ""
    tel = ""

#    Get value from URL
    for i in form.keys():
        if i == "username":
            username = form[i].value
        elif i == "password":
            password = form[i].value
        elif i == "fname":
            fname = form[i].value
        elif i == "lname":
            lname = form[i].value
        elif i == "addr":
            addr = form[i].value
        elif i == "email":
            email = form[i].value
        elif i == "tel":
            tel = form[i].value

    querySelectTypeDep = "INSERT INTO table_user(\"user_Fname\", \"user_Lname\", \"user_Addr\", \"user_Email\", \"user_Tel\", \"user_Username\", \"user_Password\") VALUES ('"+fname+"', '"+lname+"', '"+addr+"', '"+email+"', '"+tel+"', '"+username+"', md5('"+password+"'));"
#    print querySelectTypeDep

    cur.execute(querySelectTypeDep)
    conn.commit()
    conn.close()

    print "{\"status\": \"ok\"}"

if __name__ == '__main__':
    main()
