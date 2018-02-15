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

if (!isset($_SESSION['user'])){
		$json['message'] = 'Vous devez être connecté pour pouvoir utiliser ce service !';
		echo json_encode($json);
		exit; 
}

$user = $_SESSION['user'] ;

while (true) {
	$somethingsChange = false ;
		if (isset($_POST['nomProfile'])  &&  !empty($_POST['nomProfile'])){
			$newNom = $_POST['nomProfile'] ;
			$user->setProfileUser('nom', $newNom);
			$_SESSION['user'] = $user ;
			$somethingsChange = true ;
		}
		if (isset($_POST['passwordProfile']) && !empty($_POST['passwordProfile'])){
			$newPassword = $_POST['passwordProfile'];
			$user->setProfileUser('password', $newPassword);
			$_SESSION['user'] = $user ;
			$somethingsChange = true ;
		}
		if (isset($_POST['presentationProfile'])  &&  !empty($_POST['presentationProfile'])){
			$newPresentation = $_POST['presentationProfile'];
			$user->setProfileUser('presentation', $newPresentation);
			$_SESSION['user'] = $user ;
			$somethingsChange = true ;
		}
		if ($somethingsChange==false){
			$json['message'] = 'Rien a sauvegarder..';
			break;
		}
		
		$json['status'] = 'ok' ;
		unset($json['message']);
		$json['args']['name'] = $user->getIdentifiant();
		$json['args']['description'] = $user->getPresentation();
		$json['result'] = array(
			'identifiant' => $user->getIdentifiant(),
			'nom' =>  $user->getNom(),
			'presentation' => $user->getPresentation());
		
		break;
	}
echo json_encode($json);	
		
?>
