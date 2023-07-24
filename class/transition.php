<?php
/**
 * Objet représentant une transition.
 * Une transition contient les evenements d'une scene.
 * @author Dominique Roduit
 */
class Transition {
    /** int: Identifiant unique de la transition */
	private $id;
	/** String: Identifiant de la scene de destination appelée par la transition */
	//private $idScenePost;
	/** Array<Event>: Evenements contenus dans cette transition */
	private $events;

	
	/**
	 * Construit une nouvelle transition contenant 
	 */
	function __construct($id, $events) {
		$this->id = $id;
		$this->events = $events == null ? array() : $events;
		//$this->idScenePost = $idScenePost;
	}
	
	/**
	 * Ajout d'un évènement à la transition
	 * @param (Event) $e : Evenement à ajouter à la transition
	 */
	function addEvent($e) {
	    $this->events[] = $e;
	}
	
	/**
	 * @return (Array<Event>) Liste des évènements de la transition
	 */
	function getEvents() { return $this->events; }
	
	/**
	 * @return (Array<String>) Liste des cibles dans lesquels charger la prochaine scène
	 */
	//function getTargets() { return $this->targets; }

}