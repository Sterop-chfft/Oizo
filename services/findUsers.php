<?php

require('../php/classes/Identifiant.class.php');

header('Content-Type: application/json');

$json = array(
	'status' => 'error',
	'message' => '',
	'args' => array(),
	'result' => array(),
);
	$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");			
	$req = $connexion->prepare("select identifiant from users");	
	$req->execute();
	$req->setFetchMode(PDO::FETCH_ASSOC);
	while( $data = $req->fetch()){
	
	array_push($json['result'], $data['identifiant']);
		
	}
		
	$json['status'] = 'ok';
	unset($json['message']);
	echo json_encode($json);
?>
