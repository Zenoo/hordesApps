<?php

	if(isset($_POST['requestId']) || isset($_POST['messageId'])){
		//GESTION BDD
		$PARAM_hote='HIDDEN';
		$PARAM_nom_bd='HIDDEN';
		$PARAM_utilisateur='HIDDEN'; 
		$PARAM_mdp='HIDDEN';

		try
		{
			$connexion = new PDO('mysql:host='.$PARAM_hote.';
			dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mdp);
			$connexion->exec("set names utf8");

		}
		//gestion des erreurs
		catch(Exception $e)
		{
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
			exit();
			
		}
		
		if(!isset($_POST['messageId'])){
			$sql='SELECT * FROM conv WHERE requestId = ' . $_POST['requestId'] . ' ORDER BY messageId DESC;';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		else{
			$desc = str_replace('"', '\"', $_POST['text']);
			$sql='INSERT INTO conv(requestId,senderId,messageId,creationId,text,timestamp) VALUES(' . $_POST['requestId'] . ', ' . $_POST['senderId'] . ', ' . $_POST['messageId'] . ', ' . $_POST['creationId'] . ', "' . $desc . '", ' . time() . ');';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			
			$sql='SELECT * FROM conv WHERE requestId = ' . $_POST['requestId'] . ' AND messageId = ' . $_POST['messageId'] . ';';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		
	}

?>