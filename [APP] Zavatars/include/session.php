<?php
/* MODIFIER CES CONSTANTES */
const APP_TWINOID_ID   = 000000;
const APP_SECRET_KEY   = "HIDDEN";
const APP_REDIRECT_URI = "http://zav.zenoo.fr/redir.php";
const APP_SCOPES       = "";
function do_post_json($url, $params, $http_options = array())
{
    $default_options = array(
        'method' => 'POST',
        'timeout' => 10,
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($params)
    );
    $http_options    = array_merge($default_options, $http_options);
    $context         = stream_context_create(array(
        'http' => $http_options
    ));
    $fp              = fopen($url, 'rb', false, $context);
    if (!$fp) {
        $err = error_get_last();
        return $err['message'];
    }
    $response = stream_get_contents($fp);
    if ($response === false) {
        $err = error_get_last();
        return $err['message'];
    }
    return json_decode($response);
}
function twin_auth_href($redir_link = NULL)
{
    $toAdd = "";
    if (APP_SCOPES != "")
        $toAdd = "&scope=" . APP_SCOPES;
    return 'https://twinoid.com/oauth/auth?client_id=' . APP_TWINOID_ID . '&response_type=code' . $toAdd . '&state=';
}
session_cache_limiter('nocache');
session_name('sid');
session_start();
if (isset($_SESSION['token_refresh']) && (time() >= $_SESSION['token_refresh'])) {
    header("Location: " . twin_auth_href());
    $_SESSION = array();
    exit();
}
?>