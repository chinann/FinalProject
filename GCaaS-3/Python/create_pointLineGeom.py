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

import csv
import psycopg2
import os, sys
import uuid
import cgi

def main():
    conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
    print "Opened database successfully"
    cur = conn.cursor()

    path = "C:/xampp/htdocs/GCaaS-3/file"
    dirs = os.listdir( path )
    cur.execute('SELECT "deployment_Area" FROM public.table_deployment where "deploymentID" = 5;')
    rows = cur.fetchone()
    geomArea = rows[0]
    geomInsert = "ST_GeomFromText('POINT(101.211931 13.281768)',4326)"

##    print(geomArea)
##    print "sql: " + select
##    row = cursor.fetchone()
##    while row is not None:
##      row = cur.fetchone()
##      if row != None :
##      print(row)

    pointCreate = '''CREATE TABLE  public.readCSV_test(id serial, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(Point,4326));'''
    lineCreate = '''CREATE TABLE  public.readCSV_test(id serial, name_area character varying(100), display_name character varying(100), geom geometry(LineString,4326));'''
    polygonCreate = '''CREATE TABLE  public.readCSV_test(id serial, name_area character varying(100), display_name character varying(100), geom geometry(Polygon,4326));'''
##    sde.st_geometry ('linestring (33 2, 34 3, 35 6)', 4326)
##    sde.st_geometry ('polygon ((3 3, 4 6, 5 3, 3 3))', 4326)

    cur.execute(pointCreate)
    conn.commit()
    print "Create database successfully"

    for file in dirs:
        pathFile = path + "/" + file
        with open(pathFile, 'rb') as csvfile:
            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')
            for row in spamreader:
                if row[0][:9] != 'Name_Area':
                    x = row[0].split(",")
                    print "listData: " + x[0] + "," + x[1] + "," + x[2] + "," + x[3];

                    geomPoint = "ST_GeomFromText('POINT(" + x[2] + " " + x[1] + ")',4326)"
                    geomLine = ST_GeomFromText('LINESTRING(x1 y1,x2 y2,x3 y3)',4326);
                    geomPolygon = ST_GeomFromText('POLYGON(x1 y1,x2 y2,x3 y3,x1 y1)',4326);

                    select = "SELECT ST_Intersects(\'" + geomArea + "\'," + geom + ");"
                    print "AAA: " +select
                    cur.execute(select)
                    rows = cur.fetchone()
                    geomArea2 = rows[0]
                    if geomArea2 == True:
                        ans = "INSERT INTO public.readCSV_test(name_area, latitude, longitude, display_name, geom) VALUES(\'"  + x[0] + "\',\'" + x[1] + "\',\'" + x[2] + "\',\'" + x[3] + "\', "  + geom  + " );"
                        print "ans: " +ans
                        cur.execute(ans)
    conn.commit()
    conn.close()
    print "Insert database successfully"


if __name__ == '__main__':
    main()