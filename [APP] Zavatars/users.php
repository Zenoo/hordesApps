<?php

	if(isset($_POST['userRequest']) || isset($_POST['updateCreas'])){
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
		if(isset($_POST['userRequest'])){
			if($_POST['userRequest'] > 0) $sql='SELECT * FROM users WHERE twinId = ' . $_POST['userRequest'] . ';';
			else $sql='SELECT * FROM users ORDER BY totalCreations DESC;'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		elseif(isset($_POST['updateCreas'])){
			$sql='SELECT * FROM users WHERE twinId = ' . $_POST['userId'] . ';';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			
			$toAdd='';
			if((int)$res[0]['totalCreations'] == 4) $toAdd = ', creator = 1';
			$sql='UPDATE users SET totalCreations = totalCreations + 1, reput = reput + 1' . $toAdd . ' WHERE twinId = ' . $_POST['userId'] . ';'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
	}

?>