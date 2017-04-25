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

import cgi
import cgitb; cgitb.enable()
import csv
import psycopg2
import os, sys
import uuid
from collections import deque
from checkStaticData import CheckStaticData

form = cgi.FieldStorage()
class AddStaticData:
##    def main():
##
##        global cur
##        global conn
##        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="172.20.10.2", port="5432")
##        print "Opened database successfully"
##        cur = conn.cursor()
##
##        global pathFile
##        pathFile = "C:/xampp/htdocs/GCaaS-3/file/PolygonForm.csv"
##
##        cur.execute('SELECT "deployment_Area" FROM public.table_deployment where "deploymentID" = 5;')
##        rows = cur.fetchone()
##        global geomArea
##        geomArea = rows[0]
##
##        ## get data from add point form
##    ##  for i in form.keys():
##    ##    if i == "name_area":
##    ##        name_area = form[i].value
##    ##    elif i == "latitude":
##    ##        latitude = form[i].value
##    ##    elif i == "longitude":
##    ##        longitude = form[i].value
##    ##    elif i == "display_name":
##    ##        display_name = form[i].value
##
##    ##    cur.execute('''CREATE TABLE  public.readCSV_point(serial serial, id integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(Point,4326));''')
##    ##    cur.execute('''CREATE TABLE  public.readCSV_line(serial serial, id integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(LINESTRING,4326));''')
##    ##    cur.execute('''CREATE TABLE  public.readCSV_polygon(serial serial, id integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(Polygon,4326));''')
##        conn.commit()
##        print "Create database successfully"
##        value = 'polygon'
##        if value == 'point':
##            id_="1"
##            name_area="Klang"
##            latitude="13.00535"
##            longitude="99.60449"
##            display_name="klang"
##    ##        addPoint(geomArea,pathFile)
##            addOnePoint(id_, geomArea, name_area, latitude, longitude, display_name)
##
##        elif value == 'line':
##            id_="1"
##            name_area = 'kleang'
##            long_lat = [['101.211931','13.281768'],['101.80014','13.98101']]
##            display_name = 'Kleang'
##            addOneLine(id_,geomArea,name_area,long_lat,display_name)
##    ##        addLine(geomArea,pathFile)
##        else:
##    ##        addPolygon(geomArea,pathFile)
##            id_="1"
##            name_area = 'kleang'
##            long_lat = [['99.4067','13.3423'],['99.6539','13.5506'],['99.8736','13.0531'],['99.4067','13.3423']]
##            display_name = 'Kleang'
##            addOnePolygon(id_,geomArea,name_area,long_lat,display_name)

    def addPoint(geomArea,pathFile):
        testSentData = CheckStaticData()
        dictData = testSentData.checkPoint(geomArea,pathFile)
        ans = "INSERT INTO public.readCSV_point(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + dictData['id'] + "\',\'" + dictData['name_area'] + "\',\'" + dictData['latitude'] + "\',\'" + dictData['longitude'] +  "\',\'" + dictData['display_name'] + "\'," + dictData['geomPoint']  + ");"
        cur.execute(ans)
        conn.commit()
        conn.close()
        print "Insert database successfully"

    def addLine(geomArea,pathFile):
        testSentData = CheckStaticData()
        dictData = testSentData.checkLine(geomArea,pathFile)
        print dictData
        for i in range(len( dictData['long_lat'])):
            ans = "INSERT INTO public.readCSV_line(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + dictData['id'] + "\',\'" + dictData['name_area'] + "\',\'" + dictData['long_lat'][i][1] + "\',\'" + dictData['long_lat'][i][0] +  "\',\'" + dictData['display_name'] + "\'," + dictData['geomLine']  + ");"
            print ans
            cur.execute(ans)
            conn.commit()
        conn.close()
        print "Insert database successfully"

    def addPolygon(geomArea,pathFile):
        testSentData = CheckStaticData()
        dictData = testSentData.checkPolygon(geomArea,pathFile)
        print dictData
        for i in range(len( dictData['long_lat'])):
            ans = "INSERT INTO public.readCSV_polygon(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + dictData['id'] + "\',\'" + dictData['name_area'] + "\',\'" + dictData['long_lat'][i][1] + "\',\'" + dictData['long_lat'][i][0] +  "\',\'" + dictData['display_name'] + "\'," + dictData['geomPolygon']  + ");"
            print ans
            cur.execute(ans)
            conn.commit()
        conn.close()
        print "Insert database successfully"


    def addOnePoint(id_,geomArea,name_area,latitude,longitude,display_name):
        geomPoint = "ST_GeomFromText('POINT(" + longitude + " " + latitude + ")',4326)"
        ans = "INSERT INTO public.readCSV_test2(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + id_ + "\',\'" + name_area + "\',\'" + latitude + "\',\'" + longitude +  "\',\'" + display_name + "\'," + geomPoint  + ");"
        print ans
        cur.execute(ans)
        conn.commit()
        conn.close()
        print "Insert database successfully"

    def addOneLine(id_,geomArea,name_area,long_lat,display_name):
        totalArea = geomArea
        geomLine = ''
        count = 1
        for i in range(len(long_lat)):
            if count == 1:
                geomLine = "ST_GeomFromText('LINESTRING(" + str(long_lat[i][0]) + " " + str(long_lat[i][1])
            else:
                geomLine = geomLine + "," + str(long_lat[i][0])  + " " + str(long_lat[i][1])
            count = count + 1

        geomLine = geomLine + ")',4326)"
        for i in range(len(long_lat)):
            ans = "INSERT INTO public.readCSV_line(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + id_ + "\',\'" + name_area + "\',\'" + long_lat[i][1] + "\',\'" + long_lat[i][0] +  "\',\'" + display_name + "\'," + geomLine  + ");"
            print ans
            cur.execute(ans)
            conn.commit()
        conn.close()
        print "Insert database successfully"


    def addOnePolygon(id_,geomArea,name_area,long_lat,display_name):
        totalArea = geomArea
        geomPolygon = ''
        count = 1
        for i in range(len(long_lat)):
            if count == 1:
                geomPolygon = "ST_GeomFromText('POLYGON((" + str(long_lat[i][0]) + " " + str(long_lat[i][1])
            else:
                geomPolygon = geomPolygon + "," + str(long_lat[i][0])  + " " + str(long_lat[i][1])
            count = count + 1
        geomPolygon = geomPolygon + "))',4326)"
        for i in range(len(long_lat)):
            ans = "INSERT INTO public.readCSV_polygon(id, name_area, latitude, longitude, display_name, geom) VALUES(\'"  + id_ + "\',\'" + name_area + "\',\'" + long_lat[i][1] + "\',\'" + long_lat[i][0] +  "\',\'" + display_name + "\'," + geomPolygon  + ");"
            print ans
            cur.execute(ans)
            conn.commit()
        conn.close()
        print "Insert database successfully"

