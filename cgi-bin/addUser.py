#!/Python27/python.exe
# -*- coding: utf-8 -*-
"""
Created on Mon Feb 29 16:40:50 2016

@author: wgs01
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

    user=""
    role=""
    dep=""
    userID=""
    roleID=""
    depID=""

    for i in form.keys():
        if i == "user":
            user = form[i].value
        elif i == "role":
            role = form[i].value
        elif i == "depname":
            dep = form[i].value

    querySearchUserID = "SELECT \"userID\" FROM table_user WHERE \"user_Username\" = '" + user +"';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySearchUserID)
    rows = cur.fetchall()

    for row in rows:
        userID = row[0]

    querySearchRoleUserID = "SELECT \"roleUserID\" FROM table_role WHERE \"role_Name\" = '" + role +"';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
#    print querySearchRoleUserID
    cur.execute(querySearchRoleUserID)
    rows = cur.fetchall()

    for row in rows:
        roleID = row[0]

    querySearchDeploymentID = "SELECT \"deploymentID\" FROM table_deployment WHERE \"deployment_Name\" = '" + dep +"';";
#    querySelect = "SELECT \"staticDataLayer_sql\" FROM \"table_staticDataLayer\" WHERE \"staticDataLayer_Name\" = 'Police station';";
    cur.execute(querySearchDeploymentID)
    rows = cur.fetchall()

    for row in rows:
        depID = row[0]

    try:
        queryInsetWorker = "INSERT INTO table_worker( \"userID\", \"deploymentID\", \"roleUserID\") VALUES ('" + str(userID) +"', '" + str(depID) +"', '" + str(roleID) +"');"
        cur.execute(queryInsetWorker)
        print "{\"status\": \"ok\"}"
    except psycopg2.Error as e:
        print "{\"status\": \"error\"}"


    conn.commit()
    conn.close()


if __name__ == '__main__':
    main()