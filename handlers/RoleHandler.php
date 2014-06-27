<?php
require_once 'settings.php';
require_once 'mysql.php';
require_once 'role.php';

	class RoleHandler {
		// Get a role by id.
		public static function getRole($id) {
			$con = MySQL::open(Settings::db_name_infected);
			
			$result = mysqli_query($con, 'SELECT * FROM ' . Settings::db_table_roles . ' WHERE id=\'' . $id . '\'');
			$row = mysqli_fetch_array($result);
			
			MySQL::close($con);

			if ($row) {
				return new Role($row['id'], $row['name']);
			}
		}
		
		// Get a list of all roles.
		public static function getRoles() {
			$con = MySQL::open(Settings::db_name_infected);
			
			$result = mysqli_query($con, 'SELECT id FROM ' . Settings::db_table_roles);
			
			$roleList = array();
			
			while ($row = mysqli_fetch_array($result)) {
				array_push($roleList, self::getRole($row['id']));
			}
			
			MySQL::close($con);

			return $roleList;
		}
	}
?>