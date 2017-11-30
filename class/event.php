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
	/** String: Id de la scene ou de la vue de destination appelée par l'evenement */
	private $idPage;
	/** String: Type de page (scene ou vue) appelée par l'évenement */
	private $typePage;
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
	 * @return (String) Identifiant unique de la scene ou vue à charger au déclenchement de l'évènement
	 */
	function getIdPage() { return $this->idPage; }
	/**
	 * @return (String) Type de page (scene ou vue) appelée par l'evenement.
	 */
	function getTypePage() { return $this->typePage; }
	/**
	 * @return (Array<String>) Iframes cibles dans lesquels charger la prochaine scène
	 */
	function getTargets() { return $this->targets; }
	
	/**
	 * @return Le nom de fichier selon si la destination est une vue ou une scene
	 */
	function getDestFilename() {
	    if($this->getTypePage() == "view")
	        return getViewFilename($this->getIdPage());
	    else
	        return getSceneFilename($this->getIdPage());
	}
	
	/**
	 * Défini la scene suivante à charger lorsque l'evenement est déclenché.
	 * @return L'objet modifié pour pouvoir effectuer des appels en chaine
	 */
	function setDestTypePage($type) {
	    $this->typePage = $type;
	}
	/**
	 * Défini l'identifiant de la vue ou de la scene à afficher au déclenchement de l'évènement
	 * @param (String) $id  : Identifiant dela vue ou de la scene
	 */
	function setDestId($id) {
	    $this->idPage = $id;
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