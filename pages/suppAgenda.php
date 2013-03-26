<div id="suppAgenda">
	<form method="post" action="include/suppAgendaDefinitif.php"><br/>	
	<?php
		include 'include/conn.php';
		$requete = $connexion-> query("SELECT * from agenda");
		while($row = $requete->fetch())
		{
			echo('<input type="checkbox" style="width:50px; height:50px;" name="choix[]" value="'.$row['idAgenda'].'">');
			echo('<h4>'.$row['nomAgenda'].'</h4>');
		}
	?>
	<center><input type="submit" value="Supprimer" class="btsupp" /></center>
	</form>
</div>