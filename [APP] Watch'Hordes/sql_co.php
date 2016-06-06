<?php
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
?>