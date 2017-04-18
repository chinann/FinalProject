#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     13/03/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import cgi
import cgitb; cgitb.enable()
form = cgi.FieldStorage()

def main():

    global conn
    conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
    print "Opened database successfully"
    global cur
    global totalArea
    cur = conn.cursor()

    cur.execute('SELECT "deployment_Area" FROM public.table_deployment where "deploymentID" = 5;')
    rows = cur.fetchone()
    totalArea = rows[0]

    for i in form.keys():
        if i == "name_area":
            name_area = form[i].value
        elif i == "latitude":
            latitude = form[i].value
        elif i == "longitude":
            longitude = form[i].value
        elif i == "display_name":
            display_name = form[i].value

    geomPoint = "ST_GeomFromText('POINT(" + longitude + " " + latitude + ")',4326)"
    querySelectIntersect = "SELECT ST_Intersects(\'" + totalArea + "\'," + geomPoint + ");"
    print "AAA: " +querySelectIntersect
    cur.execute(select)
    rows = cur.fetchone()
    geomArea2 = rows[0]
    if geomArea2 == True:
        print "TRUE"
    else:
        print "FALSE"
##    ans = "INSERT INTO public.checkOnePoint(name_area, latitude, longitude, display_name, geom) VALUES(\'"  + name_area + "\',\'" + latitude + "\',\'" + longitude + "\',\'" + display_name + "\', "  + geomPoint  + " );"
##    cur.execute(ans)





if __name__ == '__main__':
    main()
