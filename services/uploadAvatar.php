<?php 
require('../php/classes/Identifiant.class.php');

session_start();
header('Content-Type: application/json');

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

function redimensionne($ext, $from, $to, $format){
	switch ($ext)
		{
			case 'gif':
				$source = imagecreatefromgif($from);
				break;
			case 'png':
				$source = imagecreatefrompng($from);
				break;
			default:
				$source = imagecreatefromjpeg($from);
		}
	$sizeOriginal = getimagesize($from);
	
	if($sizeOriginal[0]==$sizeOriginal[1]){
		$new = imagecreatetruecolor($format, $format);
		imagecopyresized($new, $source, 0, 0, 0, 0, $format, $format, $sizeOriginal[1], $sizeOriginal[0]);
	}
	else {
		$taille = min($sizeOriginal[0], $sizeOriginal[1]);  
		$Aux = imagecreatetruecolor($taille, $taille);
		$new = imagecreatetruecolor($format, $format); 
		
		imagecopy($Aux, $source, $taille/2-($sizeOriginal[0]/2), $taille/2-($sizeOriginal[1]/2), 0 , 0 , $sizeOriginal[0] , $sizeOriginal[1]);   
		imagecopyresized($new , $Aux, 0,0,0,0, $format, $format, $taille, $taille);
		imagedestroy($Aux); 
	}
	imagepng($new, $to); 
	imagedestroy($new);  
}
	
		
while (true) {
	if(!isset($_FILES['img'])){
		$json['message'] = 'Image non inserée !';
		break;
	}
	if ($_FILES['img']['error'] != 0){
		$json['message'] = 'Erreur lors de l\'enregistrement de l\image' ;
		break;
	}
	if ($_FILES['img']['size'] >= 3145728 ){
		$json['message'] = 'Image est trop grande !' ;
		break ; 
	}
	
	$user = $_SESSION['user'] ;
	$ident = $user->getIdentifiant() ;
	$imageAlreadyCustom = $user->getAvatar();
	
	$imageNew = $_FILES['img']['name'];
	
	$Ext = explode('.', $imageNew);
	$Ext = strtolower($Ext[count($Ext)-1]);
	
	
	if (/*$Ext != 'jpg' &&*/ $Ext != 'jpeg' && $Ext != 'gif' && $Ext != 'png'){
		$json['message'] = 'Extension inconnue !'.$Ext ;
		break;
	}
	
	move_uploaded_file($_FILES['img']['tmp_name'], "../images/avatar/$ident-original.$Ext");
	if ($imageAlreadyCustom=='custom'){
		imagedestroy("../images/avatar/$ident-256.png");
		imagedestroy("../images/avatar/$ident-48.png");
	}
	redimensionne($Ext, "../images/avatar/$ident-original.$Ext", "../images/avatar/$ident-256.png", 256); 
	redimensionne($Ext, "../images/avatar/$ident-original.$Ext", "../images/avatar/$ident-48.png", 48);   
	
	chmod("../images/avatar/$ident-256.png", 0777);
	chmod("../images/avatar/$ident-48.png", 0777); 
	unlink("../images/avatar/$ident-original.$Ext");

	$_SESSION['user']->setAvatar();

	
	$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");
	$req = $connexion->prepare("update users set avatar='custom' where identifiant=:id");
	$req->bindParam('id', $ident);
	$req->execute();
	
	$json['args'] = $_FILES['image'];
	unset($json['args']['tmp_name']);	
	unset($json['message']);
	$json['result']=true;
	$json['status'] = 'ok';
	break;
}
echo json_encode($json);
	
?>

