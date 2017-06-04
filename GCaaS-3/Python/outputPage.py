#-------------------------------------------------------------------------------
# Name:        module1
# Purpose:
#
# Author:      chinan
#
# Created:     16/04/2017
# Copyright:   (c) chinan 2017
# Licence:     <your licence>
#-------------------------------------------------------------------------------

class OutputPage(webapp.RequestHandler):
    def func (a,b):
        return a+b #just an example

    def get(self):
        form = cgi.FieldStorage()
        chem_name = form.getvalue('chemical_name')
        Para1 = form.getvalue('Para1')  #get values from input page--user inputs
        Para1 = float(Para1)
        Para2 = form.getvalue('Para2')  #get values from input page--user inputs
        Para2 = float(Para2)
        out = func (Para1,Para1)

        # [...] generate dynamic data [...]
html = html + template.render (templatepath + 'outputpage_start.html', {})
html = html + template.render (templatepath + 'outputpage_js.html', {})
html = html + """<table width="500" class='out' border="1" data-dynamic="%s">""" % json.dumps(your_generated_data_dict)
#tr/td elements and templating as needet
self.response.out.write(html)

        out_json=simplejson.dumps(out)  # I need to send out to JavaScript
        #writ output page
        templatepath = os.path.dirname(__file__) + '/../templates/'
        html = html + template.render (templatepath + 'outputpage_start.html', {})
        html = html + template.render (templatepath + 'outputpage_js.html', {})
        html = html + """<table width="500" class='out', border="1">
                          <tr>
                            <td>parameter 1</td>
                            <td>&nbsp</td>
                            <td>%s</td>
                          </tr>
                          <tr>
                            <td>parameter 2</td>
                            <td>&nbsp</td>
                            <td>%s</td>
                          </tr>
                          </table><br>"""%(Para1, Para2)
        html = html + template.render(templatepath + 'outputpage_end.html', {})
        #attempt to 'send' Python data (out_json) to JavaScript, but I failed.
        html = html + template.render({"my_data": out_json})
        self.response.out.write(html)

app = webapp.WSGIApplication([('/.*', OutputPage)], debug=True)