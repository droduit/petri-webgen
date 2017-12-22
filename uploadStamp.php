<?php 
session_start();

include_once('header.inc.php');

//generate unique file name
$fileName = time().'_'.basename($_FILES["file"]["name"]);

//file upload path
$targetDir = "uploads/";
$targetFilePath = $targetDir . $fileName;

//allow certain file formats
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
    $response['status'] = 'ok';
    $_SESSION['stamp'] = $targetFilePath;
    $response['file'] = $_SESSION['stamp'];
} else{
    $response['status'] = 'err';
}
   
echo json_encode($response);
?>