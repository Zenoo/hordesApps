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

function get_twinoid_user_info() {
  global $error_msg;
  if (! isset($_SESSION['token'])) {
    return false;
  }
  $json = do_post_json('http://www.hordes.fr/tid/graph/me',
    array(
      'access_token' => $_SESSION['token'],
      'fields' => 'name,twinId,mapId,hero,job'
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
  
  if(!isset($_COOKIE['twinId'])){
		$_SESSION['hordesGeneral'] = $json;
		setcookie('twinId', $_SESSION['hordesGeneral']->twinId, strtotime('today 23:59'), null, null, false, true);
		setcookie('job', $_SESSION['hordesGeneral']->job, strtotime('today 23:59'), null, null, false, true);
		setcookie('name', $_SESSION['hordesGeneral']->name, strtotime('today 23:59'), null, null, false, true);
		setcookie('mapId', $_SESSION['hordesGeneral']->mapId, strtotime('today 23:59'), null, null, false, true);
		header("Refresh:0");
	} 
	
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
		echo 'N‹ : '.$e->getCode();
		exit();
	}

	$sql='SELECT *
		FROM tempData
		WHERE id = ' . $_COOKIE['twinId'] . ' AND mday = ' . $today['mday'] . ' AND month = ' . $today['month'] . ' AND year = ' . $today['year'] . ';';
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
	
	// Infos a stocker today
	if(count($res)==0){
		//Delete des infos des jours precedents
		$sql='DELETE FROM tempData WHERE id = ' . $_COOKIE['twinId'];
		$prep=$connexion->prepare($sql);
		$prep->execute();
		
		$json=null;
		$json = do_post_json('https://twinoid.com/graph/user/' . $_COOKIE['twinId'],
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
		$_SESSION['twinGeneral'] = $json; //Recup pictos
		
		$ermitFound=0;
		foreach($_SESSION['twinGeneral']->sites[0]->stats as $key => $value){
			if($value->id == "jermit"){
				$ermitFound=$value->score;
				break;
			}
		}
		
		$gardFound=0;
		foreach($_SESSION['twinGeneral']->sites[0]->stats as $key => $value){
			if($value->id == "jguard"){
				$gardFound=$value->score;
				break;
			}
		}
		
		$techFound=0;
		foreach($_SESSION['twinGeneral']->sites[0]->stats as $key => $value){
			if($value->id == "jtech"){
				$techFound=$value->score;
				break;
			}
		}
		
		$tamerFound=0;
		foreach($_SESSION['twinGeneral']->sites[0]->stats as $key => $value){
			if($value->id == "jtamer"){
				$tamerFound=$value->score;
				break;
			}
		}
		
		$rangrFound=0;
		foreach($_SESSION['twinGeneral']->sites[0]->stats as $key => $value){
			if($value->id == "jrangr"){
				$rangrFound=$value->score;
				break;
			}
		}
		
		$pelleFound=0;
		foreach($_SESSION['twinGeneral']->sites[0]->stats as $key => $value){
			if($value->id == "jcolle"){
				$pelleFound=$value->score;
				break;
			}
		}
		
		$sql='INSERT INTO tempData(id, mday, month, year, name, job, hunter, guardian, tech, tamer, eclair, collec) 
		VALUES(' . $_COOKIE['twinId'] . ', 
		' . $today['mday'] . ', 
		"' . $today['month'] . '", 
		' . $today['year'] . ', 
		"' . $_COOKIE['name'] . '", 
		"' . $_COOKIE['job'] . '", 
		' . $ermitFound . ', 
		' . $gardFound . ', 
		' . $techFound . ', 
		' . $tamerFound . ', 
		' . $rangrFound . ',
		' . $pelleFound . '
		);';
		$prep=$connexion->prepare($sql);
		$prep->execute();
		
	}
	
	$sql='SELECT *
			FROM tempData
			WHERE id = ' . $_COOKIE['twinId'] . ' AND mday = ' . $today['mday'] . ' AND month = "' . $today['month'] . '" AND year = ' . $today['year'] . ';';
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$userInfo=$prep->fetchAll(PDO::FETCH_ASSOC);
		$jobs = array(
			'hunter' => 'Ermite',
			'guardian' => 'Gardien',
			'tech' => 'Technicien',
			'basic' => 'Habitant',
			'tamer' => 'Apprivoiseur',
			'eclair' => 'Eclaireur',
			'collec' => 'Fouineur',
			null => 'ame'
		);
		
		$job2picto = array(
			'hunter' => 'jermit',
			'guardian' => 'jguard',
			'tech' => 'jtech',
			'tamer' => 'jtamer',
			'eclair' => 'jrangr',
			'collec' => 'jcolle'
		);
		
		if($userInfo[0]['job'] == 'basic' && $userInfo[0]['basic']==null){
			$json = do_post_json('http://www.hordes.fr/tid/graph/me',
			array(
			  'access_token' => $_SESSION['token'],
			  'fields' => 'playedMaps'
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
			$_SESSION['dataBasic'] = $json; //Recup pictos
		}
  
  return true;
}

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
    if (get_twinoid_user_info()) {
        header("Location: " . $redir_link);
        exit();
    }
  }
} elseif (isset($_GET['error'])) {
  $_SESSION = array();
  $error_msg = "Error during Twinoid redirection:<br /><em>" . $_GET['error'] . "</em>";
} else {
  $error_msg = "Incorrect call.  Missing parameters.";
}

header("Location: http://zav.zenoo.fr");
//echo "<p class=\"error_box\">The connection to the Twinoid server has failed.  You are not authenticated and you will not be able to use some features of this site.</p>";
?>