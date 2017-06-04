# -*- coding: utf-8 -*-
"""
Created on Fri Mar 18 13:42:39 2016

@author: Administrator
"""

from clicarriots import api

jsonFile = r"C:\xampp\htdocs\sensorTest.json"

def writeJson(str):
    ff = open(jsonFile, "w")
    ff.write(str)
    ff.close() 

if __name__ == '__main__':
    client_stream = api.Stream("a2f74866c391680418ff3d40e9a00ba45197035d7d51bce97cefa0677c509ce2")
    
    #without params (get data from every device in carriots)
    code, response = client_stream.list()  
    
    #with params
    code, response = client_stream.list({"device": "position@forearn.forearn"})
    
    #print (code, response)
    #print response
    
    data = ""
    
    for obj in response['result']:
        data =  data + str(obj['data']).replace('u \'',"").replace('\'','\"') + ","
#        data =  data + str(obj['data'])+ ","
    data = "{\"Object\":[" + data[:-1] + "]}"
    print data
    writeJson(data)