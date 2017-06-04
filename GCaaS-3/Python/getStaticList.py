#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     23/05/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

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

    for i in form.keys():
        if i == "depname":
            depname = form[i].value
        else:
            typeStatic = form[i].value

    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticID\" > 23;"
    cur.execute(querySelect)
    rows = cur.fetchall()
    count = 0
    for row in rows:
        if count == len(rows)-1 :
            json = json + "{\"staticID\":\"" + row[0] + "\",\"staticDataLayer_Name\":\"" + str(row[1]) + "\"}]"
        else:
            json = json + "{\"staticID\":\"" + row[0] + "\",\"staticDataLayer_Name\":\"" + str(row[1]) + "\"},"
        count +=1


if __name__ == '__main__':
    main()
