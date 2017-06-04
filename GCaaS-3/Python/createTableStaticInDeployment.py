#!/Python27/python.exe
# -*- coding: utf-8 -*-
#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     26/05/2017
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
        if i == "dataDic":
            dataToInsert = form[i].value

    count = 0
    querySelect =  "SELECT \"staticDataLayer_Name\" FROM public.\"table_staticDataLayer\" where \"staticID\" < 6;"
    cur.execute(querySelect)
    rows = cur.fetchall()
    for row in rows:
        query = row[0]
        if (query == "All Temple"):
           sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
                print sqlCreateStaticData
                cur2.execute(sqlCreateStaticData)
                conn2.commit()
                selectStatic = "select name, x, y, address, geom from church union select  name, x, y, address, geom from muslim ;"
                cur.execute(selectStatic)
                rows = cur.fetchall()
                for i in rows:
                    sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
                    print sqlInsertStaticData
                    cur2.execute(sqlInsertStaticData)
                    conn2.commit()

          conn2.close()

##        print query
##        selectStatic = "select name, x, y, address, geom from gov_hos union select  name, x, y, address, geom from bma_hos union select  name, x, y, address, geom from drug_clinic union select  name, x, y, address, geom from health_branch union select  name, x, y, address, geom from priv_hos;"
##        cur.execute(selectStatic)
##        rows = cur.fetchall()
##        for i in rows:
##
##            sqlInsertStaticData = "INSERT INTO \"" + query + "\"(display_name, longitude, latitude, name_area, geom)  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\';"
##            print sqlInsertStaticData
##        if (query == "All Hospital"):
##            sqlCreate = "CREATE TABLE  public.\""+ query + "\"(serial serial, name character varying(100), x double precision, y double precision, address character varying(300), geom geometry(Point,32647));"
##            sqlInsert = "INSERT INTO public.\"" + query + "\"(name, x, y, address, geom) select name, x, y, address, geom from gov_hos union select  name, x, y, address, geom from bma_hos union select  name, x, y, address, geom from drug_clinic union select  name, x, y, address, geom from health_branch union select  name, x, y, address, geom from health_center union select  name, x, y, address, geom from priv_hos;"
##        elif (query == "All School"):
##            sqlCreate = "CREATE TABLE  public.\""+ query + "\"(serial serial, name character varying(100), x double precision, y double precision, address character varying(300), geom geometry(Point,32647));"
##            sqlInsert = "INSERT INTO public.\"" + query + "\"(name, geom, address, x, y) select name, geom, address, x, y from bec_school union select  name, geom, address, x, y from bma_school  union select  name, geom, address, x, y from college union select  name, geom, address, x, y from private_school union select  name, geom, address, x, y from university;"
##        elif (query == "All Police station"):
##            sqlCreate = "CREATE TABLE  public.\""+ query + "\"(serial serial, name character varying(100), x double precision, y double precision, address character varying(300), geom geometry(Point,32647));"
##            sqlInsert = "INSERT INTO public.\"" + query + "\"(name, geom, address, x, y) select name, geom, address, x, y frompolice_station;"
##        elif (query == "All Fire station"):
##            sqlCreate = "CREATE TABLE  public.\""+ query + "\"(serial serial, name character varying(100), x double precision, y double precision, address character varying(300), geom geometry(Point,32647));"
##            sqlInsert = "INSERT INTO public.\"" + query + "\"(name, geom, address, x, y) select name, geom, address, x, y from fire_station;"
##        elif (query == "All Temple"):
##            sqlCreate = "CREATE TABLE  public.\""+ query + "\"(serial serial, name character varying(100), x double precision, y double precision, address character varying(300), geom geometry(Point,32647));"
##            sqlInsert = "INSERT INTO public.\"" + query + "\"(name, geom, address, x, y) select name, geom, address, x, y from church union select  name, geom, address, x, y from muslim ;"

##        cur.execute(sqlCreate)
##        conn.commit()
##        cur.execute(sqlInsert)
##        conn.commit()
##        conn.close()
        print "tttttttt"

##        query = row[0]
##        print query
##        cur.execute(query)
##        rows = cur.fetchall()
##        print rows
##        for row in rows:
##            print row

if __name__ == '__main__':
    main()
