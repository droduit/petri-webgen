<?php
/**
 * Représente un élément HTML contenu dans une Scene
 * 
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
	/** Array<[type, duration, delay, effect]> : Animations sur le sprite */
	private $animations;
	
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
		$this->childsId = is_null($childsId) ? array() : $childsId;
		
		// By default, empty event queue
		$this->events = array();
		$this->animations = array();
	}
	
	/**
	 * Défini l'identifiant du sprite
	 * @param (String) $id : Identifiant unique du sprite dans la page
	 */
	function setId($id) {
	    $this->id = $id;
	    $this->name = getSpriteName($id);
	}
	/**
	 * @return (String) L'identifiant du sprite dans la scène
	 */
	function getId() { return $this->id; }
	
	/**
	 * @deprecated
	 * @return Le nom du sprite dans la scène
	 */
	function getName() { return $this->name; }
	
	/**
	 * @return Les attributs HTML associés au sprite
	 */
	function getProps() { return $this->props; }
	
	/**
	 * Ajoute un ou plusieurs attribut HTML aux attributs déjà existants du sprite
	 * @param (Array<String>) $props : Le ou les attributs à ajouter, dans un tableau
	 */
	function addProps($props) {
	    if ($props != NULL)
	       $this->props = array_merge($this->props, $props);
	}
	
	/**
	 * Ajoute des animations aux animations deja existantes du sprite
	 * @param Array<[type, duration, delay, effect]> $animations : nouvelles animations
	 */
	function addAnimations($animations) {
	    if (!is_null($animations))
	       $this->animations = array_merge($this->animations, $animations);
	}
	
	function getAnimations() { return $this->animations; }
	
	/**
	 * Attache un évènement à l'élément HTML représentant le sprite.
	 * Les évènements sont générés en JS.
	 * @param (Event) $e : Evenement à attacher
	 */
	function attachEvent($e) {
	    $this->events[] = $e;
	}
	
	/**
	 * Défini la scène parent contenant ce sprite 
	 * @param (Scene) $scene La scène contenant le sprite
	 */
	function setSceneParent($scene) {
	    $this->sceneParent = $scene;
	}
	
	/**
	 * @return La scène parent contenant le script
	 */
	function getSceneParent() {
	    return $this->sceneParent;
	}
	
	/**
	 * @return Les identifiants des sprites contenus dans ce sprite
	 */
	function getChildsIds() { return $this->childsId; }
	
	/**
	 * @return Les sprites (les objets Sprite) contenus dans ce sprite
	 */
	function getChilds() {
	    $childs = array();
	    foreach ($this->getChildsIds() as $childId) {
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
	 * @return Array Les identifiants uniques de tous les sprites enfants contenus par ce sprite
	 */
	function getAllChildsIds() {
	    return $this->allChildsIds;
	}
	
	/**
	 * @return Array Tous les objets Sprite enfants contenu par ce
	 * sprite dans leur ordre hiérarchique donné par tree traversal
	 */
	function getAllChildsInOrder() {
	    $childsIds = $this->getAllChildsIds();
	    if ($childsIds == null) return null;  
	    $allChilds = array();
	    foreach ($childsIds as $c) {
	        array_push($allChilds, $this->getSceneParent()->getSprite($c));
	    }
	    return $allChilds;
	}
	
	/**
	 * Génère le code HTML représentant graphiquement ce sprite
	 * @return String Le code HTML représentant ce sprite
	 */
	function getHTML() {
		$html = "";
		
		$toRender = $this->getAllChildsInOrder();
		
		if ($toRender == null) {
		    $toRender = array($this);
		}

		$lastChilds = array();
		$onlySpritesWithChilds = array_filter($toRender, function($sprite){
			return $sprite->getChildsIds() != null;
		});
		foreach ($onlySpritesWithChilds as $sprite) {
			$allChildsIds = $sprite->getAllChildsIds();
		    $lastChildId = array_pop($allChildsIds);
		    
		    if (isset($lastChilds[$lastChildId])) {
		        array_push($lastChilds[$lastChildId], $sprite->getId());
		    } else {
		      $lastChilds[$lastChildId] = array($sprite->getId());
		    }
		}
		
		foreach ($lastChilds as $k => $v) {
		    $lastChilds[$k] = array_reverse($v);
		}

		$closeBuffer = array();
		foreach ($toRender as $sprite) {
		    $currentId = $sprite->getId();
		    
    		$elmType = $sprite->type;
    		
    		if ($elmType == "video") {
    		    if (strpos($sprite->props['attr']['src'], '://') != false) {
    				$elmType = "iframe";
    				$sprite->props['attr']['src'] = str_replace('watch?v=', '/embed/', $sprite->props['attr']['src']);
    				$sprite->props['attr']['frameborder'] = "0";
    				$sprite->props['attr']['allowfullscreen'] = "true";
    			}
    		}
    		
    		$closingBalise = !in_array($elmType, array('img', 'input'));
    		
    		$html .= '<'.$elmType.' '
    		            .$sprite->getHTMLAttributes().' ';
    		
            // Si une animation in existe, on masque l'élément au chargement
            foreach ($sprite->animations as $anim) {
                if ($anim['type'] == "in") {
                    $html .= 'style="display:none" ';
                    break;
                }
            }
    		
			if (isset($sprite->props['attr'])) {
				if (!isset($sprite->props["attr"]['class'])) {
					$html.= ' class="'.$sprite->name.'"';
				} else {
					$html.= ' class="'.$sprite->name.' '.$sprite->props["attr"]['class'].'"';
				}
			} else {
				$html.= ' class="'.$sprite->name.'"';
			}
    
			if ($closingBalise) {
			    $html .= '>'.$sprite->value;
			    
			    $closeBalise = '</'.$elmType.'>';
			    if ($sprite->getChildsIds() == null) {
			        $html .= $closeBalise;
			    } else {
			        $closeBuffer[$currentId] = $closeBalise;
			    }
			} else {
			    $html .= empty($sprite->value) ? ' />' : ' value="'.$sprite->value.'" />';
			}
			
			
			if (array_key_exists($currentId, $lastChilds)) {
			    foreach ($lastChilds[$currentId] as $idParentToClose) {
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
		if (!isset($this->props['attr'])) return "";
		
		if (count($this->props['attr']) > 0) {
		    
		    foreach ($this->props['attr'] as $name => $val) {
				if ($name != "class")
			         $htmlAttrs .= $name."=\"".$val."\" ";
			}
		}
		
		if ($this->type == "video" && !isset($this->props['attr']['controls'])) {
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
		// debug($this->events);
		
		foreach ($this->events as $e) {
		    $code = "";
		    
		    $dests = $e->getDests();
		    
		    $selector = ".".$this->getName();
		    $actionName = $e->getEventType();
		    $targetCode = "document.location.href";
		    $sceneFilename = getSceneFilename($this->getSceneParent()->getId());
		    
		    // Si on charge dans une nouvelle page
		    if ($e->isExternal()) {
		        $destSrc = $dests[0]->getFilename();
		        
		        $code = 'if(isInView) {'. // la scene est dans une vue et on veut atteindre une nouvelle page
		  		            //'alert("1");'.
                            'parent.'.$targetCode."='".$destSrc."';".
                        '} else { '. //'alert("2");'.
                            $targetCode."='".$destSrc."';".
		                '}';
		    }
		    // Si on charge dans une ou plusieurs iframe(s) de la meme page
		    else {
		        // On ne traite que les destinations non js ici
		        if (!(count($dests) == 1 && $dests[0]->getType() == "js")) {
    		        $code .= "if(isInView) {";
        		    foreach ($dests as $dest) {
        		        
        		        if ($dest->getType() != "js") {
            		        $destSrc = $dest->getFilename();
            		        
            		        foreach ($dest->getTargets() as $target) {
                		        $code .=
                		        // Si on charge dans la frame dans laquelle on est
                    		    'if($("iframe[petri][src*=\''.$sceneFilename.'\']", parent.document).attr("id") == "'.$target.'") {'.
                    		          //'alert("3");'.
            		                  $targetCode."='".$destSrc."';".
            		            // Si on charge dans une autre frame
                		        '} else { '. //'alert("4 '.$destSrc.' '.$target.'");'.
            		                  "$('iframe[petri][id=".$target."]', parent.document)".
            		                  ".attr('src', '".$destSrc."'); ".
            		            '}';
            		        }
        		        }
        		    }
        		    $code .= "} else { "; // alert('5');
        		    $code .=      $targetCode."='".$destSrc."';";
        		    $code .= "}";
		        }
		        
    		    // On ne traite que les actions js ici
    		    foreach ($dests as $dest) {
    		        if ($dest->getType() != "js") {
    		            continue;
    		        }
    		        
    		        $jsArray = $dest->getJS();
    		        $targetSprite = $this->getSceneParent()->getSprite($jsArray['target']);
    		        
    		        if ($jsArray['action'] == "load") {
    		            foreach ($jsArray['source'] as $type => $source) {
    		              if ($type == "scene") {
    		                  // on charge une scene en ajax dans $jsArray['target']
    		                  $code .= '$.post("'.getSceneFilename($source).'", {}, function(fullHtml){'.
        		                          'var bodyHtml = /<body.*?>([\s\S]*)<\/body>/.exec(fullHtml)[1];'.
        		                          '$(".'.$targetSprite->getName().'").html(bodyHtml);'.
        		                       '});';
    		              } elseif ($type == "sprite") {
    		                  // on charge un sprite dans $jsArray['target']
    		                  $code .= '$.post("'.getSceneFilename($source).'", {}, function(fullHtml){'.
                		                  'var spriteHtml = /<body.*?>([\s\S]*)<\/body>/.exec(fullHtml)[1];'.
                		                  '$(".'.$targetSprite->getName().'").html(spriteHtml);'.
                		                '});';
    		              }
    		            }
    		            
    		        } elseif ($jsArray['action'] == "animation") {
    		            $code .= $this->getJSAnimations($targetSprite, $jsArray['animations']);
    		        }
    		        
    		    }
            }
            
            // $('video').on('ended', function(){ ... });
            // play, pause, ended
            // Seulement de l'interprétation de noms
            switch ($e->getEventType()) {
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
	 * Génère un chaine contenant le code Javascript des animations attachés à cet élément HTML
	 * @return La chaine contenant le code Javascript qui gère les animations de cet élément. 
	 */
	function getJSAnimations($targetSprite, $animations) {
	    $jsAnim = "";
	    $selector = '$(".'.$targetSprite->getName().'")';
	    
	    // On parcours toutes les animations.
	    foreach ($animations as $anim) {
	        // Si le type de l'animation n'est pas donné, on ne la traite pas.
	        if (isset($anim['type'])) {
	            // Définition des valeurs par defaut si pas renseigné
    	        if (!isset($anim['effect']))
    	            $anim['effect'] = "fade";
    	        if (!isset($anim['duration']))
    	            $anim['duration'] = 500;
    	        
    	        // Ajout d'un delay si existe
    	        if (isset($anim['delay'])) {
    	            $jsAnim .= 'setTimeout(function(){ ';
    	        }
    	        
    	        // Si on veut repeter l'animation
    	        if (isset($anim['interval'])) {
    	            $jsAnim .= 'setInterval(function(){ ';
    	        }
    	        
    	        $nameAnim = 'show';
    	        if ($anim['type'] == "in") $nameAnim = "show";
    	        elseif ($anim['type'] == "out") $nameAnim = "hide";
    	        elseif ($anim['type'] == "toggle") $nameAnim = "toggle";
    	       
    	        
    	        if ($anim['duration'] == 0) {
    	            $jsAnim .= $selector.".css('display', 'none');";
    	        } else {
    	            if ($anim['type'] == "custom") {
    	                $options = array();
    	                foreach ($anim as $k => $v) {
    	                    if (in_array($k, array("duration","type","interval","delay","effect")))
    	                        continue;
    	                    array_push($options, $k.':"'.$v.'"');
    	                }
    	  
    	                $jsAnim .= $selector.".animate({".implode(',', $options)."}, ".$anim['duration'].");";
    	            } else {
                        $jsAnim .= $selector.".".$nameAnim."('".$anim['effect']."', ".$anim['duration'].");";
    	            }
    	        }
    	        
    	        if (isset($anim['interval'])) {
    	            $jsAnim .= "}, ".$anim['interval'].");";
    	        }
                if (isset($anim['delay'])) {
                    $jsAnim .= '}, '.$anim['delay'].");";
                }
	        }
	    }
	    
	    return $jsAnim;
	}
	
	
	/**
	 * Ne devrait pas etre utilisé ! Utiliser le champ $this->allChilds a la place
	 * Trouve les identifiants de tous les sprites enfants de ce sprite en traversant
	 * l'arbre des éléments.
	 * @return Les identifiants uniques de tous les sprites enfants, dans leur ordre hiérarchique, 
	 *         c'est à dire l'ordre dans lesquels ils doivent être affichés.
	 */ 
	function computeAllChildsIds() {
	    if ($this->getChildsIds() == null) {
			return null;
		}
	    
	    $tmpChilds = $this->getChilds();
	    $allChilds = array($this->getId());
	    
	    while (count($tmpChilds) > 0) {
	        $child = array_shift($tmpChilds);
	        array_push($allChilds, $child->getId());
	        //debug($child->getId());
	        
	        $childs = array_reverse($child->getChilds());
	        foreach ($childs as $c) {
	            array_unshift($tmpChilds, $c);
	        }
	    }
	    return $allChilds;
	}
	
}