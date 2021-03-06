<?php
require_once 'session.php';
require_once 'handlers/storesessionhandler.php';
require_once 'handlers/tickettypehandler.php';
require_once 'handlers/eventhandler.php';
require_once 'paypal/paypal.php';

$result = false;
$message = null;
$url = null;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if (isset($_GET['ticketType']) &&
		isset($_GET['amount'])) {		
		$type = $_GET['ticketType'];
		$amount = $_GET['amount'];

		if (!StoreSessionHandler::hasStoreSession($user)) {
			$ticketType = TicketTypeHandler::getTicketType($type);
			$current = EventHandler::getCurrentEvent();
			
			if ($current->isBookingTime()) {
				//Register store session
				$price = $ticketType->getPriceForUser($user) * $amount;
				$code = StoreSessionHandler::registerStoreSession($user, $ticketType, $amount, $price);
				$url = PayPal::getPaymentUrl($ticketType, $amount, $code, $user);

				if (isset($url)) {
					$result = true;
				} else {
					$message = "Noe gikk galt da vi snakket med paypal";
				}
			} else {
				$message = "Billettsalget har ikke åpnet!";
			}
		} else {
			$message = "Du har allerede en session!";
		}
	} else {
		$message = 'Du har ikke fyllt ut alle feltene.';
	}
} else {
	$message = "Du er ikke logget inn!";
} 

if ($result) {
	echo json_encode(array('result' => $result, 'url' => $url));
} else {
	echo json_encode(array('result' => $result, 'message' => $message));
}
?>