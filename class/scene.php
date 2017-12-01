<?php
/**
 * Représente une scene d'un conteneur (page).
 * La scene est une page HTML contenue dans une iframe
 * si le conteneur contient plusieurs scenes, ou une page HTML
 * indépendante si le conteneur ne contient que cette scene.
 * 
 * @author Dominique Roduit
 */
class Scene {
    /** Array<any>: Objet brut complet tel que recu par le fichier json */
	private $sceneArray;
	/** String: Identifiant unique de la scene dans la page */
	private $id;
	/** String: Titre de la page, contenu de la balise <title> */
	private $title;
	/** Array<Sprite>: Liste des sprites contenus dans la scene */
	private $sprites;
	/** Array<String>: Identifiants des sprites contenus dans la scene */
	private $childsIds;

	
	/**
	 * Construit une nouvelle scene
	 * @param scene:Array<any> Objet brut complet tel que recu par le fichier json
	 */
	function __construct($scene=array()) {
		$this->sceneArray = $scene;
		
		$this->id = $this->sceneArray['id'];	
		$this->title = isset($scene['title']) ? $scene['title'] : $this->id;
		$this->sprites = array();
		
		$this->childsIds = array();
	}
	
	/**
	 * Ajout d'un Sprite à la scène 
	 * @param sprite Sprite à ajouter dans la scène
	 */
	function addSprite($sprite) {
	    $sprite->setSceneParent($this);
	    $this->sprites[$sprite->getId()] = $sprite;

	    $childsIds = $sprite->getChildsIds();
	    foreach($childsIds as $ci) {
	       array_push($this->childsIds, $ci);
	    }
	}
	
	/**
	 * @param spriteId Identifiant unique du sprite que l'on veut récupérer
	 * @return Le sprite dont l'id est spriteId, s'il est contenu dans la scene. Sinon, null.
	 */
	function getSprite($spriteId) {
		return (isset($this->sprites[$spriteId])) ? 
		  $this->sprites[$spriteId] : null;
	}
	
	/**
	 * Délégation des évenements de la transition aux sprites de la scène
	 */
	function bindTransition($transition) {
		foreach($transition->getEvents() as $e) {
			if($this->sprites[(string)$e->getElemTrigger()] != null) {
				$this->sprites[(string)$e->getElemTrigger()]->attachEvent($e);
			}
		}
	}
	
	/**
	 * Créé le style css de la scene et de tous ses sprites.
	 * Regroupe 3 type de styles : 
	 *  - Le style appliqué à la scene. C'est à dire au <body> complet
	 *  - Les class personnalisées de l'utilisateur qu'il peut attribuer aux sprites
	 *  - Le style propre à chaque sprite
	 *  
	 *  @return Tout le CSS de la scene et de ses sprites dans une balise <style>...</style>
	 */
	function getCSS() {
		global $CSS_STYLES;
		$htmlProp = "<style>";
		
		// Style of the scene
		if(isset($this->sceneArray['style'])) {
			$htmlProp .= "body {";
			foreach($this->sceneArray['style'] as $k => $v) {
				$htmlProp .= $k .":". $v."; ";
			}
			$htmlProp .= "} ";
		}
		
		// Custom class
		if(isset($this->sceneArray['css'])) {
			foreach($this->sceneArray['css'] as $class => $values) {
				$htmlProp .= $class." {";
				foreach($values as $k => $v) {
					$htmlProp .= $k .":". $v."; ";
				}
				$htmlProp .= "} ";
			}
		}
		
		// Styles of sprites
		foreach($this->sprites as $sprite) {
			$props = $sprite->getProps();
			
			if(isset($props['style']) && count($props['style']) == 0)
				return $htmlProp=="" ? "" : $htmlProp."</style>";
			
			foreach($CSS_STYLES as $kindCss) {
				if(isset($props[$kindCss]) && count($props[$kindCss]) > 0) {
					$kind = ($kindCss != "style") ? ":".$kindCss : "";
					$htmlProp.= ".".$sprite->getName().$kind." {";
					foreach($props[$kindCss] as $k => $v) {
						$htmlProp .= $k.":".$v."; ";
					}
					$htmlProp .= "}";
				}
			}
		}
		
		$htmlProp .= "</style>";
		
		return $htmlProp;
	}
	
	/**
	 * @return Tout le code javascript/Jquery des sprites et personnalisé de l'utilisateur,
	 *         contenu dans une balise <script>...</script>
	 */
	function getJS() {
		$scripts = "";
		
		// Custom JS entré par l'utilisateur
		foreach($this->sceneArray['js'] as $js) {
		    $scripts .= $js;
		    if(substr($js, -1) != ";") $scripts .= ";";
		}
	   
		// JS des sprites de la scene
		foreach($this->sprites as $sprite) {
			$scripts .= $sprite->getJSEvents();
			$scripts .= $sprite->getJSAnimations();
		}
		
		if(empty($scripts)) return "";
		return "<script>$(function(){ ".$scripts." });</script>";
	}
	
	/**
	 * @return Tout le code HTML des sprites de la scene
	 */
	function getHTMLContent() {
		$html = "";
		$spritesToRender = array_filter($this->sprites, function($s){
		    return !in_array($s->getId(), $this->childsIds);
		});
		
		if(count($spritesToRender) > 0) {
		    foreach($spritesToRender as $sprite) {
				$html .= $sprite->getHTML();
			}
		}
		return $html;
	}
	
	/**
	 * @return (Array<String>) Identifiants uniques des sprites contenus dans cette scene
	 */
	function getSpritesIds() { return $this->childsIds; }
	/**
	 * @return Le tableau d'objets brut de tous les éléments de cette scene tel que recu par le JSON
	 */
	function getArray() { return $this->sceneArray; }
	/**
	 * @return Tous les sprites de la scene
	 */
	function getSprites() { return $this->sprites; }
	/**
	 * @return Identifiant unique de la scene
	 */
	function getId() { return $this->id; }
	/**
	 * @return Titre de la scene
	 */
	function getTitle() { return $this->title; }
	/**
	 * Affiche une emprunte de cet objet scene de manière formatée dans un but de debuggage
	 */
	function debug() { echo "SCENE : ".$this->id.' <hr><pre>'; print_r($this); echo '</pre><hr><br>'; }
    /**
     * Attribue à chaque sprite de la scene leurs enfants
     * respectifs en appelant leur fonction de calcul des enfants 
     */
	function processSpritesChilds() {
	    foreach($this->sprites as $sprite) {
	        $sprite->setAllChildsIds($sprite->computeAllChildsIds());
	    }
	}
}
?>