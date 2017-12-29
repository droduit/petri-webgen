<?php
include_once('header.inc.php');

if(isset($_POST['debug'])) {
    $debug_mode = $_POST['debug'] == 1 ? true : false;
}
if(isset($_POST['mdpNeeded'])) {
    $mdpRequired = $_POST['mdpNeeded'] == 1 ? true : false;
}

// Chargement du fichier
$json_filename = $debug_mode ? "petri.json" : $_SESSION['file'];
$json = file_get_contents($json_filename);
$petri = json_decode($json, true);


if(!$debug_mode && $mdpRequired && $_SESSION['pwd'] != getUserPwdHash()) {
    include_once('upload.php');
} else {
    // Parse du fichier, traduction du JSON en HTML via la création
    // des objets indispensables à la génération des pages
    include_once('parser.php');
    
    // Physical files generation --------------------------
    $fileList = array();
    if(count($err) == 0) {
        
    	$nScenes = 0;
		$nPages = 0;
		
		if(!$debug_mode) {
			// Création du dossier dans lequel insérer les fichiers générés
			if(!file_exists(getDirGeneration())) 
				mkdir(getDirGeneration(), 0777);
			
			// Copie des dépendances
			copyr(OUTPUT_DIR."/".DEPENDENCIES_DIR, getDirGeneration()."/".DEPENDENCIES_DIR);
			
			$dirGeneration = getDirGeneration();
		} else {
			$dirGeneration = OUTPUT_DIR;
		}
		
    	// On créé un fichier pour chaque scenes
    	foreach($sceneArray as $scene) {
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
    	foreach($views as $view) {
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
    	if($index != NULL) {
    	    $content = getContentIndex($index);
        	$filename = "index.html";
        	$index['filename'] = $filename;
        	createFile($dirGeneration."/".$filename, $content);
    	}
    }
    // ---------------------------------------------------------
    
    
    if(!checkStampSanity()) { ?>
    	<div class="error">L'emprunte numérique du réseau de pétri est malformée ou a été modifiée</div>
    <?php } 
        				
    if(count($err) > 0) {
        // Display Errors if there is some
        echo '<div class="error">'.count($err).' error(s) occured during the generating process</div>';
        echo '<ul>';
        foreach($err as $er) {
            echo "<li>".$er."</li>";
        }
        echo '</ul>';
        
        unset($_SESSION['file']);
        unset($_SESSION['pwd']);
        unset($_SESSION['stamp']);
    } else {
        if(checkStampSanity() && getSavedStamp() != getStampOf($json_filename)) {  ?>
        	<div class="error">Attention ! Le fichier a été modifié par rapport à l'original</div>
        <?php } ?>
        
    	<div class="success"><b>Congrats!</b> Your Petri network was successfully converted into a brand new website!</div>
    	<script>$(function(){ setTimeout(function(){ $('.success').slideUp(800); }, 4000); });</script>
    	
    	<?php
    	if($index != NULL) { ?>
    		<a class="startIndex" href="<?= getDirGeneration()."/".$index['filename'] ?>">Démarrer mon site</a>
    	<?php 
    	}
    	?>
    	
    	<div class="titleCat"></div>
    	<div class="bt-back"><img src="img/arrow-r.svg" style="width:16px; transform:rotate(-180deg);" align-auto> Retour</div>
    	
    	<?php
    	echo '<ul class="files">';
    	$typesFile = array();
    	$indexExist = false;
    	foreach($fileList as $f) {
    	    $filename = str_replace(OUTPUT_DIR."/", '', $f);
    	    $typeF = explode("_", $filename)[0];
            $typeF .= "s";
    	    
    	    if(!isset($typesFile[$typeF])) {
    	        $typesFile[$typeF] = 1;
    	    } else {
    	       $typesFile[$typeF]++;
    	    }
    	    
            echo '<a href="'.getDirGeneration()."/".$f.'" typefile="'.$typeF.'" style="display:none"><li><img src="img/arrow-r.svg" width="16px" align-auto> '.$filename.'</li></a>';
    	}
    	
    	
    	foreach($typesFile as $tf => $number) {
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
    
    <div class="export button">Exporter le site</div>
    <?php if(!$debug_mode) {?>
    <div class="btUploadNew button">Convertir un autre fichier</div>
    <?php } ?>

<?php 
}
?>
