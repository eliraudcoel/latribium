<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}	
	include 'conn.php';
	$nom=$_POST["nom"];
	$prenom=$_POST["prenom"];
	$pseudo=$_POST["pseudo"];
	$pwd=$_POST["pwd"];
	$email=$_POST["email"];
	$email2=$_POST["email2"];
	$statut=$_POST["statut"];
	$signature = $_POST['signature'];
	$temps = time(); 
	$okPseudo;
	$nomavatar;
	$okEmail;
	$cle = md5(microtime(TRUE)*100000);
	
	$pagePrecedente = $_SERVER["HTTP_REFERER"];
	
	//Vérification de l'avatar :
	if(is_uploaded_file($_FILES["imageAvatar"]["tmp_name"]))
	{
		//On définit les variables :
		$maxsize = 102400; //Poid de l'image
		$maxwidth = 900; //Largeur de l'image
		$maxheight = 900; //Longueur de l'image
		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); //Liste des extensions valides
		 
		if ($_FILES['imageAvatar']['error'] > 0)
		{
				$avatar_erreur = "Erreur lors du transfert de l'avatar : ";
		}
		if ($_FILES['imageAvatar']['size'] > $maxsize)
		{
				$i++;
				$avatar_erreur += "Le fichier est trop gros : (<strong>".$_FILES['imageAvatar']['size']." Octets</strong>    contre <strong>".$maxsize." Octets</strong>)";
		}

		$image_sizes = getimagesize($_FILES['imageAvatar']['tmp_name']);
		if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
		{
				$i++;
				$avatar_erreur += "Image trop large ou trop longue : 
				(<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre <strong>".$maxwidth."x".$maxheight."</strong>)";
		}
		 
		$extension_upload = strtolower(substr(  strrchr($_FILES['imageAvatar']['name'], '.')  ,1));
		if (!in_array($extension_upload,$extensions_valides) )
		{
				$i++;
				$avatar_erreur += "Extension de l'avatar incorrecte";
		}

		if(!isset($avatar_erreur)) //S'il n'y a pas d'erreur, on upload
		{
			$avatar = $_FILES['imageAvatar'];
			$extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
			$name = time();
			$nomavatar = str_replace(' ','',$name).".".$extension_upload;
			$name = "../avatars/".str_replace(' ','',$name).".".$extension_upload;
			if(!move_uploaded_file($avatar['tmp_name'],$name))
			{
				exit("Impossible de copier le fichier dans $content_dir");
			}
			echo "Le fichier a bien été uploadé";
		}
	}
	
	function verificationPseudo($pseudo)
	{
		include 'conn.php';
		$verif = true;
		
		$verifQuetePseudo = $connexion-> query("SELECT pseudo from utilisateur where pseudo='".$pseudo."'");

		$rowPseudo = $verifQuetePseudo->fetch();
		
		if($rowPseudo)
		{
			$verif= false;
		}else{
			$verif= true;
		}
		return $verif;
	}
	
	function verificationEmailIdentique($email,$email2)
	{
		$verif = true;
		
		if($email!=$email2)
		{
			echo("<script language='JavaScript'>alert('Les adresses emails ne sont pas identiques')</script>");
			$verif= false;
		}
		return $verif;
	}
	function verificationEmail($email)
	{
		include 'conn.php';
		$verif = true;
		
		$requeteVerifEmail = $connexion-> query("SELECT email from utilisateur where email='".$email."'");
		
		$rowEmail = $requeteVerifEmail->fetch();
		
		if($rowEmail)
		{
			$verif = false;
		}else
		{
			$verif = true;
		}

		return $verif;
	}
	
	$requete = $connexion -> query("SELECT max(LAST_INSERT_ID(idUtil)) as nb FROM utilisateur");
	$reqSql = $requete->fetch(PDO::FETCH_OBJ); 
	$idUtilMax = $reqSql->nb;
	$idUtilMax = $idUtilMax + 1;
	
	$okPseudo = verificationPseudo($pseudo);
	$okEmailIdentique = verificationEmailIdentique($email,$email2);
	
	if($okEmailIdentique == true)
	{
		$okEmail = verificationEmail($email);
	}
	
	if($okPseudo == true && $okEmail == true)
	{		
		$sql= $connexion->prepare('INSERT INTO utilisateur (idUtil, nomUtil,prenomUtil, pseudo, 
		mdp, email, avatar, signature, dateInscription, derniere_visite, statut, cle, actif)
        VALUES (:id,:nom, :prenom, :pseudo, :pass, :email, :nomavatar, :signature, :temps, :temps, :statut, :cle, :actif)');
		$sql->bindValue(':id', $idUtilMax, PDO::PARAM_STR);
		$sql->bindValue(':nom', $nom, PDO::PARAM_STR);
		$sql->bindValue(':prenom', $prenom, PDO::PARAM_STR);
		$sql->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
		$sql->bindValue(':pass', $pwd, PDO::PARAM_STR);
		$sql->bindValue(':email', $email, PDO::PARAM_STR);
		$sql->bindValue(':nomavatar', $nomavatar, PDO::PARAM_STR);
		$sql->bindValue(':signature', $signature, PDO::PARAM_STR);
		$sql->bindValue(':temps', $temps, PDO::PARAM_INT);
		$sql->bindValue(':temps', $temps, PDO::PARAM_INT);
		$sql->bindValue(':statut', $statut, PDO::PARAM_STR);
		$sql->bindValue(':cle', $cle, PDO::PARAM_STR);
		$sql->bindValue(':actif', 0);
		
		$instru=$_POST["instru"];
		$nbAnnee=$_POST["nombreAnnee"];
		
		$requete2 = $connexion -> query("SELECT max(LAST_INSERT_ID(idCompet)) as id FROM competences");
		$reqSql2 = $requete2->fetch(PDO::FETCH_OBJ); 
		$idCompetMax = $reqSql2->id;
		$idCompetMax = $idCompetMax + 1;
		
		$requete3 = $connexion -> query("SELECT idInstru as idInstrum FROM instrument where nomInstrument='".$instru."'");
		$reqSql3 = $requete3->fetch(PDO::FETCH_OBJ); 
		$idInstruCorr = $reqSql3->idInstrum;
	 
		$sql2="INSERT INTO competences VALUES(";
		$sql2.="".$idCompetMax.",";
		$sql2.="'".$idUtilMax."',";
		$sql2.="'".$idInstruCorr."',";
		$sql2.="'".$nbAnnee."')";
		
		$sql->execute();
		$requeteSql2=$connexion -> query($sql2) or die ('Erreur SQL !'.mysql_error());
		
		echo("<script language='JavaScript'>alert('Votre inscription a été pris en compte, vous allez recevoir un email')</script>");
		
		// Préparation du mail contenant le lien d'activation
		$destinataire = $email;
		$sujet = "Activer votre compte" ;
		$entete = "From: admin@latribium.p.ht" ;

		// Le lien d'activation est composé du login(log) et de la clé(cle)
		$message = 'Bienvenue sur La tribium,

		Pour activer votre compte, veuillez cliquer sur le lien ci dessous
		ou copier/coller dans votre navigateur internet.

		http://latribium.p.ht/index.php?page=validation.php&log='.urlencode($pseudo).'&cle='.urlencode($cle).'


		---------------
		Ceci est un mail automatique, Merci de ne pas y répondre.';


		mail($destinataire, $sujet, $message, $entete) ;
		?>
			<script language='JavaScript'>setTimeout("document.location='../index.php?page=accueil.php'", 500);</script>
		<?php
	}
	
	if($okPseudo == false && $okEmail == false)
	{
		?>
			<script language='JavaScript'>
			alert('Oups!!\nVotre pseudo est déjà utilisé, veuillez en choisir un autre.\nVotre email est déjà utilisé, vous êtes peut-être déjà inscrit.\nVeuillez changer vos données s\'il-vous-plaît');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
	}
	
	if($okPseudo == false && $okEmail == true)
	{
		?>
			<script language='JavaScript'>
			alert('Oups!!\nVotre pseudo est déjà utilisé, veuillez en choisir un autre. \nVeuillez changer vos données s\'il-vous-plaît');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
	}
	
	if($okPseudo == true && $okEmail == false)
	{
		?>
			<script language='JavaScript'>
			alert('Oups!!\nVotre email est déjà utilisé, vous etes peut-être déjà inscrit.\nVeuillez changer vos données s\'il-vous-plaît');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
	}
 ?>