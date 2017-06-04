#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Mon Feb 29 16:40:50 2016

@author: wgs01
"""

import psycopg2
import cgi
import cgitb; cgitb.enable()
from datetime import datetime

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    typeStatic = ""
    query = ""
    json = "["

    for i in form.keys():
        if i == "typeStatic":
            typeStatic = form[i].value
        if i == "depname":
            depname = form[i].value

    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = '" + typeStatic +"';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySelect)
    rows = cur.fetchall()
    conn2 = psycopg2.connect(database="DB_"+depname, user="postgres", password="1234", host="localhost", port="5432")
    cur2 = conn2.cursor()

    for row in rows:
        query = row[0]
        cur2.execute(query)
        row2 = cur2.fetchall()


    count = 0
    for r in row2:
        if count == len(row2)-1 :
            json = json + "{\"name\":\"" + r[0] + "\",\"longitude\":\"" + str(r[1]) + "\",\"latitude\":\"" + str(r[2]) + "\",\"name_area\":\"" + str(r[3]) + "\",\"id\":\"" + str(r[4]) + "\"}]"
        else:
            json = json + "{\"name\":\"" + r[0] + "\",\"longitude\":\"" + str(r[1]) + "\",\"latitude\":\"" + str(r[2]) + "\",\"name_area\":\"" + str(r[3]) + "\",\"id\":\"" + str(r[4]) + "\"},"
        count +=1


    conn.close()
    print json

if __name__ == '__main__':
    main()