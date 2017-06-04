#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     14/04/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------


import types
import cgi
import cgitb; cgitb.enable()
import csv
import psycopg2
import os, sys
import uuid
import json
#from collections import deque
from checkStaticData import CheckStaticData
try:
  import simplejson as json
except:
  import json
form = cgi.FieldStorage()

class StaticDataLayer:

    @staticmethod
    def setTypeData(typeData):
        self.typeData = typeData

    @staticmethod
    def checkStaticDataLayer(typeData,typeInput):

        global conn
        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
        global cur
        cur = conn.cursor()
        cur.execute('SELECT "deployment_Area" FROM public.table_deployment where "deploymentID" = 5;')
        rows = cur.fetchone()
        geomArea = rows[0]

        ## add point from csv file
        if type(typeInput) == types.DictType and typeData == 'point':
            print 'test'
            inputDataLayer = typeInput
            dictData = testSentData.checkOnePoint(geomArea,inputDataLayer,cur)
            json_string = json.dumps(dictData)
            print json_string

        ## add point from web
        elif type(typeInput) != types.DictType and typeData == 'point':
            pathFile = typeInput
            arrayData = CheckStaticData.checkPoint(geomArea,pathFile,cur)
##            print arrayData
            json_string = json.dumps(arrayData)
            print json_string
##
        elif type(typeInput) == types.DictType and typeData == 'line':
             print 'test'
             inputDataLayer = typeInput
             dictData = testSentData.checkOneLine(geomArea,inputDataLayer,cur)
             json_string = json.dumps(dictData)
             print json_string

        elif type(typeInput) != types.DictType and typeData == 'line':
            pathFile = typeInput
            arrayData = CheckStaticData.checkLine(geomArea,pathFile,cur)
            json_string = json.dumps(arrayData)
##            print arrayData
            print json_string

##
##
##        elif type(typeInput) == types.DictType and typeData == 'polygon':
##            print 'test'
####            dictData = testSentData.checkOnePolygon(typeInput)
##        elif type(typeInput) != types.DictType and typeData == 'polygon':
##            pathFile = typeInput
##            arrayData = CheckStaticData.checkPolygon(geomArea,pathFile)
##
##
####        if typeData == 'point' and typeInput == 'csvFile':
####            dictData = testSentData.checkPoint(geomArea,pathFile)
######        elif typeData == 'point' and typeInput == 'fromWeb':
######            dictData = testSentData.checkOnePoint(geomArea,pathFile)
####        elif typeData == 'line' and typeInput == 'csvFile':
####            dictData = testSentData.checkLine(geomArea,pathFile)
######        elif typeData == 'line' and typeInput == 'fromWeb':
######            dictData = testSentData.checkPoint(geomArea,pathFile)
####        elif typeData == 'polygon' and typeInput == 'csvFile':
####            dictData = testSentData.checkPolygon(geomArea,pathFile)
######        elif typeData == 'polygon' and typeInput == 'fromWeb':
######            dictData = testSentData.checkPoint(geomArea,pathFile)
##    @staticmethod
##    def addStaticDataLayer():
##        testSentData = AddStaticData()
##        dictData = testSentData.checkPoint(geomArea,pathFile)

##    @staticmethod
##    def printError():


def main():
    print "Content-type: text/html\n"
    print "\n\n"
    for i in form.keys():
        if i == "pathFile":
            pathFile = form[i].value
        elif i == "inputType":
            inputType = form[i].value
##    pathFile = "C:/xampp/htdocs/GCaaS-3/file/LineForm.csv"
##    print inputType
##    print pathFile
##    pathFile = {'name_area': 3}

##    pathFile = "../" + pathFile
##    print pathFile
    StaticDataLayer.checkStaticDataLayer('point',pathFile)

if __name__ == '__main__':
    main()

