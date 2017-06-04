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
import os, sys
import cgi
import cgitb; cgitb.enable()
import json
try:
  import simplejson as json
except:
  import json
form = cgi.FieldStorage()

def main():
    print "Content-type: text/html\n"
    print "\n\n"

    for i in form.keys():
        if i == "dataDic":
            dataToAdd = form[i].value

    dataDic = json.loads(dataToAdd)
    print dataDic
##    dataLayerType = dataDic['listItem'][0]['dataLayerType']

    staticName = dataDic['listItem']['staticName']
    if(staticName == 'Hospital' or staticName == 'School' or staticName == 'Temple' or staticName == 'Fire station'):
        staticName = "All " + staticName
    nameDB = 'DB_' + dataDic['listItem']['depname']
    conn = psycopg2.connect(database=nameDB , user="postgres", password="1234", host="localhost", port="5432")
    cur = conn.cursor()

    tempDisplayName = dataDic['listItem']['display_name']
    if not isinstance(tempDisplayName, unicode):
        tempDisplayName = str(tempDisplayName)
    sqlUpdate = "UPDATE \""+ str(staticName) + "\" SET name_area= \'" + dataDic['listItem']['name_area']  + "\' , display_name= \'" + tempDisplayName + "\' , latitude= " + str(dataDic['listItem']['latitude']) +  " , geom= ST_Transform("  + str(dataDic['listItem']['geom']) + ", 32647) , longitude= " +str(dataDic['listItem']['longitude'])+ " WHERE serial = " + str(dataDic['listItem']['id']) + ";"

    cur.execute(sqlUpdate)
    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()
