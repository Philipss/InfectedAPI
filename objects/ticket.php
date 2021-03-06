<?php
require_once 'settings.php';
require_once 'qr.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/paymenthandler.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/tickettypehandler.php';
require_once 'handlers/seathandler.php';
require_once 'handlers/checkinstatehandler.php';
require_once 'objects/object.php';

class Ticket extends Object {
	private $eventId;
	private $paymentId;
	private $typeId;
	private $buyerId;
	private $userId;
	private $seatId;
	private $seaterId;

	/*
	 * Returns the event this ticket is for
	 */
	public function getEvent() {
		return EventHandler::getEvent($this->eventId);
	}
	
	/*
	 * Returns the payment that this ticket is linked to, if any.
	 */
	public function getPayment() {
		return PaymentHandler::getPayment($this->paymentId);
	}

	/*
	 * Returns the ticket type
	 */
	public function getType() {
		return TicketTypeHandler::getTicketType($this->typeId);
	}
	
	/*
	 * Returns the buyer of this ticket, also who bought/got it in the first place.
	 */
	public function getBuyer() {
		return UserHandler::getUser($this->buyerId);
	}
	
	/*
	 * Returns the user of this ticket.
	 */
	public function getUser() {
		return UserHandler::getUser($this->userId);
	}
	
	/*
	 * Returns the seat that this ticket is seated at
	 */
	public function getSeat() {
		return SeatHandler::getSeat($this->seatId);
	}
	
	/*
	 * Returns the seater of this ticket.
	 *
	 * The seater is the user account that is allowed to decide what seat this ticket is seated on.
	 */
	public function getSeater() {
		return UserHandler::getUser($this->seaterId);
	}
	
	/* 
	 * Returns true if this ticket can be refunded.
	 */
	public function isRefundable() {
		$event = $this->getEvent();
		$timeLeftToEvent = date('U', $event->getStartTime()) - time();
		
		return $timeLeftToEvent >= Settings::refundBeforeEventTime;
	}
	
	/*
	 * Returns true if this ticket is checked in.
	 */
	public function isCheckedIn() {
		return CheckInStateHandler::isCheckedIn($this);
	}
	
	/*
	 * Returns true if given user is allowed to seat this ticket.
	 */
	public function canSeat($user) {
		return ($this->userId == $user->getId() && $this->seaterId == 0) || 
				$this->seaterId == $user->getId();
	}
	
	/*
	 * Returns a human readable representation of the ticket
	 */
	public function getHumanName() {
		$event = $this->getEvent();
		$season = date('m', $event->getStartTime()) == 2 ? 'VINTER' : 'HØST';
		$theme = $event->getTheme();
		$eventName = !empty($theme) ? $theme : $season . date('Y', $event->getStartTime());
	
		return strtoupper(Settings::name . '_' . $eventName . '_' . $this->getId());
	}
	
	public function getQrImagePath() {
		return QR::getCode('https://crew.infected.no/api/pages/utils/verifyTicket.php?id=' . $this->getId());
	}
}
?>