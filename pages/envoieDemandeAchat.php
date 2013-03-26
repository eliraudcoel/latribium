<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}

	$pseudo=$_GET["pseudo"];
	$id = $_GET["idAnnonce"];
	$pseudoInte=$_SESSION["pseudo"];
	
	$requete = $connexion -> query("SELECT email as emailEnvoie FROM utilisateur WHERE pseudo='".$pseudo."'");
	$reqSql = $requete->fetch(PDO::FETCH_OBJ); 
	$emailEnvoie = $reqSql->emailEnvoie;
	
	$requete2 = $connexion -> query("SELECT email as emailInt FROM utilisateur WHERE pseudo='".$pseudoInte."'");
	$reqSql2 = $requete2->fetch(PDO::FETCH_OBJ); 
	$emailInt = $reqSql2->emailInt;
	
	$requete3 = $connexion -> query("SELECT titreVente as titre FROM articleventes WHERE idVente='".$id."'");
	$reqSql2 = $requete2->fetch(PDO::FETCH_OBJ); 
	$reqSql3 = $requete3->fetch(PDO::FETCH_OBJ); 
	$titre = $reqSql3->titre;
	
	// Préparation du mail contenant le lien d'activation
	$destinataire = $emailEnvoie;
	$sujet = "Activer votre compte" ;
	$entete = "From: latribium@free.fr" ;

	// Le lien d'activation est composé du login(log) et de la clé(cle)
	$message = 'Votre annonce : "'.$titre.'" a intéressé '.$pseudoInte.'.

	Pour répondre à cette demande, vous pouvez le ou la contacter à cette adresse email : '.$emailInt.'

	---------------
	Ceci est un mail automatique, Merci de ne pas y répondre.';


	mail($destinataire, $sujet, $message, $entete) ;
?>
<script language='JavaScript'>
	alert('Un email a été envoyé à la personne qui a créé cette vente, elle vous contactera par mail');
	setTimeout("document.location='./index.php?page=achats_ventes.php&spage=1'", 500);
</script>
<script>
	document.getElementById("Achat/Ventes").style.color="#DCDCDC";
</script>