<?php

	require('../php/classes/Identifiant.class.php');

	header('Content-Type: application/json');
	
	$json = array(
		'status' => 'error',
		'message' => '',
		'args' => array(),
		'result' => null
	);
	
	while(true){
		if (!isset($_POST['ident'])  ||  $_POST['ident'] == ''){
			$json['message'] = 'Identifiant vide !';
			break;
		}
		if (!preg_match('#^[a-zA-Z0-9_]+$#', $_POST['ident'])){
			$json['message'] = 'Identifiant incorrect !';
			break;
		}
		if (!isset($_POST['name'])  ||  $_POST['name'] == ''){
			$json['message'] = 'Nom vide !';
			break;
		}
		if (!isset($_POST['password']) || $_POST['password'] == ''){
			$json['message'] = 'Password vide !';
			break;
		}
		
		$ident = $_POST['ident'];
		$pwd = $_POST['password'];
		$name = $_POST['name'];
						
		$json['args']['ident'] = $ident;
		$json['args']['name'] = $name;
						
		$user = new Identifiant($ident, $name, 'default', $pwd);
						
		if($user->alreadyUsed()){
			$json['message'] = 'Identifiant already used';
			break;
		}
						
		$user->saveNewUser();
						
		unset($json['message']);
		$json['status'] = 'ok';
		$json['result'] = array('ident' => $user->getIdentifiant(),'name' => $user->getNom());
						
		break;
	}
	echo json_encode($json);

	
?>
