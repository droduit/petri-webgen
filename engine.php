<?php 
// Contains errors generated during the processing
$err = array();

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

// Transitions
foreach($petri['transitions'] as $trans) {
    $assocOut = array();
    foreach($trans['associationOut']['regles'] as $aO) {
        $assocOut[$aO['id']] = $aO['scenes'];
    }
    
    foreach($trans['associationIn']['regles'] as $aI) {
        $sceneTo = $assocOut[$aI['id']][0];
        
        foreach($aI['scenes'] as $sceneFrom) {
            $transition = new Transition($aI['id'], $sceneTo, null);
            
            foreach($aI['sprites'] as $sprite) {
                $split = explode('.', $sprite);
                if(count($split) != 2) {
                    $err[] = "La regle id=".$aI['id']." de la transition id=".$trans['id']." est malformée";
                }
                $eventType = $split[1];
                $elemTrigger = $split[0];
                $event = new Event($eventType, $elemTrigger);
                $event->setScenePost($sceneTo);
                $transition->addEvent($event);
            }
            
            $sceneArray[$sceneFrom]->bindTransition($transition);
        }
    }
}
// ------------------------------------------------------
?>