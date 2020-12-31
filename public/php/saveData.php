<?php

$postData = json_decode(file_get_contents("php://input"));
$validuser = true;
$call = $postData->call;
$participant = isset($postData->participant) ? $postData->participant : 0;
$items = isset($postData->items) ? $postData->items : 0;

switch ($call) {
	case "login":
		echo json_encode(writeData($mysqli, $participant, $items));
		break;
	default:
		# code...
		break;
}

function writeData($mysqli, $participant, $items)
{
	$sql = $mysqli->prepare("INSERT INTO `Lukas` (`vp`, `session`, `items`) VALUES (?, ?, ?)");
	$sql->bind_param("sss", $participant->code, $participant->session, $items);
	$sql->execute();
	if ($sql->affected_rows > 0) {
		echo "1";
	} else {
		exit("0");
	}
	$sql->close();
}
