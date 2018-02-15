<?php
	

class Identifiant { 
	
	private $identifiant;
	private $nom;
	private $avatar;
	private $presentation;
	private $password;

	public function __construct($identifiant,$nom, $presentation, $password, $avatar='default'){
		if($presentation=='default'){
			$presentation = 'Nouveau sur Rézozio !';
		}
		$this->identifiant = $identifiant;
		$this->nom = $nom;
		$this->avatar = $avatar;
		$this->presentation = $presentation;
		$this->password = $password ;
	}
	
	
  public function getIdentifiant(){
    return $this->identifiant;
  }
  public function getNom(){
    return $this->nom;
  }
  public function getAvatar(){
    return $this->avatar;
  }
  public function getPresentation(){
    return $this->presentation;
  }
  public function setAvatar($custom='custom'){
	  $this->avatar = 'custom' ;
  }
  
  
	public function getImageAvatar(){
	  return '<img src="'.findFile('images/avatar/').($this->getAvatar() == 'default' ? 'default.png' : $this->getIdentifiant().'.png').'" />';
	}	
	public function getImageAvatar48(){
	  return '<img src="'.findFile('images/avatar/').($this->getAvatar() == 'default' ? 'default48.png' : $this->getIdentifiant().'48.png').'" />';
	}	
	
	public function saveNewUser(){
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");
		$id=$this->getIdentifiant();
		$nom=$this->nom;
		$presentation=$this->presentation;
		$pwd=$this->password;
		$avatar=$this->avatar;
		
		$req = $connexion->prepare("insert into users values (:id, :nom, :presentation, :avatar, :pwd)");
		$req->bindParam(':id', $id);
		$req->bindParam(':nom', $nom);
		$req->bindParam(':presentation', $presentation);
		$req->bindParam(':avatar', $avatar);
		$req->bindParam(':pwd', $pwd);
		
		$req->execute();
	}
	
	public function alreadyUsed(){
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");
		$id=$this->getIdentifiant();
		$req = $connexion->prepare("select identifiant from users where identifiant=:id");
		$req->bindParam('id', $id);
		$req->execute();
		
		$data = $req->fetch() ;
			
		return ($data!=false)  ;
	}
	
	public function getAvatar256(){
		$ident=  $this->getIdentifiant() ;
			if ($this->getAvatar()=='default'){
				return "<img src=images/avatar/default-256.png />";
			}
			else{
				return "<img src=images/avatar/$ident-256.png />";
			}
	}
		
		
	public function getAvatar48(){
			$ident=  $this->getIdentifiant() ;
			if ($this->getAvatar()=='default'){
				return "<img src=images/avatar/default-48.png />";
			}
			else{
				return "<img src=images/avatar/$ident-48.png />";
			}
	}
	
	
	public function setProfileUser($colonne, $newValue){
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");
		
		$req = $connexion->prepare("update users set $colonne=:newValue where identifiant=:id");
		$req->bindParam('id', $this->getIdentifiant());
		$req->bindParam('newValue', $newValue);
		$req->execute();
		
		switch($colonne){
			case 'nom' :
				$this->nom = $newValue ;
				break;
			case 'presentation' :
				$this->presentation = $newValue ;
				break;
			default :
				$this->password = $newValue ;
		}			
	}	
	

	
	
