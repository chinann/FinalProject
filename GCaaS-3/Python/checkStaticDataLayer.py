#!/Python27/python.exe
# -*- coding: utf-8 -*-

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
import types
import cgi
import cgitb; cgitb.enable()
import psycopg2
import json
try:
  import simplejson as json
except:
  import json
form = cgi.FieldStorage()


class CheckStaticDataLayer:
    global conn
    conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
    global cur
    cur = conn.cursor()

    @staticmethod
    def get_last_row(csv_filename):
        with open(csv_filename, 'r') as f:
            try:
                lastrow = deque(csv.reader(f), 1)[0]
            except IndexError:  # empty file
                lastrow = None
            return lastrow

    @staticmethod
    def checkStaticDataLayer(staticType,inputType,depname,staticName):
        sqlSelectGeomArea = "SELECT \"deployment_Area\" FROM public.table_deployment where \"deployment_Name\" = \'" + depname +  "\';"
##        print sqlSelectGeomArea
        cur.execute(sqlSelectGeomArea)
        rows = cur.fetchone()
        geomArea = rows[0]

        ## add point from web
        if type(inputType) == types.DictType and staticType == 'point':
            inputDataLayer = inputType
            dictData = CheckStaticDataLayer.checkOnePoint(geomArea,inputDataLayer,cur)
            print dictData
##            json_string = json.dumps(dictData)
##            print json_string

        ## add point from csv file
        elif type(inputType) != types.DictType and staticType == 'point':
            pathFile = inputType
            arrayData = CheckStaticDataLayer.checkPoint(geomArea,pathFile,cur,staticType,depname,staticName)
            json_string = json.dumps(arrayData)
            print json_string

        ## add line from web
        elif type(inputType) == types.DictType and staticType == 'line':
             print 'test'
             inputDataLayer = inputType
             dictData = CheckStaticDataLayer.checkOneLine(geomArea,inputDataLayer,cur)
             print dictData

        ## add line from csv file
        elif type(inputType) != types.DictType and staticType == 'line':
            pathFile = inputType
            arrayData = CheckStaticDataLayer.checkLine(geomArea,pathFile,cur,staticType,depname,staticName)
            json_string = json.dumps(arrayData)
            print json_string

        ## add polygon from web
        elif type(inputType) == types.DictType and staticType == 'polygon':
             print 'test'
             inputDataLayer = inputType
             dictData = CheckStaticDataLayer.checkOnePolygon(geomArea,inputDataLayer,cur)
             print dictData

        ## add Polygon from csv file
        elif type(inputType) != types.DictType and staticType == 'polygon':
            pathFile = inputType
            arrayData = CheckStaticDataLayer.checkPolygon(geomArea,pathFile,cur,staticType,depname,staticName)
            json_string = json.dumps(arrayData)
            print json_string

    @staticmethod
    def checkPoint(geomArea,pathFile,cur,staticType,depname,staticName):
        arrayDataTrue = []
        arrayDataFalse = []
        dictData = {}
        with open(pathFile, 'rb') as csvfile:
            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')

            for row in spamreader:
                if row[0][:2] != 'ID':
                    x = row[0].split(",")
                    dictData = {"id":x[0],"name_area":x[1], "latitude":x[2] ,"longitude":x[3] ,"display_name":x[4],"status": "unverified" , "dataLayerType": staticType, "depname": depname , "staticName": staticName }
                    checkReturn = CheckStaticDataLayer.checkOnePoint(geomArea,dictData,cur)
                    if checkReturn != 'FALSE':
                        geomPoint = checkReturn
                        dictData.update({"geom":geomPoint})
                        dictData.update({"status": 'true'})
                        arrayDataTrue.append(dictData.copy())
                    else:
                        dictData.update({"status": 'false'})
                        arrayDataFalse.append(dictData.copy())

        if arrayDataFalse != []:
            return arrayDataFalse
        else:
            return arrayDataTrue

    @staticmethod
    def checkLine(geomArea,pathFile,cur,staticType,depname,staticName):
        lastrow = CheckStaticDataLayer.get_last_row(pathFile)
        arrayDataTrue = []
        arrayDataFalse = []
        count = 1
        numLatLong = 0
        geomLine = ""
        long_lat = []
        with open(pathFile, 'rb') as csvfile:
            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')

            for row in spamreader:
                if row[0][:2] != 'ID':
                    x = row[0].split(",")
                    if count == 1:
                        checkLineID = x[0]
                        checkName = x[1]
                    if x[0] == checkLineID and x[1] == checkName:
                        dictData = {"id":x[0],"name_area":x[1], "display_name":x[4], "status": 'unverified', "dataLayerType": staticType , "depname": depname, "staticName": staticName}
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])
                    else:
                        dictData.update({"long_lat": long_lat})
                        checkReturn = CheckStaticDataLayer.checkOneLine(geomArea,dictData,cur)
                        if checkReturn == "FALSE":
                            dictData.update({"status": "false"})
                            arrayDataFalse.append(dictData.copy())
                        else:
                            geomLine = checkReturn
                            dictData.update({"geom": geomLine})
                            dictData.update({"status": "true"})
                            arrayDataTrue.append(dictData.copy())

                        long_lat= []
                        dictData = {}
                        count = 0
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])

                    if lastrow[3] == x[3]:
                        dictData.update({"long_lat": long_lat})
                        checkReturn = CheckStaticDataLayer.checkOneLine(geomArea,dictData,cur)
                        if checkReturn != "FALSE":
                            geomLine = checkReturn
                            dictData.update({"geom": geomLine})
                            dictData.update({"status": "true"})
                            arrayDataTrue.append(dictData.copy())
                        else:
                            dictData.update({"status": "false"})
                            arrayDataFalse.append(dictData.copy())

                    checkLineID = x[0]
                    checkName = x[1]
                    count = count + 1

        if arrayDataFalse != []:
            return arrayDataFalse
        else:
            return arrayDataTrue

    @staticmethod
    def checkPolygon(geomArea,pathFile,cur,staticType,depname,staticName):
        arrayDataTrue = []
        arrayDataFalse = []
        lastrow = CheckStaticDataLayer.get_last_row(pathFile)
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
                        dictData = {"id":x[0],"name_area":x[1], "display_name":x[4], "status": "unverified",  "dataLayerType": staticType, "depname": depname , "staticName": staticName}
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])
                    else :
                        checkReturn = CheckStaticDataLayer.checkOnePolygon(geomArea,dictData,cur)
                        if checkReturn == "FALSE":
                            dictData.update({"status": "false"})
                            arrayDataFalse.append(dictData.copy())
                        else:
                            geomPolygon = checkReturn
                            dictData.update({"geom": geomPolygon})
                            dictData.update({"status": "true"})
                            arrayDataTrue.append(dictData.copy())

                        dictData = {}
                        long_lat= []
                        lat = str(x[2])
                        longL = str(x[3])
                        long_lat.append([longL, lat])
                        count = 0

                    if lastrow[3] == x[3] and count != 1:
                        dictData.update({"long_lat": long_lat})
                        checkReturn=CheckStaticDataLayer.checkOnePolygon(geomArea,dictData,cur)
                        if checkReturn != "FALSE":
                            geomPolygon = checkReturn
                            dictData.update({"geom": geomPolygon})
                            dictData.update({"status": "true"})
                            arrayDataTrue.append(dictData.copy())
                        else:
                            dictData.update({"status": "false"})
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
        id_ = inputDataLayer["id"]
        name_area = inputDataLayer["name_area"]
        latitude = inputDataLayer["latitude"]
        longitude = inputDataLayer["longitude"]
        display_name = inputDataLayer["display_name"]
        geomPoint = "ST_GeomFromText('POINT(" + str(longitude) + " " + str(latitude) + ")',4326)"
        querySelectIntersect = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomPoint + ");"
