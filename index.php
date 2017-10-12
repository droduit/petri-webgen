<?php 
$debug = true;

require_once('utils.php');
require_once('objects.php');
		
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Petri modelisation to website converter</title>
		<meta charset="UTF-8">
		<script src="js/jquery-3.2.1.min.js"></script>
	</head>
	
	<body>
	
		<?php
		
		// Chargement du fichier
		// $petri = simplexml_load_file("petri.xml");
		$json = file_get_contents('petri.json');
		$petri = json_decode($json, true);
		
		// Information processing ---------------------
		// Scenes
		$sceneArray = array();
		foreach($petri['scenes'] as $s) {
			$scene = new Scene($s);
			
			// Sprites --------------------------------
			foreach($s['sprites'] as $sprite) {
				
				// Fetch de tous les tableaux
				$props = array();
				foreach($SPRITE_TAG_TO_FETCH as $attrName) {
					$props[$attrName] = isset($sprite[$attrName]) ? $sprite[$attrName] : array();
				}
				
				$value = isset($sprite['value']) ? $sprite['value'] : NULL;
				$name = uniqid($sprite['nature']."_".$sprite['id']);
				$newSprite = new Sprite($sprite['nature'], $name, $value, $props);
				
				// Ajout du sprite à la scene
				$scene->addToken($newSprite);
			}
			// -----------------------------------------
			
			// Ajout de la scene 
			$sceneArray[$s['id']] = $scene;
		}
		

		/*
		// Pre
		$arcsPre = array();
		foreach($petri['pre'] as $pre) {
			$arcsPre[$pre['id-trans']] = $pre['id-scene']; 
		}
		
		// Post
		$arcsPost = array();
		foreach($petri['post'] as $post) {
			$arcsPost[$post['id-trans']] = $post['id-scene']; 
		}
		*/
		
		/*
		// Transitions
		//$transArray = array();
		foreach($petri->transitions->transition as $t) {	
			$transition = new Transition($t['id'], $arcsPost[(string)$t['id']], null);
			foreach($t->event as $e) {
				$event = new Event($e['name'], $e['trigger']);
				$event->setScenePost($arcsPost[(string)$t['id']]);
				$transition->addEvent($event); 
			}
			$sceneArray[(string)$t['id-scene']]->bindTransition($transition);
		}
		*/
		// ------------------------------------------------------
		
		// Physical files generation --------------------------
		$nFile = 0;
		foreach($sceneArray as $scene) {
			$content = getHeader($scene->getArray());
			$content .= $scene->getCSS();
			if(count($scene->getSprites()) > 0) {
				foreach($scene->getSprites() as $token) {
					$content .= $token->getHTML();
				}
			}
			$content .= getFooter($scene->getId());
			createFile($content, $scene->getId());
			$nFile++;
		}
		// ---------------------------------------------------------
			
		?>
		
		<?= $nFile ?> files successfully generated
		<?php
		foreach(getListDir("generated") as $f) {
			echo '<li><a href="generated/'.$f.'">'.$f.'</a></li>';
		}
		
		?>
		
		<?php if($debug && isset($_GET['reload'])) { ?>
			<script>$(function(){ document.location.href="generated/<?= $_GET['reload'] ?>.html"; });</script>
		<?php } ?>
		
	</body>
	
</html>