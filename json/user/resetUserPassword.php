<?php
require_once 'handlers/userhandler.php';
require_once 'handlers/passwordresetcodehandler.php';

$result = false;
$message = null;

if (!isset($_GET['code'])) {
	if (isset($_GET['identifier']) &&
		!empty($_GET['identifier'])) {
		
		$identifier = $_GET['identifier'];
		
		if (UserHandler::userExists($identifier)) {
			$user = UserHandler::getUserByIdentifier($identifier);
			
			if ($user != null) {
				$user->sendPasswordResetMail();
				$result = true;
				$message = 'En link har blitt sendt til din registrerte e-post adresse, klikk på linken for å endre passordet ditt.';
			}
		} else {
			$message = 'Kunne ikke finne brukeren i vår database.';
		}
	} else {
		$message = 'Du må skrive inn en e-postadresse eller ett brukernavn!';
	}
} else {
	if (isset($_GET['password']) &&
		isset($_GET['confirmpassword']) &&
		!empty($_GET['password']) &&
		!empty($_GET['confirmpassword'])) {
		$code = $_GET['code'];
		$password = $_GET['password'];
		$confirmPassword = $_GET['confirmpassword'];
		
		if (PasswordResetCodeHandler::existsPasswordResetCode($code)) {
			$user = PasswordResetCodeHandler::getUserFromPasswordResetCode($code);
			
			if ($password == $confirmPassword) {
				PasswordResetCodeHandler::removePasswordResetCode($code);
				UserHandler::updateUserPassword($user->getId(), hash('sha256', $password));
				$result = true;
				$message = 'Passordet ditt er nå endret.';
			} else {
				$message = 'Passordene du skrev inn var ikke like!';
			}
		} else {
			$message = 'Linken du fikk for å resette passwordet ditt er ikke lengre gyldig.';
		}
	} else {
		$message = 'Du har ikke fyllt ut alle feltene.';
	}
}

echo json_encode(array('result' => $result, 'message' => $message));
?>