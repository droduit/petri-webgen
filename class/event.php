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
	/** String: Id de la scene de destination appelée par l'evenement */
	private $scenePost;
	/** Array<String>: Id des frames dans lesquelles charger la scene de destination */
	private $targets;
	
	
	/**
	 * Construit un objet Event liant un Sprite à un type d'évenement
	 * @param eventType Type de l'évènement (click, dblclick, ...)
	 * @param elemTrigger objet Sprite auquel l'évènement est lié  
	 */
	function __construct($eventType, $elemTrigger) {
		$this->eventType = $eventType;
		$this->elemTrigger = $elemTrigger;
	}
	
	/**
	 * @return (String) Type de l'évènement (click, dblclick, ...)
	 */
	function getEventType() { return $this->eventType; }
	/**
	 * @return (String) Identifiant unique du sprite qui va déclencher cet évenement
	 */
	function getElemTrigger() { return $this->elemTrigger; }
	/**
	 * @return (String) Identifiant unique de la scene à charger au déclenchement de l'évènement
	 */
	function getScenePost() { return $this->scenePost; }
	/**
	 * @return (Array<String>) Iframes cibles dans lesquels charger la prochaine scène
	 */
	function getTargets() { return $this->targets; }
	
	/**
	 * Défini la scene suivante à charger lorsque l'evenement est déclenché.
	 * @return L'objet modifié pour pouvoir effectuer des appels en chaine
	 */
	function setScenePost($post) {
		$this->scenePost = $post;
		return $this;
	}
	/**
	 * Défini les iframe cibles dans lesquels charger la scene suivante définie par scenePost
	 * @param targets  list des iframes (identifiées par l'identifiant unique des scenes)
	 *                 dans lesquels charger le contenu de la scene suivante défini par scenePost
	 * @return L'objet modifié pour pouvoir effectuer des appels en chaine
	 */
	function setTargets($targets) {
	    $this->targets = $targets;
	    return $this;
	}
	
	
}
?>