<?php

//Ici, renseignez la xKey et les parametres personnalises du message
$xKey = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

$type = 'mailCampaign';	//Code pour envoyer une campagne de mail
$name = 'MailCampaignFromApi (php)';	//Nom de l'action
$description = 'MailCampaignFromApi (php)';	//Description de l'action

$informationFolder = 0123;	//Id du dossier dans lequel vous voulez mettre l'action ('null' pour aucun dossier)
$informationCategory = 0123;	//Id de la categorie de campagne (Infos compte > Parametrage > Categories de campagnes)

$contentHeadersFromPrefix = 'prefix';	//Adresse expeditice
$contentHeadersFromLabel = 'label';	//Libelle expediteur
$contentHeadersReply = 'address@reply.com'; //Adresse de reponse

$contentSubject = 'Subject of the message';	//Objet du mail
$contentHTML = 'Html message';	//Message HTML
$contentText = 'Text message';	//Message texte

$idTestSegment = 0123;	//Id du segment de test
$idSelectSegment = array(0123);	//Ids des segments selectionnes


//On trouve l'addresse pour la requete
$url = 'http://v8.mailperformance.com/actions';

//Creation du Json du message
$arr = array(
	'type' => $type,
	'name' => $name,
	'description' => $description,
	'informations' => array(
		'folder' => $informationFolder,
		'category' => $informationCategory),
	'scheduler' => array(
		'type' => 'asap',	//Envoie : immediat = 'asap' / Date = 'scheduled'
		//'startDate' => '2015-07-27T08:15:00Z',	//Si type = 'scheduled' sinon a enlever
		'segments' => array(
			'selected' => $idSelectSegment)),	//Les segments selectionnes
	'content' => array(
		'headers' => array(
			'from' => array(
				'prefix' => $contentHeadersFromPrefix,
				'label' => $contentHeadersFromLabel),
			'reply' => $contentHeadersReply),
		'subject' => $contentSubject, 
		'html' => $contentHTML, 
		'text' => $contentText));
		

//On affiche le message
$message = json_encode($arr);
echo $message . "\n";

//Connexion
$con = connect($url, $xKey, $message);

$result = $con['result'];
$info = $con['info'];
$req = $con['req'];

//Verification des reponses
if ($info['http_code'] != 200)
{
	echo 'Error : ' . $info['http_code'];
}
else
{
	//L'action a bien ete cree
	echo 'Action : ' . $name . " created.\n\n";
	
	//On recupere l'id de l'action
	$tab = json_decode($result, TRUE);
	$idAction = $tab['id'];
	
	//On valide l'action
	$url = 'http://v8.mailperformance.com/actions/' . $idAction . '/validation';
	
	//Creation du Json du message pour le test
	$arr = array(
	'fortest' => true,	//Phase de test
	'campaignAnalyser' => false,	//Campaign Analyzer : 'true' = oui / 'false' = non
	'testSegments' => array($idTestSegment),	//Les Ids des differents segments de tests
	'mediaForTest' => null,	//Rediriger tous les tests vers une seule adresse ('null' pour aucune adresse)
	'textandHtml' => false,	//Envoyer la version texte et la version html : 'true' = oui / 'false' = non
	'comments' => null);	//Commentaire ('null' pour aucuns commentaires)
	
	//On affiche le Json
	$message = json_encode($arr);
	echo $message . "\n";
	
	//Connexion
	$con = connect($url, $xKey, $message);
	$result = $con['result'];
	$info = $con['info'];
	$req = $con['req'];
	
	//On attend que la phase de test soit terminee (peut prendre plusieurs minutes)
	$actionState = waitForState($idAction, $xKey);
	
	//On verifie les reponses
	if ($info['http_code'] != 204 || $actionState == 20)
	{
		if ($actionState == 20)
			echo 'Error : the test failed';
		else
			echo 'Error : ' . $info['http_code'];
	}
	else
	{
		//La phase de test a reussi
		echo 'The action ' . $name . " is tested.\n\n";
		
		
		//Creation du Json du message pour la validation
		$arr = array(
		'fortest' => false,	//Phase de validation
		'campaignAnalyser' => false,	//Campaign Analyzer : 'true' = oui / 'false' = non
		'testSegments' => array($idTestSegment),	//Les Ids des differents segments de tests
		'mediaForTest' => null,	//Rediriger tous les tests vers une seule adresse ('null' pour aucune adresse)
		'textandHtml' => false,	//Envoyer la version texte et la version html : 'true' = oui / 'false' = non
		'comments' => null);	//Commentaire ('null' pour aucuns commentaires)
		
		//Onaffiche le Json
		$message = json_encode($arr);
		echo $message . "\n";
		
		//Connexion
		$con = connect($url, $xKey, $message);
		
		$result = $con['result'];
		$info = $con['info'];
		$req = $con['req'];
		
		//On verifie les reponses
		if ($info['http_code'] != 204)
		{
			echo 'Error : ' . $info['http_code'];
		}
		else
		{
			//La phase de validation a reussi
			echo 'The action ' . $name . "is validated.\n\n";
		}
	}
}
curl_close($req);



//Fonctions -----



//Utilisation de cURL pour remplir les requetes
function startCurlInit($url)
{
	$init = curl_init();
	curl_setopt($init, CURLOPT_URL, $url);
	curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
	return ($init);
}

//Fonction de connexion
function connect($url, $xKey, $message)
{
	//On remplit la requete
	$req = startCurlInit($url);
	curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'POST');

	//Mise en place du xKey et des options
	curl_setopt($req, CURLOPT_HTTPHEADER, array(
	'X-Key: ' . $xKey,
	'Content-Type: application/json',
	'Content-Length: ' . strlen($message)));
	curl_setopt($req, CURLOPT_POSTFIELDS, $message);

	//Execution de la requete
	$result = curl_exec($req);

	//Verification des reponses
	$info = curl_getinfo($req);
	
	return (array('result' => $result, 'info' => $info, 'req' => $req));
}
	
//Fonction qui verifie que la phase de test soit fini (peut prendre plusieurs minutes)
function waitForState($idAction, $xKey)
{
	$actionState = 30;
	
	while ($actionState != 38 && $actionState != 20)
	{
		//On attend 20 secondes
		echo "Wait 20sec...\n";
		sleep(20);
	
		//On trouve l'adresse pour la requete
		$url = 'http://v8.mailperformance.com/actions/' . $idAction;
	
		//On remplit la requete 'GET'
		$req = startCurlInit($url);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, 'GET');
	
		//Mise en place du xKey et des options
		curl_setopt($req, CURLOPT_HTTPHEADER, array(
		'X-Key: ' . $xKey,
		'Content-Type: application/json'));
	
		//Execution de la requete
		$result = curl_exec($req);
	
		$tab = json_decode($result, TRUE);
		$actionState = $tab['informations']['state'];
	}
	return ($actionState);
}

?>