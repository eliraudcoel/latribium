<?php
	include 'conn.php';
	$tabChoix = (isset($_POST['choix']))?$_POST['choix']:null;
	
	if (!empty($tabChoix)) {
        foreach($tabChoix as $cle => $valeur)
		{			
			$req = $connexion-> exec("DELETE FROM agenda WHERE idAgenda='".$valeur."'");
			?>
			<script language='JavaScript'> 
				alert('Votre planning a été supprimé!');
				setTimeout("document.location='../index.php?page=planning.php'", 500);
			</script>
			<?php
		}
	}else{
		?>
			<script language='JavaScript'>
			alert('Vous n\'avez pas selectionné un planning');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
		}
?>