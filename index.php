<?php
require_once('php/classes/Identifiant.class.php');
require_once('php/classes/Messages.class.php');
require_once('php/fonctions.php');

session_start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta charset="UTF-8" />
	<title>Rézozio</title>
       <link rel="stylesheet" href="css/index.css" />
       	<script type="text/javascript" src="js/fonction.js"></script>
		<script type="text/javascript" src="js/fonction2.js"></script>
</head>
<body>
<main>
	<div id='accesProfil'>
		<?php
			if (isset($_SESSION['user'])){
				require_once('php/html/logoutPage.html');
				echo "<button><a href='profil.php'>Mon profil</a></button>" ;
				require_once('php/html/postMessage.html');

			}
			else{	
				require_once('php/html/inscriptionPage.html');
				require_once('php/html/loginPage.html');
			}
		?>
	<br>
	</div> 	
	<div id='divMessage'>
		<?php			
			$jsonMessage = json_decode(file_get_contents('http://webtp.fil.univ-lille1.fr/~soltysiak/Projet2/services/findMessages.php?count=15'.(isset($_SESSION['user']) ? '&follower='.$_SESSION['user']->getIdentifiant().'&mentioned='.$_SESSION['user']->getIdentifiant() : '')), true);
			
			foreach ($jsonMessage['result']['list'] as $message){
					$msg = new Messages($message['id']);
					echo $msg->displayMessage();
			}

		?>
		<div id='noResultMessage'></div>
	</div> 			
	<div id="findUser">
		<input type='text' id='searchUser' name='searchUser' onkeyup='searchUser()'/>
		<?php	
			$jsonUser = json_decode(file_get_contents('http://webtp.fil.univ-lille1.fr/~soltysiak/Projet2/services/findUsers.php'), true);
			echo tabId($jsonUser['result']);
		?>
		<div id='noResultProfil'></div>
	</div>		
	
</main>	
</body>
</html>
