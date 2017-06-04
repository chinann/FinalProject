#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Mon Feb 29 16:40:50 2016

@author: wgs01
"""

import psycopg2
import os, sys
import cgi
import cgitb; cgitb.enable()
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT
from datetime import datetime

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    name = ""
    url = ""
    typeDep = ""
    des = ""
    polygon = ""
    tw = ""
    now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    username = ""

#    Get value from URL
    for i in form.keys():
        if i == "deployName":
            name = form[i].value
        elif i == "description":
            des = form[i].value
        elif i == "typeDep":
            typeDep = form[i].value
        elif i == "area":
            polygon = form[i].value
        elif i == "url":
            url = form[i].value
        elif i == "TW":
            tw = form[i].value
        elif i == "User":
            username = form[i].value


    querySelectTypeDep = "SELECT \"typeID\" FROM table_type WHERE \"type_Name\" = '" + typeDep + "'"
    cur.execute(querySelectTypeDep)
    rows = cur.fetchall()
    for row in rows:
        typeID = row[0]

#    Check Deployment name and URL
    querySelectDepName = "SELECT * FROM table_deployment WHERE \"deployment_Name\" = '" + name + "'"
    cur.execute(querySelectDepName)
    rows = cur.fetchall()

    if len(rows) != 0:
        querySelectURL = "SELECT * FROM table_deployment WHERE \"deployment_URL\" = '" + url + "'"
        cur.execute(querySelectURL)
        rows2 = cur.fetchall()
        if len(rows2) != 0:
            print "{\"status\": \"both\"}"
        else:
            print "{\"status\": \"name\"}"
    else :
        querySelectURL = "SELECT * FROM table_deployment WHERE \"deployment_URL\" = '" + url + "'"
        cur.execute(querySelectURL)
        rows2 = cur.fetchall()
        if len(rows2) != 0:
            print "{\"status\": \"url\"}"
        else:
            queryInsetDep = "INSERT INTO table_deployment(\"deployment_Name\", \"deployment_Description\", \"deployment_URL\", \"deployment_DateCreate\", \"deployment_LastAccess\", \"deployment_Area\", \"typeID\") VALUES ('" + name +"', '"+ des +"', '" + url +"', TIMESTAMP '"+ now +"', TIMESTAMP '"+ now +"', ST_GeomFromText('" + polygon + "',4326), '"+str(typeID)+"');"
            cur.execute(queryInsetDep)

            querySelectDepID = "SELECT \"deploymentID\" FROM table_deployment WHERE \"deployment_Name\" = '" + name + "'"
            cur.execute(querySelectDepID)
            rows = cur.fetchall()
            for row in rows:
                depID = row[0]

            queryInsetCon = "INSERT INTO table_configuration(\"configurationID\", \"deploymentID\", \"dynamicID\") VALUES ('" + str(depID) +"', '"+ str(depID) +"', '" + str(depID) +"');"
            cur.execute(queryInsetCon)

            queryInsetVersionAndStatus = "INSERT INTO table_version(\"versionID\", \"deploymentID\", version_status) VALUES ('" + str(depID) +"', '"+ str(depID) +"', 'New');"
            cur.execute(queryInsetVersionAndStatus)

            querySelectUserDetail = "SELECT \"userID\", \"roleUserID\" FROM table_user WHERE \"user_Username\" = '" + username + "'"
            cur.execute(querySelectUserDetail)
            rows = cur.fetchall()
            for row in rows:
                userID = row[0]
                roleUserID = row[1]

            queryInsetWorker = "INSERT INTO table_worker(\"userID\", \"deploymentID\", \"roleUserID\") VALUES ('" + str(userID) +"', '"+ str(depID) +"', '" + str(1) + "');"
            cur.execute(queryInsetWorker)

            if tw != "":
                queryInsetDynamic = "INSERT INTO \"table_dynamicDataLayer\"(\"dynamicID\", \"dynamic_SocialMedia\", \"dynamic_Hardware\", \"dynamic_NativeApp\") VALUES ('" + str(depID) +"', '1', '0', 'False');"
                cur.execute(queryInsetDynamic)

            sqlCreateDBDeployment = "CREATE DATABASE \"DB_" + name + "\""

            cur.execute(sqlCreateDBDeployment)
            conn.commit()


            nameDep = "DB_" + name
            conn2 = psycopg2.connect(database=nameDep, user="postgres", password="1234", host="localhost", port="5432")

            cur2 = conn2.cursor()

            sqlCreateExtension = "CREATE EXTENSION postgis SCHEMA public VERSION \"2.1.7\";"
            cur2.execute(sqlCreateExtension)

            sqlCreateTWH = "CREATE TABLE \"table_postTWH\" (\"postID\" serial NOT NULL, \"post_Name\" character varying(50) NOT NULL, \"post_GeomIncident\" geometry NOT NULL, \"post_GeomInformer\" geometry, \"post_Date\" character varying(50) NOT NULL, \"podt_HelpNo\" integer, \"post_Status\" character varying(20) NOT NULL, \"post_Message\" text, \"post_Hashtag\" text NOT NULL,  \"post_ProfileImg\" character varying(100) NOT NULL, \"post_Place\" character varying(100) NOT NULL, \"deployment_Name\" character varying(100) NOT NULL,  CONSTRAINT \"postID_PK\" PRIMARY KEY (\"postID\"))"
            cur2.execute(sqlCreateTWH)

            sqlCreateTWR = "CREATE TABLE \"table_postTWR\"(\"postRID\" integer NOT NULL, \"post_Name\" character varying(30) NOT NULL, \"post_GeomInformer\" geometry NOT NULL, \"post_Date\" character varying(50) NOT NULL, \"post_Status\" character varying(20) NOT NULL, \"post_RoadCon\" character varying(20), \"post_Message\" text, \"post_Hashtag\" text NOT NULL, \"deployment_Name\" character varying(100) NOT NULL, CONSTRAINT \"postRID_PK\" PRIMARY KEY (\"postRID\"))"
            cur2.execute(sqlCreateTWR)

            sqlCreateCate = "CREATE TABLE table_category ( \"categoryID\" serial NOT NULL, \"category_Name\" character varying(50) NOT NULL, CONSTRAINT \"categoryID_PK\" PRIMARY KEY (\"categoryID\"))"
            cur2.execute(sqlCreateCate)

            sqlCreateStatic = "CREATE TABLE table_staticDataLayer_deployment ( \"staticID\" serial NOT NULL, \"deployment_Name\" character varying(100) NOT NULL, \"staticDataLayer_Name\" character varying(100) NOT NULL , CONSTRAINT \"staticID_PK\" PRIMARY KEY (\"staticID\"))"
            cur2.execute(sqlCreateStatic)
            conn2.commit()


##            querySelect =  "SELECT \"staticDataLayer_Name\" FROM public.\"table_staticDataLayer\" where \"staticID\" < 6;"
##            cur.execute(querySelect)
##            rows = cur.fetchall()
##
##            for row in rows:
##                query = row[0]
##                if (query == "All Hospital"):
##                    sqlCreateStaticData = "CREATE TABLE \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##                    cur2.execute(sqlCreateStaticData)
##                    conn2.commit()
##                    selectStatic = "select name, x, y, address, geom from gov_hos union select  name, x, y, address, geom from bma_hos union select  name, x, y, address, geom from drug_clinic union select  name, x, y, address, geom from health_branch union select  name, x, y, address, geom from priv_hos;"
##                    cur.execute(selectStatic)
##                    xx = cur.fetchall()
##                    for i in xx:
##                        sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                        cur2.execute(sqlInsertStaticData)
##                        conn2.commit()
##                elif (query == "All School"):
##                    sqlCreateStaticData = "CREATE TABLE \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##                    cur2.execute(sqlCreateStaticData)
##                    conn2.commit()
##                    selectStatic = "select name, x, y, address, geom from bec_school union select  name, x, y, address, geom from bma_school  union select  name, x, y, address, geom from college union select  name, x, y, address, geom from private_school union select  name, x, y, address, geom from university;"
##                    cur.execute(selectStatic)
##                    xx = cur.fetchall()
##                    for i in xx:
##                        sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                        cur2.execute(sqlInsertStaticData)
##                        conn2.commit()
####                elif (query == "All Police station"):
####                    sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
####                    cur2.execute(sqlCreateStaticData)
####                    conn2.commit()
####                    selectStatic = "select name, x, y, address, geom from police_station;"
####                    cur.execute(selectStatic)
####                    xx = cur.fetchall()
####                    for i in xx:
####                        sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
####                        cur2.execute(sqlInsertStaticData)
####                        conn2.commit()
##                elif (query == "All Fire station"):
##                    sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##                    cur2.execute(sqlCreateStaticData)
##                    conn2.commit()
##                    selectStatic = "select name, x, y, address, geom from fire_station;"
##                    cur.execute(selectStatic)
##                    xx = cur.fetchall()
##                    for i in xx:
##                        sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                        cur2.execute(sqlInsertStaticData)
##                        conn2.commit()
##
##                elif (query == "All Temple"):
##                    sqlCreateStaticData = "CREATE TABLE  \""+ query + "\"(serial serial, display_name character varying(100), longitude double precision, latitude double precision, name_area character varying(300), geom geometry(Point,32647));"
##                    cur2.execute(sqlCreateStaticData)
##                    conn2.commit()
##                    selectStatic = "select name, x, y, address, geom from church union select  name, x, y, address, geom from muslim ;"
##                    cur.execute(selectStatic)
##                    rows = cur.fetchall()
##                    for i in rows:
##
##                        if i[3] == None:
##                            sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'\' , \'" + str(i[4]) + "\');"
##                        else:
##                            sqlInsertStaticData = "INSERT INTO \"" + query + "\"(\"display_name\", \"longitude\", \"latitude\", \"name_area\", \"geom\")  VALUES(\'" + i[0] + "\' , \'" + str(i[1]) +  "\' , \'" + str(i[2]) +  "\' , \'" + i[3] +  "\' , \'" + str(i[4]) + "\');"
##                        cur2.execute(sqlInsertStaticData)
##                        conn2.commit()

##            cur2.execute(sqlCreateStaticData)
##            conn2.commit()
##            cur2.execute(sqlInsertStaticData)

            conn2.close()
            conn.close()
            print "{\"status\": \"ok\"}"
    #    rows = cur.fetchall()
    #    now = datetime.now().strftime('%Y%m%d')
    #    # Create file json name by date(20160301_132331.json from form YYYYMMDD_HHMMSS)
    #    nameFile = path + "data_" + now + ".json"
    #
    #    file = open(nameFile, "w")
    #    json = "{\"total\": " + str(len(rows)) + " ,\"metaData\":{ \"root\": \"records\" ,\"totalProperty\": \"total\" ,\"recordNo\": 0 ,\"recordCount\": " + str(len(rows)) + " ,\"fields\": [ { \"name\":\"region\", \"type\":\"string\" }, { \"name\":\"long_itude\", \"type\":\"double\" }, { \"name\":\"lat_itude\", \"type\":\"double\" }, { \"name\":\"mag_nitute\", \"type\":\"double\" }] },\"records\": ["
    #
    #    count = 0
    #    for row in rows:
    #        geom = row[1].split("(")
    #        geom = geom[1].split()
    #        lng = geom[0]
    #        lat = geom[1].replace(")","")
    #
    #        if count == len(rows)-1 :
    #            json = json + "{\"region\":\"" + row[0] + "\",\"long_itude\":\"" + lng + "\",\"lat_itude\":\"" + lat + "\",\"mag_nitute\":" + str(row[2]) + "}]}"
    #        else:
    #            json = json + "{\"region\":\"" + row[0] + "\",\"long_itude\":\"" + lng + "\",\"lat_itude\":\"" + lat + "\",\"mag_nitute\":" + str(row[2]) + "},"
    #        count +=1
    #
    #    file.write(json)
    #    file.close()
    #
    #    print "{\"status\":'1'}";

if __name__ == '__main__':
    main()