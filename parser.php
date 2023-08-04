<?php 
// Contains errors generated during the parsing
$err = array();

// Test s'il existe des scenes en doublon
$scenesIds = array();
$spritesIds = array();
$usedAsChilds = array();
foreach ($petri['scenes'] as $s) {
	if (in_array($s['id'], $scenesIds))
		$err[] = "The scene with id <b>".$s['id']."</b> is duplicated.";
	else
		array_push($scenesIds, $s['id']);
	
	// Test s'il existe des scenes en doublon
	if (isset($s['sprites'])) {
		$spritesIds[$s['id']] = array();
		foreach($s['sprites'] as $sprite) {
			if (in_array($sprite['id'], $spritesIds[$s['id']]))
				$err[] = "The sprite with id <b>".$sprite['id']."</b> in the scene <b>".$s['id']."</b> is duplicated.";
			else
				array_push($spritesIds[$s['id']], $sprite['id']);
		}
		
		$usedAsChilds[$s['id']] = array();
		foreach ($s['sprites'] as $sprite) {
			$childsId = (!isset($sprite['childs']) || count($sprite['childs']) == 0) ? null : $sprite['childs'];
							
			if (is_array($childsId)) {
				foreach ($childsId as $child) {
					if (!in_array($child, $spritesIds[$s['id']]))
						$err[] = "The sprite with id <b>".$child."</b> is used as child of the sprite <b>".$sprite['id']."</b> but does not exist.";
				}
				
				if (in_array($child, $usedAsChilds[$s['id']])) {
					$err[] = "The sprite <b>".$child."</b> is used as child by many different container sprites. This is not allowed.";
				}
				
				$usedAsChilds[$s['id']] = array_merge($usedAsChilds[$s['id']], $childsId);
			}
			
		}
		
	}
}

// Si pas d'erreurs de doublons, traitement des données
if (count($err) <= 0) {
	// Scenes =======================================================
	$sceneArray = array();

	// On parcours chaque scenes
	foreach ($petri['scenes'] as $s) {
		$scene = new Scene($s);
		
		// Sprites --------------------------------
		if (isset($s['sprites'])) {
			foreach ($s['sprites'] as $sprite) {
				
				// Fetch de tous les tableaux
				$props = array();
				foreach ($SPRITE_ATTR_TO_FETCH as $attrName) {
					if (isset($sprite[$attrName])) {
						$props[$attrName] = $sprite[$attrName];
					}
				}
				
				if (isset($sprite['clone'])) {
					$count = (isset($sprite['count'])) ? $sprite['count'] : 1;
					
					for($i = 0; $i < $count; ++$i) {
						$newSprite = clone $scene->getSprite($sprite['clone']);
						$newSprite->setId(uniqid($sprite['id']));
						$newSprite->addProps($props);
						
						if (isset($sprite['animations']))
							$newSprite->addAnimations($sprite['animations']);
						
						$scene->addSprite($newSprite);
					}
				} else {
					$value = isset($sprite['value']) ? $sprite['value'] : NULL;
					$childsId = (!isset($sprite['childs']) || count($sprite['childs']) == 0) ? null : $sprite['childs'];
			  
					$newSprite = new Sprite($sprite['nature'], $sprite['id'], $value, $props, $childsId);
					
					if (isset($sprite['animations']))
						$newSprite->addAnimations($sprite['animations']);
					
					// Ajout du sprite à la scene
					$scene->addSprite($newSprite);
				}
				
			}
			
			$scene->processSpritesChilds();
		}
		// ----------------------------------------
		
		// Ajout de la scene
		$sceneArray[$s['id']] = $scene;
	}
	// ==============================================================


	// Groupement des scenes dans des pages =========================
	$views = array();
	if (isset($petri['views'])) {
		// Pour chaque vue, on utilise le tableau avec les scene ayant le flag isInView=true
		foreach ($petri['views'] as $view) {
			$viewObj = new View($view['id'], isset($view['title']) ? $view['title'] : "");
			
			if (isset($view['scenes'])) {
				foreach($view['scenes'] as $frame) {
					if (!isset($sceneArray[$frame['scene-id']]))
						array_push($err, "The scene <b>".$frame['scene-id']."</b> is embedded into the view <b>".$view['id']."</b>, but this scene does not exist.");
					else
						$viewObj->addScene($frame['frame-id'], $sceneArray[$frame['scene-id']], $frame['style']);
				}
			} else {
				array_push($err, "The view <b>".$view['id']."</b> does not contain any scene.");
			}
			array_push($views, $viewObj);
		}
	}
	// ==============================================================


	// Transitions ==================================================
	if (isset($petri['transitions'])) {
		foreach($petri['transitions'] as $trans) {
			
			$assocOut = array();
			foreach ($trans['associationOut']['regles'] as $aO) {
				$dests = array();
				foreach($aO['dest'] as $dest) {
					if (!isset($dest['targets']))	$dest['targets'] = null;
					if (!isset($dest['js']))			$dest['js'] = null;
					if (!isset($dest['id']))			$dest['id'] = null;
					
					array_push($dests, new Dest($dest['type'], $dest['id'], $dest['targets'], $dest['js']));
				}
				$assocOut[$aO['id']] = $dests;
			}
			
			foreach ($trans['associationIn']['regles'] as $aI) {
				$dests = $assocOut[$aI['id']];
				
				
				foreach ($aI['scenes'] as $sceneFrom) {
					$transition = new Transition($aI['id'], null);
					
					// Creation des evenements pour chaque sprites de la scene
					foreach ($aI['sprites'] as $sprite) {
						$split = explode('.', $sprite);
						if (count($split) != 2) {
							$err[] = "The rule id=".$aI['id']." of the transition id=".$trans['id']." is malformed. The sprites must have the shape <i>spriteId.eventName</i>";
						}
					   
						$eventType = $split[1];
						$elemTrigger = $split[0];
						
						$event = new Event($eventType, $elemTrigger, $dests);
						
						// Ajout de l'evenement à la transition
						$transition->addEvent($event);
					}
					
					// Attachenement de la transition à la scene
					if (!isset($sceneArray[$sceneFrom]))
						array_push($err, "A transition is attached to the scene <b>".$sceneFrom."</b>, but this scene does not exist");
					else
						$sceneArray[$sceneFrom]->bindTransition($transition);
				}
			}
		}
	}
	// ==============================================================

	// On définit la page index =====================================
	$index = isset($petri['index']) ? $petri['index'] : null;
	// ==============================================================

	// Récupération des dépendences personnalisées de l'utilisateur==
	$dependencies = isset($petri['include']) ? $petri['include'] : null;
	// ==============================================================
}
?>