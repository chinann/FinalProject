
	<?php

		require('../connectDB.php');

 
		if(isset($_FILES))
		{

				$file_name = $_FILES['filUpload']['name'];
				$file_size =$_FILES['filUpload']['size'];
				$file_tmp =$_FILES['filUpload']['tmp_name'];
				$file_type=$_FILES['filUpload']['type'];
				move_uploaded_file($file_tmp,"../CSVFileUpload/".$file_name);

			$pathFile1 = "CSVFileUpload/";
			$pathFile = $pathFile1.$file_name;
			$inputType = "CSVFile";
			echo $pathFile;
		}
        else{
            echo "testtttttt";
        }
	?>

