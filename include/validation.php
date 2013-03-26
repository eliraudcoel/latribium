<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'conn.php'; 
	
	// Récupération des variables nécessaires à l'activation
	$login = $_GET['log'];
	$cle = $_GET['cle'];
			
	// Récupération de la clé correspondant au $login dans la base de données
	$stmt = $connexion->prepare("SELECT cle,actif FROM utilisateur WHERE pseudo like :login ");
	if($stmt->execute(array(':login' => $login)) && $row = $stmt->fetch())
	  {
		$clebdd = $row['cle'];	// Récupération de la clé
		$actif = $row['actif']; // $actif contiendra alors 0 ou 1
	  }
			
			
	// On teste la valeur de la variable $actif récupéré dans la BDD
	if($actif == '1') // Si le compte est déjà actif on prévient
	  {
			?>
				<script language='JavaScript'>
				alert('Votre compte est déjà actif !');
				window.onload=function(){setTimeout("document.location='../index.php'", 500);}
				</script>
			<?php
	  }
	else // Si ce n'est pas le cas on passe aux comparaisons
	  {
		 if($cle == $clebdd) // On compare nos deux clés	
		   {								
			  // La requête qui va passer notre champ actif de 0 à 1
			  $stmt = $connexion->prepare("UPDATE utilisateur SET actif = 1 WHERE pseudo like :login ");
			  $stmt->bindParam(':login', $login);
			  $stmt->execute();
			  ?>
				<script language='JavaScript'>
				alert('Votre compte a bien été activé !');
				window.onload=function(){setTimeout("document.location='../index.php'", 500);}
				</script>
			<?php
		   }
		 else // Si les deux clés sont différentes on provoque une erreur...
		   {
			?>
				<script language='JavaScript'>
				alert('Erreur ! Votre compte ne peut être activé...');
				window.onload=function(){setTimeout("document.location='../index.php'", 500);}
				</script>
			<?php
		   }
	  }
?>