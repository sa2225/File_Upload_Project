<?php

$target_file =  __DIR__ . "/uploads/" . $_GET['fileName'];

$obj = new main();
$obj->displayFileContents($target_file, $fileType);

class main {
public function __construct() {
	} 

	// Main function that handles displaying the file
	function displayFileContents($target_file){
		echo "in function";
		$file = fopen($target_file,"r");
		echo "file opened";
		$file_contents_array = fgetcsv($file));
		echo "array created";
		print_r($file_contents_array);

	}

	public function __destruct() {
    }

}

?>