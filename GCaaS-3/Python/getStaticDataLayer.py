#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     22/05/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

import psycopg2
import cgi
import cgitb; cgitb.enable()
from staticData_v2 import StaticData_v2
from staticData import StaticData

form = cgi.FieldStorage()
conn = psycopg2.connect(database="GCaaS", user="postgres", password="1234", host="localhost", port="5432")
cur = conn.cursor()

def main():
    print "Content-type: text/html\n"
    print "\n\n"


    for i in form.keys():
        if i == "staticName":
            staticName = form[i].value

    if(staticName == 'Province Thailand' or staticName == 'All Hospital' or staticName == 'All School' or staticName == 'All Police station' or staticName == 'All Fire station' or staticName == 'All Temple'):
        StaticData.main()
    else:
        StaticData_v2.main()

if __name__ == '__main__':
    main()