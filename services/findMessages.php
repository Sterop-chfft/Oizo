<?php 
require('../php/classes/Identifiant.class.php');
require('../php/fonctions.php');

header('Content-Type: application/json');

$json = array(
	'status' => 'error',
	'message' => '',
	'args' => array(),
	'result' => array('list' => array(),'hasMore' => false)
);

$author    = (isset($_REQUEST['author'])    ? $_REQUEST['author']    : null);
$follower  = (isset($_REQUEST['follower'])  ? $_REQUEST['follower']  : null);
$mentioned = (isset($_REQUEST['mentioned']) ? $_REQUEST['mentioned'] : null);
$before    = (isset($_REQUEST['before'])    ? $_REQUEST['before']   : null);
$after     = (isset($_REQUEST['after'])     ? $_REQUEST['after']     : null);
$count     = (isset($_REQUEST['count']) && intval($_REQUEST['count']) > 0 ? intval($_REQUEST['count']) : 15);


$params = array();

	$requete = 'select distinct m.id, m.identifiant, m.texte, m.date from messages m';
	
	if (isset($follower))
		$requete .= ', follow f';
	
	if (isset($author) || isset($follower) || isset($mentioned) || isset($before) || isset($after))
		$requete .= ' WHERE';
		
	
	if (isset($author)){
		$requete .= ' m.identifiant=:author AND';
		$params['author'] = $author;
		$json['args']['author'] = $author;
	}
	
	if (isset($follower)){
		$requete .= ' f.identifiant = :ident  and m.identifiant=f.followed OR';
		$params['ident'] = $follower;
		$json['args']['follower'] = $follower;
	}
	
	if (isset($mentioned)){
		$mentionedBis = "@$mentioned%" ;
		$requete .= ' m.texte like \''.$mentionedBis.'\' AND';
		//$params['mention'] = $mentionedBis;
		$json['args']['mentioned'] = $mentioned;
	}
	
	if (isset($before)){
		$requete .= ' m.id < :before AND';
		$params['before'] = $before;
		$json['args']['before'] = $before;
	}
	
	if (isset($after)){
		$requete .= ' m.id > :after AND';
		$params['after'] = $after;
		$json['args']['after'] = $after;
	}
	
	if (preg_match('#.+ AND#', $requete))
		$requete = substr($requete, 0, -4);
	
	$connexion= new PDO("pgsql:host=localhost;dbname=soltysiak","soltysiak","s160692s");	
	
	$req = $connexion->prepare($requete . ' ORDER BY id DESC');	
	$req->execute($params);
	
	$i = 0;
	while (($data = $req->fetch()) && $i++ < $count){
		$user = getUserFromId($data['identifiant']);
		
		$line = array(
			'id' => $data['id'],
			'author' => $data['identifiant'],
			'name' => $user->getNom(),
			'content' => $data['texte'],
			'date' => $data['date'],);
			
		$json['result']['list'][] = $line;
	}
	
	
	$json['status'] = 'ok';
	$json['result']['hasMore'] = ($data != false);
	unset($json['message']);
	
echo json_encode($json);


?>
