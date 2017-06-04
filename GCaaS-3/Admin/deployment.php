<?php
include "../twitteroauth.php";
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?v=AIzaSyChsKVqmyv9qxepQVE9qlnUj8sXbsuQrhs&signed_in=true&libraries=drawing"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
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
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <h4 class="control-sidebar-heading">Dynamic data layers</h4>
            <form role="form">
                <div class="checkbox">
                    <label><input type="checkbox" id="twitter" value="checked" onclick="dynamicTwitter()"><img
                            src="../img/marker/twitter.png">Twitter</label>
                </div>
            </form>
            <h4 class="control-sidebar-heading">Static data layers</h4>
            <form role="form">
                <div>
                    <div class="checkbox">
                        <label><input type="checkbox" id="Thai" value="checked" onclick="displayDataLayer()"><img src="../img/thailand.png">Province Thailand</label>
                    </div>
                    <!--checkbox hospital-->
                    <div class="checkbox" id="check-hos">
                        <label><input type="checkbox" id="hospital" name="hCheckbox" value="checked" onclick="staticHospital()">
                            <x data-toggle="collapse" href="#hospital-list" onclick="checkHos()"><img src="../img/marker/hospital.png">Hospitals
                        </label></x>
                        <div id="hospital-list" class="collapse hide" style="padding-left: 20px">
                            </div>
                    </div><!--END checkbox hospital-->
                    <!--checkbox school-->
                    <div class="checkbox" id="check-sch">
                        <label><input type="checkbox" id="school" name="sCheckbox" value="checked" onclick="staticSchool()">
                            <x data-toggle="collapse" href="#school-list" onclick="checkSch()"><img src="../img/marker/school.png">Schools
                        </label></x>
                        <div id="school-list" class="collapse hide" style="padding-left: 20px">
                           </div>
                    </div><!--END checkbox school-->
                    <div class="checkbox">
                        <label><input type="checkbox" id="police" value="checked" onclick="staticPolice()"><img
                                src="../img/marker/police.png">Police stations</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" id="fire" value="checked" onclick="staticFire()"><img
                                src="../img/marker/fire.png">Fire stations</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" id="temple" value="tChecked" onclick="staticTemple()">
                            <x data-toggle="collapse" href="#temple-list" onclick="checkTemp()"><img src="../img/marker/temple.png">Temples
                        </label></x>
                        <div id="temple-list" class="collapse hide" style="padding-left: 20px">
                            </div>
                    </div>
                     <?php
                        require('../connectDB.php');
                        $result_staticID_array = array();
                        $result_staticDataLayer_Name_array = array();
                        if (! $_SESSION['connection']) {
                            echo "Connection Failed.";
                            exit;
                        }
                        else {

                            $result_staticID = pg_exec($_SESSION['connection'], "SELECT \"staticID\", \"staticDataLayer_Name\" FROM \"table_staticDataLayer\"  WHERE \"deployment_Name\" = '".$_SESSION['depname'] ."'" );
                            $rows_deploy = pg_numrows($result_staticID);
                            $column_deploy = pg_numfields($result_staticID);
                            if($rows_deploy != 0) {
                                for ($i = 0; $i < $rows_deploy; $i++) {
                                    for ($j = 0; $j < $column_deploy; $j++) {
                                         if ($j == 0) {
                                             $result_staticID_array[$i] = pg_result($result_staticID,$i,0); 
                                            //  echo $result_staticID_array[$i] ;
                                         }
                                         else{
                                             $result_staticDataLayer_Name_array[$i] = pg_result($result_staticID,$i,$j); 
                                            //  echo $result_staticDataLayer_Name_array[$i] ;
                                         }                                       
                                    } 
                                         
                                }
                                for ($i = 0; $i < $rows_deploy; $i++) {
                                    echo '<div class="checkbox">
                                        <label><input type="checkbox" id="dynamicLayer'.$i .'" value="checked" onclick="dynamicLayer()"><img
                                                src="../img/marker/marker-icon.png">'. $result_staticDataLayer_Name_array[$i] .'</label>
                                    </div>';
                              
                                }
                            }
                                                    
                        }
                  
                     ?>
                </div>

            </form>
        </div><!-- /.tab-pane -->

        <!-- Stats tab content -->
        <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->

        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <form method="post">
                <h3 class="control-sidebar-heading">General Settings</h3>
                <div class="input-group">
                    <h4><a data-toggle="collapse" href="#layer-menu"><img src="../img/layer.png"> Static data layer</a>
                    </h4>
                    <div class="collapse" id="layer-menu">
                        <label class="control-label">Add Static data layer</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="add-layer" placeholder="choose shape file">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="open"><i class="fa fa-folder-open" aria-hidden="true"></i> </button>
                            </span>
                        </div>
                        <button class="btn btn-default" type="button" id="upload" style="margin: 10px 20px 0px 0px">
                            upload
                        </button>
                    </div>
                </div><!-- /input-group -->
                <div class="input-group">
                    <h4><a data-toggle="collapse" href="#twitter-menu"><img src="../img/twitter.png"> Twitter</a></h4>
                    <div class="collapse" id="twitter-menu">
                        <label class="control-label">Add Hashtag (#)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="add-hash" placeholder="Ex.#floodTH ...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="ok"><i class="fa fa-check"
                                                                                         aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div><!-- /input-group -->
                <h4><a data-toggle="collapse" href=""><img src="../img/facebook.png"> Facebook</a></h4>
                <h4><a data-toggle="collapse" href=""><img src="../img/youtube.png"> Youtube</a></h4>
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
    var markerHos=[];
    var markerHosClus=[];
    var markerHosCluster;
    var markerHosGov=[];
    var markerHosGovClus=[];
    var markerHosGovCluster;
    var markerHosPriv=[];
    var markerHosPrivClus=[];
    var markerHosPrivCluster;
    var markerHosHeal=[];
    var markerHosHealClus=[];
    var markerHosHealCluster;
    var markerSchGov=[];
    var markerSchGovClus=[];
    var markerSchGovCluster;
    var markerSch=[];
    var markerSchClus=[];
    var markerSchCluster;
    var markerSchPriv=[];
    var markerSchPrivClus=[];
    var markerSchPrivCluster;
    var markerSchUni=[];
    var markerSchUniClus=[];
    var markerSchUniCluster;
    var markerPol=[];
    var markerPolClus=[];
    var markerPolCluster;
    var markerFir=[];
    var markerFirClus=[];
    var markerFirCluster;
    var markerTem=[];
    var markerTemClus=[];
    var markerTemCluster;
    var markerTemple=[];
    var markerTempleClus=[];
    var markerTempleCluster;
    var markerChurch=[];
    var markerChurchClus=[];
    var markerChurchCluster;
    var markerMus=[];
    var markerMusClus=[];
    var markerMusCluster;
    var markerTW=[];
    var markerTWClus = [];
    var markerTWCluster;
    var markerDynamicLayer=[];
    var markerDynamicLayerClus=[];
    var markerDynamicLayerCluster;
    var markAdd;
    var lat=null;
    var lng=null;
    var temple = '../img/marker/temple.png';
    var hospital = '../img/marker/hospital.png';
    var police = '../img/marker/police.png';
    var fire = '../img/marker/fire.png';
    var school = '../img/marker/school.png';
    var twitter = '../img/marker/twitter.png';
    var marker_icon = '../img/marker/marker-icon.png';
    var polygonObj;
    var regtangObj;
    var firstPoint;
    var polygon = "MULTIPOLYGON(((";

    setInterval( function(){

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
            // console.log(xmlhttp.responseText);
            // var obj = JSON.parse(xmlhttp.responseText);
            // console.log(obj);
          }
        }
        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/fetchTW.php",true);
        xmlhttp.send();
    },3000);


    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 13, lng: 100},
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
              searchByPolygon();
                google.maps.event.addListener(regtangObj, 'bounds_changed', searchByPolygon);
            }

            else if (e.type == google.maps.drawing.OverlayType.POLYGON)
            {
              polygonObj = e.overlay;
              if (polygonObj.getPath().getLength()>=3) {
                searchByPolygon();
                google.maps.event.addListener(polygonObj.getPath(), 'insert_at', searchByPolygon);
                google.maps.event.addListener(polygonObj.getPath(), 'set_at', searchByPolygon);
              }
              else{
                alert("ใส่จุดอย่างน้อย3จุด");
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

    function dynamicLayer(){
        var xmlhttp;
        var myLatlng;
        rows_deploy = <?php echo $rows_deploy; ?>;
        result_staticID_array =  parseInt(<?php echo json_encode($result_staticID_array); ?>);
        result_staticDataLayer_Name_array = <?php echo json_encode($result_staticDataLayer_Name_array); ?>;
        console.log(rows_deploy);
        //console.log(result_staticID_array);
        //console.log(result_staticDataLayer_Name_array);

        if (markAdd!=null) {
            clearMarkers();
        }
        
        for (var i = 0; i < markerDynamicLayer.length; i++) {
          markerDynamicLayer[i].setMap(null);
        }
        
        console.log(
            "length : " + rows_deploy
        );
        for (var i = 0; i < rows_deploy; i++) {
            if (document.getElementById('dynamicLayer'+i).checked == true) {
                console.log(1111111);
                if(window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else {
                    // code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)//200=status ok!
                    {
                        console.log(result_staticDataLayer_Name_array[i]);
                        for (var y = 0; y < markerDynamicLayer.length; y++) {
                            markerDynamicLayer[y].setMap(null);
                        }
                        var infowindow;
                        myLatlng = JSON.parse(xmlhttp.responseText);
                        console.log(myLatlng);
                        for (var i = 0; i < myLatlng.length; i++) {
                            var contentStr = '<div id="content">'+
                            '<div id="siteNotice">'+
                            '</div>'+
                            '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                            '<div id="bodyContent">'+
                            '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                            '</div>';
                            if (i == myLatlng.length-1) {
                                var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                                console.log(myLatlng[i].latitude);
                                markerDynamicLayer[i] = new google.maps.Marker({
                                    position: latlng,
                                    map: map,
                                    icon: marker_icon,
                                    title: myLatlng[i].name,
                                    info: new google.maps.InfoWindow({
                                        content: contentStr
                                    })
                                });

                                infowindow = markerDynamicLayer[i].info;
                                google.maps.event.addListener(markerDynamicLayer[i], 'click', function() {
                                    for(var i =0;i<=markerDynamicLayer.length-1;i++){
                                        markerDynamicLayer[i].info.close();
                                    }
                                    this.info.open(map,this);
                                });
                                map.setCenter(latlng);
                                map.setZoom(10);
                            }
                            else{
                                markerDynamicLayer[i] = new google.maps.Marker({
                                    position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                    map: map,
                                    icon: marker_icon,
                                    title: myLatlng[i].name,
                                    info: new google.maps.InfoWindow({
                                        content: contentStr
                                    })
                                });

                                infowindow = markerDynamicLayer[i].info;
                                google.maps.event.addListener(markerDynamicLayer[i], 'click', function() {
                                    for(var i =0;i<=markerDynamicLayer.length-1;i++){
                                        markerDynamicLayer[i].info.close();
                                    }
                                    this.info.open(map,this);
                                });
                            }
                            markerDynamicLayerClus.push(markerDynamicLayer[i]);
                        }
                        markerDynamicLayerCluster = new MarkerClusterer(map, markerDynamicLayerClus);
                    }
                }
                
                xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/getListDataLayer.py?typeStatic="+ result_staticDataLayer_Name_array[i] +"&depname=<?php echo $_SESSION['depname'] ?>" ,true);
                xmlhttp.send();
            }
            else{
                for (var j = 0; j < markerDynamicLayer.length; j++) {
                    markerDynamicLayer[j].setMap(null);
                }
                for (var k = 0; k < markerDynamicLayerClus.length; k++) {
                    markerDynamicLayerClus[k].setMap(null);
                }
                markerDynamicLayerClus = [];
                markerDynamicLayer = [];
                if(markerDynamicLayerCluster ){
                    markerDynamicLayerCluster.clearMarkers();
                }
                
                map.setCenter(new google.maps.LatLng(13, 100));
                map.setZoom(8);
                console.log("else");
            }
            
        }

    }

    function staticHospital() {
        var xmlhttp;
        var myLatlng;

        $("#hospital-list").collapse('show');
        checkHos();

        if (markAdd!=null) {
            clearMarkers();
        }

        for (var i = 0; i < markerHos.length; i++) {
          markerHos[i].setMap(null);
        }

        if (document.getElementById('hospital').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=All Hospital",true);
            xmlhttp.send();
        }
        else{
            for (var i = 0; i < markerHos.length; i++) {
              markerHos[i].setMap(null);
            }
            for (var i = 0; i < markerHosClus.length; i++) {
              markerHosClus[i].setMap(null);
            }
            markerHosClus = [];
            markerHos = [];
            markerHosCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticSomeHospital() {
        var xmlhttp;
        var myLatlng;

        if (markAdd!=null) {
            clearMarkers();
        }

        if (document.getElementById('h1').checked == true) {
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
                    for (var i = 0; i < markerHosGov.length; i++) {
                      markerHosGov[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHosGov[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHosGov[i].info;
                            google.maps.event.addListener(markerHosGov[i], 'click', function() {
                                for(var i =0;i<=markerHosGov.length-1;i++){
                                    markerHosGov[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHosGov[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHosGov[i].info;
                            google.maps.event.addListener(markerHosGov[i], 'click', function() {
                                for(var i =0;i<=markerHosGov.length-1;i++){
                                    markerHosGov[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosGovClus.push(markerHosGov[i]);
                    }
                    markerHosGovCluster = new MarkerClusterer(map, markerHosGovClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Government Hospital",true);
            xmlhttp.send();
        }

        else {
            for (var i = 0; i < markerHosGov.length; i++) {
              markerHosGov[i].setMap(null);
            }
            for (var i = 0; i < markerHosGovClus.length; i++) {
              markerHosGovClus[i].setMap(null);
            }
            markerHosGovClus = [];
            markerHosGov = [];
            markerHosGovCluster.clearMarkers();
        }

        if (document.getElementById('h2').checked == true) {
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
                    for (var i = 0; i < markerHosPriv.length; i++) {
                      markerHosPriv[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHosPriv[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHosPriv[i].info;
                            google.maps.event.addListener(markerHosPriv[i], 'click', function() {
                                for(var i =0;i<=markerHosPriv.length-1;i++){
                                    markerHosPriv[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHosPriv[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHosPriv[i].info;
                            google.maps.event.addListener(markerHosPriv[i], 'click', function() {
                                for(var i =0;i<=markerHosPriv.length-1;i++){
                                    markerHosPriv[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosPrivClus.push(markerHosPriv[i]);
                    }
                    markerHosPrivCluster = new MarkerClusterer(map, markerHosPrivClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Private Hospital",true);
            xmlhttp.send();
        }

        else {
            for (var i = 0; i < markerHosPriv.length; i++) {
              markerHosPriv[i].setMap(null);
            }
            for (var i = 0; i < markerHosPrivClus.length; i++) {
              markerHosPrivClus[i].setMap(null);
            }
            markerHosPrivClus = [];
            markerHosPriv = [];
            markerHosPrivCluster.clearMarkers();
        }

        if (document.getElementById('h3').checked == true) {
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
                    for (var i = 0; i < markerHosHeal.length; i++) {
                      markerHosHeal[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHosHeal[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHosHeal[i].info;
                            google.maps.event.addListener(markerHosHeal[i], 'click', function() {
                                for(var i =0;i<=markerHosHeal.length-1;i++){
                                    markerHosHeal[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHosHeal[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: hospital,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHosHeal[i].info;
                            google.maps.event.addListener(markerHosHeal[i], 'click', function() {
                                for(var i =0;i<=markerHosHeal.length-1;i++){
                                    markerHosHeal[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosHealClus.push(markerHosHeal[i]);
                    }
                    markerHosHealCluster = new MarkerClusterer(map, markerHosHealClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Health Center",true);
            xmlhttp.send();
        }

        else{
            for (var i = 0; i < markerHosHeal.length; i++) {
              markerHosHeal[i].setMap(null);
            }
            for (var i = 0; i < markerHosHealClus.length; i++) {
              markerHosHealClus[i].setMap(null);
            }
            markerHosHealClus = [];
            markerHosHeal = [];
            markerHosHealCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticSchool() {
        var xmlhttp;
        var myLatlng;

        $("#school-list").collapse('show');
        checkSch();

        if (markAdd!=null) {
            clearMarkers();
        }

        for (var i = 0; i < markerSch.length; i++) {
          markerSch[i].setMap(null);
        }

        if (document.getElementById('school').checked == true) {
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
                    for (var i = 0; i < markerSch.length; i++) {
                      markerSch[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerSch[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerSch[i].info;
                            google.maps.event.addListener(markerSch[i], 'click', function() {
                                for(var i =0;i<=markerSch.length-1;i++){
                                    markerSch[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerSch[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerSch[i].info;
                            google.maps.event.addListener(markerSch[i], 'click', function() {
                                for(var i =0;i<=markerSch.length-1;i++){
                                    markerSch[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerSchClus.push(markerSch[i]);
                    }
                    markerSchCluster = new MarkerClusterer(map, markerSchClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=All School",true);
            xmlhttp.send();
        }
        else{
            for (var i = 0; i < markerSch.length; i++) {
              markerSch[i].setMap(null);
            }
            for (var i = 0; i < markerSchClus.length; i++) {
              markerSchClus[i].setMap(null);
            }
            markerSchClus = [];
            markerSch = [];
            markerSchCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticSomeSchool() {
        var xmlhttp;
        var myLatlng;

        if (markAdd!=null) {
            clearMarkers();
        }

        if (document.getElementById('s1').checked == true && document.getElementById('s2').checked == true) {
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

                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Government and Private School",true);
            xmlhttp.send();
        }

        if (document.getElementById('s1').checked == true && document.getElementById('s3').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Government School and University",true);
            xmlhttp.send();
        }

        if (document.getElementById('s2').checked == true && document.getElementById('s3').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Private School and University",true);
            xmlhttp.send();
        }

        if (document.getElementById('s1').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Government School",true);
            xmlhttp.send();
        }

        if (document.getElementById('s2').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Private School",true);
            xmlhttp.send();
        }

        if (document.getElementById('s3').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: school,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=University",true);
            xmlhttp.send();
        }

        else{
            for (var i = 0; i < markerHos.length; i++) {
              markerHos[i].setMap(null);
            }
            for (var i = 0; i < markerHosClus.length; i++) {
              markerHosClus[i].setMap(null);
            }
            markerHosClus = [];
            markerHos = [];
            markerHosCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticPolice() {
        var xmlhttp;
        var myLatlng;

        if (markAdd!=null) {
            clearMarkers();
        }

        for (var i = 0; i < markerPol.length; i++) {
          markerPol[i].setMap(null);
        }

        if (document.getElementById('police').checked == true) {
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
                    for (var i = 0; i < markerPol.length; i++) {
                      markerPol[i].setMap(null);
                    }
                    var infowindow;
                    console.log(xmlhttp.responseText);
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerPol[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: police,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerPol[i].info;
                            google.maps.event.addListener(markerPol[i], 'click', function() {
                                for(var i =0;i<=markerPol.length-1;i++){
                                    markerPol[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerPol[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: police,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerPol[i].info;
                            google.maps.event.addListener(markerPol[i], 'click', function() {
                                for(var i =0;i<=markerPol.length-1;i++){
                                    markerPol[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerPolClus.push(markerPol[i]);
                    }
                    markerPolCluster = new MarkerClusterer(map, markerPol);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=All Police station",true);
            xmlhttp.send();
        }
        else{
            for (var i = 0; i < markerPol.length; i++) {
              markerPol[i].setMap(null);
            }
            for (var i = 0; i < markerPolClus.length; i++) {
              markerPolClus[i].setMap(null);
            }
            markerPolClus = [];
            markerPol = [];
            markerPolCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticFire() {
        var xmlhttp;
        var myLatlng;

        if (markAdd!=null) {
            clearMarkers();
        }

        for (var i = 0; i < markerFir.length; i++) {
          markerFir[i].setMap(null);
        }

        if (document.getElementById('fire').checked == true) {
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
                    for (var i = 0; i < markerFir.length; i++) {
                      markerFir[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerFir[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: fire,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerFir[i].info;
                            google.maps.event.addListener(markerFir[i], 'click', function() {
                                for(var i =0;i<=markerFir.length-1;i++){
                                    markerFir[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerFir[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: fire,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerFir[i].info;
                            google.maps.event.addListener(markerFir[i], 'click', function() {
                                for(var i =0;i<=markerFir.length-1;i++){
                                    markerFir[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerFirClus.push(markerFir[i]);
                    }
                    markerFirCluster = new MarkerClusterer(map, markerFirClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=All Fire station",true);
            xmlhttp.send();
        }
        else{
            for (var i = 0; i < markerFir.length; i++) {
              markerFir[i].setMap(null);
            }
            for (var i = 0; i < markerFirClus.length; i++) {
              markerFirClus[i].setMap(null);
            }
            markerFirClus = [];
            markerFir = [];
            markerFirCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticTemple() {
        var xmlhttp;
        var myLatlng;

        if (markAdd!=null) {
            clearMarkers();
        }

        for (var i = 0; i < markerTem.length; i++) {
          markerTem[i].setMap(null);
        }

        if (document.getElementById('temple').checked == true) {
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
                    for (var i = 0; i < markerTem.length; i++) {
                      markerTem[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerTem[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerTem[i].info;
                            google.maps.event.addListener(markerTem[i], 'click', function() {
                                for(var i =0;i<=markerTem.length-1;i++){
                                    markerTem[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerTem[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerTem[i].info;
                            google.maps.event.addListener(markerTem[i], 'click', function() {
                                for(var i =0;i<=markerTem.length-1;i++){
                                    markerTem[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerTemClus.push(markerTem[i]);
                    }
                    markerTemCluster = new MarkerClusterer(map, markerTemClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=All Temple",true);
            xmlhttp.send();
        }
        else{
            for (var i = 0; i < markerTem.length; i++) {
              markerTem[i].setMap(null);
            }
            for (var i = 0; i < markerTemClus.length; i++) {
              markerTemClus[i].setMap(null);
            }
            markerTemClus = [];
            markerTem = [];
            markerTemCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function staticSomeTemple() {
        var xmlhttp;
        var myLatlng;

        if (markAdd!=null) {
            clearMarkers();
        }

        if (document.getElementById('t1').checked == true && document.getElementById('t2').checked == true) {
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

                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Temple and Church",true);
            xmlhttp.send();
        }

        if (document.getElementById('t1').checked == true && document.getElementById('t3').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Temple and Muslim",true);
            xmlhttp.send();
        }

        if (document.getElementById('t2').checked == true && document.getElementById('t3').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Church and Muslim",true);
            xmlhttp.send();
        }

        if (document.getElementById('t1').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Temple",true);
            xmlhttp.send();
        }

        if (document.getElementById('t2').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Church",true);
            xmlhttp.send();
        }

        if (document.getElementById('t3').checked == true) {
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
                    for (var i = 0; i < markerHos.length; i++) {
                      markerHos[i].setMap(null);
                    }
                    var infowindow;
                    myLatlng = JSON.parse(xmlhttp.responseText);
                    for (var i = 0; i < myLatlng.length; i++) {
                        var contentStr = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                        '<div id="bodyContent">'+
                        '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                        '</div>';

                        if (i == myLatlng.length-1) {
                            var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                            markerHos[i] = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                    content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                              this.info.open(map,this);
                            });
                            map.setCenter(latlng);
                            map.setZoom(10);
                        }
                        else{
                            markerHos[i] = new google.maps.Marker({
                                position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                map: map,
                                icon: temple,
                                title: myLatlng[i].name,
                                info: new google.maps.InfoWindow({
                                content: contentStr
                                })
                            });

                            infowindow = markerHos[i].info;
                            google.maps.event.addListener(markerHos[i], 'click', function() {
                                for(var i =0;i<=markerHos.length-1;i++){
                                    markerHos[i].info.close();
                                }
                                this.info.open(map,this);
                            });
                        }
                        markerHosClus.push(markerHos[i]);
                    }
                    markerHosCluster = new MarkerClusterer(map, markerHosClus);
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/staticData.py?typeStatic=Muslim",true);
            xmlhttp.send();
        }

        else{
            for (var i = 0; i < markerHos.length; i++) {
              markerHos[i].setMap(null);
            }
            for (var i = 0; i < markerHosClus.length; i++) {
              markerHosClus[i].setMap(null);
            }
            markerHosClus = [];
            markerHos = [];
            markerHosCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
        };
    }

    function dynamicTwitter(){
        var xmlhttp;
        var myLatlng;

        for (var i = 0; i < markerTW.length; i++) {
          markerTW[i].setMap(null);
        }

        if (document.getElementById('twitter').checked == true) {
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
                    if (obj.status != "none") {
                        for (var i = 0; i < markerTW.length; i++) {
                          markerTW[i].setMap(null);
                        }

                        var infowindow;
                        myLatlng = JSON.parse(xmlhttp.responseText);
                        for (var i = 0; i < myLatlng.length; i++) {
                            if (myLatlng[i].status == "New"){
                                var contentStr = '<div id="content">'+
                                '<div id="siteNotice">'+
                                '</div>'+
                                '<h3 id="firstHeading" class="firstHeading">' + '<img src =\"' + myLatlng[i].profileImg + '\" style="width:52px; height:52px;">' + "&nbsp&nbsp&nbsp" + myLatlng[i].name +'</h3>'+
                                '<div id="bodyContent">'+
                                '<p><b>Message </b>: '+ myLatlng[i].msg +'</p>'+
                                '<p><b>Date of tweets </b>: '+ myLatlng[i].date +'</p>'+
                                '<p><b>Status </b>: '+ myLatlng[i].status +
                                '<p><b>Location </b>: '+ myLatlng[i].place +'</p>'+
                                '<p><b>Latitude </b>: '+ myLatlng[i].latitude +' <b>Longitude </b>: '+ myLatlng[i].longitude +'</p>'+
                                '</div>';
                            }
                            else if (myLatlng[i].status == "Visited") {
                                var contentStr = '<div id="content">'+
                                '<div id="siteNotice">'+
                                '</div>'+
                                '<h3 id="firstHeading" class="firstHeading">' + '<img src =\"' + myLatlng[i].profileImg + '\" style="width:52px; height:52px;">' + "&nbsp&nbsp&nbsp" + myLatlng[i].name +'</h3>'+
                                '<div id="bodyContent">'+
                                '<p><b>Message </b>: '+ myLatlng[i].msg +'</p>'+
                                '<p><b>Date of tweets </b>: '+ myLatlng[i].date +'</p>'+
                                '<p><b>Status </b>: '+ myLatlng[i].status +
                                '<p><b>Location </b>: '+ myLatlng[i].place +'</p>'+
                                '<p><b>Latitude </b>: '+ myLatlng[i].latitude +' <b>Longitude </b>: '+ myLatlng[i].longitude +'</p>'+
                                '</div>';
                            }
                            else{
                                var contentStr = '<div id="content">'+
                                '<div id="siteNotice">'+
                                '</div>'+
                                '<h3 id="firstHeading" class="firstHeading">' + '<img src =\"' + myLatlng[i].profileImg + '\" style="width:52px; height:52px;">' + "&nbsp&nbsp&nbsp" + myLatlng[i].name +'</h3>'+
                                '<div id="bodyContent">'+
                                '<p><b>Message </b>: '+ myLatlng[i].msg +'</p>'+
                                '<p><b>Date of tweets </b>: '+ myLatlng[i].date +'</p>'+
                                '<p><b>Status </b>: '+ myLatlng[i].status +
                                '<p><b>Location </b>: '+ myLatlng[i].place +'</p>'+
                                '<p><b>Latitude </b>: '+ myLatlng[i].latitude +' <b>Longitude </b>: '+ myLatlng[i].longitude +'</p>'+
                                '</div>';
                            };


                            if (i == myLatlng.length-1) {
                                var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                                markerTW[i] = new google.maps.Marker({
                                    position: latlng,
                                    map: map,
                                    icon: twitter,
                                    title: myLatlng[i].name,
                                    info: new google.maps.InfoWindow({
                                       content: contentStr
                                    })
                                });

                                infowindow = markerTW[i].info;
                                google.maps.event.addListener(markerTW[i], 'click', function() {
                                    for(var i =0;i<=markerTW.length-1;i++){
                                        markerTW[i].info.close();
                                    }
                                    this.info.open(map,this);
                                });
                                map.setCenter(latlng);
                                map.setZoom(10);
                            }
                            else{
                                markerTW[i] = new google.maps.Marker({
                                    position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                    map: map,
                                    icon: twitter,
                                    title: myLatlng[i].name,
                                    info: new google.maps.InfoWindow({
                                        content: contentStr
                                    })
                                });

                                infowindow = markerTW[i].info;
                                google.maps.event.addListener(markerTW[i], 'click', function() {
                                    for(var i =0;i<=markerTW.length-1;i++){
                                        markerTW[i].info.close();
                                    }
                                    this.info.open(map,this);
                                });
                            }
                            // markerTWClus.push(markerTW[i]);
                        }
                    }
                    else{
                        alert("Didn't have Dynamic data layer")
                    };
                }
            }
            xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/dynamicData.py?dbName=<?php echo $_SESSION['depname'] ?>",true);
            xmlhttp.send();
        }
        else{
            for (var i = 0; i < markerTW.length; i++) {
              markerTW[i].setMap(null);
            }
            // for (var i = 0; i < markerTWClus.length; i++) {
            //   markerTWClus[i].setMap(null);
            // }
            // markerTWClus = [];
            // markerTW = [];
            // markerTWCluster.clearMarkers();
            map.setCenter(new google.maps.LatLng(13, 100));
            map.setZoom(8);
            // clearInterval(tw);
        };
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
                obj = JSON.parse(xmlhttp.responseText);

                if (obj.status == "ok") {
                    alert("Save Successfully");
                }

            }
        }
        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/status.py?status="+status+"&id="+id+"&dbName=<?php echo $_SESSION['depname'] ?>",true);
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
                animation: google.maps.Animation.DROP
            });

            google.maps.event.addListener(markAdd, 'drag', function(event) {
                lat = event.latLng.lat();
                lng = event.latLng.lng();

                var contentString = '<b>Location</b><br>' +
                  'Latitude: ' + lat + ', ' +
                  'Longitude: ' + lng + '<br><b>Type: </b>' + '<select name="type" id="type">' + '<option value="Help">Help</option>' +
                  '<option value="Road">Road</option>' + '</select>' + '<br><b>Message: </b>' + '<textarea class="form-control" id="msg" style="resize:none;"></textarea>' +
                  '<input type="button" class="btn-primary btn-block" value="OK" style="margin-top: 10px;" onclick="saveRequest('+lat+','+lng+')">';
                infoWindow.setContent(contentString);
                infoWindow.open(map,markAdd);
            });
            google.maps.event.addListener(markAdd, 'click', function(event) {
                lat = event.latLng.lat();
                lng = event.latLng.lng();

                var contentString = '<b>Location</b><br>' +
                  'Latitude: ' + lat + ', ' +
                  'Longitude: ' + lng + '<br><b>Type: </b>' + '<select name="type" id="type">' + '<option value="help">Help</option>' +
                  '<option value="road">Road</option>' + '</select>' + '<br><b>Message: </b>' + '<textarea class="form-control" id="msg" style="resize:none;"></textarea>' +
                  '<input type="button" class="btn-primary btn-block" value="OK" style="margin-top: 10px;" onclick="saveRequest('+lat+','+lng+')">';
                infoWindow.setContent(contentString);
                infoWindow.open(map,markAdd);
            });
        }, timeout);
    }

    function saveRequest(lat,lng){
        var x = document.getElementById("type").selectedIndex;
        var type = document.getElementsByTagName("option")[x].value;
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
        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/insertRequest.py?type="+type+"&msg="+msg+"&lat="+lat+"&lng="+lng+"&dbName=<?php echo $_SESSION['depname'] ?>",true);
        xmlhttp.send();
    }

    function clearMarkers(){
        markAdd.setMap(null);
        markAdd=null;
    }

    function searchByPolygon() {
        var xmlhttp;
        var myLatlng;
        var firstPoint;
        if (regtangObj != null) {
            polygonObj = null;
            polygon = "MULTIPOLYGON(((";
            polygon = polygon + regtangObj.getBounds().getNorthEast().lng() + " " + regtangObj.getBounds().getNorthEast().lat() + ", " + regtangObj.getBounds().getSouthWest().lng() + " " + regtangObj.getBounds().getNorthEast().lat() + ", " + regtangObj.getBounds().getSouthWest().lng() + " " + regtangObj.getBounds().getSouthWest().lat() + ", " + regtangObj.getBounds().getNorthEast().lng() + " " + regtangObj.getBounds().getSouthWest().lat() + ", " + regtangObj.getBounds().getNorthEast().lng() + " " + regtangObj.getBounds().getNorthEast().lat() + ")))" ;
        }

        else if (polygonObj != null){
            regtangObj = null
            polygon = "MULTIPOLYGON(((";
            for (var i = 0; i < polygonObj.getPath().getLength(); i++)
            {
                if (i == 0) {
                  firstPoint = polygonObj.getPath().j[i];
                  polygon = polygon + firstPoint.lng() + " " + firstPoint.lat() + ", " ;
                }
                else {
                  polygon = polygon + polygonObj.getPath().j[i].lng() + " " + polygonObj.getPath().j[i].lat() + ", " ;
                }
            }
            polygon = polygon + firstPoint.lng() + " " + firstPoint.lat() + ")))" ;
        }

        console.log(polygon);

        if (markAdd!=null) {
          clearMarkers();
        }

        if (document.getElementById("hospital").checked == false && document.getElementById("school").checked == false && document.getElementById("police").checked == false && document.getElementById("fire").checked == false &&document.getElementById("temple").checked == false) {
            alert("Don't have data layers selected")
        }
        else {
            if (document.getElementById("hospital").checked == true) {
                var type = "hospital";
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
                        var infowindow;
                        myLatlng = JSON.parse(xmlhttp.responseText);
                        if (myLatlng.length==0) {
                            alert("ไม่พบสถานที่ค้นหา");
                        }
                        else{
                            for (var i = 0; i < markerHos.length; i++) {
                              markerHos[i].setMap(null);
                            }
                            for (var i = 0; i < markerHosClus.length; i++) {
                              markerHosClus[i].setMap(null);
                            }
                            markerHosClus = [];
                            markerHos = [];
                            markerHosCluster.clearMarkers();

                            var infowindow;
                            myLatlng = JSON.parse(xmlhttp.responseText);
                            for (var i = 0; i < myLatlng.length; i++) {
                                var contentStr = '<div id="content">'+
                                '<div id="siteNotice">'+
                                '</div>'+
                                '<h3 id="firstHeading" class="firstHeading">'+ myLatlng[i].name +'</h3>'+
                                '<div id="bodyContent">'+
                                '<p>ละติจูด: '+ myLatlng[i].latitude +' ลองจิจูด: '+ myLatlng[i].longitude +'</p>'+
                                '</div>';

                                if (i == myLatlng.length-1) {
                                    var latlng = new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude);
                                    markerHos[i] = new google.maps.Marker({
                                        position: latlng,
                                        map: map,
                                        icon: hospital,
                                        title: myLatlng[i].name,
                                        info: new google.maps.InfoWindow({
                                            content: contentStr
                                        })
                                    });

                                    infowindow = markerHos[i].info;
                                    google.maps.event.addListener(markerHos[i], 'click', function() {
                                        for(var i =0;i<=markerHos.length-1;i++){
                                            markerHos[i].info.close();
                                        }
                                      this.info.open(map,this);
                                    });
                                    map.setCenter(latlng);
                                    map.setZoom(10);
                                }
                                else{
                                    markerHos[i] = new google.maps.Marker({
                                        position: new google.maps.LatLng(myLatlng[i].latitude,myLatlng[i].longitude),
                                        map: map,
                                        icon: hospital,
                                        title: myLatlng[i].name,
                                        info: new google.maps.InfoWindow({
                                        content: contentStr
                                        })
                                    });

                                    infowindow = markerHos[i].info;
                                    google.maps.event.addListener(markerHos[i], 'click', function() {
                                        for(var i =0;i<=markerHos.length-1;i++){
                                            markerHos[i].info.close();
                                        }
                                        this.info.open(map,this);
                                    });
                                }
                                markerHosClus.push(markerHos[i]);
                            }
                            markerHosCluster = new MarkerClusterer(map, markerHosClus);
                        }
                    }
                }
                xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/searchByArea.py?type="+type+"&polygon="+polygon,true);
                xmlhttp.send();
            }
        }
    }

    

    function checkHos() {
        var c = document.getElementById('hospital').checked;
        $("#hospital-list").collapse('show');
        if (c == true) {
            $("#hospital-list").collapse('show');
            document.getElementById('hospital').checked = true;
            
        }
        else {
            $("#hospital-list").collapse('hide');
            document.getElementById('hospital').checked = false;
            
        }
    }



    function checkSch() {
        var c = document.getElementById('school').checked;
        $("#school-list").collapse('show');
        if (c == true) {
            $("#school-list").collapse('show');
            document.getElementById('school').checked = true;
            


        }
        else {
            $("#school-list").collapse('hide');
            document.getElementById('school').checked = false;
         
        }
    }


    function checkTemp() {
        var c = document.getElementById('temple').checked;
        $("#temple-list").collapse('show');
        if (c == true) {
            $("#temple-list").collapse('show');
            document.getElementById('temple').checked = true;
            
        }
        else {
            $("#temple-list").collapse('hide');
            document.getElementById('temple').checked = false;
          
        }
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


</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChsKVqmyv9qxepQVE9qlnUj8sXbsuQrhs&libraries=places,drawing&callback=initMap"
    async defer></script>
</body>
</html>
