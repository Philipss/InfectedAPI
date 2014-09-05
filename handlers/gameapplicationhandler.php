<?php
require_once 'settings.php';
require_once 'mysql.php';
require_once 'objects/gameapplication.php';

class GameApplicationHandler {
	public static function getGameApplication($id) {
		$con = MySQL::open(Settings::db_name_infected_main);
		
		$result = mysqli_query($con, 'SELECT * FROM `' . Settings::db_table_infected_main_gameapplications . '` 
									  WHERE `id` = \'' . $con->real_escape_string($id) . '\';');
										
		$row = mysqli_fetch_array($result);
		
		MySQL::close($con);
		
		if ($row) {
			return new GameApplication($row['id'], 
									   $row['eventId'], 
									   $row['gameId'], 
									   $row['name'], 
									   $row['tag'], 
									   $row['contactname'], 
									   $row['contactnick'], 
									   $row['phone'], 
									   $row['email']);
		}
	}
	
	public static function getGameApplications($game) {
		$con = MySQL::open(Settings::db_name_infected_main);
		
		$result = mysqli_query($con, 'SELECT `id` FROM `' . Settings::db_table_infected_main_gameapplications . '` 
									  WHERE `gameId` = \'' . $con->real_escape_string($game->getId()) . '\';');
									
		$gameApplicationList = array();
		
		while ($row = mysqli_fetch_array($result)) {
			array_push($gameApplicationList, self::getGameApplication($row['id']));
		}
		
		MySQL::close($con);
		
		return $gameApplicationList;
	}
	
	public static function getGameApplicationsForEvent($game, $event) {
		$con = MySQL::open(Settings::db_name_infected_main);
		
		$result = mysqli_query($con, 'SELECT `id` FROM `' . Settings::db_table_infected_main_gameapplications . '` 
									  WHERE `eventId` = \'' . $con->real_escape_string($event->getId()) . '\'
									  AND `gameId` = \'' . $con->real_escape_string($game->getId()) . '\';');
									
		$gameApplicationList = array();
		
		while ($row = mysqli_fetch_array($result)) {
			array_push($gameApplicationList, self::getGameApplication($row['id']));
		}
		
		MySQL::close($con);
		
		return $gameApplicationList;
	}
	
	public static function createGameApplication($event, $game, $name, $tag, $contactname, $contactnick, $phone, $email) {
		$con = MySQL::open(Settings::db_name_infected_main);
		
		mysqli_query($con, 'INSERT INTO `' . Settings::db_table_infected_main_gameapplications . '` (`eventId`, `gameId`, `name`, `tag`, `contactname`, `contactnick`, `phone`, `email`) 
							VALUES (\'' . $con->real_escape_string($event->getId()) . '\', 
									\'' . $con->real_escape_string($game->getId()) . '\', 
									\'' . $con->real_escape_string($name) . '\', 
									\'' . $con->real_escape_string($tag) . '\', 
									\'' . $con->real_escape_string($contactname) . '\', 
									\'' . $con->real_escape_string($contactnick) . '\', 
									\'' . $con->real_escape_string($phone) . '\', 
									\'' . $con->real_escape_string($email) . '\');');
		
		MySQL::close($con);
	}
}
?>