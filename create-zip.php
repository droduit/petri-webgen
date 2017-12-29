<?php 
include_once('header.inc.php');

$zipname = "zips/project".crc32(session_id()).".zip";

if(file_exists($zipname)) {
	$response['status'] = "ok";
	$response['file'] = $zipname;
} else {
	$zip = new ZipArchive();
	if($zip->open($zipname, ZipArchive::CREATE) === true) {

		$dirGeneration = getDirGeneration();
		if(isset($_POST['debug'])) {
			if($_POST['debug'] == 1)
				$dirGeneration = OUTPUT_DIR;
		}
		
		// Fichiers générés
		foreach(getListDir(getDirGeneration().'/') as $file) {
			$fileParts = explode(".", $file); 
			if($fileParts[count($fileParts)-1] == "html")
				$zip->addFile($dirGeneration.'/'.$file, $file);
		}
		
		// Dependencies CSS
		$dirCss = $dirGeneration.'/'.DEPENDENCIES_DIR.'/css/';
		foreach(getListDir($dirCss) as $file) {
			$fileParts = explode(".", $file); 
			if($fileParts[count($fileParts)-1] == "css")
				$zip->addFile($dirCss.$file, DEPENDENCIES_DIR.'/css/'.$file);
		}
		$dirCssImg = $dirGeneration.'/'.DEPENDENCIES_DIR.'/css/images/';
		foreach(getListDir($dirCssImg) as $file) {
			$fileParts = explode(".", $file); 
			if($fileParts[count($fileParts)-1] == "png")
				$zip->addFile($dirCssImg.$file, DEPENDENCIES_DIR.'/css/images/'.$file);
		}
		
		// Dependencies JS
		$dirJs = $dirGeneration.'/'.DEPENDENCIES_DIR.'/js/';
		foreach(getListDir($dirJs) as $file) {
			$fileParts = explode(".", $file); 
			if($fileParts[count($fileParts)-1] == "js")
				$zip->addFile($dirJs.$file, DEPENDENCIES_DIR.'/js/'.$file);
		}
		
		$zip->setArchiveComment("Powered by PetriMedia");
		$zip->close();
		
		$response['status'] = "ok";
		$response['file'] = $zipname;
	} else {
		$response['status'] = "err";
	}
}
   
echo json_encode($response);
?>