<?php

	if(isset($_POST['userRequest']) || isset($_POST['newRequest']) || isset($_POST['requestId']) || isset($_POST['acceptAv']) ||isset($_POST['checkAlreadyRequested']) || isset($_POST['becomeCrea']) || isset($_POST['cancelCrea']) || isset($_POST['cancelReq']) || isset($_POST['deleteReq'])){
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
		if(isset($_POST['userRequest'])){
			if(isset($_POST['onlyProfile'])) $sql='SELECT * FROM request WHERE (requesterId = ' . $_POST['userRequest'] . ' OR creators LIKE "%' . $_POST['userRequest'] . '%") AND completed = 0  AND deleted = 0 ORDER BY id DESC;';
			elseif($_POST['userRequest'] > 0) $sql='SELECT * FROM request WHERE requesterId = ' . $_POST['userRequest'] . ' AND deleted = 0 ORDER BY id DESC;';
			else $sql='SELECT * FROM request WHERE deleted = 0 ORDER BY id DESC;';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		elseif(isset($_POST['deleteReq'])){
			$sql='UPDATE request SET deleted = 1 WHERE id = ' . $_POST['reqId'] . ';';
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		elseif(isset($_POST['requestId'])){
			$sql='SELECT * FROM request WHERE id = ' . $_POST['requestId'] . ';';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		elseif(isset($_POST['newRequest'])){
			if($_POST['newRequest'] == 1){
				
				$sql='SELECT * FROM request WHERE requesterId = ' . $_POST['twinId'] . ' AND completed = 0 AND deleted = 0;';
				$prep=$connexion->prepare($sql);
				$prep->execute();
				$resTmp=$prep->fetchAll(PDO::FETCH_ASSOC);
				if(!count($resTmp)>0){
					
					$desc = str_replace('"', '\"', $_POST['desc']);
					$title = str_replace('"', '\"', $_POST['title']);
					
					$sql='INSERT INTO request(requesterId,name,title,description,creators) VALUES(' . $_POST['twinId'] . ', "' . $_POST['name'] . '", "' . $title . '", "' . $desc . '", "");';
					$prep=$connexion->prepare($sql);
					$prep->execute();
					
					$sql='SELECT * FROM request WHERE requesterId = ' . $_POST['twinId'] . ' AND title = "' . $title . '" AND description = "' . $desc . '";';
					$prep=$connexion->prepare($sql);
					$prep->execute();
					$res=$prep->fetchAll(PDO::FETCH_ASSOC);
					
					$sql='SELECT * FROM conv WHERE requestId = ' . $res[0]['id'] . ';';
					$prepTmp=$connexion->prepare($sql);
					$prepTmp->execute();
					$resTmp=$prepTmp->fetchAll(PDO::FETCH_ASSOC);
					
					$sql='INSERT INTO conv(requestId,senderId,messageId,text,timestamp) VALUES(' . $res[0]['id'] . ', ' . $_POST['twinId'] . ', ' . count($resTmp) . ', "' . $desc . '", ' . time() . ');';
					$prep2=$connexion->prepare($sql);
					$prep2->execute();
					
					echo json_encode($res);
				}
			}
		}
		elseif(isset($_POST['acceptAv'])){
			$sql='UPDATE request SET completed = 1, creators = "" WHERE id = ' . $_POST['reqId'] . ';'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
			
			$sql='UPDATE creations SET accepted = 1 WHERE id = ' . $_POST['creationId'] . ';'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		elseif(isset($_POST['checkAlreadyRequested'])){
			$sql='SELECT * FROM request WHERE requesterId = ' . $_POST['userId'] . ' AND completed = 0 AND deleted = 0;';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res=$prep->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($res);
		}
		elseif(isset($_POST['becomeCrea'])){
			$sql='UPDATE request SET creators = CONCAT(creators, \'' . $_POST['creatorId'] . ',\') WHERE id = ' . $_POST['reqId'] . ';'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		elseif(isset($_POST['cancelCrea'])){
			$sql='UPDATE request SET creators = REPLACE(creators,\'' . $_POST['creatorId'] . ',\',\'\') WHERE id = ' . $_POST['reqId'] . ';'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		elseif(isset($_POST['cancelReq'])){
			$sql='UPDATE request SET completed = 1, creators = "" WHERE id = ' . $_POST['reqId'] . ';'; 
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
	}

?>