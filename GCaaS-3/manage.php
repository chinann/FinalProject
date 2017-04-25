<?php
  function chkBrowser($nameBroser){
	   return preg_match("/".$nameBroser."/",$_SERVER['HTTP_USER_AGENT']);

  }


// if(chkBrowser("Windows NT")==1){
//     header("Location: show.php?device=Windows NT");
// }
     if(chkBrowser("Android") == 1){
      header("Location: manage-mobile.php");
    } elseif(chkBrowser("Safari") == 1){
      if(chkBrowser("Chrome") == 0){
        header("Location: manage-mobile.php");
      }
      // else{
      //   print "Request from Mac";
      // }
    }
    elseif(chkBrowser("iPhone") == 1){
        header("Location: manage-mobile.php");
		//print "Request from Mobile";
	}


session_start();
if(isset($_SESSION['current'])){
     $_SESSION['oldlink']=$_SESSION['current'];
}else{
     $_SESSION['oldlink']='no previous page';
}
$_SESSION['current']=$_SERVER['PHP_SELF'];
if (!$_SESSION["username"]) {
    header("Location: http://".$_SESSION['host']."/GCaaS-3/index.php");
    exit(0);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Manage</title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <link href="css/bootstrap.css" rel="stylesheet"/>
    <!-- FONT AWESOME CSS -->
    <link href="css/font-awesome.min.css" rel="stylesheet"/>
    <!-- FLEXSLIDER CSS -->
    <link href="css/flexslider.css" rel="stylesheet"/>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <!-- CUSTOM STYLE CSS -->
    <link href="css/style.css" rel="stylesheet"/>
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">

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
            <a class="navbar-brand" href="index.php"><img class="logo-custom" src="img/logo.png" alt="" height="50"
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
                            <img src="img/default-user.png" class="img-circle" alt="User Image">
                            <p>
                                <?php echo $_SESSION["username"] ?> <!--Username and Role-->
                                <small>Member since Nov. 2015</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="manage.php" class="btn btn-default btn-flat">Manage</a>
                            </div>
                            <div class="pull-right">
                                <a href="logout.php" class="btn btn-danger btn-flat" onclick="alertFn()">Sign out</a>
                                <script>
                                    function alertFn() {
                                        var x;
                                        if (confirm("Do you want to log out?") == true) {
                                            window.location ='../logout.php'

                                        } else {
                                            window.close()
                                        }
                                        document.getElementById("demo").innerHTML = x;
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

<?php

    require('connectDB.php');
    // $connection = pg_connect("host=172.16.150.177 port=5432 dbname=GCaaS user=postgres password=1234");

    if (! $_SESSION['connection']) {
        echo "Connection Failed.";
        exit;
    }
    else {
        $result_user = pg_exec($connection, "SELECT \"user_Fname\", \"user_Lname\", \"user_Email\", \"user_Tel\", \"user_Addr\" FROM table_user WHERE \"user_Username\" = '" .$_SESSION["username"]."'");
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

<!--NAVBAR SECTION-->
<div id="body-content" style="padding: 20px 50px 0px 50px;margin-top: 80px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>User Account</h2>
        </div>
        <div class="panel-body">
            <b>Frist Name : </b> <?php echo $user_Fname ?> <br>
            <b>Last Name : </b> <?php echo $user_Lname ?> <br>
            <b>Address : </b> <?php echo $user_Addr ?> <br>
            <b>E-mail : </b> <?php echo $user_Email ?> <br>
            <b>Telephone : </b> <?php echo $user_Tel ?> <br>
        </div>
    </div>
</div>

<div id="bar-menu" style="padding: 20px 50px 20px 50px;background-color: rgba(101, 99, 252, 0.36);left:50px">
    <button type="button" class="btn btn-danger" onclick="deleteSelected()"><i class="fa fa-trash-o" aria-hidden="true"> </i> Delete selected</button>
    <a href="createDeploy.php" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true" ></i> Create deployment </a>
</div>

<!--table-->
<div id="table-div">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th><input class="checkbox-inline" type="checkbox" id="boxAll" value="" onclick="checkAll()"> All </th>
            <th>Deployment Name</th>
            <th>Role</th>
            <th> </th>
            <th> </th>
        </tr>
        </thead>
        <tbody>

            <?php
                require('connectDB.php');
            // $connection = pg_connect("host=172.16.150.177 port=5432 dbname=GCaaS user=postgres password=1234");
            if (! $_SESSION['connection']) {
                echo "Connection Failed.";
                exit;
            }
            else {
                $myresult = pg_exec($connection, "SELECT * FROM table_worker WHERE \"userID\" = " . $_SESSION["userID"]);
                $rows_count = pg_numrows($myresult);
                $column_count = pg_numfields($myresult);
                $deploymentName = "";
                $deploymentURL = "";
                $roleUserID = "";
                $roleName = "";
                if ($rows_count != 0) {
                    for ($i = 0; $i < $rows_count; $i++) {
                        for($j = 0; $j < $column_count; $j++){
                            if($j==1){
                                $deploymentID = pg_result($myresult, $i, $j);
                                $x = pg_exec($connection,"SELECT \"deployment_Name\" ,\"deployment_URL\" FROM table_deployment  WHERE \"deploymentID\" = ".$deploymentID);
                                $xrow = pg_numrows($x);
                                for($a = 0;$a < $xrow ; $a++){
                                    $deploymentName = pg_result($x,$a,0);
                                }
                                ?>
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="input<?php echo $i?>" value="" onclick="unCheckAll(this)"></td>
                                    <td><?php echo $deploymentName?></td>

                                <?php
                            }
                            else if($j==3) {
                                $roleUserID = pg_result($myresult, $i, $j);
                                $y = pg_exec($connection, "SELECT \"role_Name\"  FROM table_role  WHERE \"roleUserID\" =" . $roleUserID);
                                $yrow = pg_numrows($y);
                                for ($b = 0; $b < $yrow; $b++) {
                                    $roleName = pg_result($y, $b, 0);
                                }
                                ?>
                                    <td> <?php echo $roleName?> </td>
                                    <td><a href="<?php echo $roleName ?>/manageDeployment.php?depName=<?php echo $deploymentName?>">Go to Deployment "<?php echo $deploymentName?>"</a></td>
                                    <td><button class="btn btn-danger" onclick="deleteRow()"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
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

<script>
    function deleteRow(){
        if(confirm("Do you want to delete deployment name \"<?php echo $deploymentName?>\" ?") == true){
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
                    console.log(JSON.parse(xmlhttp.responseText));
                    obj = JSON.parse(xmlhttp.responseText);
                    console.log(obj.status);

                    alert("Delete successfully");
                    location.reload();
                }
            }
            xmlhttp.open("GET","http://" + <?php $_SESSION['host'] ?> + "/cgi-bin/deleteRow.py?deployName=<?php echo $deploymentName?>&user=<?php echo $_SESSION['username'] ?>",true);
            xmlhttp.send();
        }
    }


    function checkAll(){
        var row = document.getElementsByTagName('input');
        if(document.getElementById('boxAll').checked == true){
            for (var i = 0; i < row.length; i++) {
                row[i].checked = true;
                console.log(i);
            }
        }
        else{
            for (var i = 0; i < row.length; i++) {
                row[i].checked = false;
            }
        }
    }

    function unCheckAll(input){
      if(input.checked == false){
        document.getElementById('boxAll').checked = false;
      }
    }

    function deleteSelected(){
      var row = document.getElementsByTagName('input');
      var count=0;
      for(var i=0 ; i < row.length; i++){
        if(row[i].checked == true){
          count++;
        }
      }
      if(count==0){
        alert("Please,select item to delete.");
      }
      else{
        if(confirm("Do you want to delete selected item ?") == true){
          alert("successfully");
        }
      }
    }
</script>

<div id="footer">

</div>
<!-- FOOTER SECTION END-->

<!--  Jquery Core Script -->
<script src="js/jquery-1.10.2.js"></script>
<!--  Core Bootstrap Script -->
<script src="js/bootstrap.js"></script>

</body>

</html>
