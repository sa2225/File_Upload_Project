<?php

$target_file =  __DIR__ . "/uploads/" . $_GET['fileName'];

$file = fopen($target_file,"r");

$file_contents_array = fgetcsv($file));

print_r($file_contents_array);

?>