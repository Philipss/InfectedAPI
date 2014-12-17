<?php
require_once 'settings.php';
require_once 'mysql.php';
require_once 'objects/team.php';

class TeamHandler {
    /* Get a team by id */
    public static function getTeam($id) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT * FROM `' . Settings::db_table_infected_crew_teams . '` 
                                      WHERE `id` = \'' . $mysql->real_escape_string($id) . '\';');
                                      
        $row = $result->fetch_array();
        
        $mysql->close();
        
        if ($row) {
            return new Team($row['id'], 
                            $row['groupId'], 
                            $row['name'], 
                            $row['title'], 
                            $row['description'], 
                            $row['leader']);
        }
    }
    
    /* Get a group by userId */
    public static function getTeamForUser($userId) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `teamId`
                                      FROM `' . Settings::db_table_infected_crew_memberof . '` 
                                      WHERE `userId` = \'' . $mysql->real_escape_string($userId) . '\';');
                                    
        $row = $result->fetch_array();
        
        $mysql->close();
        
        if ($row) {
            return self::getTeam($row['teamId']);
        }
    }
    
    /* Get an array of all teams */
    public static function getTeams() {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `id` FROM `' . Settings::db_table_infected_crew_teams . '`;');
        
        $teamList = array();
        
        while ($row = $result->fetch_array()) {
            array_push($teamList, self::getTeam($row['id']));
        }
        
        $mysql->close();
        
        return $teamList;
    }
    
    /* Get an array of all teams */
    public static function getTeamsForGroup($groupId) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);

        $result = $mysql->query('SELECT `id` FROM `' . Settings::db_table_infected_crew_teams . '`
                                      WHERE `groupId` = \'' . $mysql->real_escape_string($groupId) . '\';');
        
        $teamList = array();
        
        while ($row = $result->fetch_array()) {
            array_push($teamList, self::getTeam($row['id']));
        }
        
        $mysql->close();
        
        return $teamList;
    }
    
    /* Create a new team */
    public static function createTeam($group, $name, $title, $description, $leader) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $mysql->query('INSERT INTO `' . Settings::db_table_infected_crew_teams . '` (`groupId`, `name`, `title`, `description`, `leader`) 
                            VALUES (\'' . $mysql->real_escape_string($group->getId()) . '\', 
                                    \'' . $mysql->real_escape_string($name) . '\', 
                                    \'' . $mysql->real_escape_string($title) . '\', 
                                    \'' . $mysql->real_escape_string($description) . '\', 
                                    \'' . $mysql->real_escape_string($leader) . '\')');
        
        $mysql->close();
    }
    
    /* Remove a team */
    public static function removeTeam($group, $team) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $mysql->query('DELETE FROM `' . Settings::db_table_infected_crew_teams . '` 
                            WHERE `id` = \'' . $mysql->real_escape_string($team->getId()) . '\'
                            AND `groupId` = \'' . $mysql->real_escape_string($group->getId()) . '\';');
        
        $mysql->close();
    }
    
    /* Update a team */
    public static function updateTeam($team, $group, $name, $title, $description, $leader) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $mysql->query('UPDATE `' . Settings::db_table_infected_crew_teams . '` 
                            SET `groupId` = \'' . $mysql->real_escape_string($group->getId()) . '\', 
                                `name` = \'' . $mysql->real_escape_string($name) . '\', 
                                `title` = \'' . $mysql->real_escape_string($title) . '\', 
                                `description` = \'' . $mysql->real_escape_string($description) . '\', 
                                `leader` = \'' . $mysql->real_escape_string($leader) . '\' 
                            WHERE `id` = \'' . $mysql->real_escape_string($team->getId()) . '\';');
        
        $mysql->close();
    }
    
    /* Returns an array of users that are members of this team */
    public static function getMembers($groupId, $teamId) {
        $mysql = MySQL::open(Settings::db_name_infected);
        
        $result = $mysql->query('SELECT `' . Settings::db_table_infected_users . '`.`id` FROM `' . Settings::db_table_infected_users . '`
                                      LEFT JOIN `' . Settings::db_name_infected_crew . '`.`' . Settings::db_table_infected_crew_memberof . '`
                                      ON `' . Settings::db_table_infected_users . '`.`id` = `userId` 
                                      WHERE `groupId` = \'' . $mysql->real_escape_string($groupId) . '\'
                                      AND `teamId` = \'' . $mysql->real_escape_string($teamId) . '\' 
                                      ORDER BY `firstname` ASC;');
        
        $memberList = array();
        
        while ($row = $result->fetch_array()) {
            array_push($memberList, UserHandler::getUser($row['id']));
        }
        
        $mysql->close();
        
        return $memberList;
    }
    
    /* Is member of a team which means it's not a plain user */
    public static function isTeamMember($userId) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `teamId` 
                                      FROM `' . Settings::db_table_infected_crew_memberof. '` 
                                      WHERE `userId` = \'' . $mysql->real_escape_string($userId) . '\' 
                                      AND `teamId` != \'0\'');
                                      
        $row = $result->fetch_array();
        
        $mysql->close();
        
        return $row ? true : false;
    }
    
    /* Return true if user is leader for a team */
    public static function isTeamLeader($userId) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $result = $mysql->query('SELECT `leader` 
                                      FROM `' . Settings::db_table_infected_crew_teams . '` 
                                      WHERE `leader` = \'' . $mysql->real_escape_string($userId) . '\';');
                                      
        $row = $result->fetch_array();
        
        $mysql->close();
        
        return $row ? true : false;
    }
    
    /* Sets the users team */
    public static function changeTeamForUser($user, $group, $team) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        if ($user->isGroupMember()) {    
            $mysql->query('UPDATE `' . Settings::db_table_infected_crew_memberof . '` 
                                SET `teamId` = \'' . $mysql->real_escape_string($team->getId()) . '\' 
                                WHERE `userId` = \'' . $mysql->real_escape_string($user->getId()) . '\' 
                                AND `groupId` = \'' . $mysql->real_escape_string($group->getId()) . '\';');    
        }
        
        $mysql->close();
    }
    
    /*
     * Removes a user from a team.
     */
    public static function removeUserFromTeam($user) {
        $mysql = MySQL::open(Settings::db_name_infected_crew);
        
        $mysql->query('UPDATE `' . Settings::db_table_infected_crew_memberof . '` 
                            SET `teamId` = \'0\'
                            WHERE `userId` = \'' . $mysql->real_escape_string($user->getId()) . '\';');    
        
        $mysql->close();
    }
}
?>