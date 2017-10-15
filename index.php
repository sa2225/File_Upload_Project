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
        
    }

    public function post() {
        print_r($_POST);
    }
}

class uploadform extends page
{
	// Generate and display the upload form
    public function get()
    {
		$form = '<div class="divmidfloater">';
 		$form .= '<h1>View Your CSV files!</h1><br><br>';
 		$form .= '<h3>Please upload your CSV below to view it:</h3><br><br>';
 		$form .= '<form enctype="multipart/form-data" method="POST" action="index.php?page=uploadform">';
 		$form .= '<input type="file" name="fileToUpload" id="fileToUpload" accept=".csv"><br><br>';
        $form .= '<input type="submit" value="Upload & View" name="submit">';
        $form .= '</form> ';
        $this->html .= $form;
    }

    // Handle the submission of the form and uploading of the file
    public function post() {

    	// Defining the target directory where the file will be stored
        $target_dir =  __DIR__ . "/uploads/";

        // Defining the full path - target directory + filename with extension
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$this->performFileUpload($target_file);
    }

    // Main function that handles file uploading
	private function performFileUpload($target_file){
		// Checking if the file doesnt already exist, and that it is of the correct file format
		if (!$this->isFileAlreadyExisting($target_file)){
			// this command uploads the file to the directory specified, and returns true if successful
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { 
		        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		        header('Location: https://web.njit.edu/~sa2225/file_upload_project/file_upload_project/index.php?page=uploadsuccess&fileName=' . urlencode(basename($_FILES["fileToUpload"]["name"])));
		    } else {
		        echo "Sorry, there was an error uploading your file.";
				echo '<br><br><input type="button" value="Upload another file" onclick="history.back()">';
		    }
		} 
		// If file aready exists or is of incorrect format
		else {
			echo "Sorry, there was an error uploading your file.";
		    echo '<br><br><input type="button" value="Upload another file" onclick="history.back()">';
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
        $this->html .= '<div class="divmidfloater">';
        $this->html .= '<H1>Your Uploaded File</H1><br>';
        $this->html .= '<h3>File name: ';
        $this->html .= $_REQUEST['fileName'];
        $this->html .= '</h3><br><br>';
        $this->html .= '<input type="button" value="Upload another file" onclick="history.back()"><br><br>';
        $this->html .= '</div>';
		$target_file =  __DIR__ . "/uploads/" . $_REQUEST['fileName'];
        $this->displayFileContents($target_file);
    }

	// Main function that handles displaying the file
	private function displayFileContents($target_file){
		
		$file = fopen($target_file,"r");
		
		$this->html .= '<table>';
		while (($line = fgetcsv($file)) !== false) {
		        $this->html .= '<tr>';
		        foreach ($line as $cell) {
		                $this->html .=  '<td>' . htmlspecialchars($cell) . '</td>';
		        }
		        $this->html .= '</tr>';
		}
		fclose($file);
		$this->html .= '</table>';
	}
	public function __destruct(){
        $this->html .= '</body></html>';
        print_r($this->html);
    }
}
?>