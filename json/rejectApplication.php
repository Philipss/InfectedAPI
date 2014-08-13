<?php
require_once 'session.php';
require_once 'handlers/applicationhandler.php';

$result = false;
$message = null;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin') ||
		$user->isGroupMember() && $user->isGroupLeader()) {
		if (isset($_GET['id']) &&
			isset($_GET['reason']) &&
			is_numeric($_GET['id']) &&
			!empty($_GET['reason'])) {
			$id = $_GET['id'];
			$reason = $_GET['reason'];
			
			ApplicationHandler::rejectApplication($id, $reason);
			$result = true;
		} else {
			$message = 'Ingen søknad spesifisert.';
		}
	} else {
		$message = 'Du har ikke tillatelse til dette.';
	}
} else {
	$message = 'Du er ikke logget inn.';
}

echo json_encode(array('result' => $result, 'message' => $message));
?>