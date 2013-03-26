<div id="suppAnnonce">
	<form method="post" action="include/suppEvenementDefinitif.php"><br/>
	<?php
		include 'include/conn.php';
		$j = 1;
		
		$requete = $connexion-> query("SELECT * from evenement");
		while($row = $requete->fetch())
		{
			echo('<input type="checkbox" style="width:50px; height:50px;" name="choix[]" value="'.$row['idEv'].'">');
			echo'<div id="evenement'.$j.'" class="evenement">';
			echo '<h1>'.$row['nomEv'].'</h1></center>';
			echo '</br>';
			echo '&nbsp;&nbsp;<h4>'.$row['descriptionEv'].'</h4>';
			echo '</br>';
			echo('</div>');
			$j=$j+1;
		}
	?>
		<center><input type="submit" value="Supprimer" class="btsupp" /></center>
	</form>
</div>