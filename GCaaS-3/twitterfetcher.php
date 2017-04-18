     	   <?php include "twitteroauth.php"; ?>
     	   <?php 
     	   $consumer="cbyagg294ICwg4SwvVdTDOnAk";
     	   $consumersecret="k3GeVHy21yoTMjCTgLcjQMFZvDj5Mf1T4QSGRrxK9iZNiuSax9";
     	   $accesstoken="131746690-9c1O8LTHonwN5i7C8aXmNrUNV7ui9LwLAPCEfKWV";
     	   $accesstokensecret="AtXEXhPTraD7lglCG6oB0Sdi2RFBNxADIu2bGwIRPiO31";
     	   
     	   $twitter = new TwitterOAuth($consumer,$consumersecret,$accesstoken,$accesstokensecret);
     	   $tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q=%23sotustheseries&result_type=th&count=50');   	   
     	     print_r($tweets);
           echo "</br>";
           echo "</br>";
           echo "</br>";

     	   foreach ($tweets as $tweet){
				var_dump($tweet);	   	
     	   	foreach($tweet as $t){
					var_dump($t);
     	   			echo "</br>"."<img src =".$t->user->profile_image_url." >";
					echo "&nbsp&nbsp&nbsp ".$t->user->name."</br>";
     	   			echo $t->text;
     	   			echo " ";
     	   			echo $t->geo->coordinates[0]."&nbsp&nbsp ".$t->geo->coordinates[1]."&nbsp&nbsp ";
     	   			echo $t->place->full_name." &nbsp&nbsp";
     	  			echo $t->created_at." &nbsp&nbsp";
     	  			echo "</br>";
     	   	}
     	   	
     	   }
		?> 