<?php
	if(isset($_POST['tabX']) || isset($_POST['mapId'])){
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
		
		if(count($_POST['tabX'])){
			
			$sql='SELECT * FROM traces WHERE mapId = ' . $_POST['mapId'] . ' AND x = "' . $_POST['tabX'] . '" AND y = "' . $_POST['tabY'] . '";';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			if(!$res){
					$sql='INSERT INTO traces(mapId, pseudo, x, y) 
					VALUES(' . (int)$_POST['mapId'] . ', "' . $_POST['name'] . '", "' . $_POST['tabX'] . '", "' . $_POST['tabY'] . '");';
				$prep=$connexion->prepare($sql);
				$prep->execute();
			}
		}
		elseif(count($_POST['mapId'])){
			$sql='SELECT * FROM traces WHERE mapId = ' . $_POST['mapId'];
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		
	}

?>