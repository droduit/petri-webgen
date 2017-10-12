<?php
class Transition {
	private $id;
	private $idScenePost;
	private $events;
	
	function __construct($id, $idScenePost, $events) {
		$this->id = $id;
		$this->events = $events == null ? array() : $events;
		$this->idScenePost = $idScenePost;
		
	}
	
	function addEvent($e) {
		$this->events[] = $e;
	}
	
	function getEvents() { return $this->events; }

}
?>