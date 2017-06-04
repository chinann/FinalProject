#!/Python27/python.exe
# -*- coding: utf-8 -*-
#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     25/05/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import os, sys
import cgi
import cgitb; cgitb.enable()

form = cgi.FieldStorage()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    for i in form.keys():
        if i == "idDataLayer":
            idDataLayer = form[i].value
        elif i == "staticName":
            staticName = form[i].value
        else:
            depname = form[i].value

    nameDB = 'DB_' + depname
    conn = psycopg2.connect(database=nameDB , user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()
    if(staticName == 'Hospital' or staticName == 'School' or staticName == 'Temple' or staticName == 'Fire station'):
        staticName = "All " + staticName
    sqlDel = "DELETE FROM \""+ str(staticName) + "\" WHERE serial =" + idDataLayer + ";"
    cur.execute(sqlDel)
    conn.commit()
    conn.close()

    print "Delect Success"


if __name__ == '__main__':
    main()
