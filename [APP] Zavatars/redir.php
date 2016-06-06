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
  $json = do_post_json('http://twinoid.com/graph/me',
    array(
      'access_token' => $_SESSION['token'],
      'fields' => 'id,name,picture,locale,sites.fields(npoints)'
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
  if (! is_numeric($json->id)) {
    $error_msg = "Invalid Twinoid data: id=" . $json->id;
  }
  $_SESSION['uid'] = intval($json->id);
  $_SESSION['name'] = $json->name;
  if (! isset($_SESSION['locale'])) {
    $_SESSION['locale'] = $json->locale;
  }
  if (isset($json->picture) && isset($json->picture->url)) {
    $_SESSION['avatar'] = $json->picture->url;
  }
  $_SESSION['oldNames']='';
	if(count(get_object_vars($json->oldNames))>0){
		foreach ($json->oldNames as $key => $val) {
			$_SESSION['oldNames']+=$val->name+',';
		}
		$_SESSION['oldNames']=rtrim($_SESSION['oldNames'],',');
	}
	if (! isset($_SESSION['birthday'])) {
	  $_SESSION['birthday']=$json->birthday;
	}
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
  if (! isset($_SESSION['uid']) || ! is_int($_SESSION['uid'])) {
    return false;
  }
	$sql='SELECT * FROM users WHERE twinId = ' . $_SESSION['uid'];
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
	if(count($res)>0){
		$insertSQL='UPDATE users SET name = "' . $_SESSION['name'] . '", picture = "' . $_SESSION['avatar'] . '", oldnames = "' . $_SESSION['oldNames'] . '", updated = ' . time() . ' WHERE twinId = ' . $_SESSION['uid']; 
		$prep=$connexion->prepare($insertSQL);
		$prep->execute();
	}
	else{
		$insertSQL='INSERT INTO users(twinId,name,picture,oldnames,birthday,updated,description) VALUES(' . $_SESSION['uid'] . ', "' . $_SESSION['name'] . '", "' . $_SESSION['avatar'] . '", "' . $_SESSION['oldNames'] . '", "' . $_SESSION['birthday'] . '", ' . time() . ',"")';
		$prep=$connexion->prepare($insertSQL);
		$prep->execute();
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
      if (get_db_user_info()) {
        header("Location: " . $redir_link);
        exit();
      }
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