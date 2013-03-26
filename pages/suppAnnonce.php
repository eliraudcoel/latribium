<div id="suppAnnonce">
	<form method="post" action="include/suppAnnonceDefinitif.php"><br/>
	<?php
		include 'include/conn.php';
		$pseudo = $_GET["pseudo"];
		$j = 0;
		$i = 0;
		$cote = "right";
		
		$requete = $connexion-> query("SELECT * FROM articleventes WHERE auteurVente='".$pseudo."' ORDER BY idVente DESC");
		while ($row = $requete->fetch())
		{
			echo('<INPUT type="checkbox" style="width:50px; height:50px;" name="choix[]" value="'.$row['idVente'].'">');
			echo'<div id="annonce'.$j.'" class="annonce">';

			$requete2 = $connexion-> query("SELECT * from images where idImage='".$row['imageIdVente']."'");
			while($row2 = $requete2->fetch())
			{
				if($i%2 == 1)
				{
					echo '<img class="left" align="left" src="data:'.$row2['typeImage'].';base64,'.base64_encode($row2['contenuImage']).'" alt="'.$row2['nomImage'].'" title="'.$row2['nomImage'].'" >';				
					$cote = "left";
				}else
				{
					echo '<img class="right" align="right" src="data:'.$row2['typeImage'].';base64,'.base64_encode($row2['contenuImage']).'" alt="'.$row2['nomImage'].'" title="'.$row2['nomImage'].'" >';
					$cote ="right";
				}
				$i= $i+1;
			}
			echo '<center>';
			echo '<h1>'.$row['titreVente'].'</h1></center>';
			echo '</br>';
			echo '&nbsp;&nbsp;<h4>'.$row['descriptionVente'].'</h4>';
			echo '</br>';
			echo '<h4 align="'.$cote.'">Vendu par '.$row['auteurVente'].'</h4>';
			echo '<h4 align="'.$cote.'">Prix : '.$row['prixVente'].'€</h4>';
			echo '<br/><br/>';
			$j=$j+1;
		}
		?>
		<center><input type="submit" value="Supprimer" class="btsupp" /></center>
	</form>
</div>