<?php
/**
 * Objet liant un Sprite à un type d'évenement
 * @author Dominique Roduit
 */
class Event {
    /** String: Type de l'évenement (click, dblclick, ...) */
	private $eventType;
	/** String: Element qui déclenche cet évenement */
	private $elemTrigger;
	/** Array<Dest> : Destinations associées à l'evenement */
	private $dests;
	
	
	/**
	 * Construit un objet Event liant un Sprite à un type d'évenement
	 * @param eventType Type de l'évènement (click, dblclick, ...)
	 * @param elemTrigger objet Sprite auquel l'évènement est lié  
	 */
	function __construct($eventType, $elemTrigger, $dests) {
		$this->eventType = $eventType;
		$this->elemTrigger = $elemTrigger;
		$this->dests = $dests;
	}
	
	/**
	 * @return (String) Type de l'évènement (click, dblclick, ...)
	 */
	function getEventType() { return $this->eventType; }
	/**
	 * @return (String) Identifiant unique du sprite qui va déclencher cet évenement
	 */
	function getElemTrigger() { return $this->elemTrigger; }

	function getDests() { return $this->dests; }
	
	/**
	 * Indique si la destination de l'evenement est une page externe ou si c'est a charger
	 * sur la page local dans une frame différente ou la meme frame.
	 * @return true si la destination est une page externe
	 */
	function isExternal() {
	    if(count($this->dests) == 1) {    
	        $noTarget = is_null($this->dests[0]->getTargets()) || count($this->dests[0]->getTargets()) == 0;
	        if($this->dests[0]->getType() != "js" && $noTarget) {
	            return true;
	        }
	    }
	    return false;
	}
}


class Dest {
    /** String: Type de destination (scene, vue, ou js) appelée par l'évenement */
    private $type;
    /** String: Id de la scene ou de la vue de destination appelée par l'evenement */
    private $id;
    /** Array<String>: Id des frames dans lesquelles charger la scene de destination */
    private $targets;

    private $js;
    
    function __construct($type, $id, $targets, $js) {
        $this->type = $type;
        $this->id = $id;
        $this->targets = $targets;
        $this->js = $js;
    }
    
    /**
     * @return (String) Identifiant unique de la scene ou vue à charger au déclenchement de l'évènement
     */
    function getId() { return $this->id; }
    /**
     * @return (String) Type de page (scene ou vue) appelée par l'evenement.
     */
    function getType() { return $this->type; }
    /**
     * @return (Array<String>) Iframes cibles dans lesquels charger la prochaine scène
     */
    function getTargets() { return $this->targets; }
    
    function getJS() { return $this->js; }
    
    /**
     * Défini la scene suivante à charger lorsque l'evenement est déclenché.
     * @return L'objet modifié pour pouvoir effectuer des appels en chaine
     */
    function setType($type) {
        $this->type = $type;
    }
    /**
     * Défini l'identifiant de la vue ou de la scene à afficher au déclenchement de l'évènement
     * @param (String) $id  : Identifiant dela vue ou de la scene
     */
    function setId($id) {
        $this->id = $id;
    }
    /**
     * Défini les iframe cibles dans lesquels charger la scene suivante définie par scenePost
     * @param targets  list des iframes (identifiées par l'identifiant unique des scenes)
     *                 dans lesquels charger le contenu de la scene suivante défini par scenePost
     * @return L'objet modifié pour pouvoir effectuer des appels en chaine
     */
    function setTargets($targets) {
        $this->targets = $targets;
    }  
    
    /**
     * @return Le nom de fichier selon si la destination est une vue ou une scene
     */
    function getFilename() {
        if ($this->getType() == "view")
            return getViewFilename($this->getId());
        else
            return getSceneFilename($this->getId());
    }
   
}