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
		$json['message'] = 'Vous n\'êtes pas connecté !';
		echo json_encode($json);
		exit();
	}
	  
	if (!isset($_POST['message']) || empty($_POST['message'])){
		$json['message'] = 'Message vide';
		break ;
	}
	
	$ident = $_SESSION['user']->getIdentifiant();
	$message=$_POST['message'];
	
	$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");	
	$req = $connexion->prepare("insert into messages values (:identifiant, :texte)");
	$req->bindParam(':identifiant',$ident);
	$req->bindParam(':texte',$message);
	
	$result = $req->execute();
		
	if(!$result){
		$json['message'] = 'Erreur de sauvegarde';
		break;
	}
	
	$json['status'] = 'ok';
	unset($json['message']);
	$json['args']['source'] = $message;
	$json['result'] = $ident;
	break;
}
echo json_encode($json);
			
?>
