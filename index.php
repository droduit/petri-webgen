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
	
			// List of all child nodes of the scene
			$child_nodes = array();
			foreach($s['sprites'] as $sprite) {
				if(isset($sprite['childs'])) {
					foreach($sprite['childs'] as $c) {
						$child_nodes[$c] = getSpriteName($sprite['id']);
					}
				}
			}
			
			
			// Sprites --------------------------------
			foreach($s['sprites'] as $sprite) {
				// Fetch de tous les tableaux
				$props = array();
				foreach($SPRITE_TAG_TO_FETCH as $attrName) {
					if(isset($sprite[$attrName])) {
						$props[$attrName] = $sprite[$attrName];
					}
				}
				
				$value = isset($sprite['value']) ? $sprite['value'] : NULL;
				$newSprite = new Sprite($sprite['nature'], $sprite['id'], $value, $props);
				
				// Ajout du sprite à la scene ou dans son sprite parent
				if(isset($child_nodes[$sprite['id']])) {
					$scene->getSprite($child_nodes[$sprite['id']])->addChild($newSprite);
				} else {
					$scene->addSprite($newSprite);
				}
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
			$content = getHeader($scene);
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
		
		<div style="border:1px solid #ddd; border-radius: 8px; padding: 6px; margin:20px auto; float:left">
			<div style="float:left; margin-right: 20px">
				<img width="115" src="https://scontent-frx5-1.xx.fbcdn.net/v/t1.0-9/22045584_10214332095521962_2816500085493263649_n.jpg?oh=43eef7581d707362c13306aeac10e41a&oe=5A7A2AA9" />
			</div>
			<div style="float: left">
				<h2>Dominique Roduit</h2>
				<h5>EPFL</h5>
			</div>
			<div style="clear:both"></div>
		</div>
		
		<div style="clear:both"></div>
		
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