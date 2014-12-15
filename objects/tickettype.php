<?php
require_once 'handlers/tickethandler.php';
require_once 'handlers/eventhandler.php';
require_once 'objects/object.php';

class TicketType extends Object {
	private $humanName;
	private $price;
	private $internalName;

	/*
	 * Ticket type
	 *
	 * Ticket type implementation
	 *
	 * Id: Unique id of ticket type
	 * HumanName: Human readable name for tickets
	 */
	public function __construct($id, $humanName, $price, $internalName) {
		parent::__construct($id);
		
		$this->humanName = $humanName;
		$this->price = $price;
		$this->internalName = $internalName;
	}

	public function getHumanName() {
		return $this->humanName;
	}

	public function getPrice() {
		return $this->price;
	}

	public function getInternalName() {
		return $this->internalName;
	}

	/*
	 * Returns the price of this ticket, taking discount into consideration
	 */
	public function getPriceForUser($user) {
		$price = $this->getPrice();
		$discount = 20;
		
		$ticketList = TicketHandler::getTicketsForOwner($user);
		
		foreach ($ticketList as $ticket) {
			$year = date('Y', EventHandler::getCurrentEvent()->getStartTime());
			$ticketYear = date('Y', $ticket->getEvent()->getStartTime());
		
			// We'll check if this user has a ticket in the same calender year, if it has, then give the discount.
			if ($year == $ticketYear) {
				$price -= $discount;
				
				break;
			}
		}
		
		return $price;
	}
}
?>