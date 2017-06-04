#!/Python27/python.exe
# -*- coding: utf-8 -*-
#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     25/05/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import cgi
import cgitb; cgitb.enable()
form = cgi.FieldStorage()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    for i in form.keys():
        if i == "typeStatic":
            typeStatic = form[i].value
        elif i == "depname":
            depname = form[i].value


    nameDB = 'DB_' + depname
    conn = psycopg2.connect(database=nameDB , user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()

    if(typeStatic == 'Hospital' or typeStatic == 'School' or typeStatic == 'Temple' or typeStatic == 'Fire station' or typeStatic == 'Police station'):
        typeStatic = "All " + typeStatic
        sqlSearchStaticName = "SELECT \"staticDataLayer_Name\" FROM table_staticDataLayer_deployment where \"staticDataLayer_Name\" = '" + typeStatic + "';"
        cur.execute(sqlSearchStaticName)
        row = cur.fetchone()
        if row is None:
            conn.close()
            conn = psycopg2.connect(database="GCaaS" , user="postgres", password="1234", host="localhost", port="5432")
            cur = conn.cursor()
            sqlSelect = "SELECT \"serial\" FROM \"" + typeStatic +"\" ORDER BY serial DESC LIMIT 1"
        else:
            sqlSelect = "SELECT \"serial\" FROM \"" + typeStatic +"\" ORDER BY serial DESC LIMIT 1"

    else:
        sqlSelect = "SELECT \"serial\" FROM \"" + typeStatic +"\" ORDER BY serial DESC LIMIT 1"
    cur.execute(sqlSelect)
    row = cur.fetchone()
    print "%s" % row
    conn.close()


if __name__ == '__main__':
    main()
