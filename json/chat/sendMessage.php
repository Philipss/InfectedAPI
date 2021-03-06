<?php
require_once 'session.php';
require_once 'handlers/chathandler.php';

$result = false;
$message = null;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if (isset($_GET['id']) && isset($_GET['message'])) {
		$chat = ChatHandler::getChat($_GET['id']);

		if ($chat != null) {
			if ($user->hasPermission('*') || 
				$user->hasPermission('compo.chat') ||
				ChatHandler::isChatMember($user, $chat)) {
				ChatHandler::sendChatMessage($user, $chat, $_GET['message']);
				$result = true;
			} else {
				$message = 'Du er ikke med i denne chatten!';
			}
		} else {
			$message = 'Chatten finnes ikke!';
		}
	} else {
		$message = 'Vi mangler felt';
	}
} else {
	$message = 'Du er ikke logget inn.';
}

echo json_encode(array('result' => $result, 'message' => $message));
?>