<?php
include_once('header.inc.php');

// Chargement du fichier
$json = file_get_contents('petri.json');
$petri = json_decode($json, true);

include_once('parser.php');

// Physical files generation --------------------------
$fileList = array();
if(count($err) == 0) {
    
	$nPage = 0;
	$nScene = 0;
	// On parcours chaque pages
	foreach($pages as $containerId => $p) {

	    // On créé un fichier pour chaque scene de la page
	    foreach($p as $scene) {
	      $s = $sceneArray[$scene];
	      $content = getHeader($s);
		  $content .= $s->getHTMLContent();
		  $content .= getFooter();
		  
		  $filename = SCENE_DIR."/scene_".$s->getId().'.html';
		  array_push($fileList, $filename);
		  createFile($filename, $content);
		  $nScene++;
	    }
	    
	    
	    $content = getHeader($sceneArray[$p[0]]);
	    foreach($p as $scene) {
	        $s = $sceneArray[$scene];
	        $content .= getFrame($s);
	    }
	    $content .= getFooter();
	    
	    
	    if(count($p) > 1) {
    	    $filename = OUTPUT_DIR."/page_".$containerId.'.html';
    	    array_push($fileList, $filename);
    	    createFile($filename, $content);
    	    $nPage++;
	    }
	
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
	<div><img src="img/arrow-r.svg" width="16px" align-auto><?= $nPage ?> pages containing <?= $nScene ?> scenes were generated</div>
	<?php
	echo '<ul class="files">';
	foreach($fileList as $f) {
        echo '<a href="'.$f.'"><li>'.str_replace(OUTPUT_DIR."/", '', $f).'</li></a>';
	}
	echo '</ul>';
}
?>