#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     19/09/2016
# Copyright:   (c) chinan 2016
# Licence:     <your licence>
#-------------------------------------------------------------------------------

def main():
    print "nan"

if __name__ == '__main__':
    main()



##import requests
##import httplib
##
##def patch_send():
##    old_send= httplib.HTTPConnection.send
##    def new_send( self, data ):
##        print data
##        return old_send(self, data) #return is not necessary, but never hurts, in case the library is changed
##    httplib.HTTPConnection.send= new_send
##
##patch_send()
##requests.get("http://www.google.com")
