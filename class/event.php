<?php
class Event {
	private $eventType;
	private $elemTrigger;
	private $scenePost;
	
	function __construct($eventType, $elemTrigger) {
		$this->eventType = $eventType;
		$this->elemTrigger = $elemTrigger;
	}
	
	function getEventType() { return $this->eventType; }
	function getElemTrigger() { return $this->elemTrigger; }
	function getScenePost() { return $this->scenePost; }
	
	function setScenePost($post) {
		$this->scenePost = $post;
		return $this;
	}
	
}
?>