<?php
/*ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', true );
*/
	include_once('include/session.php');
	include_once('../../uploaded/dBug.php');
	
	if(!isset($_SESSION['hordesGeneral'])){
		header("Location: " . twin_auth_href());
		exit();
	}
	
	echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>BienTaMap</title>
		<link rel="icon" href="favicon.ico" />
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	
	<body>
	<div id="expe">Mode expé</div>
	<button id="valid">OK</button>
	<div id="pageCache"><div id="caseContentEdit"></div></div>
	<div id="expeList"><span>Tracés</span></div>';
	
	$today = getdate();
	if($today['hours'] == 0 && $today['minutes'] < 37){
		echo '<div id="errorH"><p>Le site subit des attaques répé... HaaaAAAARGH <img src="http://www.hordes.fr/favicon.ico"></p></div>';
		exit();
	}
	
	
	//Get du nb de zz sur la case
	$initZfound=0;
	foreach($_SESSION['hordesMap']->zones as $zone){
		if($zone->x == $_SESSION['hordesGeneral']->x && $zone->y == $_SESSION['hordesGeneral']->y){
			$initZfound=$zone->z;
			break;
		}
	}
	
	$afterZfound=NULL;
	
	
	//GESTION BDD
	$PARAM_hote='es15.siteground.eu';
	$PARAM_nom_bd='zenoo829_zooMap';
	$PARAM_utilisateur='zenoo829_su'; 
	$PARAM_mdp='n9q4nzn9q4nz';

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
	
	function searchByPos($x, $y, $array) {
		foreach ($array as $key => $val) {
			
			if ($val['x'] == $x && $val['y'] == $y) {
				return $key;
			}
		}
		return null;
	}
	
	//Stripes
	$op=array(8784052,1679224,3077869,1738652,4647,88248);
	$sql='SELECT * FROM opcheck WHERE id = ' . $_SESSION['hordesGeneral']->mapId;
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$opcheck=$prep->fetchAll(PDO::FETCH_ASSOC);
	
	if(in_array($_SESSION['hordesGeneral']->twinId, $op)){
		echo '<p id="triggerChecking">Editer les cases &agrave; visiter.</p>';
	}


	//Vérif table créée
	$sql="SELECT COUNT(*)
		FROM information_schema.tables 
		WHERE table_schema = 'zenoo829_zooMap' 
		AND table_name = 'M" . $_SESSION['hordesGeneral']->mapId . "';";
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);
	
	//Map connue
	reset($res[0]);
	if(current($res[0]) != 0){
		
		//Check si case déjà maj today en init
		$sql='SELECT initZ,afterZ FROM M' . $_SESSION['hordesGeneral']->mapId . ' WHERE x = ' . $_SESSION['hordesGeneral']->x . ' AND y = ' . $_SESSION['hordesGeneral']->y . ' AND day = ' . $_SESSION['hordesMap']->days;
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		//Si case non maj
		if($res == Array()){	
			if($initZfound == 0){
				$insertSQL='INSERT INTO M' . $_SESSION['hordesGeneral']->mapId . '(day,x,y,initZ,afterZ,initPseudo,afterPseudo) VALUES(' . $_SESSION['hordesMap']->days . ', ' . $_SESSION['hordesGeneral']->x . ', ' . $_SESSION['hordesGeneral']->y . ', ' . $initZfound . ', ' . $initZfound . ', "' . $_SESSION['hordesGeneral']->name . '", "' . $_SESSION['hordesGeneral']->name . '")';
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
				
				//Delete stripes
				$StripesCheck=searchByPos($_SESSION['hordesGeneral']->x,$_SESSION['hordesGeneral']->y,$opcheck);
				if($StripesCheck >= 0){
					if($opcheck[$StripesCheck]['checked'] == 1){
						$insertSQL='UPDATE opcheck SET checked = 0 WHERE x = ' . $_SESSION['hordesGeneral']->x . ' AND y = ' . $_SESSION['hordesGeneral']->y . ' AND id = ' . $_SESSION['hordesGeneral']->mapId;
						$prep=$connexion->prepare($insertSQL);
						$prep->execute();
					}
				}
			}
			else{
				$insertSQL='INSERT INTO M' . $_SESSION['hordesGeneral']->mapId . '(day,x,y,initZ,initPseudo) VALUES(' . $_SESSION['hordesMap']->days . ', ' . $_SESSION['hordesGeneral']->x . ', ' . $_SESSION['hordesGeneral']->y . ', ' . $initZfound . ', "' . $_SESSION['hordesGeneral']->name . '")';
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
			}
		}
		
		//Si case déjà maj
		else{ 
			if($res[0]['initZ'] != NULL){
				$insertSQL='UPDATE M' . $_SESSION['hordesGeneral']->mapId . ' SET afterZ = ' . $initZfound . ', afterPseudo = "' . $_SESSION['hordesGeneral']->name . '" WHERE x = ' . $_SESSION['hordesGeneral']->x . ' AND y = ' . $_SESSION['hordesGeneral']->y . ' AND day = ' . $_SESSION['hordesMap']->days;
				$prep=$connexion->prepare($insertSQL);
				$prep->execute();
				
				if($initZfound == 0){
					//Delete stripes
					$StripesCheck=searchByPos($_SESSION['hordesGeneral']->x,$_SESSION['hordesGeneral']->y,$opcheck);
					if($StripesCheck >= 0){
						if($opcheck[$StripesCheck]['checked'] == 1){
							$insertSQL='UPDATE opcheck SET checked = 0 WHERE x = ' . $_SESSION['hordesGeneral']->x . ' AND y = ' . $_SESSION['hordesGeneral']->y . ' AND id = ' . $_SESSION['hordesGeneral']->mapId;
							$prep=$connexion->prepare($insertSQL);
							$prep->execute();
						}
					}
				}

			}
			$afterZfound=$initZfound;
			$initZfound=$res[0]['initZ'];
			
		}
		
		
		
	}
	//Map inconnue
	else{
		// sql to create table
		$sql = "CREATE TABLE M" . $_SESSION['hordesGeneral']->mapId . " (
		day INT(2) UNSIGNED, 
		x INT(2) NOT NULL,
		y INT(2) NOT NULL,
		initZ INT(2),
		afterZ INT(2),
		initPseudo VARCHAR(50),
		afterPseudo VARCHAR(50),
		primary key (day, x, y)
		)";
		$connexion->exec($sql);
		
		if($initZfound == 0){
			$insertSQL='INSERT INTO M' . $_SESSION['hordesGeneral']->mapId . '(day,x,y,initZ,afterZ,initPseudo,afterPseudo) VALUES(' . $_SESSION['hordesMap']->days . ', ' . $_SESSION['hordesGeneral']->x . ', ' . $_SESSION['hordesGeneral']->y . ', ' . $initZfound . ', ' . $initZfound . ', "' . $_SESSION['hordesGeneral']->name . '", "' . $_SESSION['hordesGeneral']->name . '")';
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
		}
		else{
			$insertSQL='INSERT INTO M' . $_SESSION['hordesGeneral']->mapId . '(day,x,y,initZ,initPseudo) VALUES(' . $_SESSION['hordesMap']->days . ', ' . $_SESSION['hordesGeneral']->x . ', ' . $_SESSION['hordesGeneral']->y . ', ' . $initZfound . ', "' . $_SESSION['hordesGeneral']->name . '")';
			$prep=$connexion->prepare($insertSQL);
			$prep->execute();
		}
	}


	
	
	
	
			//domDoc des bâts
			$dom2 = new DomDocument();
			$dom2->load('buildings.xml');
			$listBuildings = $dom2->getElementsByTagName("building");
			
			// cityName + Stockage position de la ville
			echo '<p class="city">					
					<span id="cityName">' . htmlentities($_SESSION['hordesMap']->city->name) . '</span><br />
					<span id="cityID" data-id="' . $_SESSION['hordesMap']->id . '" data-name="' . $_SESSION['hordesGeneral']->name . '" >ID: ' . $_SESSION['hordesMap']->id . '</span>
				</p>';
			$cityCoordX = $_SESSION['hordesMap']->city->x;
			$cityCoordY = $_SESSION['hordesMap']->city->y;

			//Début de la map -> Initialisation
			
			
			function searchByPosEveryDay($x, $y, $array) {
				$temp=array();
				foreach ($array as $key => $val) {
					if ($val['x'] == $x && $val['y'] == $y) {
						array_push($temp, $val);
					}
				}
				return $temp;
			}
			
			function sortByMostRecent($a, $b) {
				return $a['day'] - $b['day'];
			}
			
			
			
			
			//Stockage des infos dispos
			$sql='SELECT * FROM M' . $_SESSION['hordesGeneral']->mapId . ' WHERE day = ' . $_SESSION['hordesMap']->days;
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$infos=$prep->fetchAll(PDO::FETCH_ASSOC); //Zones updated today
			$sql='SELECT * FROM M' . $_SESSION['hordesGeneral']->mapId;
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$generalInfos=$prep->fetchAll(PDO::FETCH_ASSOC); // Every zone updated
			$maxMapX = $_SESSION['hordesMap']->wid;
			$maxMapY = $_SESSION['hordesMap']->hei;
			$listZones = $_SESSION['hordesMap']->zones;
			
			
			
			$building = false;
			$buildingID = 0;
			$buildingTitle = "";
			$nbPresents = 0;
			$foundZone = false;			
			echo '<table id="map" style="width:' . (21+$maxMapX*42) . 'px;height:' . (16+$maxMapY*42) . 'px"> <tbody style="width:100%;height:100%">
					<tr>
						<div id="overDiv"></div>
						<script src="overlib.js"></script>						<th class="location">-</th>'."\n";			//Affichage règle du haut
			for($k=0;$k<$maxMapX;$k++){
				echo '<th class="location">' . ($k-$cityCoordX) . '</th>'."\n";
			}
			echo '</tr>'."\n";
			for ($i=0;$i<$maxMapY;$i++){ //Boucle des Y
				echo '<tr>						<th class="location">' . ($cityCoordY-$i) . '</th>'."\n";
				for ($j=0;$j<$maxMapX;$j++){ //Boucle des X 
					
					//Ville 
					if ($i == $cityCoordY && $j == $cityCoordX){
						echo '<td data-city=1 data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . ' class="mapCase cityCase" href="javascript:void(0);" onmouseover="return overlib(\'[0,0] Ville\');" onmouseout="return nd();"></td>'."\n";
					}
					//Case actuelle
					elseif ($i == $_SESSION['hordesGeneral']->y && $j == $_SESSION['hordesGeneral']->x){
						echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . '  class="mapCase here" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . ']\');" onmouseout="return nd();">'."\n";
						
						//Stripes
						$checked=searchByPos($j,$i,$opcheck);
						if($checked >= 0){
							if($opcheck[$checked]['checked'] == 1){
								echo '<div class="stripes"></div>'."\n";
							}
						}
						//START OPCHECK
						if(in_array($_SESSION['hordesGeneral']->twinId, $op)){
							if($checked >= 0){
								if($opcheck[$checked]['checked'] == 1) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck" checked="checked"></div>'."\n";
								elseif($opcheck[$checked]['checked'] == 0) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
							}
							else echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
						}
						//END OPCHECK
						echo '<div class="init">' . $initZfound . '</div>'."\n";
						echo '<div class="after">' . $afterZfound . '</div>'."\n";
						echo '</td>'."\n";
					}
					
					
					// Vide
					else{
						foreach($listZones as $zns){
							// Si zone déjà explorée
							
							if($zns->x == $j && $zns->y == $i){
								//Erase du cas de la zone actuelle
								if ($i == $_SESSION['hordesGeneral']->y && $j == $_SESSION['hordesGeneral']->x){
									$foundZone = true;
									break;
								}
								
								//Bâtiment
								if($zns->building != null){
									$foundZone = true;
									if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
										$temp=searchByPosEveryDay($j,$i,$generalInfos);
										usort($temp, function($a, $b) {
											return $a['day'] - $b['day'];
										});
										$temp=array_reverse($temp);
										echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . ' data-build="' . $zns->building->type . '"  class="mapCase" style="background-image:url(\'../resources/mapHud/' . $zns->building->type . '.png\');background-size:100%" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . htmlentities($zns->building->name . '(MaJ il y a ' . ($_SESSION['hordesMap']->days-$temp[0]['day']) . ' jour(s))') . '\');" onmouseout="return nd();">'."\n";
									}
									else{
										echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . ' data-build="' . $zns->building->type . '"  class="mapCase" style="background-image:url(\'../resources/mapHud/' . $zns->building->type . '.png\');background-size:100%" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . htmlentities($zns->building->name) . '\');" onmouseout="return nd();">'."\n";
									}
									
									
										if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
											$temp=searchByPosEveryDay($j,$i,$generalInfos);
											usort($temp, function($a, $b) {
												return $a['day'] - $b['day'];
											});
											$temp=array_reverse($temp);
											echo '<div class="after">' . $temp[0]['afterZ'] . '</div>'."\n";
										}
									//Stripes
									$checked=searchByPos($j,$i,$opcheck);
									if($checked >= 0){
										if($opcheck[$checked]['checked'] == 1){
											echo '<div class="stripes"></div>'."\n";
										}
									}
									//START OPCHECK
									if(in_array($_SESSION['hordesGeneral']->twinId, $op)){
										if($checked >= 0){
											if($opcheck[$checked]['checked'] == 1) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck" checked="checked"></div>'."\n";
											elseif($opcheck[$checked]['checked'] == 0) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
										}
										else echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
									}
									//END OPCHECK
									echo '</td>'."\n";
									break; // Zone traitée
								}
								
								// Si non eplorée aujour'hui
								if($zns->nvt == 1){ 
									$foundZone = true;
									if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
										$temp=searchByPosEveryDay($j,$i,$generalInfos);
										usort($temp, function($a, $b) {
											return $a['day'] - $b['day'];
										});
										$temp=array_reverse($temp);
										echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . '  class="mapCase unseen" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . htmlentities('Non exploré aujourd hui (MaJ il y a ' . ($_SESSION['hordesMap']->days-$temp[0]['day']) . ' jour(s))') . '\');" onmouseout="return nd();">'."\n";
									}
									else{
										echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . '  class="mapCase unseen" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . htmlentities('Non exploré aujourd hui') . '\');" onmouseout="return nd();">'."\n";
									}
									
									
										if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
											$temp=searchByPosEveryDay($j,$i,$generalInfos);
											usort($temp, function($a, $b) {
												return $a['day'] - $b['day'];
											});
											$temp=array_reverse($temp);
											echo '<div class="after">' . $temp[0]['afterZ'] . '</div>'."\n";
										}
									//Stripes
									$checked=searchByPos($j,$i,$opcheck);
									if($checked >= 0){
										if($opcheck[$checked]['checked'] == 1){
											echo '<div class="stripes"></div>'."\n";
										}
									}
									//START OPCHECK
									if(in_array($_SESSION['hordesGeneral']->twinId, $op)){
										if($checked >= 0){
											if($opcheck[$checked]['checked'] == 1) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck" checked="checked"></div>'."\n";
											elseif($opcheck[$checked]['checked'] == 0) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
										}
										else echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
									}
									//END OPCHECK
									echo '</td>'."\n";
									break; // Zone traitée
								}	
								// Si explorée aujourd'hui 
								else{ 
									$toAdd="";
									if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
										$temp=searchByPosEveryDay($j,$i,$generalInfos);
										usort($temp, function($a, $b) {
											return $a['day'] - $b['day'];
										});
										$temp=array_reverse($temp);
										$toAdd=' (MaJ il y a ' . ($_SESSION['hordesMap']->days-$temp[0]['day']) . ' jour(s))';
									}
									if($zns->danger !== NULL){
										echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . '  class="mapCase zscale' . $zns->danger . $classtoAdd.'" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . $toAdd . '\');" onmouseout="return nd();">'."\n";
									}
									else echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . '  class="mapCase clean'.$classtoAdd.'" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . $toAdd . '\');" onmouseout="return nd();">'."\n";
									//Vérif si données dispo sur cette zone
									$verif=searchByPos($j, $i, $infos);
									if($verif >= 0){
										echo '<div class="init">' . $infos[$verif]['initZ'] . '</div>'."\n";
										if($infos[$verif]['afterZ'] != NULL){
											echo '<div class="after">' . $infos[$verif]['afterZ'] . '</div>'."\n";
										}
										else{
											if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
												$temp=searchByPosEveryDay($j,$i,$generalInfos);
												usort($temp, function($a, $b) {
													return $a['day'] - $b['day'];
												});
												$temp=array_reverse($temp);
												echo '<div class="after">' . $temp[0]['afterZ'] . '</div>'."\n";
											}
										}
										
									}
									else{
										if($zns->danger !== NULL){
											if($zns->danger == 1) echo '<div class="init">1-2</div>'."\n";
											if($zns->danger == 2) echo '<div class="init">2-5</div>'."\n";
											if($zns->danger == 3) echo '<div class="init">5+</div>'."\n";
											if($zns->danger == 4) echo '<div class="init">20+</div>'."\n";
										}
										if(searchByPosEveryDay($j,$i,$generalInfos) != Array()){
											$temp=searchByPosEveryDay($j,$i,$generalInfos);
											usort($temp, function($a, $b) {
												return $a['day'] - $b['day'];
											});
											$temp=array_reverse($temp);
											echo '<div class="after">' . $temp[0]['afterZ'] . '</div>'."\n";
										}
										
										
									}
									//Stripes
									$checked=searchByPos($j,$i,$opcheck);
									if($checked >= 0){
										if($opcheck[$checked]['checked'] == 1){
											echo '<div class="stripes"></div>'."\n";
										}
									}
									//START OPCHECK
									if(in_array($_SESSION['hordesGeneral']->twinId, $op)){
										if($checked >= 0){
											if($opcheck[$checked]['checked'] == 1) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck" checked="checked"></div>'."\n";
											elseif($opcheck[$checked]['checked'] == 0) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
										}
										else echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
									}
									//END OPCHECK
									echo '</td>'."\n";
									$foundZone = true;
									break; // Zone traitée
								}
							}
						}
						
						// Zone inexplorée
						if(!$foundZone){
							echo '<td data-exp="" data-expid="" data-x=' . $j . ' data-y=' . $i . '  class="mapCase neverSeen" href="javascript:void(0);" onmouseover="return overlib(\'[' . ($j-$cityCoordX) . ',' . ($cityCoordY-$i) . '] ' . htmlentities('Jamais exploré') . '\');" onmouseout="return nd();">'."\n";
							
							//Stripes
							$checked=searchByPos($j,$i,$opcheck);
							if($checked >= 0){
								if($opcheck[$checked]['checked'] == 1){
									echo '<div class="stripes"></div>'."\n";
								}
							}
							//START OPCHECK
							if(in_array($_SESSION['hordesGeneral']->twinId, $op)){
								if($checked >= 0){
									if($opcheck[$checked]['checked'] == 1) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck" checked="checked"></div>'."\n";
									elseif($opcheck[$checked]['checked'] == 0) echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
								}
								else echo '<div class="opbutton"><input type="checkbox" name="opCheck" class="opCheck"></div>'."\n";
							}
							//END OPCHECK
							
							echo '</td>'."\n";
						}
						$foundZone = false;
					}
				}
				echo '</tr>'."\n";
			}
			echo '</tbody></table>'."\n";

  
	
		

		?>
		
		<div id="footer"><p>Certaines images sont la propri&eacute;t&eacute; de MotionTwin.<p></div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/opcheck.js"></script>
		<script src="js/sides.js"></script>
	</body>
</html>