<?php
class Event {
	private $name;
	private $trigger;
	private $scenePost;
	
	function __construct($name, $trigger) {
		$this->name = $name;
		$this->trigger = $trigger;
	}
	
	function getName() { return $this->name; }
	function getTrigger() { return $this->trigger; }
	function getScenePost() { return $this->scenePost; }
	
	function setScenePost($post) {
		$this->scenePost = $post;
		return $this;
	}
	
}
?>