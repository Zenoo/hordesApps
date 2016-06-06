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
      'fields' => 'name,twinId,mapId,x,y,out'
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
  
  $_SESSION['hordesGeneral'] = $json;
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
	  'mapId' => $_SESSION['hordesGeneral']->mapId,
      'fields' => 'zones.fields(details,building),city'
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
  
  $_SESSION['hordesMap']=$json;
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
    if (getHordesMe()) {
	 if(getHordesMap()){
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


header("Location: http://wh.zenoo.fr");
//echo "<p class=\"error_box\">The connection to the Twinoid server has failed.  You are not authenticated and you will not be able to use some features of this site.</p>";
?>