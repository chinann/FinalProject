#-------------------------------------------------------------------------------
# Name:        module2
# Purpose:
#
# Author:      chinan
#
# Created:     02/03/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import csv
import psycopg2
import os, sys
import uuid
from collections import deque

class CheckStaticData:
##
##    @staticmethod
##    def test():
##        global conn
##        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
##        print "Opened database successfully"
##        global cur
##        cur = conn.cursor()
##        cur.execute('SELECT "deployment_Area" FROM public.table_deployment where "deploymentID" = 5;')
##        rows = cur.fetchone()
##        geomArea = rows[0]

    ## get data from add point form
    ##  for i in form.keys():
    ##    if i == "name_area":
    ##        name_area = form[i].value
    ##    elif i == "latitude":
    ##        latitude = form[i].value
    ##    elif i == "longitude":
    ##        longitude = form[i].value
    ##    elif i == "display_name":
    ##        display_name = form[i].value

    ##     get statictype
    ##    value = form.getvalue('statictype')
##
##        if value == 'point':
##            CheckStaticData.checkPoint(geomArea,pathFile)
##    ##    name_area="Klang"
##    ##    latitude="13.00535"
##    ##    longitude="99.60449"
##    ##    display_name="klang"
##    ##        checkOnePoint(geomArea,name_area,latitude,longitude,display_name)
##        elif value == 'line':
##    ##        name_area = 'kleang'
##    ##        long_lat = [['101.211931','13.281768'],['101.80014','13.98101']]
##    ##        display_name = 'Kleang'
##    ##        checkOneLine(geomArea,name_area,long_lat,display_name)
##            CheckStaticData.checkLine(geomArea,pathFile)
##        else:
##    ##        name_area = 'kleang'
##    ##        long_lat = [['99.4067','13.3423'],['99.6539','13.5506'],['99.8736','13.0531'],['99.4067','13.3423']]
##    ##        display_name = 'Kleang'
##    ##        checkOnePolygon(geomArea,name_area,long_lat,display_name)
##            CheckStaticData.checkPolygon(geomArea,pathFile)

    @staticmethod
    def get_last_row(csv_filename):
        with open(csv_filename, 'r') as f:
            try:
                lastrow = deque(csv.reader(f), 1)[0]
            except IndexError:  # empty file
                lastrow = None
            return lastrow

    @staticmethod
    def checkPoint(geomArea,pathFile,cur):
        arrayDataTrue = []
        arrayDataFalse = []
        dictData = {}
        with open(pathFile, 'rb') as csvfile:
            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')

            for row in spamreader:
                if row[0][:2] != 'ID':
                    x = row[0].split(",")
                    dictData = {'id':x[0],'name_area':x[1], 'latitude':x[2] ,'longitude':x[3] ,'display_name':x[4],'status': 'none'}
                    checkReturn = CheckStaticData.checkOnePoint(geomArea,dictData,cur)
                    if checkReturn != 'FALSE':
                        geomPoint = checkReturn
                        dictData.update({'geomPoint':geomPoint})
                        dictData.update({'status': 'true'})
                        arrayDataTrue.append(dictData.copy())
##                        print "arrayDataTrue: "
##                        for i in arrayDataTrue:
##                            print i + '\n'

                    else:
                        arrayDataFalse.append(dictData.copy())
##                        print "arrayDataFalse: "
##                        for i in arrayDataFalse:
##                            print i + '\n'

##        print arrayData
        if arrayDataFalse != []:
            return arrayDataFalse
        else:
            return arrayDataTrue

    @staticmethod
    def checkLine(geomArea,pathFile,cur):
        lastrow = CheckStaticData.get_last_row(pathFile)
        arrayDataTrue = []
        arrayDataFalse = []
        count = 1
        numLatLong = 0
        geomLine = ""
        long_lat = []
