<?php
include_once('header.inc.php');

if(isset($_POST['debug']) && $_POST['debug'] == 1) {
    $debug_mode = true;
}

// Chargement du fichier
$json = file_get_contents('petri.json');
$petri = json_decode($json, true);

include_once('parser.php');

// Physical files generation --------------------------
$fileList = array();
if(count($err) == 0) {
    
	$nScenes = 0;
	// On créé un fichier pour chaque scenes
	foreach($sceneArray as $scene) {
	    $content = getHeaderScene($scene);
	    $content .= $scene->getHTMLContent();
	    $content .= getFooter();
	    
	    $filename = SCENE_DIR."/".getSceneFilename($scene->getId());
	    array_push($fileList, $filename);
	    createFile($filename, $content);
	    
	    $nScenes++;
	}
	
	$nViews = 0;
	// On créé un fichier pour chaque vues
	foreach($views as $view) {
	    $content = getHeaderView($view->getTitle());
	    $content .= $view->getFramesHTML();	    
	    $content .= getFooter();
	    
	    // On créé un fichier pour chaque vues
	    $filename = OUTPUT_DIR."/".getViewFilename($view->getId());
	    array_push($fileList, $filename);
	    createFile($filename, $content);
	    
	    $nPages++;
	}
}
// ---------------------------------------------------------

if(count($err) > 0) {
    // Display Errors if there is some
    echo '<div class="error">'.count($err).' error(s) occured during the generating process</div>';
    echo '<ul>';
    foreach($err as $er) {
        echo "<li>".$er."</li>";
    }
    echo '</ul>';
} else {
    ?>
	<div class="success"><b>Congrats!</b> Your Petri network was successfully convertfiles files ed into a brand new website!</div>
	<div><img src="img/arrow-r.svg" width="16px" align-auto><?= $nPages ?> views and <?= $nScenes ?> scenes were generated</div>
	<?php
	echo '<ul class="files">';
	foreach($fileList as $f) {
        echo '<a href="'.$f.'"><li>'.str_replace(OUTPUT_DIR."/", '', $f).'</li></a>';
	}
	echo '</ul>';
}
?>