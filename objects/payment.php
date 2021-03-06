<?php
require_once 'handlers/userhandler.php';
require_once 'handlers/tickettypehandler.php';
require_once 'objects/object.php';

class Payment extends Object{
	private $userId;
	private $ticketType;
	private $price;
	private $totalPrice;
	private $transactionId;
	private $datetime;

	public function getUser() {
		return UserHandler::getUser($this->userId);
	}

	public function getTicketType() {
		return TicketTypeHandler::getTicketType($this->ticketType);
	}

	public function getPrice() {
		return $this->price;
	}

	public function getTotalPrice() {
		return $this->totalPrice;
	}

	public function getTransactionId() {
		return $this->transactionId;
	}
	
	public function getDateTime() {
		return strtotime($this->datetime);
	}
}
?>