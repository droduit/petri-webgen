<?php
class Scene {
	private $sceneArray;
	
	private $id;
	private $title;
	private $sprites;
	

	function __construct($scene=array()) {
		$this->sceneArray = $scene;
		
		$this->id = $this->sceneArray['id'];	
		$this->title = isset($scene['title']) ? $scene['title'] : $this->id;
		$sprites = array();
	}
	
	function addToken($sprite) {
		$this->sprites[$sprite->getName()] = $sprite;
	}
	
	function bindTransition($transition) {
		foreach($transition->getEvents() as $e) {
			if($this->sprites[(string)$e->getTrigger()] != null) {
				$this->sprites[(string)$e->getTrigger()]->attachEvent($e);
			}
		}
	}
	
	function getCSS() {
		global $CSS_STYLES;
		$htmlProp = "<style>";
		
		// Style of the scene
		if(in_array('style', $this->sceneArray)) {
			$htmlProp .= "body {";
			foreach($this->sceneArray['style'] as $k => $v) {
				$htmlProp .= $k .":". $v."; ";
			}
			$htmlProp .= "}";
		}
		
		// Styles of sprites
		foreach($this->sprites as $sprite) {
			$props = $sprite->getProps();
			if(count($props['style']) == 0) return "";
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
	
	function getArray() { return $this->sceneArray; }
	function getSprites() { return $this->sprites; }
	function getId() { return $this->id; }
	function getTitle() { return $this->title; }
	function debug() { echo '<pre>'; print_r($this->sceneArray); echo '</pre>'; }

}
?>