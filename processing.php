<?php
include_once('header.inc.php');

// Chargement du fichier
$json = file_get_contents('petri.json');
$petri = json_decode($json, true);

include_once('engine.php');

// Physical files generation --------------------------
if(count($err) == 0) {
	$nFile = 0;
	foreach($sceneArray as $scene) {
		$content = getHeader($scene);
		$content .= $scene->getHTMLContent();
		$content .= getFooter();
		createFile($content, $scene->getId());
		$nFile++;
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
	<div><img src="img/arrow-r.svg" width="16px" align-auto><?= $nFile ?> files generated</div>
	<?php
	echo '<ul class="files">';
	foreach(getListDir(OUTPUT_DIR) as $f) {
		echo '<a href="'.OUTPUT_DIR.$f.'"><li>'.$f.'</li></a>';
	}
	echo '</ul>';
}
?>