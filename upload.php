<?php

echo ini_get('display_errors');


/*ini_set('file_uploads', 'On');*/

$target_dir =  __DIR__ . "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);


$obj = new main();
$obj->performFileUpload($target_file, $uploadOk, $fileType);

class main {
    
    public function __construct() {
    	echo "Upload handler start";
	} 

	// Main function that handles file uploading
	function performFileUpload($target_file, $uploadOk, $fileType){
		echo "Log 1";
		// Checking if the file doesnt already exist, and that it is of the correct file format
		if (!$this->isFileAlreadyExisting($target_file) && $this->isCorrectFileFormat($fileType)){
			
			echo move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
			//chmod($target_file, 0666);
			echo "Log : Mode changed";
		    if (move_uploaded_file($_FILES["fileToUpload"]["name"], $target_file)) {
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }

		    //header('Location: http://www.google.com/');

		} 
		// If file aready exists or is of incorrect format
		else {
			echo "Sorry, there was an error uploading your file.";
		}
		echo "Log 2";

	}

	//Check if the file exists
	function isFileAlreadyExisting($target_file){
		
		echo "Log Checking existing file...";
		if (file_exists($target_file)) {
		    echo "File already exists.";
		    return true;
		} else {
			return false;
		}
	}

	// Allow certain file formats
	function isCorrectFileFormat($fileType){

		echo "Log Checking format...";
		if($fileType != "csv" ) {
	    	echo "File format is incorrect";
	    	return false;
	    }else{
	    	return true;
	    }
	}

	public function __destruct() {
	    echo "Upload handler end";
    }

}


?>