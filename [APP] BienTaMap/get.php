<?php

  include 'Library/autoload.php';                           //Auto chargement des classes
  

  define('REDIRECT_URI', "http://map.zenoo.fr/get.php");
  define('CLIENT_ID', 214);
  define('CLIENT_SECRET', "tvSRKROSsPD914KCfVvoxKnQAnV88L1t");
  define('SCOPE', "contacts,groups,applications,www.hordes.fr");
  

  session_start();                                          //D�marrage de la session

  Debug::debugModeOn();                                     //Activation du mode de d�boggage

  //D�connexion
  if(isset($_GET['reset']))
  {
    SessionManager::deleteConnection();
  }

  //Gestion de la session
  SessionManager::createConnection();
  
  //Application
  if(SessionManager::isConnected())
  {
    //echo '<a href="javascript:close();">FERMER</a>';

    //R�cup�ration de l'authentification
    $authTwinoid = $_SESSION['authTwinoid'];

    //Gestion de l'API Hordes
    $hordesApi = new HordesAPI($authTwinoid->getToken());
	
	    //Gestion des erreurs
    if($hordesApi->errors())
    {
      $description = $hordesApi->getErrorsDescriptions();

      foreach($hordesApi->getErrors() as $key => $error)
      {
        echo $error . ' : ' . $description[$key] . '<br/>';
      }
    }

  }
  
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
		echo 'N� : '.$e->getCode();
		exit();
		
	}
	
	function searchByPos($x, $y, $array) {
		foreach ($array as $key => $val) {
			
			if ($val['x'] == $x && $val['y'] == $y) {
				return $key;
			}
		}
		return null;
	}
	
	$hordesGeneral = $hordesApi->getMe("name,twinId,mapId,x,y,out"); // R�cup infos
	$hordesMap = $hordesApi->getMap($hordesGeneral->mapId,"zones.fields(details,building),city"); // R�cup zones
	
	//Get du nb de zz sur la case
	$Zfound=0;
	foreach($hordesMap->zones as $zone){
		if($zone->x == $hordesGeneral->x && $zone->y == $hordesGeneral->y){
			$Zfound=$zone->z;
			break;
		}
	}
	
	$sql="SELECT COUNT(*)
		FROM information_schema.tables 
		WHERE table_schema = 'zenoo829_zooMap' 
		AND table_name = 'M" . $hordesGeneral->mapId . "';";
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
	
	$sql='SELECT * FROM opcheck WHERE id = ' . $hordesGeneral->mapId;
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$opcheck=$prep->fetchAll(PDO::FETCH_ASSOC);
	
	//Map connue
	reset($res[0]);
	if(current($res[0]) != 0){
		
		//Check si case d�j� maj today en init
		$sql='SELECT initZ,afterZ FROM M' . $hordesGeneral->mapId . ' WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND day = ' . $hordesMap->days;
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		//Si case non maj
		if($res == Array()){	
			if($Zfound == 0){
				$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,afterZ,initPseudo,afterPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', ' . $Zfound . ', "' . $hordesGeneral->name . '", "' . $hordesGeneral->name . '")';
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
				
				//Delete stripes
				$StripesCheck=searchByPos($hordesGeneral->x,$hordesGeneral->y,$opcheck);
				if($StripesCheck >= 0){
					if($opcheck[$StripesCheck]['checked'] == 1){
						$insertSQL='UPDATE opcheck SET checked = 0 WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND id = ' . $hordesGeneral->mapId;
						$prep=$connexion->prepare($insertSQL);
						$prep->execute();
					}
				}
			}
			else{
				$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,initPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', "' . $hordesGeneral->name . '")';
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
			}
		}
		
		//Si case d�j� maj
		else{ 
			if($res[0]['initZ'] != NULL){
				$insertSQL='UPDATE M' . $hordesGeneral->mapId . ' SET afterZ = ' . $Zfound . ', afterPseudo = "' . $hordesGeneral->name . '" WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND day = ' . $hordesMap->days;
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
				
				if($Zfound == 0){
					//Delete stripes
					$StripesCheck=searchByPos($hordesGeneral->x,$hordesGeneral->y,$opcheck);
					if($StripesCheck >= 0){
						if($opcheck[$StripesCheck]['checked'] == 1){
							$insertSQL='UPDATE opcheck SET checked = 0 WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND id = ' . $hordesGeneral->mapId;
							$prep=$connexion->prepare($insertSQL);
							$prep->execute();
						}
					}
				}
			}
		}
		
		
		
	}
	//Map inconnue
	else{
		// sql to create table
		$sql = "CREATE TABLE M" . $hordesGeneral->mapId . " (
		day INT(2) UNSIGNED, 
		x INT(2) NOT NULL,
		y INT(2) NOT NULL,
		initZ INT(2),
		afterZ INT(2),
		initPseudo VARCHAR(50),
		afterPseudo VARCHAR(50),
		primary key (day, x, y)
		)";
		$connexion->exec($sql);
		
		if($Zfound == 0){
			$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,afterZ,initPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', ' . $Zfound . ', "' . $hordesGeneral->name . '")';
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
		}
		else{
			$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,initPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', "' . $hordesGeneral->name . '")';
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
		}
	}
	
  
  

		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>$(window).load(function(){close();});</script>';

		?>