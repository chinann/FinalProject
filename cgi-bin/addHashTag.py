#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Thu Jun 16 00:47:08 2016

@author: SometiimeZ
"""

import psycopg2
import cgi
import cgitb; cgitb.enable()

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="172.20.10.2", port="5432")
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    hashtag=""
    hashtagNew=""
    dep=""

    for i in form.keys():
        if i == "hashtag":
            hashtagNew = form[i].value
        elif i == "dbName":
            dep = form[i].value

    querySearchHashtag = "SELECT \"deployment_Hashtag\" FROM table_deployment WHERE \"deployment_Name\" = '" + dep +"';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySearchHashtag)
    rows = cur.fetchall()
    for row in rows:
        hashtag = row[0]

    hashtag = hashtag + " " + hashtagNew
    try:
        queryUpdateHashtag = "UPDATE table_deployment SET \"deployment_Hashtag\"= '" + hashtag + "' WHERE \"deployment_Name\" = '" + dep +"';"
        cur.execute(queryUpdateHashtag)
        print "{\"status\": \"ok\"}"
    except psycopg2.Error as e:
        print "{\"status\": \"error\"}"


    conn.commit()
    conn.close()


if __name__ == '__main__':
    main()