	public function displayMonProfil (){
		$ident = $this->getIdentifiant() ;
		$nom = $this->getNom() ;
		$presentation = $this->getPresentation() ;
		$avatar = $this->getAvatar256();
				
		$toDisplay = " 	<div id='profil'> 
							<h2>Bonjour $ident (@$nom)</h2>
							<p>Bienvenue sur la page de votre profil !</p>
							<p>Actuellement, votre présentation est :</p>	
							<p>\"$presentation\"</p>
							<span>$avatar</span>
						</div>" ;
						
		//Affichage de la div des followed
		$toDisplay .= "<div id='followed'><h2>Vos Follows</h2>" ;
		$listFollowed = $this->getFollowed() ;
		foreach($listFollowed as $followed){ 
			$idFollowed = $followed->getIdentifiant() ;
			$nomFollowed = $followed->getNom() ; 
			$avatarFollowed = $followed->getAvatar48() ;
			$toDisplay .= "	<div id='followedUser'>
								<p>$idFollowed</p>
								<span>$avatarFollowed</span>
								<button><a href=\"profil.php?profil=$idFollowed\">Profil</a></button>
							</div>" ; 
		}
		$toDisplay .= "</div>" ;
		
		//affichage de la div des follower
		$toDisplay .= "<div id='follower'><h2>Vos Follower</h2>" ;
		$listFollower = $this->getFollower() ;
		foreach($listFollower as $follower){ 
			$idFollower = $follower->getIdentifiant() ;
			$nomFollower = $follower->getNom() ; 
			$avatarFollower = $follower->getAvatar48() ;
			$toDisplay .= "	<div id='followerUser'>
								<p>$idFollower</p>
								<span>$avatarFollower</span>
								<button><a href=\"profil.php?profil=$idFollower\">Profil</a></button>
							</div>" ; 
		}	
		$toDisplay .= "</div>" ;	
		return $toDisplay ;		
	}
	
	public function displayProfil ($followed){
		$ident = $this->getIdentifiant() ;
		$nom = $this->getNom() ;
		$presentation = $this->getPresentation() ;
		$avatar = $this->getAvatar256();
				
		$toDisplay = " 	<div id='profil' data-identprofil=\"$ident\"> 
							<h2>Profil de $ident (@$nom)</h2>
							<p>\"$presentation\"</p>
							<span>$avatar</span> ";
			if(!$this->isFollowed($followed)){
					$toDisplay .="<button id='submitFollow' name='submitFollow' onclick='followOnClick()'>Follow</button>
								</div>";
			}
			else{
					$toDisplay .="<button id='submitFollow' name='submitFollow' onclick='unfollowOnClick()'>Unfollow</button>
								</div>" ;
			}
		return $toDisplay ;		
	}
	
	public function displayProfilNonCo (){
		$ident = $this->getIdentifiant() ;
		$nom = $this->getNom() ;
		$presentation = $this->getPresentation() ;
		$avatar = $this->getAvatar256();
				
		$toDisplay = " 	<div id='profil' data-identprofil=\"$ident\"> 
							<h2>Profil de $ident (@$nom)</h2>
							<p>\"$presentation\"</p>
							<span>$avatar</span> ";
		return $toDisplay ;		
	}
	
	
	public function isFollowed($followerId){
		$fd=$this->getIdentifiant();
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");
		$req = $connexion->prepare("select * from follow where identifiant=:id and followed=:fd");
		$req->bindParam('id', $followerId);
		$req->bindParam('fd', $fd);
		$req->execute();
		
		$data = $req->fetch() ;
			
		return ($data!=false)  ;
	}
	
	public function getFollowed(){
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");	
		$req = $connexion->prepare("select followed from follow where identifiant=:id");
		$req->bindParam('id',$this->getIdentifiant());
		$req->execute();
		$req->setFetchMode(PDO::FETCH_ASSOC);		
		
		$listFollowed = array() ;
		
		while ($data = $req->fetch()){
			array_push($listFollowed, getUserFromId($data['followed']));
		}
		return $listFollowed ;
	}
	
	public function getFollower(){
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");	
		$req = $connexion->prepare("select identifiant from follow where followed=:id");
		$req->bindParam('id',$this->getIdentifiant());
		$req->execute();
		$req->setFetchMode(PDO::FETCH_ASSOC);	
		
		$listFollower = array() ;
		
		while ($data = $req->fetch()){
			array_push($listFollower, getUserFromId($data['identifiant']));
		}
		return $listFollower ;
	}
	
	
}


?>
