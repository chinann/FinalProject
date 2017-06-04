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
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Manage Deployment</title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet"/>
    <!-- FONT AWESOME CSS -->
    <link href="../css/font-awesome.min.css" rel="stylesheet"/>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../css/AdminLTE.min.css">
    <!-- CUSTOM STYLE CSS -->
    <link href="../css/style.css" rel="stylesheet"/>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" id="menu">
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
                <li><?php
                    if ($_SESSION["username"]) {
                    ?>
                    <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
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
                                <?php echo $_SESSION["username"] ?><!--Username and Role-->
                                <!-- <small>Member since Nov. 2015</small> -->
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="../manage.php" class="btn btn-default btn-flat">Manage</a>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-danger btn-flat" onclick="alertFn()">Sign out
                                </button>
                                <script>
                                    function alertFn() {
                                        if (confirm("Do you want to log out?") == true) {
                                            window.location ='../logout.php'
                                        } else {
                                            window.closed;
                                        }
                                    }
                                </script>
                            </div>
                        </li>
                    </ul>
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
</div>
<!--NAVBAR SECTION-->

<?php
    // $connection = pg_connect("host=localhost port=5432 dbname=GCaaS user=postgres password=1234");
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

<!-- header body-->
<div id="body-content" style="padding: 20px 50px 0px 50px;margin-top: 80px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><?php echo $deployment_Name ?>
                <small>(last access : <?php echo $deployment_LastAccess ?>)</small>
            </h2>
        </div>
        <div class="panel-body">
            <b>Deployment URL : </b> <?php echo $deployment_URL ?> <br>
            <b>Type of Deployment : </b> <?php echo $type_Name ?> <br>
            <b>Description : </b> <?php echo $deployment_Description ?> <br>
            <b>Type of Deployment : </b> <?php echo $deployment_Area ?> <br>
            <div id="map"></div>
        </div>
    </div>
</div><!--END header body-->

<div class="panel-group" id="list-body" style="padding: 0px 50px 0px 50px;">
    <!-- Dynamic data layer-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <a data-toggle="collapse" data-parent="#accordion" href="#dynamic">
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Manage dynamic data layer</a>
            </h3>
        </div>
        <div id="dynamic" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="form-group">
                    <label for="area" class="col-sm-3 control-label">Dynamic data layer : </label>
                    <div class="col-sm-12">
                        <form class="form-horizontal" role="form">
                            <div class="panel panel-default" style="color: #333333">
                                <div class="panel-body">
                                    <div class="col-sm-4">
                                        <ul class="nav nav-pills nav-stacked">
                                            <li class="active"><a href="#social-list-config">Social network</a>
                                            </li>
                                            <li class="disabled"><a href="#">Native apps</a></li>
                                            <li class="disabled"><a href="#">Hardware</a></li>
                                        </ul>
                                    </div>

                                    <!--social list-->
                                    <div class="col-sm-8" style="text-align: left;">
                                        <div class="form-group fade in" id="social-list-config"
                                             style="margin-left: 20px">
                                            <label>Select information available in cover by area of
                                                deployment</label>
                                            <form role="form">
                                                <div>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" value="" id="tw-check-config"
                                                                      data-toggle="collapse"
                                                                      data-target="#twitter-list-config" name="selected" disabled checked>Twitter</label>
                                                    </div>
                                                    <div class="panel-collapse collapse in" id="twitter-list-config"
                                                         style="margin-left: 10px">
                                                        <div id="boxCustomize" style="background-color: rgba(220, 220, 220, 0.64)">
                                                            <ul>
                                                                <form role="form" >
                                                                    <label>display style :</label><br>
                                                                    <label class="radio-inline" style="margin-left: 50px">
                                                                        <input type="radio" name="optradio"
                                                                               id="marker-config"
                                                                               value="Twitter-marker" checked
                                                                               disabled>Marker
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="optradio"
                                                                               id="layer-config"
                                                                               value="Twitter-layer" disabled>GIS data layer
                                                                    </label>
                                                                    <a class="fa fa-question-circle" data-toggle="collapse" data-target="#DT" data-toggle="tooltip" title="point/layer" style="margin-left: 50px"></a>
                                                                </form>

                                                                <p class="collapse box" id="DT">: - Point = data will be marker you can click on point that will show data in each point.
                                                                    <br>&nbsp;&nbsp;- Layer = you can't do everything on point and infomation of point will show on sidebar.</p>
                                                            </ul>

                                                            <ul >
                                                                <form role="form" >
                                                                    <label>information you want to show in info or sidebar :</label><br>
                                                                    <div class="row" style="margin-left: 50px">
                                                                        <div class="col-sm-4">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="selected"
                                                                                       id="item1-config"
                                                                                       value="Profile"
                                                                                       checked
                                                                                       disabled>Profile
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="selected"
                                                                                       id="item2-config"
                                                                                       value="Username"
                                                                                       checked
                                                                                       disabled>Username
                                                                            </label>

                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="selected"
                                                                                       id="item3-config"
                                                                                       value="Date"
                                                                                       checked
                                                                                       disabled>Date of tweet
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="selected"
                                                                                       id="item4-config"
                                                                                       value="Picture"
                                                                                       checked
                                                                                       disabled>Picture in post
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="selected"
                                                                                       id="item5-config"
                                                                                       value="Message"
                                                                                       checked
                                                                                       disabled>Message
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="selected"
                                                                                       id="item6-config"
                                                                                       value="Position"
                                                                                       checked
                                                                                       disabled>Position
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" value="" disabled>Facebook</label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" value="" disabled>Youtube</label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--END Dynamic data layer-->

    <!-- Manage member-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <a data-toggle="collapse" data-parent="#accordion" href="#member">
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Member</a>
            </h3>
        </div>
        <div id="member" class="panel-collapse collapse ">
            <div class="panel-body">
                <!-- check if-else ว่าใช่ admin หรือไม่ ใช่แสดง-->


            </div>

            <!--table-->
            <div id="table-div" style="color: #080808">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    // $connection = pg_connect("host=localhost port=5432 dbname=GCaaS user=postgres password=1234");
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
                        $result_deploymentID = pg_exec($_SESSION['connection'], "SELECT \"deploymentID\" FROM table_deployment  WHERE \"deployment_Name\" = '" .$_SESSION["depname"]."'");
                        $rows_deploy = pg_numrows($result_deploymentID);
                        $column_deploy = pg_numfields($result_deploymentID);
                        $deploymentID = "";
                        if($rows_deploy != 0) {
                            $deploymentID = pg_result($result_deploymentID,0,0);
                        }
                        $result_userDeploy = pg_exec($_SESSION['connection'], "SELECT * FROM table_worker WHERE \"deploymentID\" = '" . $deploymentID ."'");
                        $rows_count = pg_numrows($result_userDeploy);
                        $column_count = pg_numfields($result_userDeploy);
                        $username = "";
                        $roleUserID = "";
                        $roleName = "";
                        if ($rows_count != 0) {
                            for ($i = 0; $i < $rows_count; $i++) {
                                for ($j = 0; $j < $column_count; $j++) {
                                    if ($j == 0) {
                                        $userID = pg_result($result_userDeploy, $i, $j);
                                        $x = pg_exec($_SESSION['connection'], "SELECT \"user_Username\" FROM table_user  WHERE \"userID\" = " . $userID);
                                        $xrow = pg_numrows($x);
                                        for ($a = 0; $a < $xrow; $a++) {
                                            $username = pg_result($x, $a, 0);
                                        }
                                        ?>
                                        <tr>
                                        <td><?php echo $username ?></td>
                                        <?php
                                    } else if ($j == 3) {
                                        $roleUserID = pg_result($result_userDeploy, $i, $j);
                                        $y = pg_exec($_SESSION['connection'], "SELECT \"role_Name\"  FROM table_role  WHERE \"roleUserID\" =" . $roleUserID);
                                        $yrow = pg_numrows($y);
                                        for ($b = 0; $b < $yrow; $b++) {
                                            $roleName = pg_result($y, $b, 0);
                                        }
                                        ?>
                                        <td> <?php echo $roleName ?> </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- END Manage member-->

    <?php
    $n = 0;
    $v = 0;
    $s = 0;
    $Rg = 0;
    $Rd = 0;
    $Rs = 0;
    // $connection = pg_connect("host=localhost port=5432 dbname=DB_". $_SESSION['depname'] ." user=postgres password=1234");
    // if (!$connection) {
    //     echo "Connection Failed.";
    //     exit;
    // }

    $connection = pg_connect("host=". $_SESSION['host'] ." port=". $_SESSION['port'] ." dbname=DB_". $_SESSION['depname'] ." user=" . $_SESSION['user'] ." password=". $_SESSION['password']);
    if (!$connection) {
        echo "Connection Failed.";
        exit;
    }
    else {
        $myresult = pg_exec($connection, "SELECT \"post_Status\" FROM \"table_postTWH\"");
        $rows_count = pg_numrows($myresult);
        if ($rows_count != 0) {
            for ($i = 0; $i < $rows_count; $i++) {
                $status = pg_result($myresult, $i, 0);
                if ($status == "New") {
                    $n++;
                } else if ($status == "Visited") {
                    $v++;
                } else if ($status == "Solved") {
                    $s++;
                }
            }
        }

        $Rmyresult = pg_exec($connection, "SELECT \"post_Status\" FROM \"table_postTWH\"");
        $rows_count = pg_numrows($Rmyresult);
        if ($rows_count != 0) {
            for ($j = 0; $j < $rows_count; $j++) {
                $status = pg_result($Rmyresult, $j, 0);
                if ($status == "Good") {
                    $Rg++;
                } else if ($status == "Damage") {
                    $Rd++;
                } else if ($status == "Severe") {
                    $Rs++;
                }
            }
        }
    }?>



    <!-- Summary-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <a data-toggle="collapse" data-parent="#accordion" href="#summary">
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Summary of day</a>
            </h3>
        </div>
        <div id="summary" class="panel-collapse collapse">
            <div class="panel-body">
                <div id="table-help" class="col-sm-6" style="color: #080808">
                    <h3>Help</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Status</th>
                            <th>No. of post</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>New</td>
                            <td><?php echo $n ?></td>
                        </tr>

                        <tr>
                            <td>Visited</td>
                            <td><?php echo $v ?></td>
                        </tr>

                        <tr>
                            <td>Solved</td>
                            <td><?php echo $s ?></td>
                        </tr>

                        <tr>
                            <td> </td>
                            <td><?php echo $n+$v+$s ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div id="table-road" class="col-sm-6" style="color: #080808">
                    <h3>Road condition</h3>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Damage</th>
                            <th>No. of post</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Good</td>
                            <td><?php echo $Rg ?></td>
                        </tr>

                        <tr>
                            <td>Damaged</td>
                            <td><?php echo $Rd ?></td>
                        </tr>

                        <tr>
                            <td>Severe</td>
                            <td><?php echo $Rs ?></td>
                        </tr>

                        <tr>
                            <td> </td>
                            <td><?php echo $Rg+$Rd+$Rs ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!--END Summary-->
</div>


<!--btn go to map website-->
<div style="text-align: center;margin: 20px 20px">
    <a href="deployment.php" type="button" class="btn btn-success">Go to Website <i class="fa fa-long-arrow-right"
                                                                   aria-hidden="true"></i></a>
</div>

<!-- Modal Add User-->
<div id="addUser" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h1 class="modal-title" style="text-align: center">Add user</h1>
            </div>
            <div class="modal-body">
                <div class="col-sm-2">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>
                <div class="input-group col-sm-10">

                    <input type="text" class="form-control" id="add-user" placeholder="Enter username or e-mail" onkeypress="return runSearch(event)">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="open" onclick = "pullUser()"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </span>
                </div>
                <br>
                <!--                <button type="button" class="btn btn-primary btn-block">OK</button>-->
                <div onchange="pullUser(this)">

                </div>

                <div class="row" style="margin: 5px">
                    <p class="col-sm-4" id="usern"></p>
                    <p class="col-sm-4" id="email"></p>
                    <select class="col-sm-4" id="role" style="margin-top:5px" hidden>
                        <option value="Admin">Admin</option>
                        <option value="Operater">Operater</option>
                        <option value="Rescue Team">Rescue Team</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
            </div>
            <script>
                function initialize() {
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

                function pullUser(){
                    if (document.getElementById("add-user").value != "") {
                        var user = document.getElementById("add-user").value;
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
                                if (obj.status == "None") {
                                    alert("No username!!");

                                }
                                else{
                                    document.getElementById("usern").innerHTML = obj[0].username;
                                    document.getElementById("email").innerHTML = obj[0].email;
                                    document.getElementById("role").hidden = false;
                                }
                            }
                        }
                        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/searchUser.py?user="+user,true);
                        xmlhttp.send();
                    }
                    else {
                        alert("Insert Username or E-mail");
                    }
                }

                function addUser(){
                    if (document.getElementById("add-user").value != "") {
                        var username = document.getElementById("usern").innerHTML;
                        var x = document.getElementById("role").selectedIndex;
                        var role = document.getElementsByTagName("option")[x].value;
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
                                else {
                                    alert("Cannot add user!!");
                                }
                                document.getElementById("add-user").value = "";
                                document.getElementById("usern").innerHTML = "";
                                document.getElementById("email").innerHTML = "";
                                document.getElementById("role").hidden = true;
                            }
                        }
                        xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/GCaaS-3/Python/addUser.py?user="+username+"&role="+role,true);
                        xmlhttp.send();
                    }
                    else {
                        alert("Insert Username or E-mail");
                    }
                }

                function runSearch(e)
                {
                    if (e.keyCode == 13) {
                        pullUser();
                    }
                }
            </script>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addUser()">Add</button>
                </center>
            </div>
        </div>

    </div>
</div>

<div id="footer">

</div>
<!-- FOOTER SECTION END-->

<!--  Jquery Core Script -->
<script src="../js/jquery-1.10.2.js"></script>
<!--  Core Bootstrap Script -->
<script src="../js/bootstrap.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-a-v-TmBvYfQRkceXy4d8L6cd78ip73s&callback=initialize"></script>

</body>
</html>
