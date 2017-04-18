<?php include "twitteroauth.php"; ?>
<?php
    session_start();
    if(isset($_GET['depName']) /*you can validate the link here*/){
        $_SESSION['depname'] = $_GET['depName'];
    }
    $consumer="cbyagg294ICwg4SwvVdTDOnAk";
    $consumersecret="k3GeVHy21yoTMjCTgLcjQMFZvDj5Mf1T4QSGRrxK9iZNiuSax9";
    $accesstoken="131746690-9c1O8LTHonwN5i7C8aXmNrUNV7ui9LwLAPCEfKWV";
    $accesstokensecret="AtXEXhPTraD7lglCG6oB0Sdi2RFBNxADIu2bGwIRPiO31";
    
    require('connectDB.php');
           
    if (! $_SESSION['connection']) {
    	echo "Connection Failed.";
    	exit;
    }

    // $connection = pg_connect("host=172.16.150.177 port=5432 dbname=GCaaS user=postgres password=1234");
    // if (!$connection) {
    //     echo "Connection Failed.";
    //     exit;
    // }
    else {
        $result_Hashtag = pg_exec($connection, "SELECT \"deployment_Hashtag\" FROM table_deployment WHERE \"deployment_Name\" = '" .$_SESSION["depname"]."';");
        $rows_Hashtag = pg_numrows($result_Hashtag);
        $column_Hashtag = pg_numfields($result_Hashtag);
        $deployment_Hashtag = "";
        if($rows_Hashtag != 0) {
            for ($i = 0; $i < $rows_Hashtag; $i++) {
                for ($j = 0; $j < $column_Hashtag; $j++) {
                    if ($j == 0) {
                        $deployment_Hashtag = pg_result($result_Hashtag, $i, $j);
                    }
                }
            }
        }
    }
    $deployment_Hashtag = "%23" . $deployment_Hashtag;
    $deployment_Hashtag = str_replace(" ","+%23",$deployment_Hashtag);
    pg_close($connection);
    
    require('connectDB.php');
           
    if (! $_SESSION['connection']) {
    	echo "Connection Failed.";
    	exit;
    }

    // $connect = pg_connect("host=172.16.150.177 port=5432 dbname=DB_". $_SESSION['depname'] ." user=postgres password=1234");
    // if (!$connect) {
    //     print("Connection Failed.");
    //     exit;
    // }
    else {
        $twitter = new TwitterOAuth($consumer,$consumersecret,$accesstoken,$accesstokensecret);
        $tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q='.$deployment_Hashtag.'&result_type=th&count=50');

        foreach ($tweets as $tweet){
            foreach($tweet as $t){
                $checkInput = "SELECT \"post_Name\", \"post_Date\" FROM \"table_postTWH\" WHERE \"post_Name\" = '" . $t->user->name . "' AND \"post_Date\" = '" . $t->created_at . "';";
                $result = pg_exec($connect, $checkInput);
                $rows = pg_numrows($result);
                if ($rows == 0) {
                    $sql = "INSERT INTO \"table_postTWH\"(\"post_Name\", \"post_GeomIncident\", \"post_Date\", \"post_Status\", \"post_Message\", \"post_Hashtag\", \"post_ProfileImg\", \"post_Place\") VALUES ('" . $t->user->name ."', ST_GeomFromText('POINT(" . $t->geo->coordinates[1] . " " . $t->geo->coordinates[0] . ")',4326), '" . $t->created_at . "', 'New' , '" . $t->text . "', 'GCaaS', '" . $t->user->profile_image_url . "', '" . $t->place->full_name . "');";
                    $myresult = pg_exec($connect, $sql);
                }
                // echo $sql
                // echo "</br>"."<img src =".$t->user->profile_image_url." >";
                // echo "&nbsp&nbsp&nbsp ".$t->user->name."</br>";
                // echo $t->text;
                // echo " ";
                // echo $t->geo->coordinates[0]."&nbsp&nbsp ".$t->geo->coordinates[1]."&nbsp&nbsp ";
                // echo $t->place->full_name." &nbsp&nbsp";
                // echo $t->created_at." &nbsp&nbsp";
                // echo "</br>";
            }
        }
    }
?>