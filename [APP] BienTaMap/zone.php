<!DOCTYPE HTML>
<html>
	<head>
		<title>Zoo Prep</title>
		<link rel="icon" href="favicon.ico" />
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	
	<body>
<?php

  include 'Library/autoload.php';                           //Auto chargement des classes
  include 'Config/App.inc.php';                             //Paramètres de l'application

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
    echo '<a href="'.REDIRECT_URI.'">Refresh</a> <br />' . "\n";
    echo '<a href="'.REDIRECT_URI.'?reset">Reset</a> <br />' . "\n";

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
  
    $hordesGeneral = $hordesApi->getMe("name,twinId,mapId,x,y,out"); // Récup infos
	
	$hordesMap = $hordesApi->getMap($hordesGeneral->mapId,"zones.fields(details,building),city"); // Récup zones

	
	
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
	
	$sql='SELECT day,initZ,afterZ,initPseudo,afterPseudo FROM M' . $hordesGeneral->mapId . ' WHERE x = ' . $_GET['x'] . ' AND y = ' . $_GET['y'];
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
	
		echo '<p class="city">					
					<span id="cityName">' . htmlentities($hordesMap->city->name) . '</span><br />
					<span id="cityID">ID: ' . $hordesMap->id . '</span><br />
					<span id="cityID">Zone: ' . ($_GET['x']-$hordesMap->city->x) . '/' . ($hordesMap->city->y-$_GET['y']) . '</span>
				</p>';

		echo '<div id="resum"><table><tr><th>Jour</th><th>Updater</th><th><img src="../resources/icons/small_zombie.gif"></th><th>Updater</th><th><img src="../resources/icons/item_chain.gif"></th></tr>';
		foreach($res as $day){
			echo '<tr><th>' . $day['day'] . '</th><td>' . $day['initPseudo'] . '</td><td>' . $day['initZ'] . '</td><td>' . $day['afterPseudo'] . '</td><td>' . $day['afterZ'] . '</td></tr>';
		}
		echo '</table></div>';
		
		
		?>
		<div id="footer"><p>Certaines images sont la propri&eacute;t&eacute; de MotionTwin.<p></div>
	</body>
</html>