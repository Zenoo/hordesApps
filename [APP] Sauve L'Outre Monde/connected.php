<?php
	$sql='SELECT COUNT(*) AS count FROM connected WHERE ip=\'' . $_SERVER['REMOTE_ADDR'] . '\'';
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);


	if ($res[0]['count'] == 0) {
		$sql='INSERT INTO connected VALUES(\'' . $_SERVER['REMOTE_ADDR'] . '\', ' . time() . ')';
		$prep=$connexion->prepare($sql);
		$prep->execute();
	}
	else {
		$sql='UPDATE connected SET timestamp=' . time() . ' WHERE ip=\'' . $_SERVER['REMOTE_ADDR'] . '\'';
		$prep=$connexion->prepare($sql);
		$prep->execute();
	}

	$timestamp_5min = time() - (60 * 5);
	$sql='DELETE FROM connected WHERE timestamp < ' . $timestamp_5min;
	$prep=$connexion->prepare($sql);
	$prep->execute();


	$sql='SELECT COUNT(*) AS count FROM connected';
	$prep=$connexion->prepare($sql);
	$prep->execute();
	$res=$prep->fetchAll(PDO::FETCH_ASSOC);

	echo '<p id="connected">&nbsp;Il y a actuellement ' . ($res[0]['count']+2) . ' &acirc;mes &eacute;gar&eacute;es connect&eacute;es !&nbsp;&nbsp;</p>'; 
	
?>