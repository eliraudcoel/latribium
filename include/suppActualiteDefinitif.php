<?php
	include 'conn.php';
	$tabChoix = (isset($_POST['choix']))?$_POST['choix']:null;
	
	if (!empty($tabChoix)) {
        foreach($tabChoix as $cle => $valeur)
		{			
			$req = $connexion-> exec("DELETE FROM imagesactu WHERE idImageActu='".$valeur."'");
			$req2 = $connexion -> exec("DELETE FROM article WHERE idArticle='".$valeur."'");
			?>
			<script language='JavaScript'> 
				alert('Votre actualit� a �t� supprim�!');
				setTimeout("document.location='../index.php?page=actualites.php&spage=1'", 500);
			</script>
			<?php
		}
	}else{
		?>
			<script language='JavaScript'>
			alert('Vous n\'avez pas selectionn� une actualit�');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
		}
?>