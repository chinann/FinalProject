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


    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = '" + typeStatic +"';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySelect)
    rows = cur.fetchall()
    for row in rows:
        query = row[0]
    cur.execute(query)
    rows = cur.fetchall()

    count = 0
    for row in rows:
        if count == len(rows)-1 :
            json = json + "{\"name\":\"" + row[0] + "\",\"long_itude\":\"" + str(row[1]) + "\",\"lat_itude\":\"" + str(row[2]) + "\"}]"
        else:
            json = json + "{\"name\":\"" + row[0] + "\",\"long_itude\":\"" + str(row[1]) + "\",\"lat_itude\":\"" + str(row[2]) + "\"},"
        count +=1


    conn.close()
    print json

if __name__ == '__main__':
    main()