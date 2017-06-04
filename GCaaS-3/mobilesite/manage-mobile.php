<?php
  session_start();
// if(isset($_SESSION['current'])){
//      $_SESSION['oldlink']=$_SESSION['current'];
// }else{
//      $_SESSION['oldlink']='no previous page';
// }
// $_SESSION['current']=$_SERVER['PHP_SELF'];
if (!$_SESSION["username"]) {
    header("Location: http://localhost/GCaaS-3/index.php");
    exit(0);
}
  // $_SESSION["username"] = "ton";
  // $_SESSION["userID"] = 4;
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
  <style>
  body {
      font: 16px Montserrat, sans-serif;
      line-height: 1.8;
      color: #383838;
      height: 100%; /* ให้ html และ body สูงเต็มจอภาพไว้ก่อน */
      margin: 0;
      padding: 0;
  }
  .wrapper {
   display: block;
   min-height: 100%; /* real browsers */
   height: auto !important; /* real browsers */
   height: 100%; /* IE6 bug */
   margin-bottom: -50px; /* กำหนด margin-bottom ให้ติดลบเท่ากับความสูงของ footer */
  }
  .footer {
     height: 50px; /* ความสูงของ footer */
     display: block;
     text-align: center;
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
      padding-bottom: 5px;
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
  </style>
</head>
<body>



<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#" style="padding:0%"><img class="logo-custom" src="img/logo.png" style="height:45px"/></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
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
                <li><a href="manage-mobile.php" style="color: #ffffff;">Manage</a></li>
                <li><a href="logout.php" style="color: #ffffff;" onclick="alertFn()">Sign out</a></li>
            </ul>
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
    $connection = pg_connect("host=localhost port=5432 dbname=GCaaS user=postgres password=1234");
    if (!$connection) {
        echo "Connection Failed.";
        exit;
    }
    else {
        $result_user = pg_exec($connection, "SELECT \"user_Fname\", \"user_Lname\", \"user_Email\", \"user_Tel\", \"user_Addr\" FROM table_user WHERE \"user_Username\" = '" . $_SESSION["username"] . "'");
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
<div class="wrapper">

<!-- First Container -->
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" href="#collapse1">
        <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
        User Account
      </a>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
    <div class="panel-body">
      <input id="fname" name="fname" style="font-size: 16px; border-style: none; border-bottom: 1px solid #ccc; width:100%" value="<?php echo $user_Fname ?>" readonly>
      <p style="font-size: 12px;">First Name</p>
      <input id="lname" name="lname" style="font-size: 16px; border-style: none; border-bottom: 1px solid #ccc; width:100%" value="<?php echo $user_Lname ?>" readonly>
      <p style="font-size: 12px;">Last Name</p>
      <input id="addr" name="addr" style="font-size: 16px; border-style: none; border-bottom: 1px solid #ccc; width:100%" value="<?php echo $user_Addr ?>" readonly>
      <p style="font-size: 12px;">Address</p>
      <input id="email" name="email" style="font-size: 16px; border-style: none; border-bottom: 1px solid #ccc; width:100%" value="<?php echo $user_Email ?>" readonly>
      <p style="font-size: 12px;">E-mail</p>
      <input id="phone" name="phone" style="font-size: 16px; border-style: none; border-bottom: 1px solid #ccc; width:100%" value="<?php echo $user_Tel ?>" readonly>
      <p style="font-size: 12px;">Phone</p>
    </div>
    </div>
  </div>
</div>

<!-- Second Container -->
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" href="#collapse2">
        <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
        Deployment List
      </a>
    </div>
      <div id="collapse2" class="panel-collapse collapse in">
      <div class="list-group">
        <div class="list-group-item">
          <div class="dropdown" style="font-size:12px;">
              Sorted by
              <?php
              if (!isset($_GET['sort'])) {
                $sort = "";
              } else {
                $sort = $_GET['sort'];
              }

              if ($sort != '') {
                echo "<button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"sortUsers\" data-toggle=\"dropdown\">
                        " . $sort . "
                        <span class=\"caret\"></span>
                      </button>";
              }
              else {
                echo "<button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"sortUsers\" data-toggle=\"dropdown\">
                        name
                        <span class=\"caret\"></span>
                      </button>";
              }
              ?>

             <ul class="dropdown-menu" role="menu" aria-labelledby="sortUsers">
               <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-mobile.php?sort=name">name</a></li>
               <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-mobile.php?sort=role">role</a></li>
               <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-mobile.php?sort=create date">create date</a></li>
             </ul>
          </div>
        </div>

        <?php
          if($sort == 'role') {
            $sql = "ORDER BY table_worker.\"roleUserID\" ASC";
          }
          else if($sort == 'create date') {
            $sql = "ORDER BY table_deployment.\"deployment_DateCreate\" ASC";
          }
          else {
            $sql = "ORDER BY table_deployment.\"deployment_Name\" ASC";
          }


          $myresult = pg_exec($connection, "SELECT \"deployment_Name\" , \"roleUserID\" FROM table_worker INNER JOIN table_deployment ON table_worker.\"deploymentID\" = table_deployment.\"deploymentID\" WHERE table_worker.\"userID\" = " . $_SESSION["userID"] . $sql);
          $rows_count = pg_numrows($myresult);
          if ($rows_count != 0) {
              for ($i = 0; $i < $rows_count; $i++) {
                $deploymentName = pg_result($myresult, $i, 0);
                $roleUserID = pg_result($myresult, $i, 1);
                $role = pg_exec($connection, "SELECT \"role_Name\"  FROM table_role  WHERE \"roleUserID\" = " . $roleUserID);
                $roleName = pg_result($role, 0, 0);
                ?> <div class="list-group-item">
                      <h4 class="list-group-item-heading" style="font-size: 16px;"> <?php echo $deploymentName; ?>
                        <button class="btn btn-danger pull-right" onclick="deleteRow()">
                          <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </button>
                      </h4>
                      <p class="list-group-item-text" style="font-size: 16px;">Role: <?php echo $roleName; ?></p>
                      <a style="font-size: 16px;" href="<?php echo $roleName; ?>/manageDeployment-mobile.php?depName=<?php echo $deploymentName?>">Go to Deployment "<?php echo $deploymentName?>"</a>
                   </div> <?php
              }
          }

          // for($i=1;$i<=3;$i++){
            // echo'<div class="list-group-item">
            //       <h4 class="list-group-item-heading" style="font-size: 17px;">Deployment '. $i .'
            //         <button class="btn btn-danger pull-right" onclick="deleteRow()">
            //           <i class="fa fa-trash-o" aria-hidden="true"></i>
            //         </button>
            //       </h4>
            //       <p class="list-group-item-text">Role: '. $i .'</p>
            //       <a class="list-group-item-text" style="font-size: 17px;">go to Deployment "'. $i .'"</a>
            //     </div>';
          // }
        ?>
    </div>
    </div>
  </div>
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
            xmlhttp.open("GET","http://localhost/cgi-bin/deleteRow.py?deployName=<?php echo $deploymentName?>&user=<?php echo $_SESSION['username'] ?>",true);
            xmlhttp.send();
        }
    }
</script>

<!-- Footer -->
<div class="footer bg-4">
</div>

</div>

</body>
</html>