##        print querySelectIntersect
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
        id_ = inputDataLayer["id"]
        name_area = inputDataLayer["name_area"]
        long_lat = inputDataLayer["long_lat"]
        display_name = inputDataLayer["display_name"]
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
        cur.execute(querySelectIntersect)
        rows = cur.fetchone()
        geomArea2 = rows[0]
        if geomArea2 == True:
            return geomLine
        else:
            return "FALSE"

    @staticmethod
    def checkOnePolygon(geomArea,inputDataLayer,cur):
        id_ = inputDataLayer["id"]
        name_area = inputDataLayer["name_area"]
        long_lat = inputDataLayer["long_lat"]
        display_name = inputDataLayer["display_name"]
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
        querySelectIntersect = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomPolygon + ");"
        cur.execute(querySelectIntersect)
        rows = cur.fetchone()
        geomArea2 = rows[0]
        if geomArea2 == True:
            return geomPolygon
        else:
            return "FALSE"


def main():

    print "Content-type: text/html\n"
    print "\n\n"
    staticType = None
    pathFile = None
    inputType = None
    depname = None
    staticName=None

    for i in form.keys():
        if i == "pathFile":
            pathFile = form[i].value
        elif  i == "statictype":
            staticType = form[i].value
        elif i == "inputType":
            inputType = form[i].value
        elif i == "depname":
            depname = form[i].value
        elif i == "staticName":
            staticName = form[i].value

    if inputType == "CSVFile":
        pathFile = "../" + pathFile
        CheckStaticDataLayer.checkStaticDataLayer(staticType,pathFile,depname,staticName)
    else:
        dataAdd = json.loads(inputType)
        CheckStaticDataLayer.checkStaticDataLayer(staticType,dataAdd,depname,staticName)



if __name__ == '__main__':
    main()

