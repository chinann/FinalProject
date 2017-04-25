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

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    dbName = ""

    for i in form.keys():
        if i == "msg":
            msg = form[i].value
        elif i == "lat":
            lat = form[i].value
        elif i == "lng":
            lng = form[i].value
        elif i == "dbName":
            dbName = "DB_" + form[i].value

    conn = psycopg2.connect(database = dbName, user="postgres", password="1234", host="172.20.10.2", port="5432")
    cur = conn.cursor()

    queryInsetData = "INSERT INTO \"table_postTWH\"(\"post_Name\", \"post_GeomIncident\", \"post_Date\", \"podt_HelpNo\", \"post_Status\", \"post_Message\", \"post_Hashtag\", \"deploymentID\", \"post_ProfileImg\", \"post_Place\") VALUES ('Rescue Team', ST_GeomFromText('POINT(" + lng + " " + lat + ")',4326), '" + now + "', '0', 'New', '" + msg + "', '-', '90', 'http://172.16.228.116/GCaaS-3/img/default-user.png', '-');";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(queryInsetData)

    conn.commit()
    conn.close()
    print "{\"status\": \"ok\"}"

if __name__ == '__main__':
    main()