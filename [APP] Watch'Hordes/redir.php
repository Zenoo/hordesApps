<?php
include_once('include/session.php');
function do_twinoid_auth($code) {
  global $error_msg;
  if (! isset($code)) {
    $error_msg = 'bug!';
    return false;
  }
  
  $json = do_post_json('https://twinoid.com/oauth/token',
    array(
      'client_id' => APP_TWINOID_ID,
      'client_secret' => APP_SECRET_KEY,
      'redirect_uri' => APP_REDIRECT_URI,
      'code' => $code,
      'grant_type' => 'authorization_code'
    )
  );

  if (is_string($json)) {
    $error_msg = "Error connecting to the Twinoid server:<br /><em>$json</em>";
    return false;
  }
  if (isset($json->access_token)) {
    $_SESSION['token'] = $json->access_token;
    if (isset($json->expires_in)) {
      $_SESSION['token_refresh'] = time() + $json->expires_in - 60;
    } else {
      $_SESSION['token_refresh'] = time() + 300;
    }
    unset($error_msg);
    return true;
  } else if (isset($json->error)) {
    $error_msg = "Authentication error:<br /><em>" . $json->error . "</em>.";
    return false;
  } else {
    $error_msg = "Cannot parse the response from the Twinoid server.";
    return false;
  }
}

function getHordesMe() {
  global $error_msg;
  if (! isset($_SESSION['token'])) {
    return false;
  }
  
  $json = do_post_json('http://www.hordes.fr/tid/graph/me',
    array(
      'access_token' => $_SESSION['token'],
      'fields' => 'mapId'
    )
  );

  if (is_string($json)) {
    $error_msg = "Error connecting to the Twinoid server:<br /><em>$json</em>";
    return false;
  }
  if (isset($json->error)) {
    $error_msg = "Error fetching Twinoid data:<br /><em>" . $json->error . "</em>.";
    return false;
  }
  
  $_SESSION['mapId'] = $json->mapId;
  return true;
}

function getHordesMap() {
  global $error_msg;
  if (! isset($_SESSION['token'])) {
    return false;
  }
  $json = do_post_json('http://www.hordes.fr/tid/graph/map',
    array(
      'access_token' => $_SESSION['token'],
	  'mapId' => $_SESSION['mapId'],
      'fields' => 'citizens.fields(twinId,name),cadavers.fields(twinId,name)'
    )
  );
  if (is_string($json)) {
    $error_msg = "Error connecting to the Twinoid server:<br /><em>$json</em>";
    return false;
  }
  if (isset($json->error)) {
    $error_msg = "Error fetching Twinoid data:<br /><em>" . $json->error . "</em>.";
    return false;
  }
  
  //Verif si ville incomplete
	if((count($json->citizens) + count($json->cadavers)) !== 40){
		$error_msg = 'Ville incomplete, revenez plus tard.';
		return false;
	}
	
	//Recup de la liste des twinId
	$j=0;
	$idString = "";
	while($j<(count($json->citizens)-1)){
		$hordesUserCurrent = $json->citizens[$j];
		$idString = $idString . $hordesUserCurrent->twinId . ',';
		$j = $j+1;
	}
	$hordesUserCurrent = $json->citizens[$j];
	$idString = $idString . $hordesUserCurrent->twinId;
	$j=0;
	
	//PROBLEME : PAS DE TWINID DANS LE FIELD CADAVERS !!!!
	$j=0;
	while($j<count($json->cadavers)){
		$hordesUserCurrent = $json->cadavers[$j];
		$idString = $idString . ',' . $hordesUserCurrent->twinId;
		$j++;
	}
	$j=0;
	
	$_SESSION['idString'] = $idString;
	
  return true;
}

function getHordesUsers() {
  global $error_msg;
  if (! isset($_SESSION['token'])) {
    return false;
  }
  $json = do_post_json('https://twinoid.com/graph/users/' . $_SESSION['idString'],
    array(
      'access_token' => $_SESSION['token'],
      'fields' => 'name,sites.filter(6).fields(realId,stats.fields(id,name,score))'
    )
  );
  if (is_string($json)) {
    $error_msg = "Error connecting to the Twinoid server:<br /><em>$json</em>";
    return false;
  }
  if (isset($json->error)) {
    $error_msg = "Error fetching Twinoid data:<br /><em>" . $json->error . "</em>.";
    return false;
  }
  
  $_SESSION['usersToGet'] = $json;
	
  return true;
}



