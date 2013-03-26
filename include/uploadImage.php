<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'conn.php';
	$taille_maxi = 100000000;
	$extensions = array('.png','.jpg','.gif','.jpe','.jpeg','.tif','.tiff','.PNG','.JPG','.GIF','.JPE','.JPEG','.TIF','.TIFF');
	 if (is_uploaded_file($_FILES["imageAnnonce"]["tmp_name"]))
	 {
		$fichier = $_FILES['imageAnnonce']['name'];
		$mime = $_FILES['imageAnnonce']['type'];
		$taille = filesize($_FILES['imageAnnonce']['tmp_name']);
		$extension = strrchr($_FILES['imageAnnonce']['name'], '.');
		$contenu = fopen($_FILES['imageAnnonce']['tmp_name'], 'rb');
		$titre = $_POST['titreAnnonce'];
		$descr = $_POST['descAnnonce'];
		$auteur = $_POST['pseudo'];
		$date = date("Y-m-d");		
		$prix = $_POST['prixAnnonce'];
		$prix = str_replace(' ', '', $prix);
		if (strpos($prix, ",") === true) { 
			$prix = str_replace(',', '.', $prix);
			$prix = (float) $prix;
		}
		
		//Vérifications de sécurité
		if(!in_array($extension,$extensions))
		{
			$erreur = 'Vous ne devez pas uploader un fichier de type php,doc...';
		}
		if($taille>$taille_maxi)
		{
			$erreur = 'Le fichier est trop gros...';
		}
		if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
		{
			//On formate le nom du fichier
			$fichier = strtr($fichier, 
			'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
			'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
			$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
			
			$requete = $connexion -> query("SELECT max(LAST_INSERT_ID(idImage)) as nb FROM images");
			$reqSql = $requete->fetch(PDO::FETCH_OBJ); 
			$idMax = $reqSql->nb;
			$idMax = $idMax + 1;
			
			$stmt = $connexion->prepare("insert into images (idImage, nomImage, typeImage, contenuImage, tailleImage) values (?, ?, ?, ?, ?)");
			$stmt->bindParam(1, $idMax);
			$stmt->bindParam(2, $fichier);
			$stmt->bindParam(3, $mime);
			$stmt->bindParam(4, $contenu, PDO::PARAM_LOB);
			$stmt->bindParam(5, $taille);
			$stmt->execute();
			
			$requete2 = $connexion -> query("SELECT max(LAST_INSERT_ID(idVente)) as nb FROM articleVentes");
			$reqSql2= $requete2->fetch(PDO::FETCH_OBJ); 
			$idMax2 = $reqSql2->nb;
			$idMax2 = $idMax2 + 1;
			
			$stmt2 = $connexion->prepare("insert into articleVentes (idVente, titreVente, descriptionVente, auteurVente, dateVente, prixVente, imageIdVente) values (?, ?, ?, ?, ?, ?, ?)");
			$stmt2->bindParam(1, $idMax2);
			$stmt2->bindParam(2, $titre);
			$stmt2->bindParam(3, $descr);
			$stmt2->bindParam(4, $auteur);
			$stmt2->bindParam(5, $date);
			$stmt2->bindParam(6, $prix);
			$stmt2->bindParam(7, $idMax);
			$stmt2->execute();
			

			?>
			<script language='JavaScript'> 
				alert('votre annonce a été ajouté!');
				setTimeout("document.location='../index.php?page=achats_ventes.php&spage=1'", 500);
			</script>
			<?php
		}
		else
		{
			echo('<script language=\'JavaScript\'>alert(\''.$erreur.'\');'); 
			echo('window.onload=function(){setTimeout(function(){history.back()},500);}');
			echo('</script>');
		}
	}else{ 
		echo('<script language=\'JavaScript\'>alert(\'le fichier n\'a pas été transféré\');'); 
		echo('window.onload=function(){setTimeout(function(){history.back()},500);}');
		echo('</script>');
		}
?>