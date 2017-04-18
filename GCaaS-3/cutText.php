<?php include "twitteroauth.php"; ?>
<?php 
$consumer="cbyagg294ICwg4SwvVdTDOnAk";
$consumersecret="k3GeVHy21yoTMjCTgLcjQMFZvDj5Mf1T4QSGRrxK9iZNiuSax9";
$accesstoken="131746690-9c1O8LTHonwN5i7C8aXmNrUNV7ui9LwLAPCEfKWV";
$accesstokensecret="AtXEXhPTraD7lglCG6oB0Sdi2RFBNxADIu2bGwIRPiO31";

$twitter = new TwitterOAuth($consumer,$consumersecret,$accesstoken,$accesstokensecret);
$tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q=%23GCaaS&result_type=th&recent&count=100');
?>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Twitter API | Topic test </title>
	</head>
	<body>

	<?php
			

			foreach ($tweets as $tweet){ 	   	     	   
     	   	foreach($tweet as $t){
     	      
     	   	  // echo "<script type=\"text/javascript\">console.log(".$t->geo->coordinates[0].")</script>";
     	   		$string = $t->text;
            $geo = $t->geo->coordinates[0];
  					$token = strtok($string, "#");
  				  echo $string . "</br>";
            echo $geo . "</br>";
  					
  				$i=0;
					while ($token !== false){
						$word[$i] = $token;
    					$token = strtok("#");
    					$i++;
    				}

     	   		}
     	   	}
     	   	
     	   	for($x=0;$x<count($word);$x++){
     	   			echo $word[$x];
 					 echo "</br>";
     	   	}
	

	?>
	</body>
</html>