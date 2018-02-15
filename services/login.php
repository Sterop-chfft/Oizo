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
			if (isset($_SESSION['user'])){
				$json['message'] = 'Déja connecté !';
				break;
			}
			if (!isset($_POST['ident'])  ||  $_POST['ident'] == ''){
				$json['message'] = 'Identifiant vide !';
				break;
			}
			if (!isset($_POST['password'])  ||  $_POST['password'] == ''){
				$json['message'] = 'Password vide !';
				break;
			}
			
			$ident = $_POST['ident'];
			$pwd = $_POST['password'];
			
			$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");			
			$req = $connexion->prepare("select * from users where identifiant=:id and password=:pwd");			
			$req->bindParam(':id', $ident);
			$req->bindParam(':pwd', $pwd);	
			$req->execute();
			$req->setFetchMode(PDO::FETCH_ASSOC);	
			$data = $req->fetch();	
			
			
			if($data == false){
				$json['message'] = 'Combinaison identifiant/password incorrect';
				break;
			}	
			$avat = $data['avatar'] ;
			$user = new Identifiant($ident, $data['nom'], $data['presentation'], '',  $data['avatar']);
			$_SESSION['user'] = $user;
			
			$json['status'] = 'ok';
			unset($json['message']);
			$json['args']['login'] = $ident;
			$json['result'] = $avat;
							
			break;
			
		}
	echo json_encode($json);
?>