function get_db_user_info() {
	global $error_msg;
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
	catch(Exception $e)
	{
		$error_msg = "Error " . $e->getCode() . " connecting to the database:<br /><em>" . $e->getMessage() . "</em>";
		return false;
	}
  if (! isset($_SESSION['idString'])) {
    return false;
  }
  
	$sql='SELECT * FROM city WHERE mapid = :mapid';
	$prep=$connexion->prepare($sql);
	$prep->execute(array('mapid' => $_SESSION['mapId'] ));
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
	
	//Cas 1 : Infos deja stockees
	if(count($res)==40) $_SESSION['hordesUsers']=$res;
	
	
	//Cas 2 : Infos a stocker
	else{
	
		if(getHordesUsers()){
			//Recup des pictos+pseudo+twinId+realId de tous les twinId
			
			//Stockage user ids + Insertion dans table users
			foreach($_SESSION['usersToGet'] as $key => $value){
				//Insertion de la ville
				$insertSQL='INSERT INTO city VALUES(:mapid, :cits, 0)';
				$prep=$connexion->prepare($insertSQL);
				$prep->execute(array('mapid' => $mapId,
									'cits' => $value->id));
				
				$insertSQL="INSERT INTO users(userid, name) VALUES(:userid, :name)";
				$prep=$connexion->prepare($insertSQL);
				$prep->execute(array('userid' => $value->id,
									'name' => $value->name));
				
				$currUserAdd=$value->sites[0]->stats;
				foreach($currUserAdd as $value2){
					$updateSQL='UPDATE users SET ' . $value2->id . ' = :picnb WHERE userid = :userid';
					$prep=$connexion->prepare($updateSQL);
					$prep->execute(array('picnb' => $value2->score,
										 'userid' => $value->id));
				}
			}
			
			
								 
			
			$sql='SELECT * FROM city WHERE mapid = :mapid';
			$prep=$connexion->prepare($sql);
			$prep->execute(array('mapid' => $mapId ));
			$_SESSION['hordesUsers']=$prep->fetchAll(PDO::FETCH_ASSOC);
			
			//Stockage infos pictos
			$sql2='SELECT * FROM picto ORDER BY name;';
			$prep2=$connexion->prepare($sql2);
			$prep2->execute();
			$_SESSION['infosPic']=$prep2->fetchAll(PDO::FETCH_ASSOC);
		}
		else{
			$error_msg = 'Error gathering city users information.';
			return false;
		}
		
	}
	
  return true;
}

include_once('../uploaded/dBug.php');

if (isset($_GET['state'])) {
  $redir_link = str_replace(array("^-", "^=", "^+"),
                            array(";",  "&",  "^"),
                            $_GET['state']);
} else {
  $redir_link = "";
}
if (isset($_SERVER["HTTP_HOST"])) {
  $redir_link = $_SERVER["HTTP_HOST"] . "/" . $redir_link;
} else {
  $redir_link = $_SERVER["SERVER_NAME"] . "/" . $redir_link;
}
if (strpos($redir_link, "://") === false) {
  $redir_link = "http://" . $redir_link;
}
if (isset($_GET['code'])) {
  $_SESSION = array();
  if (do_twinoid_auth($_GET['code'])) {
    if (getHordesMe()) {
	 if(getHordesMap()){
      if (get_db_user_info()) {
		header("Location: " . $redir_link);
		exit();
      }
	 }
    }
  }
} elseif (isset($_GET['error'])) {
  $_SESSION = array();
  $error_msg = "Error during Twinoid redirection:<br /><em>" . $_GET['error'] . "</em>";
} else {
  $error_msg = "Incorrect call.  Missing parameters.";
}


header("Location: http://wh.zenoo.fr");
//echo "<p class=\"error_box\">The connection to the Twinoid server has failed.  You are not authenticated and you will not be able to use some features of this site.</p>";
?>