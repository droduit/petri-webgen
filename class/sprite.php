<?php
class Sprite {
	private $type;
	private $id;
	private $name;
	private $value;
	private $events;	
	private $props;
	private $childsId;
	private $allChildsIds;
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
	
	function setId($id) { $this->id = $id; $this->name = getSpriteName($id); }
	function getId() { return $this->id; }
	function getName() { return $this->name; }
	function getProps() { return $this->props; }
	function addProps($props) { $this->props = array_merge($this->props, $props); }
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
	
	function setAllChildsIds($ids) {
	    $this->allChildsIds = $ids;
	}
	
	function getAllChildsIds() {
	    return $this->allChildsIds;
	}
	
	function getAllChildsInOrder() {
	    $childsIds = $this->getAllChildsIds();
	    if($childsIds == null) return null;  
	    $allChilds = array();
	    foreach($childsIds as $c) {
	        array_push($allChilds, $this->getSceneParent()->getSprite($c));
	    }
	    return $allChilds;
	}
	
	function getHTML() {
		$html = "";
		
		$toRender = $this->getAllChildsInOrder();
		
		if($toRender == null) 
		    $toRender = array($this);
		
		$lastChilds = array();
		$onlySpritesWithChilds = array_filter($toRender, function($sprite){ return $sprite->getChildsIds() != null; });
		foreach($onlySpritesWithChilds as $sprite) {
		    $lastChildId = array_pop($sprite->getAllChildsIds());
		    
		    if(isset($lastChilds[$lastChildId])) {
		        array_push($lastChilds[$lastChildId], $sprite->getId());
		    } else {
		      $lastChilds[$lastChildId] = array($sprite->getId());
		    }
		}
		
		foreach($lastChilds as $k => $v) {
		    $lastChilds[$k] = array_reverse($v);
		}


		$closeBuffer = array();
		foreach($toRender as $sprite) {
		    $currentId = $sprite->getId();
		    
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
    		
    		$html .= '<'.$elmType.' '
    		            .$sprite->getHTMLAttributes().' '
    		            .$sprite->getHTMLEvents();
    		
    		if(!array_key_exists('class', $sprite->props["attr"])) {
    		    $html.= ' class="'.$sprite->name.'"';
    		} else {
    		    $html.= ' class="'.$sprite->name." ".$sprite->props["attr"]['class'].'"';
    		}
    		 
			if($closingBalise) {
			    $html .= '>'.$sprite->value;
			    
			    $closeBalise = '</'.$elmType.'>';
			    if($sprite->getChildsIds() == null) {
			        $html .= $closeBalise;
			    } else {
			        $closeBuffer[$currentId] = $closeBalise;
			    }
			} else {
			    $html .= empty($sprite->value) ? ' />' : ' value="'.$sprite->value.'" />';
			}
			
			
			if(array_key_exists($currentId, $lastChilds)) {
			    foreach($lastChilds[$currentId] as $idParentToClose) {
			        $html .= $closeBuffer[$idParentToClose];
			    }
			}
			
		}
		
		return $html;
	}
	

	
	
	function getHTMLAttributes() {
		$htmlAttrs = "";
		if(!isset($this->props['attr'])) return "";
		
		if(count($this->props['attr']) > 0) {
			foreach($this->props['attr'] as $name => $val) {
				if($name != "class")
			         $htmlAttrs .= $name."=\"".$val."\" ";
			}
		}
		
		if($this->type == "video" && !isset($this->props['attr']['controls'])) {
			$htmlAttrs .= " controls ";
		}
		
		return $htmlAttrs;
	}
	

	function getHTMLEvents() {
		$htmlEvents = "";
		foreach($this->events as $e) {
			switch($e->getEventType()) {
				case "endDuration": break;
				default:
					$htmlEvents .= "on".$e->getEventType().'="document.location.href=\'scene_'.$e->getScenePost().'.html\'" ';
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
	
	// Ne devrait pas etre utilisé ! Utiliser le champ $this->allChilds a la place
	function computeAllChildsIds() {
	    if($this->getChildsIds() == null) { return null; }
	    
	    $tmpChilds = $this->getChilds();
	    $allChilds = array($this->getId());
	    
	    while(count($tmpChilds) > 0) {
	        $child = array_shift($tmpChilds);
	        array_push($allChilds, $child->getId());
	        //debug($child->getId());
	        
	        $childs = array_reverse($child->getChilds());
	        foreach($childs as $c) {
	            array_unshift($tmpChilds, $c);
	        }
	    }
	    return $allChilds;
	}
	
}
?>