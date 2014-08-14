<?php
	require_once 'session.php';
	require_once 'handlers/storesessionhandler.php';

	$result = false;
	$message = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();
		if (isset($_GET['ticketType']) &&
			isset($_GET['amount'])) {		
			$type = $_GET['ticketType'];
			$amount = $_GET['amount'];

			if(!StoreSessionHandler::hasStoreSession($user)) {
				StoreSessionHandler::registerStoreSession($user, TicketTypeHandler::getTicketType($type), $amount);

				$result = true;
				$message = 'Hi mom!';
			} else {
				$message = "Du har allerede en session!";
			}
		} else {
			$message = 'Du har ikke fyllt ut alle feltene.';
		}
	} else {
		$message = "Du er ikke logget inn!";
	} 

	echo json_encode(array('result' => $result, 'message' => $message));
?>