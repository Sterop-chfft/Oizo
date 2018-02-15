<?php
require_once('php/classes/Identifiant.class.php');
require_once('php/fonctions.php');
require_once('php/classes/Messages.class.php');

session_start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta charset="UTF-8" />
	<title>Rézozio, Profil</title>
       <link rel="stylesheet" href="css/index.css" />
	 <script type="text/javascript" src="js/fonction.js"></script>
</head>
<body>
<main>
	<div id="main">
		<button><a href='index.php'>Retour</a></button>
		<?php
			if (isset($_SESSION['user'])){
				if (!isset($_GET['profil'])){
					echo $_SESSION['user']->displayMonProfil();
					require_once('php/html/uploadAvatarPage.html');
					require_once('php/html/setProfilePage.html');
				}
				else {
					echo "<a href='profil.php'>Mon profil</a>" ;
					$identProfil = getUserFromId($_GET['profil']);
					$user = $_SESSION['user'] ;
					echo $identProfil->displayProfil($user->getIdentifiant()) ;
				}
			}
			else{
				if (isset($_GET['profil'])){
				$identProfil = getUserFromId($_GET['profil']);
				echo $identProfil->displayProfilNonCo() ;
				}
				exit();
			}
		?>
		<div id='divMessageProfil'>
		<?php
			if(isset($_GET['profil'])){
				$jsonMessage = json_decode(file_get_contents('http://webtp.fil.univ-lille1.fr/~soltysiak/Projet2/services/findMessages.php?count=15'.(isset($_SESSION['user']) ? '&author='.$_GET['profil'] : '')), true);
				foreach ($jsonMessage['result']['list'] as $message){
						$msg = new Messages($message['id']);
						echo $msg->displayMessage();
				}
			}
			else {
				$jsonMessage = json_decode(file_get_contents('http://webtp.fil.univ-lille1.fr/~soltysiak/Projet2/services/findMessages.php?count=15'.(isset($_SESSION['user']) ? '&author='.$_SESSION['user']->getIdentifiant() : '')), true);
				foreach ($jsonMessage['result']['list'] as $message){
						$msg = new Messages($message['id']);
						echo $msg->displayMessage();
				}
			}
		?>
		</div> 			
	</div>
</main>
</body>
</html>
