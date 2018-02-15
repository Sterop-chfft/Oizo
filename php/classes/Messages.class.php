<?php

class Messages { 
	
	private $identifiant;
	private $texte;
	private $date;
	private $id;


	public function __construct($id){		
		$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");			
		$req = $connexion->prepare("select * from messages where id=:id");
		$req->bindParam(':id', $id);				
		$req->execute();
		$req->setFetchMode(PDO::FETCH_ASSOC);
				
		$data = $req->fetch();
		
		$this->identifiant = $data['identifiant'];
		$this->texte = $data['texte'];
		$this->date = $data['date'];
		$this->id = $id;
	}
	
	
  public function getIdentifiant(){
    return $this->identifiant;
  }
  public function getTexte(){
    return $this->texte;
  }
  public function getDate(){
    return $this->date;
  }
  public function getId(){
    return $this->id;
  }
  
  
	public function displayMessage(){
		$id = $this->getId() ;
		$texte = $this->getTexte() ;
		$date = $this->getDate() ;
		$identifiant = $this->getIdentifiant() ;
		$user = getUserFromId($identifiant);
		$nom = $user->getNom();
		$avatar = $user->getAvatar48() ;
		$toDisplay .= "	<div class=\"msg\" id=\"$id\" data-nom=\"$nom\" data-id=\"$identifiant\">
							<p>Message de $identifiant (<a href=\"profil.php?profil=$identifiant\">@$nom</a>) $avatar</p>
							<p>\" $texte \"</p>
							<p>$date</p>
							
						</div> ";
		return $toDisplay ;
	}
	
	public function displayMessageNone(){
		$id = $this->getId() ;
		$texte = $this->getTexte() ;
		$date = $this->getDate() ;
		$identifiant = $this->getIdentifiant() ;
		$user = getUserFromId($identifiant);
		$nom = $user->getNom();
		$avatar = $user->getAvatar48() ;
		$toDisplay .= "	<div class=\"msg\" id=\"$id\" data-nom=\"$nom\" data-id=\"$identifiant\" style=\"display: none;\">
							<p>Message de $identifiant (<a href=\"profil.php?profil=$identifiant\">@$nom</a>) $avatar</p>
							<p>\" $texte \"</p>
							<p>$date</p>
							
						</div> ";
		return $toDisplay ;
	}
  
	
	
}


?>
