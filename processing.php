<?php
include_once ('header.inc.php');

if (isset($_POST['debug'])) {
    $debug_mode = $_POST['debug'] == 1 ? true : false;
}
if (isset($_POST['mdpNeeded'])) {
    $mdpRequired = $_POST['mdpNeeded'] == 1 ? true : false;
}

// Chargement du fichier
$json_filename = $debug_mode ? "demo/debug/petri.json" : $_SESSION['file'];
if (!file_exists($json_filename)) {
	unset($_SESSION['file']);
    unset($_SESSION['pwd']);
    unset($_SESSION['stamp']);
	array_push($err, "Le fichier <b>".$json_filename."</b> n'existe pas.");
	echo '<meta http-equiv="refresh" content="0;URL=index.php">';
	$petri = null;
} else {
	$json = file_get_contents($json_filename);
	$petri = json_decode($json, true);
}


if (!$debug_mode && $mdpRequired && $_SESSION['pwd'] != getUserPwdHash()) {
    include_once('upload.php');
} else {
    // Parse du fichier, traduction du JSON en HTML via la création
    // des objets indispensables à la génération des pages
    if (!is_null($petri)) 
		include_once('parser.php');
	
	if (!isset($err))
		$err = array();
	
	if ($petri == null && json_last_error() !== JSON_ERROR_NONE) {
		array_push($err, "Le fichier JSON contient des erreurs : <br>".getJSONErrorMessage());
	}
    
    // Physical files generation --------------------------
    $fileList = array();
    if (count($err) == 0) {
        
    	$nScenes = 0;
		$nPages = 0;
		
		if (!$debug_mode) {
			// Création du dossier dans lequel insérer les fichiers générés
			if (!file_exists(getDirGeneration()))
				mkdir(getDirGeneration(), 0777);
			
			// Copie des dépendances
			copyr(OUTPUT_DIR."/".DEPENDENCIES_DIR, getDirGeneration()."/".DEPENDENCIES_DIR);
			
			$dirGeneration = getDirGeneration();
		} else {
			$dirGeneration = OUTPUT_DIR;
		}
		
    	// On créé un fichier pour chaque scenes
    	foreach ($sceneArray as $scene) {
    	    $content = getHeaderScene($scene);
    	    $content .= $scene->getHTMLContent();
    	    $content .= getFooter();
    	    
    	    $filename = getSceneFilename($scene->getId());
    	    array_push($fileList, $filename);
    	    createFile($dirGeneration."/".$filename, $content);
    	    
    	    $nScenes++;
    	}
    	
    	$nViews = 0;
    	// On créé un fichier pour chaque vues
    	foreach ($views as $view) {
    	    $content = getHeaderView($view->getTitle());
    	    $content .= $view->getFramesHTML();
    	    $content .= getFooter();
    	    
    	    // On créé un fichier pour chaque vues
    	    $filename = getViewFilename($view->getId());
    	    array_push($fileList, $filename);
    	    createFile($dirGeneration."/".$filename, $content);
    	    
    	    $nPages++;
    	}
    	
    	// Création de la page d'index
    	if ($index != NULL) {
    	    $content = getContentIndex($index);
        	$filename = "index.html";
        	$index['filename'] = $filename;
        	createFile($dirGeneration."/".$filename, $content);
    	}
    }
    // ---------------------------------------------------------
    
    
    if (!checkStampSanity()) { ?>
    	<div class="error">L'emprunte numérique du réseau de pétri est malformée ou a été modifiée</div>
    <?php }
        				
    if (count($err) > 0) {
        // Display Errors if there is some
        echo '<div class="error">'.count($err).' error(s) occured during the generating process</div>';
        echo '<ul class="error-reporting">';
        foreach ($err as $er) {
            echo "<li>".$er."</li>";
        }
        echo '</ul>';
        
        unset($_SESSION['file']);
        unset($_SESSION['pwd']);
        unset($_SESSION['stamp']);
    } else {
        if (checkStampSanity() && getSavedStamp() != getStampOf($json_filename)) {  ?>
        	<div class="error">The original Petri Net was altered</div>
        <?php } ?>
        
    	<div class="success"><b>Congrats!</b> Your Petri network was successfully converted into a brand new website!</div>
    	<script>$(function(){ setTimeout(function(){ $('.success').slideUp(800); }, 4000); });</script>
    	
    	<?php
    	if (!is_null($index)) { ?>
    		<a class="startIndex" href="<?= getDirGeneration()."/".$index['filename'] ?>">Launch my web app</a>
    	<?php
    	}
    	?>
    	
    	<div class="titleCat"></div>
    	<div class="bt-back"><img src="img/arrow-r.svg" style="width:16px; transform:rotate(-180deg);" align-auto> Back</div>
    	
    	<?php
    	echo '<ul class="files">';
    	$typesFile = array();
    	$indexExist = false;
    	foreach ($fileList as $f) {
    	    $filename = str_replace(OUTPUT_DIR."/", '', $f);
    	    $typeF = explode("_", $filename)[0];
            $typeF .= "s";
    	    
    	    if (!isset($typesFile[$typeF])) {
    	        $typesFile[$typeF] = 1;
    	    } else {
    	       $typesFile[$typeF]++;
    	    }
    	    
            echo '<a href="'.getDirGeneration()."/".$f.'" typefile="'.$typeF.'" style="display:none"><li><img src="img/arrow-r.svg" width="16px" align-auto> '.$filename.'</li></a>';
    	}
    	
    	
    	foreach ($typesFile as $tf => $number) {
    	   echo '<a href="#'.$tf.'" type="'.$tf.'"><li style="text-transform:capitalize"><img src="img/arrow-r.svg" width="16px" align-auto> '.$tf." (".$number.")</li></a>";
    	}	
    
    	echo '</ul>';
    }
    ?>
    
    <script>
    $(function(){
    	$('.btUploadNew').click(function(){
    		$.post('index.php', { newUpload:1 }, function(html){
    			location.href = 'index.php';
    		});
    	});
    	
    });
    </script>
    
	<?php if (count($err) <= 0) {?>
    <div class="export button">Export static files</div>
	<?php } ?>
	
    <?php if (!$debug_mode) {?>
    <div class="btUploadNew button">Convert another Petri Net</div>
    <?php } ?>

<?php
}
?>
