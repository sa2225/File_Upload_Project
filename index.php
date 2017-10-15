
<!-- 	<body>
		<div class="divmidfloater">    
			<h1>View Your CSV files!</h1>
			<br>
			<br>	
			<h3>Please upload your CSV below to view it:</h3>
			<br>
			<br>	
			<form enctype="multipart/form-data" method="POST" action="upload.php">
		      <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" />
		      <br>
		      <input type="submit" name="submitButton" value="Upload & View"> 
		    </form>
		</div>
  </body> -->

<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


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
        $pageRequest = 'homepage';
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
        /*stringFunctions::printThis($this->html);*/
    }

    public function get() {
        echo 'default get message';
    }

    public function post() {
        print_r($_POST);
    }
}

class homepage extends page {

    public function get() {

        $form = '<form action="index.php" method="post">';
        $form .= 'First name:<br>';
        $form .= '<input type="text" name="firstname" value="Mickey">';
        $form .= '<br>';
        $form .= 'Last name:<br>';
        $form .= '<input type="text" name="lastname" value="Mouse">';
        $form .= '<input type="submit" value="Submit">';
        $form .= '</form> ';
        $this->html .= 'homepage';
        $this->html .= $form;
    }

}

class uploadform extends page
{

    public function get()
    {
        $form = '<form action="index.php?page=uploadform" method="post"
	enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
        $form .= '<input type="submit" value="Upload Image" name="submit">';
        $form .= '</form> ';
        $this->html .= '<h1>Upload Form</h1>';
        $this->html .= $form;

    }

    public function post() {
        echo 'test';
        print_r($_FILES);
    }
}



class htmlTable extends page {}


?>