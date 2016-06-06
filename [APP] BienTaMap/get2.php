<?php

  include 'Library/autoload.php';                           //Auto chargement des classes
  
  if(isset($_GET['zbefore'])){
	  define('REDIRECT_URI', "http://map.zenoo.fr/get.php?zbefore=1");
	  define('CLIENT_ID', 214);
	  define('CLIENT_SECRET', "tvSRKROSsPD914KCfVvoxKnQAnV88L1t");
	  define('SCOPE', "contacts,groups,applications,www.hordes.fr");
  }
  elseif(isset($_GET['zafter'])){
	  define('REDIRECT_URI', "http://map.zenoo.fr/get.php?zafter=1");
	  define('CLIENT_ID', 215);
	  define('CLIENT_SECRET', "9ufi3SESM21DEF3vO4rqdJAWl2eZ5yHu");
	  define('SCOPE', "contacts,groups,applications,www.hordes.fr");
  }
  

  session_start();                                          //Démarrage de la session

  Debug::debugModeOn();                                     //Activation du mode de déboggage

  //Déconnexion
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

    //Récupération de l'authentification
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
		echo 'N° : '.$e->getCode();
		exit();
		
	}
	
	$hordesGeneral = $hordesApi->getMe("name,twinId,mapId,x,y,out"); // Récup infos
	$hordesMap = $hordesApi->getMap($hordesGeneral->mapId,"zones.fields(details,building),city"); // Récup zones
	
	//Get du nb de zz sur la case
	$Zfound=0;
	foreach($hordesMap->zones as $zone){
		if($zone->x == $hordesGeneral->x && $zone->y == $hordesGeneral->y){
			$Zfound=$zone->z;
			break;
		}
	}
	
	//Cas retour before
	if(isset($_GET['zbefore'])){
		
		$sql="SELECT COUNT(*)
		FROM information_schema.tables 
		WHERE table_schema = 'zenoo829_zooMap' 
		AND table_name = 'M" . $hordesGeneral->mapId . "';";
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		
		//Map connue
		reset($res[0]);
		if(current($res[0]) != 0){

			//Check si case déjà maj today en init
			$sql='SELECT initZ,afterZ FROM M' . $hordesGeneral->mapId . ' WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND day = ' . $hordesMap->days;
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			//Si case non maj
			if($res == Array()){	
				$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,initPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', "' . $hordesGeneral->name . '")';
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
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
			
			$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,initPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', "' . $hordesGeneral->name . '")';
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
		}
		
			
			
	}
	//Cas retour after
	elseif(isset($_GET['zafter'])){
		$sql="SELECT COUNT(*)
		FROM information_schema.tables 
		WHERE table_schema = 'zenoo829_zooMap' 
		AND table_name = 'M" . $hordesGeneral->mapId . "';";
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		
		//Map connue
		reset($res[0]);
		if(current($res[0]) != 0){

			$insertSQL='UPDATE M' . $hordesGeneral->mapId . ' SET afterZ = ' . $Zfound . ' WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND day = ' . $hordesMap->days;
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
			$insertSQL='UPDATE M' . $hordesGeneral->mapId . ' SET afterPseudo = "' . $hordesGeneral->name . '" WHERE x = ' . $hordesGeneral->x . ' AND y = ' . $hordesGeneral->y . ' AND day = ' . $hordesMap->days;
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
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
			
			$insertSQL='INSERT INTO M' . $hordesGeneral->mapId . '(day,x,y,initZ,afterZ,initPseudo,afterPseudo) VALUES(' . $hordesMap->days . ', ' . $hordesGeneral->x . ', ' . $hordesGeneral->y . ', ' . $Zfound . ', ' . $Zfound . ', "' . $hordesGeneral->name . '", "' . $hordesGeneral->name . '")';
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
		}
	}
  
  

		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>$(window).load(function(){close();});</script>';

		?>