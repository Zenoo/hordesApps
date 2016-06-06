<?php
	
	include_once('include/session.php');
	include_once('../../uploaded/dBug.php');
	
	if(!isset($_COOKIE['twinId'])){
		header("Location: " . twin_auth_href());
		exit();
	}
	
	echo '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sauve l\'Outre-Monde</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link href="../resources/icons/r_ermwin.gif" rel="icon" type="image/x-icon" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!-- VIDEO.JS -->
<link href="//vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
<script src="//vjs.zencdn.net/4.12/video.js"></script>

<!-- FLIPSTER -->
<script src="js/jquery.flipster.js"></script>



<!-- carouFredsel -->
<script type="text/javascript" language="javascript" src="js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script>
	$(document).ready(function() {
		

		var $c = $(\'#member_cont\'),
			$w = $(window);

		$c.carouFredSel({
			align: false,
			items: 15,
			scroll: {
				items: 1,
				duration: 1000,
				timeoutDuration: 0,
				easing: \'linear\',
				pauseOnHover: \'immediate\'
			}
		});

		
		$w.bind(\'resize.example\', function() {
			var nw = $w.width();
			if (nw < 990) {
				nw = 990;
			}

			$c.width(nw * 3);
			$c.parent().width(nw);

		}).trigger(\'resize.example\');

	});
</script>
	
<script>
$(document).ready(function() {
	$(\'.memberQuote\').hide();
	$(\'.soloMember\').hover(
  function () {
	$(\'#newMember\').append(\'<div id="persoQuote">\'+$(this).children().last().text()+\'</div>\');
  }, 
  function () {
    $(\'#persoQuote\').remove();
  }
);
});
</script>

</head>

<body>';

	new dBug($_SESSION);
	$today = getdate();
	if($today['hours'] == 0 && $today['minutes'] < 37){
		echo '<div id="errorH"><p>Le site subit des attaques répé... HaaaAAAARGH <img src="http://www.hordes.fr/favicon.ico"></p></div>';
		exit();
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
		echo 'N° : '.$e->getCode();
		exit();
	}

		?>
	<div id="top_backG"></div>
	
	<div id="top_menu">
		<p>
			<span><a href="http://sauveloutremonde.zenoo.fr/">Accueil</a></span>
			<span>|</span>
			<span><a href="http://sauveloutremonde.zenoo.fr?p=cr">Cr&eacute;ations</a></span>
			<span>|</span>
			<span><a href="http://sauveloutremonde.zenoo.fr?p=co">Live Chat</a></span>
			<span>|</span>
			<span><a href="http://sauveloutremonde.zenoo.fr?p=r">Rejoins nous</a></span>
		</p>
		
	</div>
	
	<div id="top_logo">
		<img src="img/logo.png">
	</div>
	<?php
		
		//GESTION AFFICHAGE JOB
		
		//Si mite
		if($userInfo[0]['job'] == 'hunter'){
			echo '<div id="hordesInfos"><img src="../resources/icons/job_' . $userInfo[0]['job'] . '.gif"> Bonsoir, frère ermite. ';
			if($userInfo[0]['hunter']<50){
				echo 'Seulement ' . $userInfo[0]['hunter'] . ' jours ermite?<br />On ne nait pas ermite, on le devient.';
			}
			elseif($userInfo[0]['hunter']<100){
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />Sage décision.';
			}
			elseif($userInfo[0]['hunter']<150){
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />Mieux vaut être seul, que mal accompagné.';
			}
			elseif($userInfo[0]['hunter']<250){
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />Tu as fait le bon choix.';
			}
			else{
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />T\'as compris la vie !';
			}
			echo '</div>';
		}
		//Si non incarné
		elseif($jobs[$userInfo[0]['job']] == 'âme'){
			echo '<div id="hordesInfos"><img src="../resources/icons/job_' . $userInfo[0]['job'] . '.gif"> Bonsoir, âme égarée. ';
			if($userInfo[0]['hunter']<50){
				echo 'Seulement ' . $userInfo[0]['hunter'] . ' jours ermite?<br />On ne nait pas ermite, on le devient.';
			}
			elseif($userInfo[0]['hunter']<100){
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />Sage décision.';
			}
			elseif($userInfo[0]['hunter']<150){
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />Mieux vaut être seul, que mal accompagné.';
			}
			elseif($userInfo[0]['hunter']<250){
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />Tu as fait le bon choix.';
			}
			else{
				echo 'Déjà  ' . $userInfo[0]['hunter'] . ' jours ermite!<br />T\'as compris la vie !';
			}
			echo '</div>';
		}
		//Si non-mite
		else{
			echo '<div id="hordesInfos"><img src="../resources/icons/job_' . $userInfo[0]['job'] . '.gif"> Bonsoir, ' . $jobs[$userInfo[0]['job']] . ' égaré. ';
			
			//Si habitant
			if($userInfo[0]['job'] == 'basic'){
				$realJHhab=0;
				if($userInfo[0]['basic']==null){
					$basicDayFound=0;
					foreach($_SESSION['dataBasic']->cadavers as $key => $value){
						$basicDayFound+=$value->d;
					}
					
					$heroDayFound=$userInfo[0]['hunter']+$userInfo[0]['guardian']+$userInfo[0]['tech']+$userInfo[0]['tamer']+$userInfo[0]['eclair'];
					$realJHhab = $basicDayFound-$heroDayFound;
				}
				else $realJHhab=$userInfo[0]['basic'];
				
				
				echo '<br />' . $realJHhab . ' jours Habitant?';
			}
			
			
			else{
				$jobDayFound=-1;
				foreach($twinGeneral->sites[0]->stats as $key => $value){
					if($value->id == $job2picto[$hordesGeneral->job]){
						$jobDayFound=$value->score;
						break;
					}
				}
				
				echo '<br />' . $userInfo[0][$userInfo[0]['job']] . ' jours ' . $jobs[$userInfo[0]['job']]  . '?';
			}
			
			echo '</div>';
			
		}
		?>
	
	<div id="splitBar">
	</div>
	
	<?php
	if($_GET['p'] == 'cr'){
		//PAGE CREATIONS
		
		function searchByCat($cat, $array) {
			$temp=array();
			foreach ($array as $key => $val) {
				if ($val['cat'] == $cat) {
					array_push($temp, $val);
				}
			}
			return $temp;
		}
		
		$sql="SELECT *
			FROM creations
			ORDER BY id DESC";
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		
		$creaRev=searchByCat('rev',$res);
		$creaAff=searchByCat('aff',$res);
		$creaComm=searchByCat('comm',$res);
		$creaBd=searchByCat('bd',$res);
		$creaPoulp=searchByCat('poulp',$res);
		$creaSurv=searchByCat('surv',$res);
		$creaVid=searchByCat('vid',$res);
		
		
		//REV
		echo '<div id="revTitle">Révélations</div>';
		$firstCheck=true;
		echo '<div id="creaRev"><ul class="creationPrompt">';
		foreach($creaRev as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		//AFF
		echo '<div id="affTitle">Affiches</div>';
		$firstCheck=true;
		echo '<div id="creaAff"><ul class="creationPrompt">';
		foreach($creaAff as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		//COMM
		echo '<div id="commTitle">Commandements</div>';
		$firstCheck=true;
		echo '<div id="creaComm"><ul class="creationPrompt">';
		foreach($creaComm as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		//BD
		echo '<div id="bdTitle">Bandes dessinées</div>';
		$firstCheck=true;
		echo '<div id="creaBd"><ul class="creationPrompt">';
		foreach($creaBd as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		//POULP
		echo '<div id="poulpTitle">Poulp\'O\'Vid</div>';
		$firstCheck=true;
		echo '<div id="creaPoulp"><ul class="creationPrompt">';
		foreach($creaPoulp as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		//SURV
		echo '<div id="survTitle">Survie</div>';
		$firstCheck=true;
		echo '<div id="creaSurv"><ul class="creationPrompt">';
		foreach($creaSurv as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		//VID
		echo '<div id="vidTitle">Vidéos à part</div>';
		$firstCheck=true;
		echo '<div id="creaVid"><ul class="creationPrompt">';
		foreach($creaVid as $crea){
			if($crea['type'] == 'video'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '">';
				echo '<img class="playCrea" src="http://zenoo.fr/uploaded/play.png">';
				
				echo '</div></li>';
			}
			//Cas RP
			elseif($crea['type'] == 'text'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<p class="extract">' . substr($crea['desc'],0,100) . ' ...</p></div></li>';
			}
			//Cas img
			elseif($crea['type'] == 'img'){
				echo '<li><p class="firstP"> ' . $crea['title'] . '</p>';
				if($firstCheck){
					echo '<div class="content firstCheck" data-id="' . $crea['id']  . '">';
					$firstCheck=false;
				} 
				else echo '<div class="content" data-id="' . $crea['id']  . '">';
				echo '<img class="thumbnail" src="http://zenoo.fr/uploaded/hordes/' . $crea['img'] . '"></div></li>';
			}
		}
		echo '</ul></div>';
		
		echo '<script src="js/cat.js"></script>';
		
	}
	elseif($_GET['p'] == 'r'){
		//PAGE REJOINS NOUS
		echo '<div id="join">';
		
		echo '<p id="joincatch">Si toi aussi tu veux découvrir la sagesse, découvrir la vérité, rejoins notre cause et certifie sur l\'honneur ta bonne conduite.<br />
			Les commandements que tu devras suivre tout au long de ta vie hordienne, sans eux tu ne pourras découvrir la vérité :</p>';

		echo '</div>';
		
		echo '<img id="arrowleft" src="img/arrow.png">';
		echo '<div id="comms"><img src="img/comm1.png"></div>';
		echo '<img id="arrowright" src="img/arrow.png">';
		
		echo '<button id="certifbtn">Je certifie sur l\'honneur suivre les commandements de sage divin.</button>';
		echo '<div id="certif"><img src="img/ermbook.png"><p id="certifname">' . $userInfo[0]['name'] . '</p></div>';
		echo '<script src="js/join_arrows.js"></script>';
	}
	elseif($_GET['p'] == 'co'){
		//PAGE CONTACTS
		echo '<div id="live_cont">
				<div id="liveTitle"><p>Live chat</p></div>';
		
		$mites = array(847,7745,19461,37240,4647,88248,147038,306301,502354,582552,699236,885180,1138796,1297151,1404020,1508643,1549077,1574765,1607238,1672355,1679224,1719154,1725878,1726821,1732568,1735609,1738652,1807415,1987484,2406954,2568393,2578707,3077869,3322662,4665185,7801138,8561951,8784052);
		if(in_array($userInfo[0]['id'], $mites)){
			echo '<p id="op"><input type="checkbox" name="sage" id="sage">Poster en tant que Sage</p>';
		}
		
		echo '<div id="live"></div>
				<div id="sending">
					<form name="tchat" action="">
						<input type="text" name="textToSend" id="textToSend" />
						<input type="hidden" name="pseudo" id="pseudo" value="' . $userInfo[0]['name'] . '">
						<input type="hidden" name="job" id="job" value="' . $userInfo[0]['job'] . '">
						<input type="submit" name="submit" id="submit_btn" value="Envoyer" />
					</form>
				</div>
			</div>';
		
		echo '<div id="smiley_info">
					<img src="../resources/forum/smiley/h_smile.gif">
					<img src="../resources/forum/smiley/h_sad.gif"> 
					<img src="../resources/forum/smiley/h_sleep.gif">
					<img src="../resources/forum/smiley/h_rage.gif">
					<img src="../resources/forum/smiley/h_sick.gif">
					<img src="../resources/forum/smiley/h_pa.gif">
					<img src="../resources/forum/smiley/h_blink.gif">
					<img src="../resources/forum/smiley/h_calim.gif">
					<img src="../resources/forum/smiley/h_bag.gif">
					<img src="../resources/forum/smiley/h_exas.gif">
					<img src="../resources/forum/smiley/h_middot.gif">
					<img src="../resources/forum/smiley/h_door.gif">
					<img src="../resources/forum/smiley/h_surprise.gif">
					<img src="../resources/forum/smiley/h_lol.gif">
					<img src="../resources/forum/smiley/h_neutral.gif">
				</div>';
		
		echo '<script src="js/live.js"></script>';
	}
	elseif($_GET['p'] == 'c'){
		//PAGE VUE CREA
		if(isset($_GET['id'])){
			$sql="SELECT *
				FROM creations
				WHERE id = " . $_GET['id'];
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			
			//Cas video
			if($res[0]['type'] == 'video'){
				echo '<div id="video_cont"><video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered"
					  controls preload="auto" width="533" height="300"
					  poster="http://zenoo.fr/uploaded/hordes/' . $res[0]['img'] . '"
					  data-setup=\'{"example_option":true}\'>
					 <source src="http://zenoo.fr/uploaded/hordes/' . $res[0]['vid'] . '" type=\'video/mp4\' />
					 <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
					</video></div>';
				
				echo '<p class="info titleVid">' . $res[0]['title'] . '</p>';
			}
			//Cas RP
			elseif($res[0]['type'] == 'text'){
				echo '<div id="textCrea">
						<p id="creaTextTitle">' . $res[0]['title'] . '</p>
						<p id="creaText">' . $res[0]['desc'] . '<br /><br /><br /><br /></p>
					</div>';
			}
			//Cas img
			elseif($res[0]['type'] == 'img'){
				echo '<p class="info titleImg">' . $res[0]['title'] . '</p>';
				echo '<img id="crea" src="http://zenoo.fr/uploaded/hordes/' . $res[0]['img'] . '">';
			}
			
		}
		
	}
	
	//PAGE DEFAULT
	else{
		//SLIDER
		echo '
			<div id="slider">
				<div class="flipster">
				  <ul>
					<li "Button Block">
							<img src="img/ermite.png">
					</li>
					<li "Button Block">
							<img src="img/bichon.png">
					</li>
					<li "Button Block">
							<img src="img/eclai.png">
					</li>
					<li "Button Block">
							<img src="img/gardien.png">
					</li>
					<li "Button Block">
							<img src="img/tech.png">
					</li>
					<li "Button Block">
							<img src="img/fouine.png">
					</li>
					<li "Button Block">
							<img src="img/habi.png">
					</li>
				  </ul>
				</div>
		</div>';
		
		echo '<script>
		<!--

			$(function(){ $(".flipster").flipster({ style: \'carousel\', start: ';
			
		if($userInfo[0]['job'] == 'tamer') echo '1';
		elseif($userInfo[0]['job'] == 'eclair') echo '2';
		elseif($userInfo[0]['job'] == 'guardian') echo '3';
		elseif($userInfo[0]['job'] == 'tech') echo '4';
		elseif($userInfo[0]['job'] == 'collec') echo '5';
		elseif($userInfo[0]['job'] == 'basic') echo '6';
		else echo '0';
			
		echo '}); });

		-->
		</script>
		';
		
		//DERNIERES CREAS	
		$sql="SELECT *
			FROM creations
			ORDER BY id DESC";
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		
		
		
		//MINIATURE 1ST ELEMENT
		echo '<div id="lastAHs">
			<a href="http://sauveloutremonde.zenoo.fr?p=c&id=' . $res[0]['id'] . '">
			<div id="lastAH">';
		//Cas video
		if($res[0]['type'] == 'video'){
			echo '<img class="thumb" src="http://zenoo.fr/uploaded/hordes/' . $res[0]['img'] . '">';
			echo '<img class="play" src="http://zenoo.fr/uploaded/play.png">';
		}
		//Cas RP
		elseif($res[0]['type'] == 'text'){
			echo '<p id="mini_RP">' . substr($res[0]['desc'],0,600) . ' ...</p>';
		}
		//Cas img
		elseif($res[0]['type'] == 'img'){
			echo '<img class="thumb" src="http://zenoo.fr/uploaded/hordes/' . $res[0]['img'] . '">';
		}
		echo '<div id="lastAHtitle">
					<p class="info">' . $res[0]['title'] . '</p>
				</div></div></a>';
			
		//MINIATURE 2ND ELEMENT
		echo '
			<a href="http://sauveloutremonde.zenoo.fr?p=c&id=' . $res[1]['id'] . '">
			<div id="lastAH2">';
		
		//Cas video
		if($res[1]['type'] == 'video'){
			echo '<img class="thumb" src="http://zenoo.fr/uploaded/hordes/' . $res[1]['img'] . '">';
			echo '<img class="play" src="http://zenoo.fr/uploaded/play.png">';
		}
		//Cas RP
		elseif($res[1]['type'] == 'text'){
			echo '<p id="mini_RP">' . substr($res[1]['desc'],0,600) . ' ...</p>';
		}
		//Cas img
		elseif($res[1]['type'] == 'img'){
			echo '<img class="thumb" src="http://zenoo.fr/uploaded/hordes/' . $res[1]['img'] . '">';
		}
		echo '<div id="lastAHtitle">
					<p class="info">' . $res[1]['title'] . '</p>
				</div>
			</a></div>';
			
		//AUTRES CREAS
		echo '<div id="othersAH1">
				  <ul>';
		for($i=2;$i<17;$i++){
			echo '<li><a href="http://sauveloutremonde.zenoo.fr?p=c&id=' . $res[$i]['id'] . '">' . $res[$i]['title'] . '</a></li>';
		}
		echo '</ul>
			</div>';
			
		echo '<div id="seeMore">
				<p class="info"><a href="http://sauveloutremonde.zenoo.fr?p=cr">Voir plus</a></p>
			</div>
		</div>';
		
		//DERNIER MEMBRE
		echo '
			<div id="newMember">
				<div id="member_cont">
				
					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/2/c/a8ea44db_1138796_100x100.png">
					<p class="memberName">Arkachacal</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/4/d/7891b8e3_1611123_100x100.png">
					<p class="memberName">Asmaria</p>
					</div>
					<div class="memberQuote">Chalut</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/f/3/00460b63_2406954_100x100.png">
					<p class="memberName">BarTabac</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/6/d/cb26f7d3_582552_100x100.png">
					<p class="memberName">Blastgwen</p>
					</div>
					<div class="memberQuote">Osez, le progrès est à ce prix</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/3/e/b326e189_502354_100x100.png">
					<p class="memberName">Bouloui</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/0/6/81ef1ab6_88248_100x100.png">
					<p class="memberName">CalicoJack</p>
					</div>
					<div class="memberQuote">Avant j\'étais comme toi... Mais ça c\'était avant !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/f/9/cc783923_147038_100x100.png">
					<p class="memberName">Quinoa</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/5/9/94f451c9_1607238_100x100.png">
					<p class="memberName">Cocotouch</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/c/8/8b7fa734_1987484_100x100.png">
					<p class="memberName">DocteurMad</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/a/b/9b9aef2d_1508643_100x100.png">
					<p class="memberName">Fodase</p>
					</div>
					<div class="memberQuote">Allez viens boire un p\'ti coup dans l\'outre monde !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/b/6/c0274dee_1738652_100x100.png">
					<p class="memberName">Fodwolf</p>
					</div>
					<div class="memberQuote">Le pipi c\'est la vie!</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/7/5/6642ae39_19461_100x100.png">
					<p class="memberName">Fred26</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/a/1/e8387a41_1549077_100x100.jpg">
					<p class="memberName">Furet</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/8/8/0092da36_1732568_100x100.png">
					<p class="memberName">Furie61</p>
					</div>
					<div class="memberQuote">On ne né pas Ermite, on le devient</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/b/d/3576dffc_8784052_100x100.png">
					<p class="memberName">Gawlo</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/a/c/40b42598_1404020_100x100.jpg">
					<p class="memberName">Geonimo</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/2/0/6afee58f_7801138_100x100.png">
					<p class="memberName">Katniss</p>
					</div>
					<div class="memberQuote">dans l\'outre monde ta voie tu trouveras</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/9/3/6e1fa9f7_699236_100x100.png">
					<p class="memberName">Keres</p>
					</div>
					<div class="memberQuote">Donne aux autres plus qu’à toi même.</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/d/7/6092fe8d_6418749_100x100.gif">
					<p class="memberName">Lechanceux</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/d/8/4b4ee3d8_3322662_100x100.jpg">
					<p class="memberName">Letiti83</p>
					</div>
					<div class="memberQuote">Et au loin la Lumiere !!!</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/0/9/78711b98_23820_100x100.gif">
					<p class="memberName">Mazou</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/9/4/b0765d23_37240_100x100.jpg">
					<p class="memberName">MissP</p>
					</div>
					<div class="memberQuote">Nature, relève toi !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/f/2/149d324e_2568393_100x100.gif">
					<p class="memberName">NeCa</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/b/7/adb802a2_306301_100x100.png">
					<p class="memberName">Never69Hide</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/6/4/f4d2dc0d_4665185_100x100.jpg">
					<p class="memberName">Note</p>
					</div>
					<div class="memberQuote">Fermez, démontez, roulez !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/e/b/f15da558_7745_100x100.jpg">
					<p class="memberName">Oasis971</p>
					</div>
					<div class="memberQuote">Je suis nul !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/d/a/a4f5c787_1738884_100x100.jpg">
					<p class="memberName">Otisvonklank</p>
					</div>
					<div class="memberQuote">Je suis nul !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/a/c/d9ac671e_1679224_100x100.png">
					<p class="memberName">Pipopi</p>
					</div>
					<div class="memberQuote">Le manuel est éternel</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/9/c/22f8e31b_1719154_100x100.png">
					<p class="memberName">Prostipoulpe</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/f/9/cc783923_147038_100x100.png">
					<p class="memberName">Quinoa</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/6/c/ec0d91f5_3077869_100x100.gif">
					<p class="memberName">sAnTa</p>
					</div>
					<div class="memberQuote">Perdu? Suivez le manuel du berger !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/9/9/8eb15bbb_1672355_100x100.png">
					<p class="memberName">Saurrel</p>
					</div>
					<div class="memberQuote">Encore eût-il fallusse, que je le susse</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/9/4/d749f5f4_2024775_100x100.png">
					<p class="memberName">Shaita</p>
					</div>
					<div class="memberQuote">Je suis nul !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/f/5/e4c06c60_885180_100x100.gif">
					<p class="memberName">Sierraleon</p>
					</div>
					<div class="memberQuote">Je suis nul !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/d/9/565e9094_1807415_100x100.gif">
					<p class="memberName">Smerkoff</p>
					</div>
					<div class="memberQuote">La mémoire est un livre qui ne se ferme jamais.</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/4/8/1f4da7c5_1735609_100x100.jpg">
					<p class="memberName">TexSilver</p>
					</div>
					<div class="memberQuote">Camper dans l\'outre monde, c\'est prendre congé de ses semblables.</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/a/b/96d5d4ab_1297151_100x100.jpg">
					<p class="memberName">Truth</p>
					</div>
					<div class="memberQuote">Rescpecte l\'outre monde et le ciel t\'aidera !</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/4/6/e1a402af_1726821_100x100.png">
					<p class="memberName">VdRr</p>
					</div>
					<div class="memberQuote">Je suis nul</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/c/c/13effbbd_8561951_100x100.jpg">
					<p class="memberName">Venus</p>
					</div>
					<div class="memberQuote">Quand j\'etais petite, j\'étais comme toi, perdue.</div>
					</div>


					<div class="soloMember">
					<div class="member">
					<img class="avatar" src="http://imgup.motion-twin.com/twinoid/3/4/e28bfe04_4647_100x100.gif">
					<p class="memberName">Zenoo</p>
					</div>
					<div class="memberQuote">Bonsoir, âme égarée.</div>
					</div>
	

				</div>
			</div>'; 
			
/*
		//HEROS
		echo '<div id="heros">
			<p class="info">Heros</p>
			<div id="hero1">
				<p class="info">Hero 1</p>
			</div>
			<div id="hero2">
				<p class="info">Hero 2</p>
			</div>
		</div>';
		
		echo '<div id="bottomSpace">
			</div>';*/
	}
	?>
	
	
	
	
	
	<?php include('connected.php'); ?>
	
	
	<p id="footer">Copyright &copy; <?php echo date("Y"); ?> Zenoo</p>
	<p class="mt">CE SITE N'EST PAS AFFILIÉ À <a href="https://motion-twin.com/fr/">MOTION TWIN</a> - CERTAINS ÉLÉMENTS GRAPHIQUES APPARTIENNENT À <a href="https://motion-twin.com/fr/">MOTION TWIN</a>. </p>

</body>
</html>