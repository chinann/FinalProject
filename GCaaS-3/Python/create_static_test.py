#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     02/06/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import os, sys
import cgi
import cgitb; cgitb.enable()
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT
from datetime import datetime

def main():

    conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
    conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)
    cur = conn.cursor()


    querySelect =  "SELECT \"staticDataLayer_Name\" FROM public.\"table_staticDataLayer\" where \"staticID\" < 6;"
    cur.execute(querySelect)
    rows = cur.fetchall()

    for row in rows:
        query = row[0]
        if (query == "All Police station"):
            sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
            print sqlCreateStaticData
            cur.execute(sqlCreateStaticData)
            conn.commit()
##            selectStatic = "select name, x, y, address, geom from police_station;"
##            cur.execute(selectStatic)
##            xx = cur.fetchall()
##            for i in xx:
##                sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'\' , \'\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                cur.execute(sqlInsertStaticData)
##                conn.commit()
##        elif (query == "All Temple"):
##            sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##            cur.execute(sqlCreateStaticData)
##            conn.commit()
##            selectStatic = "select name, x, y, address, geom from church union select  name, x, y, address, geom from muslim ;"
##            cur.execute(selectStatic)
##            rows = cur.fetchall()
##            for i in rows:
##                if i[3] == None:
##                    sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'\' , \'" + str(i[4]) + "\');"
##                else:
##                    sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                cur.execute(sqlInsertStaticData)
##                conn.commit()
##        elif (query == "All School"):
##            sqlCreateStaticData = "CREATE TABLE \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##            cur.execute(sqlCreateStaticData)
##            conn.commit()
##            selectStatic = "select name, x, y, address, geom from bec_school union select  name, x, y, address, geom from bma_school  union select  name, x, y, address, geom from college union select  name, x, y, address, geom from private_school union select  name, x, y, address, geom from university;"
##            cur.execute(selectStatic)
##            xx = cur.fetchall()
##            for i in xx:
##                sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                cur.execute(sqlInsertStaticData)
##                conn.commit()
##                elif (query == "All Police station"):
##                    sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##                    cur2.execute(sqlCreateStaticData)
##                    conn2.commit()
##                    selectStatic = "select name, x, y, address, geom from police_station;"
##                    cur.execute(selectStatic)
##                    xx = cur.fetchall()
##                    for i in xx:
##                        sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                        cur2.execute(sqlInsertStaticData)
##                        conn2.commit()
##        elif (query == "All Fire station"):
##            sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##            cur.execute(sqlCreateStaticData)
##            conn.commit()
##            selectStatic = "select name, x, y, address, geom from fire_station;"
##            cur.execute(selectStatic)
##            xx = cur.fetchall()
##            for i in xx:
##                sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                cur.execute(sqlInsertStaticData)
##                conn.commit()

        conn.close()

if __name__ == '__main__':
    main()
