<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'conn.php';
	$id= $_POST['idFichier'];
	$requete = $connexion-> query("SELECT * FROM agenda WHERE idAgenda='".$id."'");
	if($requete)
	{
		while($row = $requete->fetch())
			{			
				# Extraction 
				$name=$row['nomFichier']; 
				header("Pragma: public"); 
				header("Expires: 0"); 
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
				header("Content-Type: ".$row['typeFichier']); 
				header("Content-Length: ".$row['tailleFichier']); 
				header("Content-Disposition: inline; filename=download_LaTribium-".$name); 
				header("Content-Transfer-Encoding: binary"); 
				print $row['contenuFichier']; 
			} 
	} 
	else 
	{ 
		$mess="Le fichier demandé n'existe pas."; 
		header("location:index.php?page=actualites.php?mess=".$mess); 
	} 
?>