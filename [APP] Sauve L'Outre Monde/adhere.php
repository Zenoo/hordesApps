<?php
	if(isset($_POST['name'])){
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
		if($_POST['name'] != ''){
			$sql='INSERT INTO adhere(name) 
				VALUES("' . $_POST['name'] . '");';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			
		}
		
	}
?>