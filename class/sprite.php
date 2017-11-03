<?php
class Sprite {
	private $type;
	private $id;
	private $name;
	private $value;
	private $events;	
	private $props;
	private $childsId;
	private $sceneParent;
	private $jsAttached;
		
	function __construct($type, $id, $value, $props, $childsId) {
	    $this->sceneParent = null;
	    
		$this->type = $type;
		$this->id = $id;
		$this->name = getSpriteName($id);
		$this->props = $props;
		$this->value = $value;
		$this->childsId = $childsId;
		
		// By default, empty event queue
		$this->events = array();
		
		$this->jsAttached = "";
	}
	
	function getId() { return $this->id; }
	function getName() { return $this->name; }
	function getProps() { return $this->props; }
	function attachEvent($e) { $this->events[] = $e; $this->generateJS(); }
	function getJS() { return $this->jsAttached; }
	function getChildsIds() { return $this->childsId; }
	
	function setSceneParent($scene) {
	    $this->sceneParent = $scene;
	}
	function getSceneParent() {
	    return $this->sceneParent;
	}
	
	function getChilds() {
	    $childs = array();
	    foreach($this->getChildsIds() as $childId) {
	        $childs[] = $this->getSceneParent()->getSprite($childId);
	    }
	    return $childs;
	}
	
	function getAllChildsInOrder() {
	    if($this->getChildsIds() == null) { return null; }
	    
	    $tmpChilds = $this->getChilds();
	    $allChilds = array($this);
	    while(count($tmpChilds) > 0) {
	        $child = array_shift($tmpChilds);
	        array_push($allChilds, $child);
	        //debug($child->getId());
	        
	        $childs = array_reverse($child->getChilds());
	        for($i = 0; $i < count($childs); $i++) {
	            array_unshift($tmpChilds, $childs[$i]);
	        }
	    }
	    return $allChilds;
	}
	
	function getHTML() {
	    // [0] = balises ouvrantes, [1] = balises fermantes
		$html = array("","");
		
		$closeBuffer = "";
		
		$toRender = $this->getAllChildsInOrder();
		if($toRender == null) 
		    $toRender = array($this);
		
		
		foreach($toRender as $sprite) {
		    debug("oOO : ".$sprite->getId());
    		$elmType = $sprite->type;
    		
    		if($elmType == "video") {
    		    if(strpos($sprite->props['attr']['src'], '://') != false) {
    				$elmType = "iframe";
    				$sprite->props['attr']['src'] = str_replace('watch?v=', '/embed/', $sprite->props['attr']['src']);
    				$sprite->props['attr']['frameborder'] = "0";
    				$sprite->props['attr']['allowfullscreen'] = "true";
    			}
    		}
    		
    		$closingBalise = !in_array($elmType, array('img', 'input'));
    		
    		$html[0] .= '<'.$elmType.' '
    		            .$sprite->getHTMLAttributes().' '
    		            .$sprite->getHTMLEvents()
    					.' class="'.$sprite->name.'"';
    		
    	   if($closingBalise) {
    	       $html[0] .= '>'.$sprite->value;
    	       
    	       if(count($sprite->childIds) == 0) {
    	           $html[0] .= '</'.$elmType.'>';
    	       }
    	   } else {
    	       $html[0] .= empty($sprite->value) ? ' />' : ' value="'.$sprite->value.'" />';
    	   }				
    	    
		}
		
		return $html[0].$html[1];
	}
	
	
	function getHTMLAttributes() {
		$htmlAttrs = "";
		if(!isset($this->props['attr'])) return "";
		
		if(count($this->props['attr']) > 0) {
			foreach($this->props['attr'] as $name => $val) {
				$htmlAttrs .= $name."=\"".$val."\" ";
			}
		}
		
		if($this->type == "video" && !isset($this->props['attr']['controls'])) {
			$htmlAttrs .= " controls ";
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
			switch($e->getEventType()) {
				case "endDuration": break;
				default:
					$htmlEvents .= "on".$e->getEventType().'="document.location.href=\''.$e->getScenePost().'.html\'" ';
				break;
			}
		}
		// $('video').on('ended', function(){ ... });
		// play, pause, ended
		return $htmlEvents;
	}
	
	function generateJS() {
		foreach($this->events as $e) {
			switch($e->getEventType()) {
				case "endDuration":
					$this->jsAttached .= "$('.".$this->name."').on('ended', function(){ document.location.href='".$e->getScenePost().".html'; });";
				break;
			}
		}
	}
	
}
?>