google.maps.event.addDomListener(window, 'load', initialMap);
google.maps.event.addDomListener(window, 'load', initialize);

var polygonObj;
var regtangObj;
var marker=[];
var markAdd;
var map;
var mapDup;
var firstPoint;
var polygon = "MULTIPOLYGON(((";

function pastepoly() {
    var scope = "[";
    polygonN = document.getElementById('area').value;
    polygonN = polygonN.replace("MULTIPOLYGON(((", "");
    polygonN = polygonN.replace(")))", "");
    pol = polygonN.split(",");

    for (i = 0; i < pol.length; i++) {
        position = pol[i].split(" ");
        lng = position[0];
        lat = position[1];

        if (i != pol.length -1) {
            scope = scope + "{\"lat\": " + lat + ", \"lng\": " + lng + "}," ;
        }
        else{
            scope = scope + "{\"lat\": " + lat + ", \"lng\": " + lng + "}]" ;
        };
    };

    var obj = JSON.parse(scope);

    // Construct the polygon.
    var polygonArea = new google.maps.Polygon({
        paths: obj,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35
    });
    polygonArea.setMap(mapDup);

}

function initialMap() {
  mapDup = new google.maps.Map(document.getElementById('mapDup'), {
    center: {lat: 13, lng: 100},
    zoom: 8,
    maxZoom: 20
  });
}

function initialize() {
  map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 13, lng: 100},
      zoom: 8,
      maxZoom: 20
  });

  var drawingManager = new google.maps.drawing.DrawingManager({
      drawingControl: true,
      drawingControlOptions: {
          position: google.maps.ControlPosition.TOP_CENTER,
          drawingModes: [
              google.maps.drawing.OverlayType.POLYGON,
              google.maps.drawing.OverlayType.RECTANGLE
          ]
      },
      rectangleOptions: {
          fillColor: 'black',
          fillOpacity: 0.1,
          strokeWeight: 3,
          strokeColor: 'black',
          clickable: true,
          editable: true,
          zIndex: 1
      },
      polygonOptions: {
          fillColor: 'blue',
          fillOpacity: 0.1,
          strokeWeight: 3,
          strokeColor: 'blue',
          clickable: true,
          editable: true,
          zIndex: 1
      }
  });

  drawingManager.setMap(map);
  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
      drawingManager.setDrawingMode(null);
      if (regtangObj!=null) {
        regtangObj.setMap(null);
      }
      if (polygonObj!=null) {
        polygonObj.setMap(null);
      }

      if (e.type == google.maps.drawing.OverlayType.RECTANGLE)
      {
        regtangObj = e.overlay;
          getLatLngPolygon();
          google.maps.event.addListener(regtangObj, 'bounds_changed', getLatLngPolygon);
      }

      else if (e.type == google.maps.drawing.OverlayType.POLYGON)
      {
        polygonObj = e.overlay;
        if (polygonObj.getPath().getLength()>=3) {
            getLatLngPolygon();
          google.maps.event.addListener(polygonObj.getPath(), 'insert_at', getLatLngPolygon);
          google.maps.event.addListener(polygonObj.getPath(), 'set_at', getLatLngPolygon);
        }
        else{
          alert("Enter at least three points");
          polygonObj.setMap(null);
        }
      }
  });

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

function getLatLngPolygon(){
  if (regtangObj != null) {
      polygonObj = null;
      polygon = "MULTIPOLYGON(((";
      polygon = polygon + regtangObj.getBounds().getNorthEast().lng() + " " + regtangObj.getBounds().getNorthEast().lat() + "," + regtangObj.getBounds().getSouthWest().lng() + " " + regtangObj.getBounds().getNorthEast().lat() + "," + regtangObj.getBounds().getSouthWest().lng() + " " + regtangObj.getBounds().getSouthWest().lat() + "," + regtangObj.getBounds().getNorthEast().lng() + " " + regtangObj.getBounds().getSouthWest().lat() + "," + regtangObj.getBounds().getNorthEast().lng() + " " + regtangObj.getBounds().getNorthEast().lat() + ")))" ;
  }

  else if (polygonObj != null){
      regtangObj = null
      polygon = "MULTIPOLYGON(((";
      for (var i = 0; i < polygonObj.getPath().getLength(); i++)
      {
        if (i == 0) {
          firstPoint = polygonObj.getPath().b[i];


          polygon = polygon + firstPoint.lng() + " " + firstPoint.lat() + "," ;
        }
        else {
          polygon = polygon + polygonObj.getPath().b[i].lng() + " " + polygonObj.getPath().b[i].lat() + "," ;
        }
      }
      polygon = polygon + firstPoint.lng() + " " + firstPoint.lat() + ")))" ;
  }
  document.getElementById('area').value = polygon;
  document.getElementById("config-area").innerHTML = document.getElementById('area').value ;
}

