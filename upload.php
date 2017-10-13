<?php

ini_set('file_uploads', 1);
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

$target_dir =  __DIR__ . "/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

$obj = new main();
$obj->performFileUpload($target_file, $fileType);

class main {
    
    public function __construct() {
	} 

	// Main function that handles file uploading
	function performFileUpload($target_file, $fileType){
		// Checking if the file doesnt already exist, and that it is of the correct file format
		if (!$this->isFileAlreadyExisting($target_file) && $this->isCorrectFileFormat($fileType)){

			// this command uploads the file to the directory specified, and returns true if successful
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { 
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }

		    header('Location: https://web.njit.edu/~sa2225/file_upload_project/file_upload_project/upload_success.php?fileName=' . basename($_FILES["fileToUpload"]["name"]));

		} 
		// If file aready exists or is of incorrect format
		else {
			echo "Sorry, there was an error uploading your file.";
		}

	}

	//Check if the file exists
	function isFileAlreadyExisting($target_file){
		
		if (file_exists($target_file)) {
		    echo "File already exists.";
		    return true;
		} else {
			return false;
		}
	}

	// Allow certain file formats
	function isCorrectFileFormat($fileType){

		if($fileType != "csv" ) {
	    	echo "File format is incorrect";
	    	return false;
	    }else{
	    	return true;
	    }
	}

	public function __destruct() {
    }

}


?>