<?php
	if(isset($_FILES)){
		
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
		
		//print_r($_FILES);
		
		$file = $_FILES['file'];
		$finalEcho='';
		$creatorId = $_POST['creatorId'];
		
		if ($file['error'] > 0){
			$finalEcho = "Transfer error";
			echo $finalEcho;
			exit();
		}
		
		if ($file['size'] > 500000){
			$finalEcho='File too big';
			echo $finalEcho;
			exit();
		}
		
		$validextensions = array("jpeg", "jpg", "png", "gif");
		$temporary = explode(".", $file["name"]);
		$file_extension = end($temporary);
		if (!((($file["type"] == "image/png") || ($file["type"] == "image/jpg") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/gif")) && in_array($file_extension, $validextensions))){
			$finalEcho='File not valid (' . $file["type"] . ')';
			echo $finalEcho;
			exit();
		}
		
		$image_sizes = getimagesize($file['tmp_name']);
		if ($image_sizes[0] != 80 OR $image_sizes[1] != 80){
			$finalEcho='Size not valid.';
			echo $finalEcho;
			exit();
		}
		
		if (!file_exists('img/upl/' . $creatorId)) {
			mkdir('img/upl/' . $creatorId, 0777, true);
		}
		
		$time = time();
		
		if(isset($_POST['profilePrompt'])){
			$sql='INSERT INTO creations(creatorId,extension,timestamp,accepted) VALUES(' . $creatorId . ', "' . $file_extension . '", ' . $time . ', 2);';
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		else{
			$sql='INSERT INTO creations(creatorId,extension,timestamp) VALUES(' . $creatorId . ', "' . $file_extension . '", ' . $time . ');';
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		
		$sql='SELECT * FROM creations WHERE creatorId = ' . $creatorId . ' AND timestamp = ' . $time . ';';
		$prep=$connexion->prepare($sql);
		$prep->execute();
		$res=$prep->fetchAll(PDO::FETCH_ASSOC);
		
		if(!isset($_POST['onlyProfile'])){
			$sql='SELECT * FROM conv WHERE requestId = ' . $_POST['requestId'] . ';';
			$prep=$connexion->prepare($sql);
			$prep->execute();
			$res2=$prep->fetchAll(PDO::FETCH_ASSOC);
			
			$sql='INSERT INTO conv(requestId,senderId,messageId,creationId,text,timestamp) VALUES(' . $_POST['requestId'] . ', ' . $creatorId . ', ' . count($res2) . ', ' . $res[0]['id'] . ', "", ' . $time . ');';
			$prep=$connexion->prepare($sql);
			$prep->execute();
		}
		
		$newFileName = 'img/upl/' . $creatorId . '/' . $res[0]['id'] . '.' . $file_extension;
		$upload = move_uploaded_file($file['tmp_name'],$newFileName);
		if($upload) $finalEcho='Transfer completed';
		else $finalEcho='Final transfer failed';
		
		echo $finalEcho;
		
	}
?>