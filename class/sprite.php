<?php
class Sprite {
	private $type;
	private $id;
	private $name;
	private $value;
	private $events;	
	private $props;
	private $childs;
		
	function __construct($type, $id, $value, $props) {
		$this->type = $type;
		$this->id = $id;
		$this->name = getSpriteName($id); //uniqid($sprite['nature']."_".$sprite['id']);;
		$this->props = $props;
		$this->value = $value;
		
		// By default, empty event queue
		$this->events = array();
		$this->childs = array();
	}
	
	function getId() { return $this->id; }
	function getName() { return $this->name; }
	function getProps() { return $this->props; }
	function attachEvent($e) { $this->events[] = $e; }
	
	function addChild($sprite) {
		$this->childs[$sprite->getName()] = $sprite;
	}
	function getChild($name) {
		if(isset($this->childs[$name])) {
			return $this->childs[$name];
		} else {
			// Chercher dans les enfants de manière récursive
			//while(
		}
	}
	
	function getHTML() {
		$html = "";
		
		
		$elmType = $this->type;
		if($elmType == "video") {
			if(strpos($this->props['attr']['src'], '://') != false) {
				$elmType = "iframe";
				$this->props['attr']['src'] = str_replace('watch?v=', '/embed/', $this->props['attr']['src']);
				$this->props['attr']['frameborder'] = "0";
				$this->props['attr']['allowfullscreen'] = "true";
			}
		}
		
		$closingBalise = !in_array($elmType, array('img', 'input'));
		
		$html .= '<'.$elmType.' '
					.$this->getHTMLAttributes().' '
					.$this->getHTMLEvents()
					.' class="'.$this->name.'"'
				. ($closingBalise ? '>'.$this->value.$this->getChildsHTML().'</'.$elmType.'>' :
					(empty($this->value) ? ' />' : ' value="'.$this->value.'" />'));
		return $html;
	}
	
	
	function getHTMLAttributes() {
		$htmlAttrs = "";
		if(!isset($this->props['attr'])) return "";
		
		if(count($this->props['attr']) > 0) {
			foreach($this->props['attr'] as $name => $val) {
				$htmlAttrs .= $name."=\"".$val."\" ";
			}
		}
		
		return $htmlAttrs;
	}
	
	function getChildsHTML() {
		$html = "";
		foreach($this->childs as $child) {
			$html .= $child->getHTML();
		}
		return $html;
	}
	
	function getHTMLEvents() {
		$htmlEvents = "";
		foreach($this->events as $e) {
			$htmlEvents .= "on".$e->getEventType().'="document.location.href=\''.$e->getScenePost().'.html\'" ';
		}
		// $('video').on('ended', function(){ ... });
		// play, pause, ended
		return $htmlEvents;
	}
	
	
	
}
?>