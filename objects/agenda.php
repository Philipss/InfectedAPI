<?php
require_once 'handlers/eventhandler.php';
require_once 'objects/object.php';

class Agenda extends Object {
	private $eventId;
	private $name;
	private $title;
	private $description;
	private $startTime;
	private $published;
	
	public function getEvent() {
		return EventHandler::getEvent($this->eventId);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getStartTime() {
		return strtotime($this->startTime);
	}
	
	public function isPublished() {
		return $this->published ? true : false;
	}
	
	public function isHappening() {
		return $this->getStartTime() - 5 * 60 >= time() || $this->getStartTime() + 1 * 60 * 60 >= time();
	}
}
?>