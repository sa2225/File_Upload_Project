<head>
	<style type="text/css">
		table tr:first-child{font-weight: bold;background-color: #7678ED; !important}
		table tr:nth-child(even) {background-color: #F7B801;}
		table {width: 100%;}
	</style>
</head>
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
		
		echo "<html><body><table>\n\n";
		while (($line = fgetcsv($file)) !== false) {
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