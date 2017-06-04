#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     29/03/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import os, sys
import uuid
from collections import deque
import types
import cgi
import cgitb; cgitb.enable()
import json
try:
  import simplejson as json
except:
  import json
form = cgi.FieldStorage()


class AddStaticDataLayer:
    global conn
    global cur

    @staticmethod
    def addPoint(dataDic,cur,conn,staticName):


        sqlAdd = "CREATE TABLE  public."+ str(staticName) + "(serial serial, id integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(Point,4326));"
        cur.execute(sqlAdd)
        conn.commit()
        print "create Deployment success"
        for i in dataDic['listItem']:
            ans = "INSERT INTO public." + str(staticName) + "(id, name_area, latitude, longitude, display_name, geom) VALUES(\'" + str(i['id']) + "\',\'" + i['name_area'] + "\',\'" + str(i['latitude']) + "\',\'" + str(i['longitude']) +  "\',\'" + i['display_name'] + "\'," + str(i['geom'])  + ");"
            cur.execute(ans)
        conn.commit()
        insertStaticList = "INSERT INTO public.table_staticDataLayer_deployment(\"staticDataLayer_Name\", \"deployment_Name\") VALUES ( \'"+ str(staticName) + "\',\'" +  dataDic['listItem'][0]['depname'] +"\');"
        print insertStaticList
        cur.execute(insertStaticList)
        conn.commit()
        conn.close()
        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
        cur = conn.cursor()
        sqlStaticLayer = "\'SELECT display_name, longitude, latitude, name_area FROM " + staticName + ";\'"
        insertStaticDBSQL = 'INSERT INTO public.\"table_staticDataLayer\"(\"staticDataLayer_Name\", \"staticDataLayer_sql\", \"deployment_Name\") VALUES ( \''+ str(staticName) + "\'," + sqlStaticLayer + ",\'" + dataDic['listItem'][0]['depname'] +"\');"
        cur.execute(insertStaticDBSQL)
        conn.commit()
        conn.close()
        print "Insert database successfully"

    @staticmethod
    def addLine(dataDic,cur,conn,staticName):
        sqlAdd = "CREATE TABLE  public." + str(staticName) + "(serial serial, id integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(LINESTRING,4326));"
        print
        cur.execute(sqlAdd)

        for i in dataDic['listItem']:
            for j in range(len( i['long_lat'])):
                ans = "INSERT INTO public." + str(staticName) + "(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + i['id'] + "\',\'" + i['name_area'] + "\',\'" + i['long_lat'][j][1] + "\',\'" + i['long_lat'][j][0] +  "\',\'" + i['display_name'] + "\'," + i['geom']  + ");"
                cur.execute(ans)
            conn.commit()
        conn.close()
        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
        cur = conn.cursor()
        sqlStaticLayer = "\'SELECT display_name, longitude, latitude, name_area FROM " + staticName + ";\'"
        insertStaticDBSQL = 'INSERT INTO public.\"table_staticDataLayer\"(\"staticDataLayer_Name\", \"staticDataLayer_sql\", \"deployment_Name\") VALUES ( \''+ str(staticName) + "\'," + sqlStaticLayer + ",\'" + dataDic['listItem'][0]['depname'] +"\');"
        cur.execute(insertStaticDBSQL)
        conn.commit()
        conn.close()
        print "Insert database successfully"

    @staticmethod
    def addPolygon(dataDic,cur,conn,staticName):
        sqlAdd = "CREATE TABLE  public." + str(staticName) + "(serial serial, id integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(Polygon,4326));"
        cur.execute(sqlAdd)
        for i in dataDic['listItem']:
            for j in range(len( i['long_lat'])):
                ans = "INSERT INTO public." + str(staticName) + "(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + i['id'] + "\',\'" + i['name_area'] + "\',\'" + i['long_lat'][j][1] + "\',\'" + i['long_lat'][j][0] +  "\',\'" + i['display_name'] + "\'," + i['geom']  + ");"
                cur.execute(ans)
            conn.commit()
        conn.close()
        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
        cur = conn.cursor()
        sqlStaticLayer = "\'SELECT display_name, longitude, latitude, name_area FROM " + staticName + ";\'"
        insertStaticDBSQL = 'INSERT INTO public.\"table_staticDataLayer\"(\"staticDataLayer_Name\", \"staticDataLayer_sql\", \"deployment_Name\") VALUES ( \''+ str(staticName) + "\'," + sqlStaticLayer + ",\'" + dataDic['listItem'][0]['depname'] +"\');"
        cur.execute(insertStaticDBSQL)
        conn.commit()
        conn.close()
        print "Insert database successfully"

def main():

    print "Content-type: text/html\n"
    print "\n\n"

    for i in form.keys():
        if i == "dataDic":
            dataToAdd = form[i].value

    dataDic = json.loads(dataToAdd)
    dataLayerType = dataDic['listItem'][0]['dataLayerType']
    staticName = dataDic['listItem'][0]['staticName']
    nameDB = 'DB_' + dataDic['listItem'][0]['depname']
    conn = psycopg2.connect(database=nameDB , user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()
    if(staticName == 'Hospital' or staticName == 'School' or staticName == 'Temple' or staticName == 'Fire station'):
        staticName = "All " + staticName

    sqlSearchStaticName = "SELECT \"staticDataLayer_Name\" FROM table_staticDataLayer_deployment where \"staticDataLayer_Name\" = '" + staticName + "';"
##    print sqlSearchStaticName
    cur.execute(sqlSearchStaticName)
    row = cur.fetchone()
    print row
    if row is not None:
        for r in row:
            print r
            if (r == staticName):
                sqlDel = "DROP TABLE " + staticName + " ;"
                print sqlDel
                cur.execute(sqlDel)
            else:
                print 1

    conn.commit()
    if dataLayerType == 'point':
        AddStaticDataLayer.addPoint(dataDic,cur,conn,staticName)
    elif dataLayerType == 'line':
        AddStaticDataLayer.addLine(dataDic,cur,conn,staticName)
    else:
        AddStaticDataLayer.addPolygon(dataDic,cur,conn,staticName)


if __name__ == '__main__':
    main()