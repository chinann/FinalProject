#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     27/05/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import cgi
import cgitb; cgitb.enable()
from datetime import datetime
form = cgi.FieldStorage()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    typeStatic = ""
    query = ""
    json = "["


    for i in form.keys():
        if i == "typeStatic":
            typeStatic = form[i].value
        elif i == "depname":
            depname = form[i].value

    nameDB = 'DB_' + depname
    conn = psycopg2.connect(database=nameDB , user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()
##
##    if(typeStatic == 'Hospital' or typeStatic == 'School' or typeStatic == 'Temple' or typeStatic == 'Fire station'):
##        typeStatic = "All " + typeStatic
    if(typeStatic == 'Hospital' or typeStatic == 'School' or typeStatic == 'Temple' or typeStatic == 'Fire station'):
        typeStatic = "All " + typeStatic
        sqlSearchStaticName = "SELECT \"staticDataLayer_Name\" FROM table_staticDataLayer_deployment where \"staticDataLayer_Name\" = '" + typeStatic + "';"
##    print sqlSearchStaticName
        cur.execute(sqlSearchStaticName)
        row = cur.fetchone()
        if row is not None:
            for r in row:
                if (r == typeStatic):
                    selectStatic = "select \"display_name\", ST_X(ST_Transform(ST_GeomFromText(ST_AsText(geom),32647),4326)) , ST_Y(ST_Transform(ST_GeomFromText(ST_AsText(geom),32647),4326)), \"name_area\", \"serial\" from \"" + typeStatic +"\";"
            cur.execute(selectStatic)
            rows = cur.fetchall()
            count = 0
            for row in rows:
                if count == len(rows)-1 :
                    json = json + "{\"name\":\"" + row[0] + "\",\"longitude\":\"" + str(row[1]) + "\",\"latitude\":\"" + str(row[2]) + "\",\"name_area\":\"" + str(row[3]) + "\",\"id\":\"" + str(row[4]) + "\",\"database\":\"Deployment\"}]"
                else:
                    json = json + "{\"name\":\"" + row[0] + "\",\"longitude\":\"" + str(row[1]) + "\",\"latitude\":\"" + str(row[2]) + "\",\"name_area\":\"" + str(row[3]) + "\",\"id\":\"" + str(row[4]) + "\",\"database\":\"Deployment\"},"
                count +=1
            conn.commit()
        else:
            conn2 = psycopg2.connect(database="GCaaS" , user="postgres", password="1234", host="localhost", port="5432")
            cur2 = conn2.cursor()
            selectStatic = "select \"display_name\", ST_X(ST_Transform(ST_GeomFromText(ST_AsText(geom),32647),4326)) , ST_Y(ST_Transform(ST_GeomFromText(ST_AsText(geom),32647),4326)), \"name_area\", \"serial\" from \"" + typeStatic +"\";"
            cur2.execute(selectStatic)
            rows = cur2.fetchall()
            count = 0
            for r2 in rows:
                if count == len(rows)-1 :
                    json = json + "{\"name\":\"" + r2[0] + "\",\"longitude\":\"" + str(r2[1]) + "\",\"latitude\":\"" + str(r2[2]) + "\",\"name_area\":\"" + str(r2[3]) + "\",\"id\":\"" + str(r2[4]) + "\",\"database\":\"GCaaS\"}]"
                else:
                    json = json + "{\"name\":\"" + r2[0] + "\",\"longitude\":\"" + str(r2[1]) + "\",\"latitude\":\"" + str(r2[2]) + "\",\"name_area\":\"" + str(r2[3]) + "\",\"id\":\"" + str(r2[4]) + "\",\"database\":\"GCaaS\"},"
                count +=1

##               querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = '" + typeStatic +"';"
##            cur2.execute(querySelect)
##            rows = cur2.fetchall()
##            for r1 in rows:
##                query = r1[0]
##                cur2.execute(query)
##                r2 = cur2.fetchall()
##
##            count = 0
##            for r3 in r2:
##                if count == len(r2)-1 :
##                    json = json + "{\"name\":\"" + row[0] + "\",\"longitude\":\"" + str(row[1]) + "\",\"latitude\":\"" + str(row[2]) + "\",\"name_area\":\"" + str(row[3]) + "\",\"id\":\"" + str(row[4]) + "\"}]"
##                else:
##                    json = json + "{\"name\":\"" + row[0] + "\",\"longitude\":\"" + str(row[1]) + "\",\"latitude\":\"" + str(row[2]) + "\",\"name_area\":\"" + str(row[3]) + "\",\"id\":\"" + str(row[4]) + "\"},"
##                count +=1
##            conn2.close()
    else:
        selectStatic = "select \"display_name\", \"longitude\" , \"latitude\" , \"name_area\", \"serial\" from " + typeStatic +";"
        cur.execute(selectStatic)
        rows = cur.fetchall()
        count = 0
        for row in rows:
            if count == len(rows)-1 :
                json = json + "{\"name\":\"" + row[0] + "\",\"longitude\":\"" + str(row[1]) + "\",\"latitude\":\"" + str(row[2]) + "\",\"name_area\":\"" + str(row[3]) + "\",\"id\":\"" + str(row[4]) + "\",\"database\":\"Deployment\"}]"
            else:
                json = json + "{\"name\":\"" + row[0] + "\",\"longitude\":\"" + str(row[1]) + "\",\"latitude\":\"" + str(row[2]) + "\",\"name_area\":\"" + str(row[3]) + "\",\"id\":\"" + str(row[4]) + "\",\"database\":\"Deployment\"},"
            count +=1
        conn.commit()
        conn.close()
    print json

if __name__ == '__main__':
    main()
