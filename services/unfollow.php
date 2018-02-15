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

while (true){
	if (!isset($_SESSION['user'])){
		$json['message'] = 'Vous devez être connecté pour pouvoir utiliser ce service !';
		echo json_encode($json);
		exit();
	}
	
	if (!isset($_POST['identToUnfollow'])){
		$json['message'] = 'Il n\y a personne a follow';
		break ;
	}
	
	$identUser = $_SESSION['user']->getIdentifiant() ;
	$identToUnfollow = $_POST['identToUnfollow'] ;
	
	$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");	
	$req = $connexion->prepare("delete from follow where identifiant=:ident and followed=:followed");			
	$req->bindParam(':ident', $identUser);
	$req->bindParam(':followed', $identToUnfollow);	
	$data = $req->execute();
	
	if(!data){
		$json['message'] = 'Erreur';
		break ;
	}
	
	$json['status'] = 'ok';
	unset($json['message']);
	$json['args']['followed'] = $identToUnfollow;
	$json['result'] = $identUser;
	break;
}
echo json_encode($json);
		
?>
