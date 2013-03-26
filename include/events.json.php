<?php
	header('Content-type: text/json');
	include 'conn.php';
	$separator = ",";
	$i=1;
	
	$requete = $connexion -> query("SELECT count(idEv) as nb FROM evenement");
	$reqSql = $requete->fetch(PDO::FETCH_OBJ); 
	$idMax = $reqSql->nb;

	$requete = $connexion-> query("SELECT * from evenement");
	
	echo '[';
	while($row = $requete->fetch())
	{
		echo '	{ "date": "';echo $row['dateEv'];echo'", "title": "';echo $row['nomEv'];echo'", "description": "';echo $row['descriptionEv'];echo'" }';

		if($i == $idMax)
		{
			$separatorFin = " ";
			echo $separatorFin;
		}else
		{
			echo $separator;
		}
		$i=$i+1;
	}
	echo ']';
	?>