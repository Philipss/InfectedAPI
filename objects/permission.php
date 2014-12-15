<?php
require_once 'objects/object.php';

class Permission extends Object {
	private $value;
	private $description;
	
	public function __construct($id, $value, $description) {
		parent::__construct($id);
		
		$this->value = $value;
		$this->description = $description;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getDescription() {
		return $this->description;
	}
}
?>