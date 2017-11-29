<?php 
// Contains errors generated during the parsing
$err = array();

// Scenes =======================================================
$sceneArray = array(); 

// Détermine si un container contient une seul scene ou plusieurs scene
$containers = array();
foreach($petri['scenes'] as $s) {
    if(array_key_exists($s['container'], $containers)) {
        $containers[$s['container']]++;
    } else {
        $containers[$s['container']] = 1;
    }
}

// On parcours chaque scenes
foreach($petri['scenes'] as $s) {
    $scene = new Scene($s, $containers[$s['container']] == 1);
    
    // Sprites --------------------------------
    foreach($s['sprites'] as $sprite) {
        
        // Fetch de tous les tableaux
        $props = array();
        foreach($SPRITE_ATTR_TO_FETCH as $attrName) {
            if(isset($sprite[$attrName])) {
                $props[$attrName] = $sprite[$attrName];
            }
        }
        
        if(isset($sprite['clone'])) {
            $count = (isset($sprite['count'])) ? $sprite['count'] : 1;
            
            for($i = 0; $i < $count; ++$i) {
                $newSprite = clone $scene->getSprite($sprite['clone']);
                $newSprite->setId(uniqid($sprite['id']));
                $newSprite->addProps($props);
                $scene->addSprite($newSprite);
            }
        } else {
            $value = isset($sprite['value']) ? $sprite['value'] : NULL;
            $childsId = (count($sprite['childs']) == 0) ? null : $sprite['childs'];
      
            $newSprite = new Sprite($sprite['nature'], $sprite['id'], $value, $props, $childsId);
            // Ajout du sprite à la scene
            $scene->addSprite($newSprite);
        }
        
    }
    //$scene->traverseTree();
    $scene->processSpritesChilds();
    
    // ----------------------------------------
    
    // Ajout de la scene
    $sceneArray[$s['id']] = $scene;
}
// ==============================================================


// Transitions ==================================================
foreach($petri['transitions'] as $trans) {
    $assocOut = array();
    foreach($trans['associationOut']['regles'] as $aO) {
        $assocOut[$aO['id']] = $aO['dest'];
    }
    
    foreach($trans['associationIn']['regles'] as $aI) {
        $dest = $assocOut[$aI['id']];
        
        $targetsIds = $dest['targets'];
        $targets = array();
        foreach($targetsIds as $sceneTarg) {
            $targets[] = $sceneArray[$sceneTarg];
        }
        
        $sceneTo = $dest['scenes'][0];
        
        foreach($aI['scenes'] as $sceneFrom) {
            $transition = new Transition($aI['id'], $sceneTo, $targets, null);
            
            // Creation des evenements pour chaque sprites de la scene
            foreach($aI['sprites'] as $sprite) {
                $split = explode('.', $sprite);
                if(count($split) != 2) {
                    $err[] = "La regle id=".$aI['id']." de la transition id=".$trans['id']." est malformée";
                }
               
                $eventType = $split[1];
                $elemTrigger = $split[0];
                
                $event = new Event($eventType, $elemTrigger);
                $event->setScenePost($sceneTo);
                
                // Ajout de l'evenement à la transition
                $transition->addEvent($event);
            }
            
            // Attachenement de la transition à la scene
            $sceneArray[$sceneFrom]->bindTransition($transition);
        }
    }
}
// ==============================================================

// Groupement des scenes dans des pages
$pages = array();
foreach($sceneArray as $s) {
    if(!isset($pages[$s->getContainer()]) || !is_array($pages[$s->getContainer()])) {
        $pages[$s->getContainer()] = array();
    }
    array_push($pages[$s->getContainer()], $s->getId());
}
?>