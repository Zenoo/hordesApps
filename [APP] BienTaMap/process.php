<?php
	if(isset($_POST['x'])){
		//GESTION BDD
		$PARAM_hote='front-01-mysql.shpv.fr';
		$PARAM_nom_bd='zenoo829_zooMap';
		$PARAM_utilisateur='zenoo829_su'; 
		$PARAM_mdp='n9q4nzn9q4nz';

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
		
		if($_POST['x'] >=0 && $_POST['y'] >=0){
			
			$sql='SELECT * FROM opcheck WHERE id = ' . $_POST['map'] . ' AND x = ' . $_POST['x'] . ' AND y = ' . $_POST['y'];
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			
			if($res == Array()){
				$sql='INSERT INTO opcheck(id, x, y, checked) 
					VALUES(' . (int)$_POST['map'] . ', ' . (int)$_POST['x'] . ', ' . (int)$_POST['y'] . ', ' . (int)$_POST['checked'] . ');';
				$prep=$connexion->prepare($sql);
				$prep->execute();
			}
			else{
				$sql='UPDATE opcheck SET checked = ' . (int)$_POST['checked'] . ' WHERE id = ' . (int)$_POST['map'] . ' AND x = ' . (int)$_POST['x'] . ' AND y = ' . (int)$_POST['y'] . ';';
				$prep=$connexion->prepare($sql);
				$prep->execute();
			}
			
			
			
		}
		
	}

?>