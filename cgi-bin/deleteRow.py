#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Sun Jun 12 21:24:38 2016

@author: SometiimeZ
"""

import psycopg2
import cgi
import cgitb; cgitb.enable()

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    user=""
    depName=""
    userID=""
    depID=""

    for i in form.keys():
        if i == "user":
            user = form[i].value
        elif i == "deployName":
            depName = form[i].value

    querySearchUserID = "SELECT \"userID\" FROM table_user WHERE \"user_Username\" = '" + user + "';"
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySearchUserID)
    rows = cur.fetchall()

    for row in rows:
        userID = row[0]

    querySearchDeploymentID = "SELECT \"deploymentID\" FROM table_deployment WHERE \"deployment_Name\" = '" + depName + "';"
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
#    print querySearchRoleUserID
    cur.execute(querySearchDeploymentID)
    rows = cur.fetchall()

    for row in rows:
        depID = row[0]

    try:
        queryDeleteWorker = "DELETE FROM table_worker WHERE \"deploymentID\" = '" + str(depID) + "' AND \"userID\" = '" + str(userID) + "';"
        cur.execute(queryDeleteWorker)
        print "{\"status\": \"ok\"}"
    except psycopg2.Error as e:
        print "{\"status\": \"error\"}"


    conn.commit()
    conn.close()


if __name__ == '__main__':
    main()
