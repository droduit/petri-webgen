<?php 
// Contains errors generated during the parsing
$err = array();

// Scenes =======================================================
$sceneArray = array(); 

// On parcours chaque scenes
foreach($petri['scenes'] as $s) {
    $scene = new Scene($s);
    
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
                $newSprite->addAnimations($sprite['animations']);
                
                $scene->addSprite($newSprite);
            }
        } else {
            $value = isset($sprite['value']) ? $sprite['value'] : NULL;
            $childsId = (count($sprite['childs']) == 0) ? null : $sprite['childs'];
      
            $newSprite = new Sprite($sprite['nature'], $sprite['id'], $value, $props, $childsId);
            $newSprite->addAnimations($sprite['animations']);
            
            // Ajout du sprite à la scene
            $scene->addSprite($newSprite);
        }
        
    }
    
    $scene->processSpritesChilds();
    // ----------------------------------------
    
    // Ajout de la scene
    $sceneArray[$s['id']] = $scene;
}
// ==============================================================


// Groupement des scenes dans des pages =========================
$views = array();
// Pour chaque vue, on utilise le tableau avec les scene ayant le flag isInView=true
foreach($petri['views'] as $view) {
    $viewObj = new View($view['id'], $view['title']);
    
    foreach($view['scenes'] as $frame) {
        $viewObj->addScene($frame['frame-id'], $sceneArray[$frame['scene-id']], $frame['style']);
    }
    array_push($views, $viewObj);
}
// ==============================================================


// Transitions ==================================================
foreach($petri['transitions'] as $trans) {
    
    $assocOut = array();
    foreach($trans['associationOut']['regles'] as $aO) {
        $dests = array();
        foreach($aO['dest'] as $dest) {
            array_push($dests, new Dest($dest['type'], $dest['id'], $dest['targets']));
        }
        $assocOut[$aO['id']] = $dests;
    }
    
    foreach($trans['associationIn']['regles'] as $aI) {
        $dests = $assocOut[$aI['id']];
        
        
        foreach($aI['scenes'] as $sceneFrom) {
            $transition = new Transition($aI['id'], null);
            
            // Creation des evenements pour chaque sprites de la scene
            foreach($aI['sprites'] as $sprite) {
                $split = explode('.', $sprite);
                if(count($split) != 2) {
                    $err[] = "La regle id=".$aI['id']." de la transition id=".$trans['id']." est malformée. les sprites doivent avoir la forme spriteId.eventName";
                }
               
                $eventType = $split[1];
                $elemTrigger = $split[0];
                
                $event = new Event($eventType, $elemTrigger, $dests);
                
                // Ajout de l'evenement à la transition
                $transition->addEvent($event);
            }
            
            // Attachenement de la transition à la scene
            $sceneArray[$sceneFrom]->bindTransition($transition);
        }
    }
}
// ==============================================================

// On définit la page index =====================================
$index = $petri['index'];
// ==============================================================

// Récupération des dépendences personnalisées de l'utilisateur==
$dependencies = $petri['include'];
// ==============================================================
?>