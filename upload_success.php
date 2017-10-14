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
		
		$file = fopen($target_file,"r");
		
		$file_contents = fgetcsv($file);
		
		echo "<html><body><table>\n\n";
		while (($line = $file_contents) !== false) {
		        echo "<tr>";
		        foreach ($line as $cell) {
		                echo "<td>" . htmlspecialchars($cell) . "</td>";
		        }
		        echo "</tr>\n";
		}
		fclose($file);
		echo "\n</table></body></html>";
	}

	public function __destruct() {
    }

}

?>