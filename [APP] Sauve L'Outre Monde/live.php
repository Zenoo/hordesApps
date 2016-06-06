<?php 
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
	
	$sql='SELECT * FROM live ORDER BY id DESC LIMIT 30;';
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		
		for($i=(count($res)-1);$i>=0;$i--){
			
			$res[$i]['message'] = preg_replace('"\b(https?://\S+)"', '<a href="$1" target="_blank">$1</a>', $res[$i]['message']);
			$res[$i]['message'] = str_replace(
				array(':)', ':(', 'zzz', 'grr', 'berk', ':pa:', ';)', 'calim', ':bag:', '....', ':dot:', ':door:', ':o', ':D', '-_-'), 
				array('<img src="../resources/forum/smiley/h_smile.gif">', 
					'<img src="../resources/forum/smiley/h_sad.gif">', 
					'<img src="../resources/forum/smiley/h_sleep.gif">',
					'<img src="../resources/forum/smiley/h_rage.gif">',
					'<img src="../resources/forum/smiley/h_sick.gif">',
					'<img src="../resources/forum/smiley/h_pa.gif">',
					'<img src="../resources/forum/smiley/h_blink.gif">',
					'<img src="../resources/forum/smiley/h_calim.gif">',
					'<img src="../resources/forum/smiley/h_bag.gif">',
					'<img src="../resources/forum/smiley/h_exas.gif">',
					'<img src="../resources/forum/smiley/h_middot.gif">',
					'<img src="../resources/forum/smiley/h_door.gif">',
					'<img src="../resources/forum/smiley/h_surprise.gif">',
					'<img src="../resources/forum/smiley/h_lol.gif">',
					'<img src="../resources/forum/smiley/h_neutral.gif">'
					), 
				$res[$i]['message']);


			
			//Si OP
			if($res[$i]['op'] == 1) $res[$i]['pseudo'] = 'Le Sage';
			
			//Hack dégueu
			if($res[$i]['date_min'] < 10) $res[$i]['date_min'] = '0' . $res[$i]['date_min'];
			
			//Affichage
			echo '<p class="liveMess">
			[' . $res[$i]['date_hour'] . ':' . $res[$i]['date_min'] . '] ';
			
			if($res[$i]['op'] == 1) echo '<img src="../resources/icons/r_ermwin.gif">';
			else echo '<img src="../resources/icons/job_' . $res[$i]['job'] . '.gif"> ';
			
			echo '&lt;' . $res[$i]['pseudo'] . '&gt; 
			' . $res[$i]['message'] . '
			</p>';
		}
	
?>