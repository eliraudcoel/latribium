<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}	
	include 'conn.php';
	$date=$_POST["dateEv"];
	$titre=$_POST["titreEv"];
	$desc=$_POST["descEv"];
	
	$requete = $connexion -> query("SELECT max(LAST_INSERT_ID(idEv)) as nb FROM evenement");
	$reqSql = $requete->fetch(PDO::FETCH_OBJ); 
	$idMax = $reqSql->nb;
	$idMax = $idMax + 1;

	$stmt = $connexion->prepare("insert into evenement (idEv, nomEv, descriptionEv, dateEv) values (?, ?, ?, ?)");
	$stmt->bindParam(1, $idMax);
	$stmt->bindParam(2, $titre);
	$stmt->bindParam(3, $desc);
	$stmt->bindParam(4, $date);
	$stmt->execute();
	?>
		<script language='JavaScript'> 
			alert('le fichier a été ajouté!');
			setTimeout("document.location='../index.php?page=actualites.php&spage=1'", 500);
		</script>
	<?php
?>