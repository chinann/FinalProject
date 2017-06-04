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
  <!-- Theme Made By www.w3schools.com - No Copyright -->
  <title>GCaaS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="../css/slider-menu.jquery.css" rel="stylesheet">
  <link href="../css/slider-menu.theme.jquery.css" rel="stylesheet">
  <style>
  body {
    font: 20px Montserrat, sans-serif;
    line-height: 1.8;
    color: #383838;
    height: 100%; /* ให้ html และ body สูงเต็มจอภาพไว้ก่อน */
    margin: 0;
    padding: 0;
  }
  p {font-size: 16px;}
  .margin {margin-bottom: 45px;}
  .bg-4 {
      background-color:rgba(28, 43, 75, 1);
  }
  .container-fluid {
      /*padding-top: 30px;*/
      padding-bottom: 10px;
  }
  .navbar {
      padding-top: 10px;
      padding-bottom: 10px;
      border: 0;
      border-radius: 0;
      margin-bottom: 30px;
      font-size: 12px;
      letter-spacing: 5px;
      background-color:rgba(28, 43, 75, 1);
  }
  .navbar-nav  li a:hover {
      color: #1abc9c !important;
  }
  .slider {
    border-radius: 5px;
    overflow: hidden;
    background-color: #fff;
  }
  .details,
  .copyright {
    color: #fff;
    font-size: 12px;
  }
  .details a,
  .copyright a {
    text-decoration: none;
    /*color: #00a52b;*/
  }
  .copyright {
    text-align: center;
    margin-top: -10px;
    margin-bottom: 30px;
  }
  .details {
    padding-left: 15px;
    margin-top: 30px;
  }
  .button {
    display: block;
    border-radius: 3px;
    text-align: center;
    padding-top: 10px;
    padding-bottom: 10px;
    background-color: #00a52b;
    color: #fff;
    text-decoration: none;
    margin-top: 20px;
    font-size: 14px;
    transition: background-color .2s;
  }
  .disabled {
   pointer-events: none;
   cursor: default;
 }
</style>
</head>
<body onload="setupMap()">

