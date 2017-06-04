<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<?php

		require('connectDB.php');

		if(isset($_FILES["filUpload"]))
		{
			foreach($_FILES['filUpload']['tmp_name'] as $key => $val)
			{
				$file_name = $_FILES['filUpload']['name'][$key];
				$file_size =$_FILES['filUpload']['size'][$key];
				$file_tmp =$_FILES['filUpload']['tmp_name'][$key];
				$file_type=$_FILES['filUpload']['type'][$key];
				move_uploaded_file($file_tmp,"GCaaS-3/fileUpload/".$file_name);

			}
			echo "Copy/Upload Complete";
			$pathFile1 = "GCaaS-3/fileUpload/";
			$pathFile = $pathFile1.$file_name;
			$inputType = "CSVFile";
			echo $pathFile;
		}
		?>
		<br>

		<input name="btnVerify" type="submit" value="CheckData" onclick="verifyData('<?php echo $pathFile; ?>','<?php echo $inputType; ?>')">
		<script>
			function verifyData(pathFile,inputType) {
				alert(pathFile);
				var xmlhttp;
				if(window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
					//console.log(xmlhttp);
				}
				else {// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}

				console.log(xmlhttp.status);
				xmlhttp.onreadystatechange=function()
				{
					//alert("Status Code: " . xmlhttp.status);
						if (xmlhttp.readyState==4 && xmlhttp.status==200)//200=status ok!
						{
								obj = JSON.parse(xmlhttp.responseText);
								/*
								if (obj.status == "None") {
										alert("No username!!");
								}
								*/
								alert(obj);
						}
						xmlhttp.open("GET","http://" +"<?php echo $_SESSION['host'] ?>" +"/Test/staticDataLayer.py?pathFile="+pathFile+"&inputType="+inputType,true);
						xmlhttp.send();
				}
			}
		</script>
	</body>
</html>
