<!DOCTYPE html>
<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
		<style type="text/css">
			body {font-family: 'Roboto', sans-serif;}
			table tr:first-child{font-weight: bold;background-color: #7678ED !important;font-size: 12px;}
			table tr:nth-child(even) {background-color: #F7B801;font-size: 12px;}
			table tr:nth-child(odd) {background-color: #E8EBE4;font-size: 12px;}
			table {width: 100%;font-size: 13px;}
			table, th, td {border: 1px solid black;border-collapse: collapse;}
			.divmidfloater {margin: 0 auto;}
		</style>
	</head>
	<body>
		<div class="divmidfloater">    
			<h1>View Your CSV files!</h1>
			<br>
			<br>	
			<h3>Please upload your CSV below to view it:</h3>
			<br>
			<br>	
			<form enctype="multipart/form-data" method="POST" action="upload.php">
		      <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" />
		      <input type="submit" name="submitButton" value="Upload File"> 
		    </form>
		</div>
  </body>
</html>