#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Mon Feb 29 16:40:50 2016

@author: wgs01
"""

import psycopg2
import cgi
import cgitb; cgitb.enable()

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="172.20.10.2", port="5432")
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"


    json = "["

    for i in form.keys():
        if i == "user":
            user = form[i].value


    querySearchUser = "SELECT \"user_Email\", \"user_Username\" FROM table_user WHERE \"user_Username\" = '" + user + "' OR \"user_Email\" = '" + user + "';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";

    cur.execute(querySearchUser)
    rows = cur.fetchall()

    if len(rows) != 0:
        count = 0
        for row in rows:
            if count == len(rows)-1 :
                json = json + "{\"username\":\"" + row[1] + "\",\"email\":\"" + str(row[0]) + "\"}]"
            else:
                json = json + "{\"username\":\"" + row[1] + "\",\"email\":\"" + str(row[0]) + "\"},"
            count +=1
        print json
    else :
        print "{\"status\": \"None\"}"

    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()