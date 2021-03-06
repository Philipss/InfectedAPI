<?php
require_once 'session.php';
require_once 'handlers/agendahandler.php';

$result = false;
$message = null;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('event.agenda')) {
		
		if (isset($_GET['id']) &&
			isset($_GET['title']) &&
			isset($_GET['description']) &&
			isset($_GET['startTime']) &&
			isset($_GET['startDate']) &&
			is_numeric($_GET['id']) &&
			!empty($_GET['title']) &&
			!empty($_GET['description']) &&
			!empty($_GET['startTime']) &&
			!empty($_GET['startDate'])) {
			$agenda = AgendaHandler::getAgenda($_GET['id']);
			$title = $_GET['title'];
			$description = $_GET['description'];
			$startTime = $_GET['startDate'] . ' ' . $_GET['startTime'];
			$published = isset($_GET['published']) ? $_GET['published'] : 0;
			
			if ($agenda != null) {
				AgendaHandler::updateAgenda($agenda, $title, $description, $startTime, $published);
				$result = true;
			} else {
				$message = 'Agendaen du prøver å endre finnes ikke.';
			}
		} else {
			$message = 'Du har ikke fyllt ut alle feltene!';
		}
	} else {
		$message = 'Du har ikke tillatelse til dette.';
	}
} else {
	$message = 'Du er ikke logget inn.';
}

echo json_encode(array('result' => $result, 'message' => $message));
?>