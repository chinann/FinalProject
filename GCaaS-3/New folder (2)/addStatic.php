<?php
session_start();
if(isset($_GET['depName']) /*you can validate the link here*/){
    $_SESSION['depname'] = $_GET['depName'];
}
if (!$_SESSION["username"]) {
    header("Location: http://".$_SESSION['host']."/GCaaS-3/index.php");
    exit(0);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My map</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../css/AdminLTE.css">
    <!-- CUSTOM STYLE CSS -->
    <link href="../css/style.css" rel="stylesheet"/>

    <link rel="stylesheet" href="../css/skin-blue.min.css">

    <script type="text/javascript" src="../js/markerclusterer.js"></script>
    <style>
        #map {
            width: 100%;
            height: 800px;
            /*margin-top: 100px;*/
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">

     <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1c2b4b; color: #FFFFFF;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title" >Add new static data layer</h2>
            </div>
            <div class="modal-body" > <center>
                <div class="body-modal">Static data layer name: <input id="staticName-input" type="text" placeholder="name..."> </div><br>
                <div class="body-modal">
                    <label class="radio-inline"><input type="radio" name="optradio" id="point"  checked >Point </label>
                    <label class="radio-inline" >
                    <input type="radio" name="optradio" id="line" >Line </label>
                    <label class="radio-inline" >
                    <input type="radio" name="optradio" id="polygon" >Polygon </label>
                </div> </center>
            </div>
            <div class="modal-footer" style="background-color: #1c2b4b;">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="getNameStatic()">OK</button>
            </div>
        </div>

        </div>
    </div>


<div class="navbar navbar-inverse navbar-fixed-top " id="menu">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index.php"><img class="logo-custom" src="../img/logo.png" alt="" height="50"
                                                  width="150"/></a>
        </div>
        <div class="navbar-collapse collapse move-me">
            <ul class="nav navbar-nav navbar-right">
                <?php
                    if ($_SESSION["username"]) {
                    ?>
                    <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">
                        <button type="button" class="btn btn-default btn-lg" data-toggle="modal"
                                data-target="#modal-logout"
                                style="font-size:14px;"> <?php echo $_SESSION["username"] ?> </button>
                        <br>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="../img/default-user.png" class="img-circle" alt="User Image">
                            <p>
                                <?php echo $_SESSION["username"] ?> <!--Username and Role-->
                                <!-- <small>Member since Nov. 2015</small> -->
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="../manage.php" class="btn btn-default btn-flat">Manage</a>
                            </div>
                            <div class="pull-right">
                                <button type="btn-defualt" class="btn btn-danger btn-flat" onclick="alert_logout()">Sign out</button>
                                <script>
                                    function alert_logout() {
                                        if (confirm("Do you want to log out?") == true) {
                                            window.location ='../logout.php'
                                        } else {
                                            window.close();
                                        }
                                    }
                                </script>
                            </div>
                        </li>
                    </ul>

                <?php
                }
                else {
                    ?>
                    <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#login"
                            style="font-size:14px;">Log in
                    </button><br>
                    <?php
                }
                ?>
                </li>
            </ul>
        </div>
    </div>


    <!-- Main Header -->
    <header class="main-header" style="z-index: -1">
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- deployment name -->
            <b style="float: left ;margin: 10px 50px;color:#ffffff">Deployment name : <a href="deployment.php" style="margin: 10px;color:#ffffff"> <?php echo $_SESSION['depname']  ?></a></b>

            <!-- Navbar Right Menu -->

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" class="sidebar-toggle" data-toggle="control-sidebar" role="button">
                            <span class="sr-only">Toggle navigation</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</div>
<!--NAVBAR SECTION-->

<!-- Content Wrapper. Contains page content -->
<!--Map content-->
<div style="margin-top:120px">
    <input id="pac-input" class="controls" type="text" placeholder="Search location...">
    <div id="map"></div>
</div>

<!-- Footer -->
<footer id="footer">

</footer>
<!-- FOOTER SECTION END-->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-map-marker" aria-hidden="false"></i> Marker</a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"> <i class="fa fa-upload" aria-hidden="false"></i> CSV File</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content" id="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-home-tab" >
            <h4><img src="../img/layer.png">Adding new static data layer:</h4>
            <input id="staticName" name="staticName" style="border-style: none; border-bottom: 1px solid #ccc; width:100%; padding-left:2em; background-color: #222d32;" readonly>
            <div class="input-group" style="margin: 10px">
                <button class="btn btn-warning btn-block" type="button" id="search-btn" onclick="drop()"><i class="fa fa-map-marker"></i> Add new point</button>
            </div><!-- /input-group -->

            <div class="panel-group" id="panel-group">
                <span id="mySpan"></span>
            </div>
        </div><!-- /.tab-pane -->

        <!-- Stats tab content -->
        <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->

        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <form method="post">
                <div class="input-group">
                    <h4><img src="../img/layer.png">Adding new static data layer:</h4>
                    <input id="staticName2" name="staticName2" style="border-style: none; border-bottom: 1px solid #ccc; width:100%; padding-left:2em; background-color: #222d32;" readonly>
                    <div >
                        <label class="control-label">Upload CSV File</label>
                        <div class="input-group">
                            <input id="input-1a" type="file" class="file" data-show-preview="false">
                        </div>
                        <button class="btn btn-default" type="button" id="upload" style="margin: 10px 20px 0px 0px" onclick="checkCSVFileUpload()">
                            upload
                        </button>
                        <p>__________________________________</p>
                        <p> Please download Template for csv file <a href='PointForm.xlsx' target="_blank">Download</a></p>


                    </div>
                </div><!-- /input-group -->

            </form>
        </div><!-- /.tab-pane -->
    </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
<!--</div>&lt;!&ndash; ./wrapper &ndash;&gt;-->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="../js/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../js/app.min.js"></script>

<script>
    var map;
    var markAdd;
    var markers = [];
    var lat=null;
    var lng=null;
    var polygonObj;
    var regtangObj;
    var firstPoint;
    var polygon = "MULTIPOLYGON(((";
    var service;
    var request;
    var centerArea;
    var nameArea;
    var num = 0;
    var contentString = "";


    // Get the modal
    $(document).ready(function(){
        $("#myModal").modal();
    });

    function getNameStatic(){
        var staticNameIP = document.getElementById("staticName-input").value;
        document.getElementById("staticName").value = staticNameIP;
        document.getElementById("staticName2").value = staticNameIP;

    }

    function getStaticType(){
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
                console.log(xmlhttp.responseText);
                obj = JSON.parse(xmlhttp.responseText);
                console.log(obj.status);

                  if (obj.status == "ok") {
                      alert("Successfully !");
                  }
                  else {
                      alert("No!!!");
                  }
              }
          }
          xmlhttp.open("GET","../../cgi-bin/checkStaticData.py?statictype="+statictype,true);
          xmlhttp.send();

    }

    function initMap() {

        centerArea = {lat: 13, lng: 100};

        map = new google.maps.Map(document.getElementById('map'), {
            center: centerArea,
            zoom: 8,
            maxZoom: 20
        });

        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.LEFT_TOP,
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

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });


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


    function changeStatus(id) {
        var x = document.getElementById("status").selectedIndex;
        var status = document.getElementsByTagName("option")[x].value;

        if(window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }
        else {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)//200=status ok!
            {
                console.log(xmlhttp.responseText);
                obj = JSON.parse(xmlhttp.responseText);

                if (obj.status == "ok") {
                    alert("Save Successfully");
                }

            }
        }
        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/cgi-bin/status.py?status="+status+"&id="+id+"&dbName=<?php echo $_SESSION['depname'] ?>",true);
        xmlhttp.send();
    }

    function drop() {
        if (markAdd!=null) {
          clearMarkers();
        }

        addMarkerWithTimeout(map.getCenter(), 1 * 200);
    }

    function addMarkerWithTimeout(position, timeout) {
        window.setTimeout(function() {
            infoWindow = new google.maps.InfoWindow();

            markAdd = new google.maps.Marker({
                position: position,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                number : 0 // edit by tee
            });
            markAdd.addListener("dblclick", function() {
              markAdd.setMap(null);
            });
            //edit by tee
            console.log(markAdd);
            google.maps.event.addListener(markAdd, 'drag', function(event) {
                lat = event.latLng.lat();
                lng = event.latLng.lng();

                var jsonStringGet = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ','+ lng +'&sensor=true';
                var json_obj = JSON.parse(GetJson(jsonStringGet));
                nameArea = json_obj.results[0].address_components[1].long_name;

                contentString = '<b>Location</b><br>' +
                  '<br><b>Name area: </b>' + nameArea +
                  '<br><b style="padding-top:2em;">Latitude: </b>' + lat +
                  '<br><b>Longitude: </b>' + lng +
                  '<br><b>Display Name: </b>' + '<input class="form-control" id="name" >' +
                //   '<br><b>Message: </b>' + '<textarea class="form-control" id="msg" style="resize:none;"></textarea>' +
                  '<input type="button" class="btn-primary btn-block" value="OK" style="margin-top: 5px;" onclick="showListStatic('+lat+','+lng+')">';
                infoWindow.setContent(contentString);
                infoWindow.open(map,markAdd);

            });
            google.maps.event.addListener(markAdd, 'click', function(event) {
                lat = event.latLng.lat();
                lng = event.latLng.lng();

                var jsonStringGet = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ','+ lng +'&sensor=true';
                var json_obj = JSON.parse(GetJson(jsonStringGet));
                nameArea = json_obj.results[0].address_components[1].long_name;


                contentString = '<b>Location</b><br>' +
                  '<br><b>Name area: </b>' +  nameArea +
                  '<br><b style="padding-top:2em;">Latitude: </b>' + lat +
                  '<br><b>Longitude: </b>' + lng +
                  '<br><b>Display Name: </b>' + '<input class="form-control" id="name" >' +
                //   '<br><b>Message: </b>' + '<textarea class="form-control" id="msg" style="resize:none;"></textarea>' +
                  '<input type="button" class="btn-primary btn-block" value="OK" style="margin-top: 5px;" onclick="showListStatic('+lat+','+lng+'">';


                infoWindow.setContent(contentString);
                infoWindow.open(map,markAdd);

            });



        }, timeout);
    }
    var array = [];


     function showListStatic(lat,lng,nameArea){
        var name = document.getElementById("name").value;
        var mySpan = document.getElementById('mySpan');
        var addDict = {};
        addDict['name_area'] = nameArea;
        addDict['latitude'] = lat;
        addDict['longitude'] = lng;
        addDict['display_name'] = name;
        addDict['status'] = "none";
        console.log(addDict);
        // array.push({nameE:name, latT:lat, lngG:lng});
        array.push(addDict);
        var panel = document.createElement('div');
        panel.className = 'panel panel-default';

        document.getElementById('panel-group').appendChild(panel);

        var heading = document.createElement('div');
        heading.className = 'panel-heading';

        var title = document.createElement('h4');
        title.className = 'panel-title';
        title.innerHTML = '<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" id = "nameStatic' +num +'"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></a>';
        heading.appendChild(title);

        var collapse = document.createElement('div');
        collapse.className = 'panel-collapse collapse';
        collapse.id = 'collapse1';

        var body = document.createElement('div');
        collapse.className = 'panel-body';
        collapse.id = 'listStaticDataLayer' + num;
        collapse.appendChild(body);

        panel.appendChild(heading);
        panel.appendChild(collapse);

        for(var i=0;i<array.length;i++){

            document.getElementById('nameStatic' + num ).innerHTML = array[i].nameE;

            document.getElementById('listStaticDataLayer' + num).innerHTML = "Name: " + array[i].display_name + "<br>" +
            // "Message: " + array[i].msgG + "<br>" +
            "Lat: " + array[i].latitude + "<br>" +
            "Long: " +  array[i].longitude + "<br>"  ;
        }

        num++;
        // xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/cgi-bin/insertRequest.py?name="+name+"&msg="+msg+"&lat="+lat+"&lng="+lng+"&dbName=<?php echo $_SESSION['depname'] ?>",true);
        // xmlhttp.send();
    }

    function checkCSVFileUpload() {

    }

    function GetJson(yourUrl){
        var Httpreq = new XMLHttpRequest(); // a new request
        Httpreq.open("GET",yourUrl,false);
        Httpreq.send(null);
        return Httpreq.responseText;
    }

    function callback(results, status) {

        if (status == google.maps.places.PlacesServiceStatus.OK) {
            document.getElementById("nameArea").innerHTML = "Name: " + results.name;
        }
        console.log(results);
        //document.getElementById("nameArea").innerHTML = "Tstssss: "
    }

    function saveRequest(lat,lng){

        var x = document.getElementById("type").selectedIndex;
        var name = document.getElementsByTagName("name").value;
        var msg = document.getElementById("msg").value;

        if(window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }
        else {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)//200=status ok!
            {
                obj = JSON.parse(xmlhttp.responseText);

                if (obj.status == "ok") {
                    alert("Insert data successfully");
                }
                clearMarkers();

            }
        }
        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/cgi-bin/insertRequest.py?name="+name+"&msg="+msg+"&lat="+lat+"&lng="+lng+"&dbName=<?php echo $_SESSION['depname'] ?>",true);
        xmlhttp.send();
    }

    function clearMarkers(){
        // markAdd.setMap(null);
        markAdd=null;
    }



    function displayDataLayer() {

        if (document.getElementById("Thai").checked == true) {
            Province = new google.maps.ImageMapType({
                getTileUrl: function (tile, zoom) {
                    return "http://" +"<?php echo $_SESSION['host'] ?>" +":8080/geoserver/gwc/service/tms/1.0.0/province%3Al050302_gistda_50k_sim_1km_resolve@EPSG%3A900913@png/"+zoom+"/"+tile.x+"/"+((Math.pow(2,zoom)-tile.y)-1)+".png";
                },
                maxZoom: 20,
                name: "Flood_Layer",
                tileSize: new google.maps.Size(256, 256),
                isPng: true
              });

            map.overlayMapTypes.setAt(6, Province);
        }
        else{
            map.overlayMapTypes.clear();
        };
    }

    function change(){
        var xmlhttp;
        var oldhash = document.getElementById("hashShow").value;
        var newhash = document.getElementById("add-hash").value;

        if(window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }
        else {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        if(confirm("Do you want to add hashtag \""+newhash+"\" ?") == true){
          document.getElementById("hashShow").value = oldhash + " #" + newhash;
          document.getElementById("add-hash").value = "";

          xmlhttp.onreadystatechange=function()
          {
              if (xmlhttp.readyState==4 && xmlhttp.status==200)//200=status ok!
              {
                  console.log(xmlhttp.responseText);
                  obj = JSON.parse(xmlhttp.responseText);

                  if (obj.status == "ok") {
                      alert("Update Hashtag successfully");
                  }
                  else {
                      alert("Cann't update Hashtag!!");
                  }

              }
          }
        }

        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/cgi-bin/addHashTag.py?dbName=<?php echo $_SESSION['depname'] ?>&hashtag="+newhash,true);
        xmlhttp.send();
    }

</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChsKVqmyv9qxepQVE9qlnUj8sXbsuQrhs&libraries=places,drawing&callback=initMap"
    async defer></script>
</body>
</html>
