<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$target_file =  __DIR__ . "/uploads/" . $_GET['fileName'];

$obj = new main();
$obj->displayFileContents($target_file);

class main {

	public function __construct() {
	} 

	// Main function that handles displaying the file
	function displayFileContents($target_file){
		echo "in function";
		$file = fopen($target_file,"r");
		echo "file opened";
		print_r(fgetcsv($file));
		echo "array created";
	}

	public function __destruct() {
    }

}

?>