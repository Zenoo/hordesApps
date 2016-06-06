<?php

	include_once('include/session.php');
	include_once('../../uploaded/dBug.php');
	
	if(!isset($_SESSION['hordesGeneral'])){
		header("Location: " . twin_auth_href());
		exit();
	}

	echo '<!DOCTYPE HTML>

<html>
	<head>
		<title>Watch\'Hordes</title>
		<link rel="icon" href="favicon.ico" />
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/hide.js"></script>
	</head>
	
	<body>';
  
  
	
	echo '<br /><center>
	<table id="module">
	<tbody>

	<tr>';
	//Récup avatar
	echo '<td class="avat_cont">';
	$h_avatar = $_SESSION['hordesGeneral']->avatar;
	echo '<img class="avatar" src="' . $h_avatar . '"></td>
	
	<td style="padding-left:20px">';
	
	//Récup pseudo
	echo '<br /><br />';
	$h_name = $_SESSION['hordesGeneral']->name;
	echo '<span style="font-size: 38px;color:black"><b>' . $h_name . '</b></span>';
	echo '<br /><br />';
	
	//Récup héros
	$h_hero = $_SESSION['hordesGeneral']->hero;
	if($h_hero == 1){
		echo '<img src="../resources/design/soul_hero.gif"> ' . htmlentities('Héros');
	}
	else{
	echo '<img src="../resources/design/tag_12.gif"> Citoyen';
	}
	echo '<br /><br />';
	
	//Récup nombre de villes
	$h_oldCity = $_SESSION['hordesGeneral']->cadavers;
	$h_oldCityNb = count($h_oldCity);
	echo $h_oldCityNb . ' Villes';
	echo '<br /><br />
	<a href="javascript:unhideTbody(\'10more\');" class="seeMore" onclick="changeText();" id="changeT">Voir plus</a>
	</td>
	

	</tr>
	';
	
	//Listage de toutes les villes

	$i=0;
	$h_oldCityCurrent = array();
	while($i<10){
	echo '<tr><td style="width:20px;">';
	$h_oldCityCurrent = array_values($h_oldCity)[$i];;
	echo 'J' . $h_oldCityCurrent->d . //Jour
	' </td><td> <a href="javascript:unhideTr(\'cloak' . $i . '\');">' . htmlentities($h_oldCityCurrent->name) . '</a></td></tr>'; // Nom
	echo '<br /><tr id="cloak' . $i . '" class="hidden"><td></td><td class="hiddClass"><span class="seasonID"><b>Saison ' . $h_oldCityCurrent->season . //Saison
	'</b> / ID : ' . $h_oldCityCurrent->id  .  '</span><br />' .// ID
	'<b style="color:red;padding-left:10px;">+' . $h_oldCityCurrent->score . '</b> ' . //Points de saison
	'"' . htmlentities($h_oldCityCurrent->m)  . '"<br />'; // Message
	echo '</td></tr>';
	$i++;
	}
	echo '</tbody>
	<tbody id="10more" class="hidden">';
	
		while($i<$h_oldCityNb){
	$h_oldCityCurrent = array_values($h_oldCity)[$i];
	echo '<tr><td style="width:20px;">';
	echo 'J' . $h_oldCityCurrent->d . //Jour
	' </td><td> <a href="javascript:unhideTr(\'cloak' . $i . '\');">' . htmlentities($h_oldCityCurrent->name) . '</a></td></tr>'; // Nom
	echo '<tr id="cloak' . $i . '" class="hidden"><td></td><td class="hiddClass"><span class="seasonID"><b>Saison ' . $h_oldCityCurrent->season . //Saison
	'</b> / ID : ' . $h_oldCityCurrent->id  .  '</span><br />' .// ID
	'<b style="color:red;padding-left:10px;">+' . $h_oldCityCurrent->score . '</b> ' . //Points de saison
	'"' . htmlentities($h_oldCityCurrent->m)  . '"<br />'; // Message
	echo '</td></tr>';
	$i++;
	}
	
	echo '</tbody>
	</table>';
	
	
?>
</center>
<div id="footer" align="right"><p>Certaines images sont la propri&eacute;t&eacute; de MotionTwin.<p></div>
	</body>
</html>