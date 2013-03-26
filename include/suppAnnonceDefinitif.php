<?php
	include 'conn.php';
	$tabChoix = (isset($_POST['choix']))?$_POST['choix']:null;
	
	if (!empty($tabChoix)) {
        foreach($tabChoix as $cle => $valeur)
		{			
			$req = $connexion-> exec("DELETE FROM images WHERE idImage='".$valeur."'");
			$req2 = $connexion -> exec("DELETE FROM articleventes WHERE idVente='".$valeur."'");
			?>
			<script language='JavaScript'> 
				alert('Votre annonce a été supprimé!');
				setTimeout("document.location='../index.php?page=achats_ventes.php&spage=1'", 500);
			</script>
			<?php
		}
	}else{
		?>
			<script language='JavaScript'>
			alert('Vous n\'avez pas selectionné une annonce');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
		}
?>