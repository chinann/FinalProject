#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     17/04/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

def checkPolygon():
##          lastrow = CheckStaticData.get_last_row(pathFile)
##        arrayData = []
##        count = 1
##        numLatLong = 0
##        i = 0
##        long_lat = []
##        geomPolygon = ""
##        with open(pathFile, 'rb') as csvfile:
##            spamreader = csv.reader(csvfile, delimiter=' ', quotechar='|')
##            for row in spamreader:
##                if row[0][:2] != 'ID':
##                    x = row[0].split(",")
     ##                if count == 1:
    ##                    checkPolygonID = x[0]
    ##                    checkName = x[1]
    ##                    firstLat = x[2]
    ##                    firstLong = x[3]
    ##
    ##                if x[0] == checkPolygonID and x[1] == checkName:
    ##                    if count == 1:
    ##                        geomPolygon = "ST_GeomFromText('POLYGON((" + x[3] + " " + x[2]
    ##                    else:
    ##                        geomPolygon = geomPolygon + "," + x[3] + " " + x[2]
    ##                    numLatLong = numLatLong + 1
    ##                    lastLat = x[2]
    ##                    lastLong = x[3]
    ##
    ##                elif( numLatLong >= 3 and lastLat == firstLat and lastLong == firstLong) :
    ##                    geomPolygon = geomPolygon + "))',4326)"
    ##                    select = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomPolygon + ");"
    ##                    print "AAA: " +select
    ##                    cur.execute(select)
    ##                    rows = cur.fetchone()
    ##                    geomArea2 = rows[0]
    ##                    if geomArea2 == True:
    ##                        print "TRUE"
    ##                    else:
    ##                        print "FALSE"
    ##
    ##                    firstLat = x[2]
    ##                    firstLong = x[3]
    ##                    geomPolygon = "ST_GeomFromText('POLYGON((" + x[3] + " " + x[2]
    ##                    count = 1
    ##                    numLatLong = 0
    ##
    ##
    ##                if (lastrow[3] == x[3] and lastLat == firstLat and lastLong == firstLong):
    ##                    geomPolygon = geomPolygon + "))',4326)"
    ##                    select = "SELECT ST_Intersects(\'" + geomArea + "\'," + geomPolygon + ");"
    ##                    print "AAA: " +select
    ##                    cur.execute(select)
    ##                    rows = cur.fetchone()
    ##                    geomArea2 = rows[0]
    ##                    if geomArea2 == True:
    ##                        print "TRUE"
    ##                    else:
    ##                        print "FALSE"
    ##                    firstLat = x[2]
    ##                    firstLong = x[3]
    ##                else:
    ##                    print "cannot add static data layer"
    ##

if __name__ == '__main__':
    main()
