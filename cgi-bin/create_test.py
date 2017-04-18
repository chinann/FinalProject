#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     07/02/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

#!C:\Python27\python.exe -u
#!/usr/bin/env python

import csv
import psycopg2
import os, sys
import uuid
import cgi

def setSurvey(root_name,):
    try:

##        for file in dirs:
##        ##    pathFile = "C:/Users/User-GISTDA/Desktop/Internship_nan/Project/Log/gps_20160209_062857.csv"
##        ##    file = "gps_20160209_062857.csv"
##            pathFile = path + "/" + file
##        ##    print pathFile
##            with open(pathFile, 'rb') as csvfile:
##                spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')
##                rand_uuid = uuid.uuid4()
##                str_uuid = str(rand_uuid)
##                insertRoot = "INSERT INTO public.survey(root_name, file_name, survey_uuid) VALUES(\'" + root_name + "\',\'" + file + "\',\'" + str_uuid + "\');"
####                print insertRoot
##                cur.execute(insertRoot)
##                for row in spamreader:
##                    if row[0][:5] != 'index':
##                        x = row[0].split(",")
##                        geom = "ST_GeomFromText('POINT(" + x[5] + " " + x[3] + ")',4326)"
##                        if(x[10] == ''):
##                            ans = "INSERT INTO public.pictures(index, filename, time, latitude, latitude_degree, longitude, longitude_degree, speed, angle,date, file_name, geom, survey_uuid ) VALUES(\'"  + x[0] + "\',\'" + x[1] + "\',\'" + x[2] + "\',\'" + x[3] + "\',\'" + x[4] + "\',\'" + x[5] + "\',\'" + x[6] + "\',\'" + x[7] + "\',\'" + x[8] + "\',\'" + x[9] + "\','" + file + "\'," + geom  + ",'"+ str_uuid + "');"
##                        else:
##                            ans = "INSERT INTO public.pictures(index, filename, time, latitude, latitude_degree, longitude, longitude_degree, speed, angle,date, roll, pitch, azimuth, file_name, geom , survey_uuid ) VALUES(\'"  + x[0] + "\',\'" + x[1] + "\',\'" + x[2] + "\',\'" + x[3] + "\',\'" + x[4] + "\',\'" + x[5] + "\',\'" + x[6] + "\',\'" + x[7] + "\',\'" + x[8] + "\',\'" + x[9] + "\',\'" + x[10] + "\',\'" + x[11] + "\',\'" + x[12] + "\'" + ",'" + file +"\'," + geom + ",'"+ str_uuid + "');"
####                            print ans
##                        cur.execute(ans)
##        conn.commit()
##        conn.close()
##        print "Insert database successfully"
##
##    except MyError as e:
##        print('My exception occurred, value:', e.value)


##    form = cgi.FieldStorage()
##    print "Content-Type: text/html;charset=utf-8\n"
##
##    if form.has_key('root_name') :
##        root_name = form["root_name"].value ## get style
##    else:
##        print "error : Cannot import data, because the textbox is empty!!!<BR />"
##        return
##
##    setSurvey(root_name)?


        conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="127.0.0.1", port="5432")
        print "Opened database successfully"
        cur = conn.cursor()
        print root_name

        path = "C:\xampp\htdocs\GCaaS-3\file"
        dirs = os.listdir( path )

        cur.execute('''CREATE TABLE  public.readCSV_test ("ID" serial, "NO." integer, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100));''')
        conn.commit()
        conn.close()
        print "Insert database successfully"

