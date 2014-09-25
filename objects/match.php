<?php
require_once 'handlers/matchhandler.php';
require_once 'handlers/clanhandler.php';
require_once 'mysql.php';
require_once 'settings.php';
class Match {
	const STATE_READYCHECK = 0;
	const STATE_CUSTOM_PREGAME = 1;
	const STATE_JOIN_GAME = 2;

	private $id;
	private $scheduledTime;
	private $connectDetails;
	private $winner;
	private $state;

	public function __construct($id, $scheduledTime, $connectDetails, $winner, $state) {
		$this->id = $id;
		$this->scheduledTime = $scheduledTime;
		$this->connectDetails = $connectDetails;
		$this->winner = $winner;
		$this->state = $state;
	}

	public function getId() {
		return $this->id;
	}

	public function getScheduledTime() {
		return $this->scheduledTime;
	}

	public function getConnectDetails() {
		return $this->connectDetails;
	}

	public function getWinner() {
		return $this->winner;
	}

	public function getState() {
		return $this->state;
	}

	public function isParticipant($user) {
		//Get list of clans
		$participants = MatchHandler::getParticipants($this);

		foreach($participants as $clan) {
			if(ClanHandler::isMember($user, $clan)) {
				return true;
			}
		}
	}

	//Returns true if the match can be run
	public function isReady() {
		return MatchHandler::isReady($this);
	}

	public function setState($newState) {
		$con = MySQL::open(Settings::db_name_infected_compo);

		$result = mysqli_query($con, 'UPDATE `' . Settings::db_table_infected_compo_matches . '` SET `state` = ' . $con->real_escape_string($newState) . ' WHERE `id` = ' . $this->id . ';');

		MySQL::close($con);
	}
}
?>