function submit(username){
  var xmlhttp;
  var myLatlng;
  console.log(polygon);
  var deploymentName = document.getElementById('deployName').value ;
  var url = "https://GCaaS.com/" + document.getElementById('url').value ;
  var type = document.getElementById('select').value ;
  var descrip = document.getElementById('description').value ;
  var twitter = "";
  if (document.getElementById('twitter-list').checked == true) {
      twitter = document.getElementById('basic').value ;
  };

  if (type == "Please select type of deployment...") {
      alert("Please selected Type of Deployment")
  };

  if (polygonObj == null && regtangObj == null) {
      alert("Select Area of Deployment");
  }

  if (markAdd!=null) {
        clearMarkers();
  }

  for (var i = 0; i < marker.length; i++) {
    marker[i].setMap(null);
  }

  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200 ) //200 OK do 4 stage
      {
       console.log("testtttttttt");
        console.log(xmlhttp.responseText);
        obj = JSON.parse(xmlhttp.responseText);
        // console.log(obj.status);
         

        if (obj.status == "name") {
          alert("Deployment Name already exists!!");
          document.getElementById('deployName').focus();
        }
        else if( obj.status == "url" ) {
          alert("URL already exists!!");
          document.getElementById('url').focus();
        }
        else if( obj.status == "both" ) {
          alert("Deployment Name and URL already exists!!");
          document.getElementById('deployName').focus();
          document.getElementById('url').focus();
        }
        else{
           location.replace("http://172.16.150.177/GCaaS-3/Admin/manageDeployment.php?depName="+deploymentName)
        };
      }
  }
  xmlhttp.open("GET","http://172.16.150.177/cgi-bin/create.py?deployName="+deploymentName+"&url="+url+"&typeDep="+type+"&description="+descrip+"&area="+polygon+"&TW="+twitter+"&User="+username,true);
  xmlhttp.send();
}

function basicToCus(){
  document.getElementById('marker').disabled = false ;
  document.getElementById('layer').disabled = false ;
  document.getElementById('item1').disabled = false ;
  document.getElementById('item2').disabled = false ;
  document.getElementById('item3').disabled = false ;
  document.getElementById('item4').disabled = false ;
  document.getElementById('item5').disabled = false ;
  document.getElementById('item6').disabled = false ;

  document.getElementById('marker').checked = false ;
  document.getElementById('layer').checked = false ;
  document.getElementById('item1').checked = false ;
  document.getElementById('item2').checked = false ;
  document.getElementById('item3').checked = false ;
  document.getElementById('item4').checked = false ;
  document.getElementById('item5').checked = false ;
  document.getElementById('item6').checked = false ;
}

function cusToBasic(){
  document.getElementById('marker').disabled = true ;
  document.getElementById('layer').disabled = true ;
  document.getElementById('item1').disabled = true ;
  document.getElementById('item2').disabled = true ;
  document.getElementById('item3').disabled = true ;
  document.getElementById('item4').disabled = true ;
  document.getElementById('item5').disabled = true ;
  document.getElementById('item6').disabled = true ;

  document.getElementById('marker').checked = true ;
  document.getElementById('layer').checked = false ;
  document.getElementById('item1').checked = true ;
  document.getElementById('item2').checked = true ;
  document.getElementById('item3').checked = true ;
  document.getElementById('item4').checked = true ;
  document.getElementById('item5').checked = true ;
  document.getElementById('item6').checked = true ;
}

function clearMarkers(){
  markAdd.setMap(null);
  markAdd=null;
}

function pullName(obj){
  document.getElementById("config-name").innerHTML = obj.value ;
  document.getElementById('url').value = document.getElementById('deployName').value;
  document.getElementById("config-url").innerHTML = "https://GCaaS.com/" + obj.value ;
}

function pullUrl(obj){
    document.getElementById("config-url").innerHTML = "https://GCaaS.com/" + obj.value ;
}

function pullType(obj){
  document.getElementById("config-type").innerHTML = obj.value ;
}

function pullDesc(obj){
  document.getElementById("config-desc").innerHTML = obj.value ;
}

function pullDynamic(obj){
    //document.getElementById('tw-check-config').checked = document.getElementById('tw-check').checked ;
    document.getElementById('tw-check-config').checked = true ;
    document.getElementById('marker-config').checked = document.getElementById('marker').checked ;
    document.getElementById('layer-config').checked = document.getElementById('layer').checked ;
    document.getElementById('item1-config').checked = document.getElementById('item1').checked ;
    document.getElementById('item2-config').checked = document.getElementById('item2').checked ;
    document.getElementById('item3-config').checked = document.getElementById('item3').checked ;
    document.getElementById('item4-config').checked = document.getElementById('item4').checked ;
    document.getElementById('item5-config').checked = document.getElementById('item5').checked ;
    document.getElementById('item6-config').checked = document.getElementById('item6').checked ;
}
