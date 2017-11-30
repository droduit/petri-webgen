<?php
/**
 * Représente une page html regroupant plusieurs frames.
 * 
 * @author Dominique Roduit
 */
class View {
    /** String: Identifiant unique de la vue */
    private $id;
    
    /** String: Titre de la vue, contenu de la balise <title> */
    private $title;
    
    /** Array<FrameId => Array<Scene, Position>>: Frames contenues dans la vue  */
	private $frames;
	
	/**
	 * Construit une nouvelle vue
	 * @param scene:Array<any> Objet brut complet tel que recu par le fichier json
	 */
	function __construct($id, $title) {
		$this->id = $id;
		$this->title = $title;
		$this->scenes = array();
	}
	
	/**
	 * @return Identifiant unique de la vue
	 */
	function getId() { return $this->id; }
	/**
	 * @return Titre de la vue, affiché dans la balise <title> de la page générée
	 */
	function getTitle() { return $this->title; }
	/**
	 * @return Un tableau des frames contenues dans la vue, du type Array<FrameId => Array<Scene, Position>> 
	 */
	function getFrames() { return $this->frames; }
	
	/**
	 * Ajoute une scène à la vue
	 * @param (Scene) $scene : Scene à ajouter
	 */
	function addScene($frameId, $scene, $position) {
	    $this->frames[$frameId] = array($scene, $position);
	}
	
	/**
	 * @return Les identifiants uniques des frames contenues dans la vue
	 */
	function getFramesIds() {
	    return array_keys($this->frames);
	}
	
	/**
	 * @return Les identifiants uniques des scènes contenus dans les frames de la vue
	 */
	function getScenesIds() {
	    $ids = array();
	    foreach($this->frames as $f) {
	        array_push($ids, $f[0]->getId());
	    }
	    return $ids;
	}
	
	/**
	 * @return Le code HTML de toutes les frames contenues dans cette vue
	 */
	function getFramesHTML() {
	    $content = "";
	    
    	foreach($this->getFrames() as $frameId => $sceneArray) {
    	    $scene = $sceneArray[0];
    	    $position = $sceneArray[1];
    	    
    	    $content .= $this->getFrameHTML($frameId, $scene, $position);
    	}
    	return $content;
	}
	
	/**
	 * @return Le code HTML d'une frame particulière
	 */
	private function getFrameHTML($frameId, $scene, $position) {
	    $positionCss = '';

	    $keys = array_keys($position);
	    
	    if(!in_array('width', $keys)) {
	        $position['width'] = 0;
	    }
	    
	    if(in_array('x', $keys) || in_array('y', $keys) ||
	        in_array('bottom', $keys) || in_array('right', $keys) ||
	        in_array('top', $keys) || in_array('left', $keys)){
	            
	            if(!array_key_exists('position', $position)) {
	                $position['position'] = "absolute";
	            }
	            if(array_key_exists('x', $position)) {
	                $position['left'] = addPx($position['x']);
	                unset($position['x']);
	            }
	            if(array_key_exists('y', $position)) {
	                $position['top'] = addPx($position['y']);
	                unset($position['y']);
	            }
	    }
	     
	    foreach($position as $k => $v) {
	        if($k == "width" || $k == "height") {
	            if($v == 0) $v = "100%";
	            else $v = addPx($v);
	        }    
	        $positionCss .= $k.":".$v.";";
	    }
	    
	    return
   	     '<iframe petri '.
   	     'id="'.$frameId.'" '.
         'frameborder="0" '.
         'style="z-index:0; '.$positionCss.'" '.
         'src="'.getSceneFilename($scene->getId()).'">'.
         '</iframe>';
	}
	
}
?>