<?php
//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main {

    public function __construct()
    {
        //print_r($_REQUEST);
        //set default page request when no parameters are in URL
        $pageRequest = 'uploadform';
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }
        //instantiate the class that is being requested
         $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }

    }

}

abstract class page {
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
        $this->html .= '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        print_r($this->html);
    }

    public function get() {
        echo 'default get message';
    }

    public function post() {
        print_r($_POST);
    }
}

class uploadform extends page
{

    public function get()
    {
		$form = '<div class="divmidfloater">';
 		$form .= '<h1>View Your CSV files!</h1><br><br>';
 		$form .= '<h3>Please upload your CSV below to view it:</h3><br><br>';
 		$form .= '<form enctype="multipart/form-data" method="POST" action="index.php?page=uploadform">';
 		$form .= '<input type="file" name="fileToUpload" id="fileToUpload"><br><br>';
        $form .= '<input type="submit" value="Upload & View" name="submit">';
        $form .= '</form> ';
        $this->html .= $form;
    }

    public function post() {
        $target_dir =  __DIR__ . "/uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$this->performFileUpload($target_file, $fileType);
    }

    // Main function that handles file uploading
	private function performFileUpload($target_file, $fileType){
		// Checking if the file doesnt already exist, and that it is of the correct file format
		if (!$this->isFileAlreadyExisting($target_file)){
			// this command uploads the file to the directory specified, and returns true if successful
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { 
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		        header('Location: https://web.njit.edu/~sa2225/file_upload_project/file_upload_project/index.php?page=uploadsuccess&fileName=' . urlencode(basename($_FILES["fileToUpload"]["name"])));
		    } else {
		        echo "Sorry, there was an error uploading your file.";
		    }
		} 
		// If file aready exists or is of incorrect format
		else {
			echo "Sorry, there was an error uploading your file.";
		}

	}

	//Check if the file exists
	private function isFileAlreadyExisting($target_file){
		
		if (file_exists($target_file)) {
		    echo "File already exists.";
		    return true;
		} else {
			return false;
		}
	}
}



class uploadsuccess extends page {
    
    public function __construct(){
        $this->html .= '<html>';
        $this->html .= '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
        $this->html .= '<H1>Your Uploaded File:</H1><br>';
        $this->html .= '<h3>File name:';
        $this->html .= $_REQUEST['fileName'];
        $this->html .= '</h3><br>';
		$target_file =  __DIR__ . "/uploads/" . $_REQUEST['fileName'];
        $this->displayFileContents($target_file);
    }

	// Main function that handles displaying the file
	private function displayFileContents($target_file){
		
		$file = fopen($target_file,"r");
		
		echo "<table>\n\n";
		while (($line = fgetcsv($file)) !== false) {
		        echo "<tr>";
		        foreach ($line as $cell) {
		                echo "<td>" . htmlspecialchars($cell) . "</td>";
		        }
		        echo "</tr>\n";
		}
		fclose($file);
		echo "\n</table>";
	}
	public function __destruct(){
        $this->html .= '</body></html>';
        print_r($this->html);
    }
}
?>