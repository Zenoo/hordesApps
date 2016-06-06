<?php

	if(isset($_POST['creatorId']) || isset($_POST['creationId'])){
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
		
		if(isset($_POST['creatorId'])){
			$toAdd=' AND accepted > 0';
			if(isset($_POST['accepted'])) $toAdd=' AND accepted = 1';
			if($_POST['creatorId'] > 0)$sql='SELECT * FROM creations WHERE creatorId = ' . $_POST['creatorId'] . $toAdd . ' ORDER BY id DESC;';
			else $sql='SELECT * FROM creations  ORDER BY id DESC;';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		elseif(isset($_POST['creationId'])){
			if($_POST['creationId'] > 0)$sql='SELECT * FROM creations WHERE id = ' . $_POST['creationId'] . ';';
			else $sql='SELECT * FROM creations;';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
	}

?>