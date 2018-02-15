<?php

function getUserFromId($userId){
	$connexion= new PDO("pgsql:host=localhost;dbname=XXXXX","XXXXX","XXXXX");
	
	$req = $connexion->prepare("select * from users where identifiant=:id");			
	$req->bindParam(':id', $userId);
	$req->execute();
	$req->setFetchMode(PDO::FETCH_ASSOC);	
	$data = $req->fetch();	
	$newUser = new Identifiant($userId, $data['nom'], $data['presentation'], '',  $data['avatar']);
	return $newUser ;
}

function tabId($listId){
	foreach ($listId as $id){
		$user=getUserFromId($id);
		$avatar=$user->getAvatar48();
		$userbis=$user->getNom();
				$display.="	<div class=\"listProfil\" id=\"$id\" data-nom=\"$userbis\" data-id=\"$id\">
								<span>$id (@$userbis) $avatar <a href=\"profil.php?profil=$id\">Profil</a></span>
							</div>";										
			}	
	return $display;
}
?>
