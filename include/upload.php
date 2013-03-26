<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'conn.php';
	$taille_maxi = 100000000;
	$nouveauFichier = $_POST['nomFichier'];
	$extensions = '.php';
	 if (is_uploaded_file($_FILES["fichierAgenda"]["tmp_name"]))
	 {
		$fichier = $_FILES['fichierAgenda']['name'];
		$mime = $_FILES['fichierAgenda']['type'];
		$taille = filesize($_FILES['fichierAgenda']['tmp_name']);
		$extension = strrchr($_FILES['fichierAgenda']['name'], '.');
		$contenu = fopen($_FILES['fichierAgenda']['tmp_name'], 'rb');

		//Vérifications de sécurité
		if($extension == $extensions)
		{
			$erreur = 'Vous ne devez pas uploader un fichier de type php';
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
			
			$requete = $connexion -> query("SELECT max(LAST_INSERT_ID(idAgenda)) as nb FROM agenda");
			$reqSql = $requete->fetch(PDO::FETCH_OBJ); 
			$idMax = $reqSql->nb;
			$idMax = $idMax + 1;
			if($nouveauFichier == null)
			{
				$stmt = $connexion->prepare("insert into agenda (idAgenda, nomAgenda, nomFichier, typeFichier, contenuFichier, tailleFichier) values (?, ?, ?, ?, ?, ?)");
				$stmt->bindParam(1, $idMax);
				$stmt->bindParam(2, $fichier);
				$stmt->bindParam(3, $fichier);
				$stmt->bindParam(4, $mime);
				$stmt->bindParam(5, $contenu, PDO::PARAM_LOB);
				$stmt->bindParam(6, $taille);
				$stmt->execute();
			}
			else
			{
				$stmt = $connexion->prepare("insert into agenda (idAgenda, nomAgenda, nomFichier, typeFichier, contenuFichier, tailleFichier) values (?, ?, ?, ?, ?, ?)");
				$stmt->bindParam(1, $idMax);
				$stmt->bindParam(2, $nouveauFichier);
				$stmt->bindParam(3, $fichier);
				$stmt->bindParam(4, $mime);
				$stmt->bindParam(5, $contenu, PDO::PARAM_LOB);
				$stmt->bindParam(6, $taille);
				$stmt->execute();

			}
			?>
			<script language='JavaScript'> 
				alert('le fichier a été ajouté!');
				setTimeout("document.location='../index.php?page=actualites.php'", 500);
			</script>
			<?php
		}
		else
		{
			echo $erreur;
		}
	}else{ 
			?> <script language='JavaScript'> 
				alert('le fichier n\'a pas été transféré!');
				setTimeout("document.location='../index.php?page=actualites.php'", 500);
			</script>
			<?php
		}
?>