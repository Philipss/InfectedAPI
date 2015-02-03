<?php
require_once 'session.php';
require_once 'handlers/eventhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	echo '<meta charset="UTF-8">';
	
	if ($user->hasPermission('*')) {
		if (isset($_GET['year']) &&
			isset($_GET['ageLimit']) &&
			is_numeric($_GET['year']) &&
			is_numeric($_GET['ageLimit'])) {
			$eventList = EventHandler::getEventsByYear($_GET['year']);
			
			if (!empty($eventList)) {
				$userList = EventHandler::getMembersAndParticipantsForEvents($eventList, $_GET['ageLimit']);
				
				if (!empty($userList)) {
					echo '<p>Fant ' . count($userList) . ' brukere i databasen.</p>';
					
<<<<<<< HEAD
					$fieldList = array(array('Navn:', 'E-post:', 'Telefon:', 'Adresse:', 'Fødselsdato:'));
					
					foreach ($userList as $value) {
						array_push($fieldList, array($value->getFullName(), $value->getEmail(), '', '', ''));
					}
					
					$fp = fopen('php://memory', 'w');

					foreach ($fieldList as $field) {
						fputcsv($fp, $field, ';');
					}
					
					// rewrind the "file" with the csv lines
					fseek($fp, 0);

					// tell the browser it's going to be a csv file
					header('Content-Type: application/csv');
					// tell the browser we want to save it instead of displaying it
					header('Content-Disposition: attachement; filename="' . 'test.csv' . '";');
					// make php send the generated csv lines to the browser
					fpassthru($fp);
					
					fclose($fp);
					
					/* echo '<table>';
=======
					if (date('Y') <= $_GET['year']) {
						echo '<p>Året er ikke omme enda, det kan hende du ikke får den komplette medlemslisten om du henter den ut nå.</p>';
					}
						
					echo '<table>';
>>>>>>> origin/master
						echo '<tr>';
							echo '<th>Navn:</th>';
							echo '<th>E-post:</th>';
							echo '<th>Telefon:</th>';
							echo '<th>Adresse:</th>';
							echo '<th>Fødselsdato:</th>';
							echo '<th>Alder:</th>';
						echo '</tr>';
					
						foreach ($userList as $value) {
							echo '<tr>';
								echo '<td>' . $value->getFullName() . '</td>';
								echo '<td>' . $value->getEmail() . '</th>';
								echo '<td>' . $value->getPhoneAsString() . '</th>';
								echo '<td>' . $value->getAddress() . ', ' . $value->getPostalCode() . ' ' . $value->getCity() . '</th>';
								echo '<td>' . $value->getPostalCode() . ' ' . $value->getCity() . '</th>';
								echo '<td>' . date('d.m.Y', $value->getBirthdate()) . '</th>';
								echo '<td>' . $value->getAge() . ' år</th>';
							echo '</tr>';
						}
					echo '</table>'; */
				} else {
					echo 'Ingen brukere funnet.';
				}
			} else {
				echo 'Det var ingen arrangementer dette året.';
			}
		} else {
			echo 'Du må oppgi et gyldig år.';
		}
	} else {
		echo 'Du har ikke tillatelse til dette!';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>