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

// The main class that handles page requests
class main {

    public function __construct()
    {
        //set default page request when no parameters are in URL
        $pageRequest = 'uploadform';
        
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }

        //instantiate the class that is being requested
         $page = new $pageRequest;

        // Based on the type of request - GET/POST - Calls relative method
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }

    }

}

// Abstract class to hold common HTML tag loading for every displayed page
abstract class page {
    protected $html;

    // Loading up the style sheets
    public function __construct()
    {
        $this->html .= '<html>';
        $this->html .= '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }

    // Closing the tags
    public function __destruct()
    {
        $this->html .= '</body></html>';
        print_r($this->html);
    }

    public function get() {
        
    }

    public function post() {
        //print_r($_POST);
    }
}

// Upload form class that displays the form and handles the file uploading
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

                // Using header function to redirect to upload success page
                header('Location: https://web.njit.edu/~sa2225/file_upload_project/file_upload_project/index.php?page=uploadsuccess&fileName=' . urlencode(basename($_FILES["fileToUpload"]["name"])));

            } else {

                echo "Sorry, there was an error uploading your file.";
                echo '<br><br><input type="button" value="Upload another file" onclick="history.back()">';
            }
        } 
        // If file aready exists or is of incorrect format
        else {

            echo "Sorry, this file already exists on the server. Try uploading a different file:";
            echo '<br><br><input type="button" value="Upload another file" onclick="history.back()">';
        
        }

    }

    //Check if the file exists
    private function isFileAlreadyExisting($target_file){

        // Checking the existing file path 
        if (file_exists($target_file)) {
            return true;
        } else {
            return false;
        }
    }
}

// Upload success class that handles the displaying of the file contents on successful upload
class uploadsuccess extends page {
    
    // Show initial messages for upload success and call function to generate table
    public function get() {
        $this->html .= '<div class="divmidfloater">';
        $this->html .= '<H1>Your Uploaded File</H1><br>';
        $this->html .= '<h3>File name: ';
        $this->html .= $_REQUEST['fileName']; // Fetching the name of the uploaded file that was set in the request 
        $this->html .= '</h3><br><br>';
        $this->html .= '<input type="button" value="Upload another file" onclick="history.back()"><br><br>';
        $this->html .= '</div>';

        // Building entire path to the file including filename and extension
        $target_file =  __DIR__ . "/uploads/" . $_REQUEST['fileName'];

        // Calling function to display the file contents
        $this->displayFileContents($target_file);
    }

    // Main function that handles displaying the file by generating a table
    private function displayFileContents($target_file){
        
        //Opened file stream
        $file = fopen($target_file,"r");

        //Starting to build the table
        $this->html .= '<table>';
        $firstRow = true;
        // Looping on the file to check if data lines exist - then print each value per line in a td
        // Used function fgetcsv to read CSV file
        while (($line = fgetcsv($file)) !== false) {
            $this->html .= '<tr>';

            // For first row building th tag, and td for the rest
            if($firstRow){

                //Loop to build individual cell
                foreach ($line as $cell) {
                    $this->html .=  '<th>' . htmlspecialchars($cell) . '</th>';
                }
                $firstRow = false;
            } else {   

                //Loop to build individual cell
                foreach ($line as $cell) {
                    $this->html .=  '<td>' . htmlspecialchars($cell) . '</td>';
                }
            }
            $this->html .= '</tr>';
        }
        // Closed file stream
        fclose($file);

        $this->html .= '</table>';
    }
}
?>