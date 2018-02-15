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

$size = (isset($_REQUEST['size']) && $_REQUEST['size'] == 'large' ? 'large' : 'small');
$ident = $_SESSION['user']->getIdentifiant();

if ($size=='large'){
	echo "<img images/avatar/$ident-256.png" ;
}
else{
	echo "images/avatar/$ident-48.png" ;
}

?>



