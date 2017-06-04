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

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    dbName = ""

    for i in form.keys():
        if i == "status":
            status = form[i].value
        elif i == "id":
            ids = form[i].value
        elif i == "dbName":
            dbName = "DB_" + form[i].value

    conn = psycopg2.connect(database = dbName, user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()

    queryUpdate = "UPDATE \"table_postTWH\" SET \"post_Status\"='" + status +"' WHERE \"postID\" = '" + ids +"';"
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(queryUpdate)

    conn.commit()
    conn.close()
    print "{\"status\": \"ok\"}"

if __name__ == '__main__':
    main()