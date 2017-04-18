#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Sat Jun 11 16:40:34 2016

@author: SometiimeZ
"""

import psycopg2
import cgi
import cgitb; cgitb.enable()

form = cgi.FieldStorage()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    dbName = ""
    types= ""
    polygon=""
    json = "["

    for i in form.keys():
        if i == "dbName":
            dbName = "DB_" + form[i].value
        if i == "type":
            types = form[i].value
        if i == "polygon":
            polygon = form[i].value

    conn = psycopg2.connect(database = dbName, user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()

    polygonCurr = "ST_TRANSFORM(ST_GEOMFROMTEXT('" + polygon + "',4326),32647)";

    search = "ST_INTERSECTS(ST_TRANSFORM(\"post_GeomIncident\",32647)," + polygonCurr + ")";

    querySearchByArea = "SELECT \"post_Name\", ST_x(\"post_GeomIncident\"), ST_y(\"post_GeomIncident\"), \"post_Date\", \"post_Status\", \"post_Message\", \"post_Hashtag\", \"post_ProfileImg\", \"post_Place\", \"postID\" FROM \"table_postTWH\" WHERE " + search;
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySearchByArea)
    rows = cur.fetchall()

    count = 0
    if rows != []:
        for row in rows:
            if count == len(rows)-1 :
                json = json + "{\"name\":\"" + row[0] + "\",\"long_itude\":\"" + str(row[1]) + "\",\"lat_itude\":\"" + str(row[2]) + "\",\"date\":\"" + row[3] + "\",\"status\":\"" + row[4] + "\",\"msg\":\"" + row[5] + "\",\"profileImg\":\"" + row[7] + "\",\"place\":\"" + row[8] + "\",\"ID\":\"" + str(row[9]) + "\" }]"
            else:
                json = json + "{\"name\":\"" + row[0] + "\",\"long_itude\":\"" + str(row[1]) + "\",\"lat_itude\":\"" + str(row[2]) + "\",\"date\":\"" + row[3] + "\",\"status\":\"" + row[4] + "\",\"msg\":\"" + row[5] + "\",\"profileImg\":\"" + row[7] + "\",\"place\":\"" + row[8] + "\",\"ID\":\"" + str(row[9]) + "\" },"
            count +=1
        print json
    else:
        print "{\"status\": \"none\"}"

    conn.close()

if __name__ == '__main__':
    main()