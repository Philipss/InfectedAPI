<?php
require_once 'objects/user.php';

session_start();

/*
 * Used to get information from current sessions.
 */
class Session {
	/*
	 * Returns true if the current user is authenticated.
	 */
	public static function isAuthenticated() {
		return isset($_SESSION['user']);
	}
	
	/*
	 * Returns true if the current user is a member (To clarify, is a crew member).
	 */
	public static function isMember() {
		return self::getCurrentUser()->isGroupMember();
	}
	
	/*
	 * Returns the current user.
	 */
	public static function getCurrentUser() {
		if (self::isAuthenticated()) {
			return $_SESSION['user'];
		}
	}
}
?>