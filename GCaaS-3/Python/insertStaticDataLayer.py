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
import os, sys
import uuid
from collections import deque
import cgi
import cgitb; cgitb.enable()
import json
try:
  import simplejson as json
except:
  import json
form = cgi.FieldStorage()


class InsertStaticDataLayer:
    global conn
    global cur

    @staticmethod
    def insertPoint(dataDic,cur,conn,staticName):
        if(staticName == 'Hospital' or staticName == 'School' or staticName == 'Temple' or staticName == 'Fire station'):
            staticName = "All " + staticName
            sqlSearchStaticName = "SELECT \"staticDataLayer_Name\" FROM table_staticDataLayer_deployment where \"staticDataLayer_Name\" = '" + staticName + "';"
            cur.execute(sqlSearchStaticName)
            row = cur.fetchone()

            if row is None:
               sqlCreateStaticData = "CREATE TABLE  \""+ staticName + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
               cur.execute(sqlCreateStaticData)
               conn.commit()
               sqlInsertStaticList = "INSERT INTO table_staticdatalayer_deployment(\"deployment_Name\", \"staticDataLayer_Name\") VALUES (\'" + dataDic['listItem'][0]['depname'] +"\',\'" + staticName + "\');"
               cur.execute(sqlInsertStaticList)
               conn.commit()
               conn2 = psycopg2.connect(database="GCaaS" , user="postgres", password="1234", host="localhost", port="5432")
               cur2 = conn2.cursor()
               selectStatic = "select \"display_name\", ST_X(ST_Transform(ST_GeomFromText(ST_AsText(geom),32647),4326)) , ST_Y(ST_Transform(ST_GeomFromText(ST_AsText(geom),32647),4326)), \"name_area\", \"geom\" from \"" + staticName +"\";"
               cur2.execute(selectStatic)
               rows2 = cur2.fetchall()
               count = 0
               for r2 in rows2:
                    sqlInsertStaticData = "INSERT INTO \"" + str(staticName) + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + r2[0] + "\' , \'" + str(r2[1]) +  "\' , \'" + str(r2[2]) +  "\' , \'" + r2[3] +  "\' , \'" + r2[4] + "\');"
                    cur.execute(sqlInsertStaticData)
               conn.commit()

            for i in dataDic['listItem']:
                tempDisplayName = i['display_name']
                if not isinstance(tempDisplayName, unicode):
                    tempDisplayName = str(tempDisplayName)

                ans = "INSERT INTO public.\"" + str(staticName) + "\" (name_area, latitude, longitude, display_name, geom) VALUES(\'" + str(i['name_area']) + "\',\'" + str(i['latitude']) + "\',\'" + str(i['longitude']) +  "\',\'" + tempDisplayName + "\', ST_Transform(" +str(i['geom']) + ", 32647) );"

                cur.execute(ans)
            conn.commit()
            conn.close()

        else:
            for i in dataDic['listItem']:
                tempDisplayName = i['display_name']
                if not isinstance(tempDisplayName, unicode):
                    tempDisplayName = str(tempDisplayName)

                ans = "INSERT INTO public." + str(staticName) + "(id, name_area, latitude, longitude, display_name, geom) VALUES(\'" + str(i['id']) + "\',\'" + i['name_area'] + "\',\'" + str(i['latitude']) + "\',\'" + str(i['longitude']) +  "\',\'" + tempDisplayName + "\'," + str(i['geom'])  + ");"
                print ans
                cur.execute(ans)
            conn.commit()
            conn.close()
        print "Insert database successfully"

    @staticmethod
    def insertLine(dataDic,cur,conn,staticName):


        for i in dataDic['listItem']:
            for j in range(len( i['long_lat'])):
                ans = "INSERT INTO public." + str(staticName) + "(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + i['id'] + "\',\'" + i['name_area'] + "\',\'" + i['long_lat'][j][1] + "\',\'" + i['long_lat'][j][0] +  "\',\'" + i['display_name'] + "\'," + i['geom']  + ");"
                cur.execute(ans)
            conn.commit()
        conn.close()
        print "Insert database successfully"

    @staticmethod
    def insertPolygon(dataDic,cur,conn,staticName):

        for i in dataDic['listItem']:
            for j in range(len( i['long_lat'])):
                ans = "INSERT INTO public." + str(staticName) + "(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + i['id'] + "\',\'" + i['name_area'] + "\',\'" + i['long_lat'][j][1] + "\',\'" + i['long_lat'][j][0] +  "\',\'" + i['display_name'] + "\'," + i['geom']  + ");"
                cur.execute(ans)
            conn.commit()
        conn.close()

        print "Insert database successfully"

def main():

    print "Content-type: text/html\n"
    print "\n\n"

    for i in form.keys():
        if i == "dataDic":
            dataToInsert = form[i].value

    dataDic = json.loads(dataToInsert)
    dataLayerType = dataDic['listItem'][0]['dataLayerType']
    staticName = dataDic['listItem'][0]['staticName']

    nameDB = 'DB_' + dataDic['listItem'][0]['depname']
    conn = psycopg2.connect(database=nameDB , user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()

    if dataLayerType == 'point':
        InsertStaticDataLayer.insertPoint(dataDic,cur,conn,staticName)
    elif dataLayerType == 'line':
        InsertStaticDataLayer.insertLine(dataDic,cur,conn,staticName)
    else:
        InsertStaticDataLayer.insertPolygon(dataDic,cur,conn,staticName)

if __name__ == '__main__':
    main()