##        i=0
        with open(pathFile, 'rb') as csvfile:
            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')

            for row in spamreader:
                if row[0][:2] != 'ID':
                    x = row[0].split(",")
                    if count == 1:
                        checkLineID = x[0]
                        checkName = x[1]

                    if x[0] == checkLineID and x[1] == checkName:
                        dictData = {'id':x[0],'name_area':x[1], 'display_name':x[4], 'status': 'none'}
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])
                    else:
                        dictData.update({'long_lat': long_lat})
                        checkReturn = CheckStaticData.checkOneLine(geomArea,dictData,cur)
                        print ""
                        if checkReturn == "FALSE":
                            dictData.update({'status': 'false'})
                            arrayDataFalse.append(dictData.copy())
                        else:
                            geomLine = checkReturn
                            dictData.update({'geomLine': geomLine})
                            dictData.update({'status': 'true'})
                            arrayDataTrue.append(dictData.copy())

                        long_lat= []
                        dictData = {}
                        count = 0
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])

                    if lastrow[3] == x[3]:
                        dictData.update({'long_lat': long_lat})
                        checkReturn = CheckStaticData.checkOneLine(geomArea,dictData,cur)
                        if checkReturn != "FALSE":
                            geomLine = checkReturn
                            dictData.update({'geomLine': geomLine})
                            dictData.update({'status': 'true'})
                            arrayDataTrue.append(dictData.copy())
                        else:
                            dictData.update({'status': 'false'})
                            arrayDataFalse.append(dictData.copy())

                    checkLineID = x[0]
                    checkName = x[1]
                    count = count + 1


        if arrayDataFalse != []:
            return arrayDataFalse
        else:
            return arrayDataTrue

    @staticmethod
    def checkPolygon(geomArea,pathFile,cur):
        arrayDataTrue = []
        arrayDataFalse = []
        lastrow = CheckStaticData.get_last_row(pathFile)
        count = 1
        numLatLong = 0
        i = 0
        long_lat = []
        geomPolygon = ""
        with open(pathFile, 'rb') as csvfile:
            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')
            for row in spamreader:
                if row[0][:2] != 'ID':
                    x = row[0].split(",")
                    if count == 1:
                        checkPolygonID = x[0]
                        checkName = x[1]

                    if x[0] == checkPolygonID and x[1] == checkName:
                        dictData = {'id':x[0],'name_area':x[1], 'display_name':x[4], 'status': 'none'}
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])
                    else :
                        checkReturn = CheckStaticData.checkOnePolygon(geomArea,x[1],long_lat,x[4],cur)
                        if checkReturn == "FALSE":
                            dictData.update({'status': 'false'})
                            arrayDataFalse.append(dictData.copy())
                        else:
                            geomPolygon = checkReturn
                            dictData.update({'geomPolygon': geomPolygon})
                            dictData.update({'status': 'true'})
                            arrayDataTrue.append(dictData.copy())

                        dictData = {}
                        long_lat= []
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])
                        count = 0

                    if lastrow[3] == x[3] and count != 0:
                        dictData.update({'long_lat': long_lat})
                        checkReturn=CheckStaticData.checkOnePolygon(geomArea,x[1],long_lat,x[4],cur)
                        if checkReturn != "FALSE":
                            geomPolygon = checkReturn
                            dictData.update({'geomPolygon': geomPolygon})
                            dictData.update({'status': 'true'})
                            arrayDataTrue.append(dictData.copy())
                        else:
                            dictData.update({'status': 'false'})
                            arrayDataFalse.append(dictData.copy())

                    checkLineID = x[0]
                    checkName = x[1]
                    count = count + 1
                    i = i+1
        if arrayDataFalse != []:
            return arrayDataFalse
        else:
            return arrayDataTrue


    @staticmethod
    def checkOnePoint(geomArea,inputDataLayer,cur):
        id_ = inputDataLayer['id']
        name_area = inputDataLayer['name_area']
        latitude = inputDataLayer['latitude']
        longitude = inputDataLayer['longitude']
        display_name = inputDataLayer['display_name']
        geomPoint = "ST_GeomFromText('POINT(" + longitude + " " + latitude + ")',4326)"
##        print geomPoint
        querySelectIntersect = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomPoint + ");"
##        print querySelectIntersect
    ##    print "AAA: " +querySelectIntersect
        cur.execute(querySelectIntersect)
        rows = cur.fetchone()
        geomArea2 = rows[0]
        if geomArea2 == True:
##            print "TRUE"
            return geomPoint
        else:
##            print "FALSE"
            return "FALSE"

    @staticmethod
    def checkOneLine(geomArea,inputDataLayer,cur):
        id_ = inputDataLayer['id']
        name_area = inputDataLayer['name_area']
        long_lat = inputDataLayer['long_lat']
        display_name = inputDataLayer['display_name']
        geomLine = ''
        count = 1
        if len(long_lat) >= 2:
            for i in range(len(long_lat)):
                if count == 1:
                    geomLine = "ST_GeomFromText('LINESTRING(" + str(long_lat[i][0]) + " " + str(long_lat[i][1])
                else:
                    geomLine = geomLine + "," + str(long_lat[i][0])  + " " + str(long_lat[i][1])
                count = count + 1
        else:
            print 'ERROR'

        geomLine = geomLine + ")',4326)"
        querySelectIntersect = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomLine + ");"
##        print querySelectIntersect
        cur.execute(querySelectIntersect)
        rows = cur.fetchone()
        geomArea2 = rows[0]
        if geomArea2 == True:
            return geomLine
        else:
            return "FALSE"

    @staticmethod
    def checkOnePolygon(geomArea,inputDataLayer,cur):
        id_ = inputDataLayer['id']
        name_area = inputDataLayer['name_area']
        long_lat = inputDataLayer['long_lat']
        display_name = inputDataLayer['display_name']
        geomPolygon = ''
        count = 1
        if len(long_lat) >= 2 and long_lat[0][0]== long_lat[len(long_lat)-1][0] and long_lat[0][1]== long_lat[len(long_lat)-1][1]   :
            for i in range(len(long_lat)):
                if count == 1:
                    geomPolygon = "ST_GeomFromText('POLYGON((" + str(long_lat[i][0]) + " " + str(long_lat[i][1])
                else:
                    geomPolygon = geomPolygon + "," + str(long_lat[i][0])  + " " + str(long_lat[i][1])
                count = count + 1
        else:
            print 'ERROR'

        geomPolygon = geomPolygon + "))',4326)"
    ##    print geomPolygon
        querySelectIntersect = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomPolygon + ");"
        print querySelectIntersect
        cur.execute(querySelectIntersect)
        rows = cur.fetchone()
        geomArea2 = rows[0]
        if geomArea2 == True:
            return geomPolygon

        else:
            return "FALSE"


##pathFile = "C:/xampp/htdocs/GCaaS-3/file/PointForm.csv"
##name_area="Klang"
##latitude="13.00535"
##longitude="99.60449"
##display_name="klang"
##
##CheckStaticData.test()
##testSentData.checkOnePoint(geomArea,name_area,latitude,longitude,display_name)
##testSentData.checkPoint(geomArea,pathFile)
