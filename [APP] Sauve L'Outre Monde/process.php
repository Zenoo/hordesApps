<?php
	if(isset($_POST['message'])){
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
		if($_POST['message'] != ''){
			$sql='INSERT INTO live(date_min, date_hour, pseudo, job, message, op) 
				VALUES(' . $_POST['min'] . ', ' . $_POST['heure'] . ', "' . $_POST['pseudo'] . '", "' . $_POST['job'] . '", "' . $_POST['message'] . '", ' . $_POST['op'] . ');';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			
		}
		
	}
?>