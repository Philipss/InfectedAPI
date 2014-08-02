<?php
	require_once 'session.php';
	require_once 'handlers/rowhandler.php';
	require_once 'handlers/seatmaphandler.php';

	$result = false;
	$message = null;
	$id = null;

	if (Session::isAuthenticated()) {
		$user = Session::getCurrentUser();
		if ($user->hasPermission('admin.seatmap') ||
			$user->hasPermission('admin')) {
			if(isset($_GET["row"]))
			{
				$row = RowHandler::getRow($_GET["row"]);
				if(isset($row))
				{
					if(RowHandler::safeToDelete($row))
					{
						RowHandler::deleteRow($row);
						$result = true;
					}
					else
					{
						$message = "Noen sitter på raden du prøver å slette!";
					}
				}
				else
				{
					$message = "Raden eksisterer ikke!";
				}
			}
			else
			{
				$message = "Raden er ikke satt!";
			}
		}
		else
		{
			$message = "Du har ikke tillatelse til å legge til en rad!";
		}
	}
	else
	{
		$message = "Du må logge inn først!";
	}

	if($result)
	{
		echo json_encode(array('result' => $result));
	}
	else
	{
		echo json_encode(array('result' => $result, 'message' => $message));
	}
?>