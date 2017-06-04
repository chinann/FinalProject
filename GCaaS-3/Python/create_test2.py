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


##    cur.execute('''CREATE TABLE  public.readCSV_test(id serial, name_area character varying(100), latitude Decimal(9,6), longitude Decimal(9,6), display_name character varying(100), geom geometry(Point,32647));''')
    conn.commit()
    print "Create database successfully"
    pathFile = "C:/xampp/htdocs/GCaaS-3/file/PointForm.csv"
##    for file in dirs:
##        pathFile = path + "/" + file
    with open(pathFile, 'rb') as csvfile:
        spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')
        for row in spamreader:
            if row[0][:9] != 'Name_Area':
                x = row[0].split(",")
                print "listData: " + x[0] + "," + x[1] + "," + x[2] + "," + x[3];
                geom = "ST_GeomFromText('POINT(" + x[2] + " " + x[1] + ")',32647)"
                ans = "INSERT INTO public.readCSV_test(name_area, latitude, longitude, display_name, geom) VALUES(\'"  + x[0] + "\',\'" + x[1] + "\',\'" + x[2] + "\',\'" + x[3] + "\'," + geom  + ");"

                print ans
##                    cur.execute(ans)
##    conn.commit()
    conn.close()
    print "Insert database successfully"


if __name__ == '__main__':
    main()