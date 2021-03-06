<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/clanhandler.php';

$result = false;
$message = null;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if (isset($_GET['user']) && 
		isset($_GET['clan'])) {
		$targetUser = UserHandler::getUser($_GET['user']);
		
		if ($targetUser != null) {
			$clan = ClanHandler::getClan($_GET['clan']);
			
			if ($clan != null) {
				if ($user->getId() == $clan->getChief()) {
					ClanHandler::setMemberStepinState($clan, 
													  $targetUser, 
													  ClanHandler::STATE_STEPIN_PLAYER);
					$result = true;
				} else {
					$message = 'Du er ikke chief.';
				}
			} else {
				$message = 'Clanen finnes ikke!';
			}
		} else {
			$message = 'Brukeren du prøvde å kicke finnes ikke!';
		}
	} else {
		$message = 'Vi mangler felt.';
	}
} else {
	$message = 'Du er ikke logget inn.';
}

echo json_encode(array('result' => $result, 'message' => $message));
?>