<?php
require_once 'session.php';
require_once 'settings.php';
require_once 'mysql.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

class Team {
	private $id;
	private $groupId;
	private $name;
	private $title;
	private $description;
	private $leader;
	
	public function __construct($id, $groupId, $name, $title, $description, $leader) {
		$this->id = $id;
		$this->groupId = $groupId;
		$this->name = $name;
		$this->title = $title;
		$this->description = $description;
		$this->leader = $leader;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getGroup() {
		return GroupHandler::getGroup($this->groupId);
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getleader() {
		return UserHandler::getUser($this->getLeader());
	}
	
	/* Returns an array of users that are members of this group */
	public function getMembers() {
		return TeamHandler::getMembers($this->groupId, $this->getId());
	}
	
	public function displayWithInfo() {
		echo '<div class="crewParagraph">';
			echo '<h1>' . $this->getTitle() . '</h1>';
			echo $this->getDescription();
		echo '</div>';
		
		if (Session::isAuthenticated()) {
			$this->display();
		}
	}
	
	public function display() {
		$user = Session::getCurrentUser();
		
		if ($user->isGroupMember()) {
			$memberList = $this->getMembers();
			
			$index = 0;
			
			foreach ($memberList as $member) {
				echo '<div class="';
					
					if ($index % 2 == 0) {
						echo 'crewEntryLeft';
					} else {
						echo 'crewEntryRight';
					}
					
				echo '">';
					echo '<a href="index.php?page=profile&id=' . $member->getId() . '"><img src="' . $member->getAvatar()->getFile() . '" width="146" height="110" style="float: right;"></a>';
					echo '<p>Navn: ' . $member->getFirstname() . ' "' . $member->getNickname() . '" ' . $member->getLastname() . '<br>';
					echo 'Stilling: ' . $member->getPosition() . '<br>';
					echo 'Telefon: ' . $member->getPhone() . '<br>';
					echo 'E-post: ' . $member->getEmail() . '</p>';
				echo '</div>';
					
				$index++;
			}
		}
	}
}
?>