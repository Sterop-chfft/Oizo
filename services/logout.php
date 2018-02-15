<?php

require('../php/classes/Identifiant.class.php');
header('Content-Type: application/json');

session_start();

$json = array(
		'status' => 'error',
		'message' => '',
		'args' => array(),
		'result' => null
	);
	
while(true){
	if (!isset($_SESSION['user'])){
			$json['message'] = 'Non connecté';
			break;
	}
		
	$json['status'] = 'ok';
	unset($json['message']);
	unset($json['args']);
	$json['result'] = $_SESSION['user']->getIdentifiant();
		
	session_destroy();
	unset($_SESSION['user']);
	break;
}
	echo json_encode($json);
?>
