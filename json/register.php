<?php
require_once '/../utils.php';
require_once '/../handlers/UserHandler.php';

$message = "Sucess!";
$result = false;

if (isset($_POST['firstname']) && 
	isset($_POST['lastname']) && 
	isset($_POST['username']) &&  
	isset($_POST['password']) && 
	isset($_POST['confirmpassword']) && 
	isset($_POST['email']) && 
	isset($_POST['gender']) && 
	isset($_POST['birthday']) && 
	isset($_POST['birthmonth']) && 
	isset($_POST['birthyear']) && 
	isset($_POST['phone'])) {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$username = $_POST['username'];
	$password = hash('sha256', $_POST['password']);
	$confirmPassword = hash('sha256', $_POST['confirmpassword']);
	$email = $_POST['email'];
	$gender = $_POST['gender'];
	$birthDate = $_POST['birthyear'] . '-' . $_POST['birthmonth'] . '-' . $_POST['birthday'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$postalCode = $_POST['postalCode'];
	$nickname = isset($_POST['nickname']) ? $_POST['nickname'] : $username;
	
	if (!UserHandler::userExists($username)) {
		if (empty($firstname)) {
			$message = 'Du har ikke skrevet inn noe fornavn.';
		}
		if (empty($lastname)) {
			$message = 'Du har ikke skrevet inn noe etternavn.';	
		}
		if (empty($username) || strlen($username) < 4) {
			$message = 'Du har ikke oppgitt noe brukernavn, eller det er for kort.';
		}
		if (strlen($username) > 32) {
			$message = 'Brukernavnet ditt er for langt!';
		}
		if (empty($password)) {
			$message = 'Du har ikke oppgitt noe passord!';
		}
		if (strlen($password) < 8) {
			$message = 'Passordet er for kort!';
		}
		if (strlen($password) > 32) {
			$message = 'Passordet ditt er for langt!';
		}
		if (!preg_match('#[0-9]+#', $password)) {
			$message = 'Passordet må inneholde et tall.';
		}
		if (!preg_match('#[a-z]+#', $password)) {
			$message = 'Passordet må inneholde en bokstav.';
		}
		if (!preg_match('#[A-Z]+#', $password)) {
			$message = 'Passordet må inneholde em stor bokstav.';
		}
		if (strlen($email) <= 32 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$message = 'E-posten du skrev inn er ikke en gyldig e-post addresse!';
		}
		if ($gender < 0 || $gender > 1) {
			$message = 'Du har oppgitt et ugyldig kjønn.';
		}
		if (empty($phone) || strlen($phone) != 8) {
			$message = 'Du har ikke skrevet inn et gyldig telefonnummer.';
		}
		if (empty($address)) {
			$message = 'Du må skrive inn en adresse.';
		}
		if (empty($postalCode) || strlen($postalCode) != 4) {
			$message = 'Du må skrive inn et gyldig postnummer.';
		}
		if (strlen($nickname) > 32) {
			$message = 'Kallenavnet ditt er for langt!';
		}
		// Check if passwords match.
		if ($password == $confirmPassword) {
			UserHandler::createUser($firstname, $lastname, $username, $password, $email, $gender, $birthDate, $phone, $address, $postalCode, $nickname);
			$result = true;
		} else {
			$message = 'Passordene er ikke like!';
		}
	} else {
		$message = 'Brukeren finnes allerede!';
	}
}
else
{
	$message = "Du har ikke fylt inn alle feltene!";
}

echo ''
?>