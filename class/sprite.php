<?php
/**
 * Représente un élément HTML contenu dans une Scene
 * @author Dominique Roduit
 */
class Sprite {
    /** String: Type HTML du Sprite */
	private $type;
	/** String: Identifiant unique de l'élément HTML dans la page */
	private $id;
	/** @deprecated */
	private $name;
	/** String: Valeur à l'intérieur de l'élément HTML, pourrait être NULL */
	private $value;
	/** Array<Event>: évenements attachés à l'élément HTML */
	private $events;	
	/** Array<String>: attributs de l'élément HTML */
	private $props;
	/** Array<String>: Identifiants des sprites enfants DIRECTES contenus dans cet élément */ 
	private $childsId;
	/** Array<String>: Identifiants de toute la généalogie des sprites enfants contenus dans cet élément */
	private $allChildsIds;
	/** Scene: Scene contenant ce sprite */
	private $sceneParent;
	
	/**
	 * Construit un nouveau Sprite.
	 * @param (String) $type : Type HTML du Sprite
	 * @param (String) $id : Identifiant unique du Sprite
	 * @param (String) $value : Valeur contenue dans l'élément HTML représentant ce sprite
	 * @param (Array<String>) $props : Attributs HTML de cet élément
	 * @param (Array<String>) $childsId : Liste des identifiants des sprites enfants contenus par ce sprite
	 */
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
	}
	
	function setId($id) { $this->id = $id; $this->name = getSpriteName($id); }
	function getId() { return $this->id; }
	
	function getName() { return $this->name; }
	
	function getProps() { return $this->props; }
	function addProps($props) { $this->props = array_merge($this->props, $props); }
	
	/**
	 * Attache un évènement à l'élément HTML représentant le sprite.
	 * Les évènements sont générés en JS.
	 * @param (Event) $e : Evenement à attacher
	 */
	function attachEvent($e) {
	    $this->events[] = $e;
	}
	
	function setSceneParent($scene) {
	    $this->sceneParent = $scene;
	}
	function getSceneParent() {
	    return $this->sceneParent;
	}
	
	function getChildsIds() { return $this->childsId; }
	
	function getChilds() {
	    $childs = array();
	    foreach($this->getChildsIds() as $childId) {
	        $childs[] = $this->getSceneParent()->getSprite($childId);
	    }
	    return $childs;
	}
	
	/**
	 * Défini les identifiants de tous les sprites enfants contenus par ce sprite
	 * @params (Array<String>) $ids : Liste des identifiants 
	 */
	function setAllChildsIds($ids) {
	    $this->allChildsIds = $ids;
	}
	
	/**
	 * @return Les identifiants uniques de tous les sprites enfants contenus par ce sprite
	 */
	function getAllChildsIds() {
	    return $this->allChildsIds;
	}
	
	/**
	 * @return Tous les objets Sprite enfants contenu par ce
	 * sprite dans leur ordre hiérarchique donné par tree traversal
	 */
	function getAllChildsInOrder() {
	    $childsIds = $this->getAllChildsIds();
	    if($childsIds == null) return null;  
	    $allChilds = array();
	    foreach($childsIds as $c) {
	        array_push($allChilds, $this->getSceneParent()->getSprite($c));
	    }
	    return $allChilds;
	}
	
	/**
	 * Génère le code HTML représentant graphiquement ce sprite
	 * @return Le code HTML représentant ce sprite
	 */
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
    		            .$sprite->getHTMLAttributes().' ';
    		
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
	
	/**
	 * Génère une chaine formatée pour représenter
	 * les attributs de ce sprite dans l'élément HTML
	 * le représentant graphiquement.
	 * 
	 * @return Une chaine de caractère contenant tous les attributs HTML de l'élément. 
	 */
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
	
	/**
	 * Génère un chaine contenant le code Javascript des évènements attachés à cet élément HTML
	 * @return La chaine contenant le code Javascript qui gère les évènements de cet élément. 
	 */
	function getJSEvents() { 
		$jsEvents = "";
		
		// La variable javascript isInView indique si la scene contenant ce sprite
		// se trouve dans une vue ou pas.
		
		foreach($this->events as $e) {
		    $selector = ".".$this->getName();
		    $destSrc = $e->getDestFilename();
		    $actionName = $e->getEventType();
		    $targetCode = "document.location.href";
		    $code = "";
		    
		    // Si on charge dans une nouvelle page
		    if(count($e->getTargets()) == 0) {
		        $code = 'if(isInView) {'. // la scene est dans une vue et on veut atteindre une nouvelle page
                            'parent.'.$targetCode."='".$destSrc."';".  
                        '} else {'.
                            $targetCode."='".$destSrc."';".
		                '}';
		    }
		    // Si on charge dans une ou plusieurs iframe(s) de la meme page
		    else {
		        $code .= "if(isInView) { ";
    		    foreach($e->getTargets() as $target) {
    		        $code .=
    		        // Si on charge dans la frame dans laquelle on est
        		    'if($("iframe[petri][src*=\''.$this->getSceneParent()->getId().'\']", parent.document).attr("id") == "'.$target.'") {'.
		                  $targetCode."='".$destSrc."';".
		            // Si on charge dans une autre frame
		            '} else {'.
		                  "$('iframe[petri][id=".$target."]', parent.document)".
		                  ".attr('src', '".$destSrc."'); ".
		            '}';
    		    }
    		    $code .= "} else {";
    		    $code .=      $targetCode."='".$destSrc."';";
    		    $code .= "}";
            }
            
            // $('video').on('ended', function(){ ... });
            // play, pause, ended
            // Seulement de l'interprétation de noms
            switch($e->getEventType()) {
                case "endDuration": $actionName = "ended"; break;
                default: break;
            }
            
            $jsEvents .= "$('".$selector."').on('".$actionName."', function(){";
            $jsEvents .=     $code;
            $jsEvents .= "}); ";
		}
		
		
		return $jsEvents;
	}
	
	
	/**
	 * Ne devrait pas etre utilisé ! Utiliser le champ $this->allChilds a la place
	 * Trouve les identifiants de tous les sprites enfants de ce sprite en traversant
	 * l'arbre des éléments.
	 * @return Les identifiants uniques de tous les sprites enfants, dans leur ordre hiérarchique, 
	 *         c'est à dire l'ordre dans lesquels ils doivent être affichés.
	 */ 
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