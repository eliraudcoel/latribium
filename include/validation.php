<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'conn.php'; 
	
	// R�cup�ration des variables n�cessaires � l'activation
	$login = $_GET['log'];
	$cle = $_GET['cle'];
			
	// R�cup�ration de la cl� correspondant au $login dans la base de donn�es
	$stmt = $connexion->prepare("SELECT cle,actif FROM utilisateur WHERE pseudo like :login ");
	if($stmt->execute(array(':login' => $login)) && $row = $stmt->fetch())
	  {
		$clebdd = $row['cle'];	// R�cup�ration de la cl�
		$actif = $row['actif']; // $actif contiendra alors 0 ou 1
	  }
			
			
	// On teste la valeur de la variable $actif r�cup�r� dans la BDD
	if($actif == '1') // Si le compte est d�j� actif on pr�vient
	  {
			?>
				<script language='JavaScript'>
				alert('Votre compte est d�j� actif !');
				window.onload=function(){setTimeout("document.location='../index.php'", 500);}
				</script>
			<?php
	  }
	else // Si ce n'est pas le cas on passe aux comparaisons
	  {
		 if($cle == $clebdd) // On compare nos deux cl�s	
		   {								
			  // La requ�te qui va passer notre champ actif de 0 � 1
			  $stmt = $connexion->prepare("UPDATE utilisateur SET actif = 1 WHERE pseudo like :login ");
			  $stmt->bindParam(':login', $login);
			  $stmt->execute();
			  ?>
				<script language='JavaScript'>
				alert('Votre compte a bien �t� activ� !');
				window.onload=function(){setTimeout("document.location='../index.php'", 500);}
				</script>
			<?php
		   }
		 else // Si les deux cl�s sont diff�rentes on provoque une erreur...
		   {
			?>
				<script language='JavaScript'>
				alert('Erreur ! Votre compte ne peut �tre activ�...');
				window.onload=function(){setTimeout("document.location='../index.php'", 500);}
				</script>
			<?php
		   }
	  }
?>