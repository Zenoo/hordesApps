<?php
	include_once('include/session.php');
	include_once('../uploaded/dBug.php');
	
	if(!isset($_SESSION['uid']) || $_SESSION['uid'] === null || !is_numeric($_SESSION['uid'])){
		header("Location: " . twin_auth_href());
		exit();
	}
	
	
	$banned = array(714372,337992,9079946);
	if(in_array($_SESSION["uid"],$banned)){
		echo '<img src="img/foot.png">';
		exit();
	}
	
	echo '<!DOCTYPE HTML>
		<head>
		<title>Zav\'</title>
		<link rel="icon" href="../resources/icons/item_soul.gif" />
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="css/jquery.mCustomScrollbar.css" />
		<link rel="stylesheet" href="css/wbbtheme.css" />
	</head>
	
	<body>';
	
	echo '<div id="container">
		<div id="main">
			<div id="header">
			</div>
			<div id="menu">
				<ul>
					<li id="home" class="selected"><img src="../resources/icons/small_home.gif"> Accueil</li>
					<li id="creators"><img src="../resources/icons/small_zombie.gif"> Createurs</li>
					<li id="request"><img src="../resources/icons/small_calim.gif"> Z\'en veux un</li>
					<li id="requestList"><img src="../resources/icons/r_forum.gif"> Liste des demandes</li>
					<li id="profile"><img src="../resources/icons/item_soul.gif"> Profil</li>
					<!--<li id="help"><img src="../resources/icons/small_archive.gif"> Aide</li>-->
				</ul>
			</div>';
	
	echo '<div id="hiddenInfo" data-id="' . $_SESSION["uid"] . '" data-name="' . $_SESSION['name'] . '" style="display:none"></div>';
	
	echo '<div id="wait" style="display: none;">
				<img src="http://data.hordes.fr/gfx/design/loading.gif" alt="[icon]">
				<img src="http://data.hordes.fr/gfx/loc/fr/loadingLabel.gif" alt="Loading...">
			</div>
			<div id="content">
				<div id="contentHolder" style="margin-top:-11px">
				<p class="homeText"><strong>Sur simple demande, nous pouvons créer les avatars dont VOUS avez envie.<br />
				Héros comme simple citoyen, vous y aurez tous accès.<br />
				<br />
				Chacun de vous peut en outre se proposer en tant que créateur d\'avatar.</strong>
				<br />
				En effet, dès que vous postez 5 réalisations, vous accédez au statut de créateur. Mais attention, en cas de longue inactivité, pour des raisons de disponibilité aux demandeurs, ce statut vous sera enlevé.<br />
				<br />
				Expliquez votre demande en indiquant toutes les idées qui vous traversent l\'esprit et que vous désirez retrouver sur votre avatar.<br />
				Les demandes les plus originales sauront sans nul doute attirer un créateur qui vous proposera alors ses services pour la réalisation de votre demande. Dès lors, vous aurez une conversation avec ce dernier afin d\'affiner les détails de l\'avatar et de déterminer s\'il vous convient ou pas. Il suffira enfin de valider votre création lorsque le résultat vous satisfera.<br />
				<br />
				<i>Les demandeurs peuvent regarder les différentes réalisations, ou pas, des créateurs. Il suffit de leur demander.</i><br />
				<br />
				<br />
				Petite précision aussi : Nous ne sommes pas votre cerveau. Ce qui veut dire que nous ne prendrons pas de demandes "faites comme vous voulez/au choix)" ou encore "un gars dans le desert/la ville/ je ne sais ou, qui tue des zombies lolilol avec une arme ou autre."<br />
				<br />
				<strong>Demandeurs, à vos souris ! Et créateurs à vos pixels!</strong></p>
				</div>
				<div id="rights"><p class="left">Zavatars est géré par des joueurs et n\'est en aucun cas lié a Motion Twin</p><p class="right">Développeur: <a href="http://zenoo.fr" target="_blank">Zenoo</a> / Design : <a href="https://twinoid.com/user/2468" target="_blank">Tsha</a></p></div>
			</div>
			
			<div id="footer">
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="/js/jquery.wysibb.min.js"></script>
	<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="js/load.js"></script></body>
</html>';


	
?>