<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#" style="padding:0%"><img class="logo-custom" src="../img/logo.png" style="height:45px"/></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><?php
            if ("n") {
            ?>
            <!-- User Account Menu -->
        <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <button type="button" class="btn btn-default btn-lg" data-toggle="modal"
                        data-target="#modal-logout"
                        style="font-size:14px;"> <?php echo "n" ?> </button>
                <br>
            </a>
        </li>
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
</nav>

<?php
    // $connection = pg_connect("host=172.20.10.2 port=5432 dbname=GCaaS user=postgres password=1234");
    // if (!$connection) {
    //     echo "Connection Failed.";
    //     exit;
    // }
    require('../connectDB.php');
    if (! $_SESSION['connection']) {
    	echo "Connection Failed.";
    	exit;
    }
    else {
        $result_user = pg_exec($_SESSION['connection'], "SELECT \"user_Fname\", \"user_Lname\", \"user_Email\", \"user_Tel\", \"user_Addr\" FROM table_user WHERE \"user_Username\" = '".$_SESSION["username"]."'");
        $rows_user = pg_numrows($result_user);
        $column_user = pg_numfields($result_user);
        $user_Fname = "";
        $user_Lname = "";
        $user_Email = "";
        $user_Tel= "";
        $user_Addr = "";
        if($rows_user != 0) {
            for ($i = 0; $i < $rows_user; $i++) {
                for ($j = 0; $j < $column_user; $j++) {
                    if ($j == 0) {
                        $user_Fname = pg_result($result_user, $i, $j);
                    }

                    else if ($j == 1) {
                        $user_Lname = pg_result($result_user, $i, $j);
                    }

                    else if ($j == 2) {
                        $user_Email = pg_result($result_user, $i, $j);
                    }

                    else if ($j == 3) {
                        $user_Tel = pg_result($result_user, $i, $j);
                    }

                    else if ($j == 4) {
                        $user_Addr = pg_result($result_user, $i, $j);
                    }
                }
            }
        }
    }
?>

<!-- First Container -->
<?php
    require('../connectDB.php');
    if (! $_SESSION['connection']) {
    	echo "Connection Failed.";
    	exit;
    }
    else {
        $result_deployment = pg_exec($_SESSION['connection'], "SELECT \"deployment_Name\", \"deployment_Description\", \"deployment_DateCreate\", \"deployment_LastAccess\", \"deployment_URL\", ST_AsText(\"deployment_Area\"), \"typeID\", ST_X(ST_AsText(ST_Centroid(\"deployment_Area\"))),ST_Y(ST_AsText(ST_Centroid(\"deployment_Area\"))) FROM table_deployment WHERE \"deployment_Name\" = '" .$_SESSION["depname"]."'");
        $rows_deployment = pg_numrows($result_deployment);
        $column_deployment = pg_numfields($result_deployment);
        $deployment_Name = "";
        $deployment_Description = "";
        $deployment_DateCreate = "";
        $deployment_LastAccess= "";
        $deployment_URL = "";
        $deployment_Area = "";
        $typeID = "";
        $type_Name = "";
        $cenlat = "";
        $cenlng = "";
        if($rows_deployment != 0) {
            for ($i = 0; $i < $rows_deployment; $i++) {
                for ($j = 0; $j < $column_deployment; $j++) {
                    if ($j == 0) {
                        $deployment_Name = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 1) {
                        $deployment_Description = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 2) {
                        $deployment_DateCreate = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 3) {
                        $deployment_LastAccess = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 4) {
                        $deployment_URL = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 5) {
                        $deployment_Area = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 6) {
                        $typeID = pg_result($result_deployment, $i, $j);
                        $x = pg_exec($_SESSION['connection'], "SELECT \"type_Name\" FROM table_type  WHERE \"typeID\" = " . $typeID);
                        $xrow = pg_numrows($x);
                        for ($a = 0; $a < $xrow; $a++) {
                            $type_Name = pg_result($x, $a, 0);
                        }
                    }

                    else if ($j == 7) {
                        $cenlng = pg_result($result_deployment, $i, $j);
                    }

                    else if ($j == 8) {
                        $cenlat = pg_result($result_deployment, $i, $j);
                    }
                }
            }
        }
    }
?>

<!-- First Container -->
<main class="container" role="main">
  <div class="panel panel-default">
    <div class="slider">
      <ul class="my-menu">
        <li style="background-color: #f2f2f2;"><a href="#" class="disabled" ><?php echo $_SESSION['depname'];?><div style="font-size: 14px;">  (last access : <?php echo $deployment_LastAccess ?>)</div></a>
        <li><a href="#">Summary</a>
          <ul>
            <li><a href="#" class="disabled">Deployment URL : <small><?php echo $deployment_URL ?></small></a>
            <li><a href="#" class="disabled">Type of Deployment : <small><?php echo $type_Name ?></small></a>
            <li><a href="#" class="disabled">Description : <small><?php echo $deployment_Description ?></small></a>
            <li><a href="#" class="disabled">Coverage Area : <small><?php echo $deployment_Area ?></small></a>
            <li><a href="#" class="disabled"><div id="map" style="width:100%;height:450px;"></div></a>
          </ul>
        <li><a href="#">Dynamic Data Layer</a>
          <ul>
            <li><a href="#">Social network</a>
              <ul>
                <li><a href="#">Twitter</a>
                  <ul>
                    <li style="background-color: #f2f2f2;"><a href="#" class="disabled">Display Style</a>
                      <li><a href="#" class="disabled">
                        <input type="radio" name="optradio" id="marker-config" value="Twitter-marker" checked disabled>   Marker
                      </a></li>
                      <li><a href="#" class="disabled">
                        <input type="radio" name="optradio" id="layer-config" value="Twitter-layer" disabled>   GIS data layer
                      </a></li>
                    </li>
                    <li style="background-color: #f2f2f2;"><a href="#" class="disabled">Information you want to show in sidebar</a>
                      <li><a href="#" class="disabled">
                        <input type="checkbox" name="selected" id="item1-config" value="Profile" checked disabled>   Profile
                      </a></li>
                      <li><a href="#" class="disabled">
                        <input type="checkbox" name="selected" id="item2-config" value="Username" checked disabled>   Username
                      </a></li>
                      <li><a href="#" class="disabled">
                        <input type="checkbox" name="selected" id="item3-config" value="Date" checked disabled>   Date of tweet
                      </a></li>
                      <li><a href="#" class="disabled">
                        <input type="checkbox" name="selected" id="item4-config" value="Picture" checked disabled>   Picture in post
                      </a></li>
                      <li><a href="#" class="disabled">
                        <input type="checkbox" name="selected" id="item5-config" value="Message" checked disabled>   Message
                      </a></li>
                      <li><a href="#" class="disabled">
                        <input type="checkbox" name="selected" id="item6-config" value="Position" checked disabled>   Position
                      </a></li>
                    </li>
                  </ul>
                <li><a class="disabled">Facebook</a>
                <li><a class="disabled">Youtube</a>
              </ul>
            <li><a class="disabled">Native apps</a>
            <li><a class="disabled">Hardware</a>
          </ul>
        <li><a href="#">Member</a>
          <ul>
            <?php
              require('../connectDB.php');
              if (! $_SESSION['connection']) {
                echo "Connection Failed.";
                exit;
              }
              else {
                $result_deploymentID = pg_exec($_SESSION['connection'], "SELECT \"deploymentID\" FROM table_deployment  WHERE \"deployment_Name\" = '".$_SESSION['depname']."'");
                $rows_deploy = pg_numrows($result_deploymentID);
                $deploymentID = "";
                if($rows_deploy != 0) {
                    $deploymentID = pg_result($result_deploymentID,0,0);
                }
                $result_userDeploy = pg_exec($_SESSION['connection'], "SELECT \"userID\", \"roleUserID\" FROM table_worker WHERE \"deploymentID\" = '" . $deploymentID ."'");
                $rows_count = pg_numrows($result_userDeploy);
                $column_count = pg_numfields($result_userDeploy);
                if ($rows_count != 0) {
                    for ($i = 0; $i < $rows_count; $i++) {
                        for ($j = 0; $j < $column_count; $j++) {
                            if ($j == 0) {
                                $userID = pg_result($result_userDeploy, $i, $j);
                                $x = pg_exec($_SESSION['connection'], "SELECT \"user_Username\" FROM table_user  WHERE \"userID\" = " . $userID);
                                $xrow = pg_numrows($x);
                                $username = pg_result($x, 0, 0);
                                ?><li><a class="disabled">
                                  <?php echo $username;
                            } else if ($j == 1) {
                                $roleUserID = pg_result($result_userDeploy, $i, $j);
                                $y = pg_exec($connection, "SELECT \"role_Name\"  FROM table_role  WHERE \"roleUserID\" =" . $roleUserID);
                                $yrow = pg_numrows($y);
                                for ($b = 0; $b < $yrow; $b++) {
                                    $roleName = pg_result($y, $b, 0);
                                }
                                ?><div style="float: right; width: 50%;">
                                  <?php echo $roleName ?>
                                </div>
                              </a></li><?php
                            }
                        }
                    }
                }
              }
            ?>

          </ul>
      </ul><!-- .my-menu -->

    </div><!-- .slider -->
  </div>
</main><!-- .container -->

<div class="container-fluid" style="margin-bottom: 15px;">
  <center>
    <a href="deployment-mobile.php" type="button" class="btn btn-success">Go to Website
      <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
    </a>
  </center>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="../js/slider-menu.jquery.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDcoz2-7tFl3V9pkGQx0V49L4CHC2UeZS4&sensor=true"></script>

<script>
function setupMap() {
  var scope = "[";
  var polygon = <?php echo "'" . $deployment_Area . "'" ?>;
  polygon = polygon.replace("MULTIPOLYGON(((", "");
  polygon = polygon.replace(")))", "");
  pol = polygon.split(",");

  map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: <?php echo $cenlat ?>, lng: <?php echo $cenlng ?>},
      zoom: 6,
      maxZoom: 20
  });

  for (i = 0; i < pol.length; i++) {
      position = pol[i].split(" ");
      lat = position[1];
      lng = position[0];

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
  polygonArea.setMap(map);
}

</script>
<script>

( function( $ ) {
  $( function() {
    $( '.my-menu' ).sliderMenu();
  });
})( jQuery );
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<!-- Footer -->
<footer class="container-fluid bg-4">
  <br>
</footer>

</body>
</html>
