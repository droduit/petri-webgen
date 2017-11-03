<?php 
// Contains errors generated during the parsing
$err = array();

// Scenes =======================================================
$sceneArray = array(); 
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
       
        $value = isset($sprite['value']) ? $sprite['value'] : NULL;
        $childsId = (count($sprite['childs']) == 0) ? null : $sprite['childs'];
  
        $newSprite = new Sprite($sprite['nature'], $sprite['id'], $value, $props, $childsId);
        
        // Ajout du sprite à la scene ou dans son sprite parent
        $scene->addSprite($newSprite);
    }
    $scene->traverseTree();
    
    // ----------------------------------------
    
    // Ajout de la scene
    $sceneArray[$s['id']] = $scene;
}
// ==============================================================


// Transitions ==================================================
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
// ==============================================================
?>