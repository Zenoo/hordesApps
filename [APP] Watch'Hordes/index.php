<?php
	include_once('include/session.php');
	include_once('../uploaded/dBug.php');
	
	if(!isset($_SESSION['mapId'])){
		header("Location: " . twin_auth_href());
		exit();
	}
	
	
	echo '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Watch\'Hordes</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="../js/jquery/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/msdropdown/dd.css" />
<script src="../js/msdropdown/jquery.dd.min.js"></script>
</head>
<body>';
	
	//Affichage des pseudos+pictos       
	echo '<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<script src="overlib.js"></script>
		<ul class="t1">';
	
	function searchById($id, $array) {
		foreach ($array as $key => $val) {
			if ($val['id'] === $id) {
				return $key;
			}
		}
		return null;
	}
	
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
		echo $error_msg;
		return false;
	}
	
	$strTmp="";
	foreach($_SESSION['usersToGet'] as $key => $currentUser){
		if($strTmp == "") $strTmp.=$currentUser->id;
		else $strTmp.= ',' . $currentUser->id;
	}
	
	$sql='SELECT * FROM users WHERE FIND_IN_SET(userid, "' . $strTmp . '")';
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$pictoHordes=$prep->fetchAll(PDO::FETCH_ASSOC);

	foreach($pictoHordes as $key => $currentUser){
		
		echo '<li class="tdBox">
		<table class="userTable" >
		<tr>
		<td class="userPseudo">';


		
		//Affichage pseudo du citoyen
		echo "<strong>" . htmlentities($currentUser['name']) . "</strong></td></tr>";
		
		//Affichage pictos Hordes des citoyens
		echo '<tr><td><ul>';
		foreach($currentUser as $key2 => $pictoCurrent){
			if($pictoCurrent <= 0 || $key2=='userid' || $key2=='name') continue; //Si non-picto -> saute
			
			//Sélection picto dans BDD
			$verif=searchById($key2,$_SESSION['infosPic']);
			$currPic=$_SESSION['infosPic'][$verif];
			
			echo '<li class="tdPicto" data-id="' . $currPic['id'] . '" data-nb="' . $pictoCurrent . '">
						<a href="javascript:void(0);" 
								onmouseover="return overlib(\'' . htmlentities(str_replace('\'', '´', $currPic['name'])) . '\');" 
								onmouseout="return nd();">
							<p><img alt="" src="../resources/icons/r_' . $currPic['id'] . '.gif"></p>
							<p>' . $pictoCurrent . '</p></a></li>';

		}
		echo '</ul></td></tr>
		</table>
		</li>';
	}
	echo '</ul>';
	

	
	
	
	//Tri selon picto? mechanics :
	echo '
		<script>
			$(document).ready(function(e) {
			$("#tech").msDropdown();
			});
	</script>
    <p class="submit">
      
      <select name="pictoTri" class="tech" id="tech" style="width:300px">';
	
	  
	for($i=1;$i<sizeof($_SESSION['infosPic']);$i++){
		echo '<option value="r_' . $_SESSION['infosPic'][$i]['id'] . '" data-image="../resources/icons/r_' . $_SESSION['infosPic'][$i]['id'] . '.gif"> ' . htmlentities($_SESSION['infosPic'][$i]['name']) . '</option>';
	}
	
	
	echo '</select>
      <input name="test" type="submit" id="btnSubmit" value="Trier"  />     
    </p> ';
	
  
?>
<script src="js/jquery/sorting.js"></script>
</body>
</html>