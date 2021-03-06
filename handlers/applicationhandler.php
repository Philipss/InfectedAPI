<?php
require_once 'settings.php';
require_once 'mysql.php';
require_once 'notificationmanager.php';
require_once 'handlers/eventhandler.php';
require_once 'objects/application.php';

class ApplicationHandler {
    /* 
     * Get an application by the internal id.
     */
    public static function getApplication($id) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '` 
                                 WHERE `id` = \'' . $mysql->real_escape_string($id) . '\';');
        
        $mysql->close();
		
		return $result->fetch_object('Application');
    }
    
    /*
     * Returns a list of all applications (For all events)
     */
    public static function getApplications() {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '`;');
        
        $mysql->close();

        $applicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }
        
        return $applicationList;
    }
    
    /* 
     * Returns a list of pending applications.
     */
    public static function getPendingApplications() {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `' . Settings::db_table_infected_crew_applications . '`.* FROM `' . Settings::db_table_infected_crew_applications . '`
                                 LEFT JOIN `' . Settings::db_table_infected_crew_applicationqueue . '`
                                 ON `' . Settings::db_table_infected_crew_applications . '`.`id` = `applicationId`
                                 WHERE `applicationId` IS NULL
                                 AND `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `state` = \'1\'
                                 ORDER BY `openedTime`;');
        
        $mysql->close();

        $applicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }
        
        return $applicationList;
    }
    
    /* 
     * Returns a list of pending applications.
     */
    public static function getPendingApplicationsForGroup($group) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `' . Settings::db_table_infected_crew_applications . '`.* FROM `' . Settings::db_table_infected_crew_applications . '`
                                 LEFT JOIN `' . Settings::db_table_infected_crew_applicationqueue . '`
                                 ON `' . Settings::db_table_infected_crew_applications . '`.`id` = `applicationId`
                                 WHERE `applicationId` IS NULL
                                 AND `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `groupId` = \'' . $mysql->real_escape_string($group->getId()) .  '\'
                                 AND `state` = \'1\'
                                 ORDER BY `openedTime`;');
        
        $mysql->close();

        $applicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }

        return $applicationList;
    }
    
    /*
     * Returns a list of all queued applications.
     */
    public static function getQueuedApplications() {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `' . Settings::db_table_infected_crew_applications . '`.* FROM `' . Settings::db_table_infected_crew_applications . '`
                                 LEFT JOIN `' . Settings::db_table_infected_crew_applicationqueue . '`
                                 ON `' . Settings::db_table_infected_crew_applications . '`.`id` = `applicationId`
                                 WHERE `applicationId` IS NOT NULL
                                 AND `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `state` = \'1\'
                                 ORDER BY `' . Settings::db_table_infected_crew_applicationqueue . '`.`id`;');
        
        $mysql->close();

        $applicationList = array();

        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }
        
        return $applicationList;
    }
    
    /*
     * Returns a list of all queued applications for a given group.
     */
    public static function getQueuedApplicationsForGroup($group) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `' . Settings::db_table_infected_crew_applications . '`.* FROM `' . Settings::db_table_infected_crew_applications . '`
                                 LEFT JOIN `' . Settings::db_table_infected_crew_applicationqueue . '`
                                 ON `' . Settings::db_table_infected_crew_applications . '`.`id` = `applicationId`
                                 WHERE `applicationId` IS NOT NULL
                                 AND `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `groupId` = \'' . $mysql->real_escape_string($group->getId()) .  '\'
                                 AND `state` = \'1\'
                                 ORDER BY `' . Settings::db_table_infected_crew_applicationqueue . '`.`id`;');
        
        $mysql->close();

        $applicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }
        
        return $applicationList;
    }
    
	/*
     * Returns a list of all accepted applications.
     */
    public static function getAcceptedApplications() {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '`
                                 WHERE `state` = \'2\'
								 ORDER BY `openedTime` DESC;');
        
		$mysql->close();
		
        $acceptedApplicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($acceptedApplicationList, $object);
        }
        
        return $acceptedApplicationList;
    }
    
    /*
     * Returns a list of all accepted applications for a given group.
     */
    public static function getAcceptedApplicationsForGroup($group) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '`
                                 WHERE `groupId` = \'' . $mysql->real_escape_string($group->getId()) .  '\'
								 AND `state` = \'2\'
                                 ORDER BY `openedTime` DESC;');
        
		$mysql->close();
		
        $acceptedApplicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($acceptedApplicationList, $object);
        }
        
        return $acceptedApplicationList;
    }
	
    /* 
     * Create a new application. 
     */
    public static function createApplication($group, $user, $content) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $mysql->query('INSERT INTO `' . Settings::db_table_infected_crew_applications . '` (`eventId`, `groupId`, `userId`, `openedTime`, `state`, `content`) 
                       VALUES (\'' . EventHandler::getCurrentEvent()->getId() . '\', 
                               \'' . $mysql->real_escape_string($group->getId()) . '\', 
                               \'' . $mysql->real_escape_string($user->getId()) . '\', 
                               \'' . date('Y-m-d H:i:s') . '\',
                               \'1\',
                               \'' . $mysql->real_escape_string($content) . '\');');
        
        $mysql->close();
        
        // If the group is set to queue applications, do so automatically.
        if ($group->isQueuing()) {
            $application = self::getUserApplicationForGroup($user, $group);
        
            self::queueApplication($application);
        }
        
        // Notify the group leader by email.
        NotificationManager::sendApplicationCreatedNotification($user, $group);
    }
    
    /* 
     * Remove an application.
     */
    public static function removeApplication($application) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        // Remove the application.
        $mysql->query('DELETE FROM `' . Settings::db_table_infected_crew_applications . '` 
                       WHERE `id` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
        
        $mysql->close();
        
        // Remove the application from the queue, if present.
        self::unqueueApplication($application);
    }
    
    /*
     * Accepts an application, with a optional comment.
     */
    public static function acceptApplication($user, $application, $comment, $notify) {
		// Only allow application for current event to be accepted.
		if ($application->getEvent()->getId() == EventHandler::getCurrentEvent()->getId()) {
			$mysql = MySQL::open(Settings::db_name_infected_crew);
			
			$mysql->query('UPDATE `' . Settings::db_table_infected_crew_applications . '` 
						   SET `closedTime` = \'' . date('Y-m-d H:i:s') . '\',
							   `state` = \'2\',
							   `updatedByUserId` = \'' . $mysql->real_escape_string($user->getId()) . '\',
							   `comment` = \'' . $mysql->real_escape_string($comment) . '\'
						   WHERE `id` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
			
			$mysql->close();
			
			$applicationUser = $application->getUser();
			$group = $application->getGroup();
			
			// Remove the application from the queue, if present.
			self::unqueueApplication($user, $application);
			
			// Reject users application for all other groups.
			$applicationList = self::getUserApplications($applicationUser);
			
			foreach ($applicationList as $value) {
				if ($group->getId() != $value->getGroup()->getId()) {
					self::closeApplication($user, $value);
				}
			}
			
			// Set the user in the new group
			GroupHandler::changeGroupForUser($applicationUser, $group);
			
			// Notify the user by email, if notify is true.
			if ($notify) {
				// Send email notification to the user.
				NotificationManager::sendApplicationAccpetedNotification($application);
			}
		}
    }
    
    /*
     * Rejects an application, with a optional comment.
     */
    public static function rejectApplication($user, $application, $comment, $notify) {
		// Only allow application for current event to be rejected.
		if ($application->getEvent()->getId() == EventHandler::getCurrentEvent()->getId()) {
			$mysql = MySQL::open(Settings::db_name_infected_crew);
			
			$mysql->query('UPDATE `' . Settings::db_table_infected_crew_applications . '` 
						   SET `closedTime` = \'' . date('Y-m-d H:i:s') . '\',
							   `state` = \'3\', 
							   `updatedByUserId` = \'' . $mysql->real_escape_string($user->getId()) . '\',
							   `comment` = \'' . $mysql->real_escape_string($comment) . '\'
						   WHERE `id` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
			
			$mysql->close();
			
			// Remove the application from the queue, if present.
			self::unqueueApplication($user, $application);
			
			// Notify the user by email, if notify is true.
			if ($notify) {
				NotificationManager::sendApplicationRejectedNotification($application, $comment);
			}
		}
    }
    
    /*
     * Rejects an application, with a optional comment.
     */
    public static function closeApplication($user, $application) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $mysql->query('UPDATE `' . Settings::db_table_infected_crew_applications . '` 
                       SET `closedTime` = \'' . date('Y-m-d H:i:s') . '\',
                           `state` = \'4\',
						   `updatedByUserId` = \'' . $mysql->real_escape_string($user->getId()) . '\',
                           `comment` = \'Closed by the system.\'
                       WHERE `id` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
        
        $mysql->close();
        
        // Remove the application from the queue, if present.
        self::unqueueApplication($user, $application);
    }
    
    /*
     * Checks if an application is queued.
     */
    public static function isQueued($application) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `id` FROM `' . Settings::db_table_infected_crew_applicationqueue . '` 
                                 WHERE `applicationId` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
        

        $mysql->close();

        $row = $result->fetch_array();
                
        return $row ? true : false;
    }
    
    /*
     * Puts an application in queue.
     */
    public static function queueApplication($user, $application, $notify) {
		// Only allow application for current event to be queued.
		if ($application->getEvent()->getId() == EventHandler::getCurrentEvent()->getId()) {
			if (!self::isQueued($application)) {
				$mysql = MySQL::open(Settings::db_name_infected_crew);
				
				$mysql->query('INSERT INTO `' . Settings::db_table_infected_crew_applicationqueue . '` (`applicationId`) 
							   VALUES (\'' . $mysql->real_escape_string($application->getId()) . '\');');
							   
				$mysql->query('UPDATE `' . Settings::db_table_infected_crew_applications . '` 
							   SET `updatedByUserId` = \'' . $mysql->real_escape_string($user->getId()) . '\'
							   WHERE `id` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
							   
				$mysql->close();
			}
			
			// Notify the user by email, if notify is true.
			if ($notify) {
				NotificationManager::sendApplicationQueuedNotification($application);
			}
		}
    }
    
    /*
     * Removes an application from queue.
     */
    public static function unqueueApplication($user, $application) {
		// Only allow application for current event to be unqueued.
		if ($application->getEvent()->getId() == EventHandler::getCurrentEvent()->getId()) {
			$mysql = MySQL::open(Settings::db_name_infected_crew);
			
			$mysql->query('DELETE FROM `' . Settings::db_table_infected_crew_applicationqueue . '` 
						   WHERE `applicationId` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
						   
			$mysql->query('UPDATE `' . Settings::db_table_infected_crew_applications . '` 
			               SET `updatedByUserId` = \'' . $mysql->real_escape_string($user->getId()) . '\'
			               WHERE `id` = \'' . $mysql->real_escape_string($application->getId()) . '\';');
                                    
			$mysql->close();
		}
    }
    
    /*
     * Returns a true if user has application for group.
     */
    public static function hasUserApplicationForGroup($user, $group) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `id` FROM `' . Settings::db_table_infected_crew_applications . '`
                                 WHERE `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `userId` = \'' . $mysql->real_escape_string($user->getId()) . '\'
                                 AND `groupId` = \'' . $mysql->real_escape_string($group->getId()) . '\'
                                 AND (`state` = \'1\' OR `state` = \'2\');');
        
        $mysql->close();

        $row = $result->fetch_array();
		
        return $row ? true : false;
    }
    
    /*
     * Returns the application for group and user.
     */
    public static function getUserApplicationForGroup($user, $group) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '`
                                 WHERE `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `userId` = \'' . $mysql->real_escape_string($user->getId()) . '\'
                                 AND `groupId` = \'' . $mysql->real_escape_string($group->getId()) . '\'
                                 AND (`state` = \'1\' OR `state` = \'2\');');
        
        $mysql->close();
        
        return $result->fetch_object('Application');
    }
    
    /*
     * Returns a list of all applications for given user.
     */
    public static function getUserApplications($user) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '`
                                 WHERE `eventId` = \'' . EventHandler::getCurrentEvent()->getId() . '\'
                                 AND `userId` = \'' . $user->getId() . '\';');
        
        $mysql->close();

        $applicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }
        
        return $applicationList;
    }
    
    /*
     * Returns a list of all applications for that event.
     */
    public static function getApplicationsForEvent($event) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_applications . '`
                                 WHERE `eventId` = \'' . $event->getId() . '\';');
        
        $mysql->close();

        $applicationList = array();
        
        while ($object = $result->fetch_object('Application')) {
            array_push($applicationList, $object);
        }
        
        return $applicationList;
    }
}
?>