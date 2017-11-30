<?php
include_once('header.inc.php');

if(isset($_POST['debug']) && $_POST['debug'] == 1) {
    $debug_mode = true;
}

// Chargement du fichier
$json = file_get_contents('petri.json');
$petri = json_decode($json, true);

// Parse du fichier, traduction du JSON en HTML via la création
// des objets indispensables à la génération des pages
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
	<div class="success"><b>Congrats!</b> Your Petri network was successfully converted into a brand new website!</div>
	<div class="result"><img src="img/arrow-r.svg" width="16px" align-auto><?= $nPages ?> views and <?= $nScenes ?> scenes were generated</div>
	<script>$(function(){ setTimeout(function(){ $('.success, .result').slideUp(800); }, 4000); });</script>
	<div class="titleCat"></div>
	<div class="bt-back"><img src="img/arrow-r.svg" style="width:16px; transform:rotate(-180deg);" align-auto> Retour</div>
	<?php
	echo '<ul class="files">';
	$typesFile = array();
	foreach($fileList as $f) {
	    $filename = str_replace(OUTPUT_DIR."/", '', $f);
	    $typeF = explode("_", $filename)[0];
	   
	    if(!isset($typesFile[$typeF])) {
	        $typesFile[$typeF] = 1;
	    } else {
	       $typesFile[$typeF]++;
	    }
	    
        echo '<a href="'.$f.'" typefile="'.$typeF.'" style="display:none"><li>'.$filename.'</li></a>';
	}
	
	foreach($typesFile as $tf => $number) {
	   echo '<a href="#'.$tf.'" type="'.$tf.'"><li style="text-transform:capitalize">'.$tf."s (".$number.")</li></a>";
	}	

	echo '</ul>';
}
?>