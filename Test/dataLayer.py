#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     28/03/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------
class DataLayer:
    def __init__(self,geomLine,name_area,long_lat,display_name):
        self.geomLine = geomLine
        self.name_area = name_area
        self.long_lat = long_lat
        self.display_name = display_name

    def displayData(self):
        print 'geomLine: ', self.geomLine
        print 'name_area: ', self.name_area
        print 'display_name: ', self.display_name
