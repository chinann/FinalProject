#!/Python27/python.exe
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     14/04/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------


import types
import cgi
import cgitb; cgitb.enable()
import csv
import psycopg2
import os, sys
import uuid
#from collections import deque
from checkStaticData import CheckStaticData
##try:
##  import simplejson as json
##except:
##  import json
form = cgi.FieldStorage()

def main():
    print "Content-type: text/html\n"
    print "\n\n"
##    for i in form.keys():
##        if i == "pathFile":
##            pathFile = form[i].value
##        elif i == "inputType":
##            inputType = form[i].value
    ##pathFile = "C:/xampp/htdocs/GCaaS-3/file/LineForm.csv"
##    inputType = pathFile
    print "Hello World"


if __name__ == '__main__':
    main()

