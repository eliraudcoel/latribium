<div id="suppActu">
	<form method="post" action="include/suppActualiteDefinitif.php"><br/>
	<?php
		include 'include/conn.php';
		$pseudo = $_GET["pseudo"];
		$j = 1;
		$i = 0;
		$cote = "right";
		
		$requete = $connexion-> query("SELECT * FROM article WHERE  auteur='".$pseudo."' ORDER BY idArticle DESC");
		while ($row = $requete->fetch())
		{
			echo('<INPUT type="checkbox" style="width:50px; height:50px;" name="choix[]" value="'.$row['idArticle'].'">');
			echo'<div id="actu'.$j.'" class="actu">';
			
			$requete2 = $connexion-> query("SELECT * from imagesactu where idImageActu='".$row['idImageActu']."'");
			while($row2 = $requete2->fetch())
			{
				if($i%2 == 1)
				{
					echo '<img class="left" align="left" src="data:'.$row2['typeImageActu'].';base64,'.base64_encode($row2['contenuImageActu']).'" alt="'.$row2['nomImageActu'].'" title="'.$row2['nomImageActu'].'" >';				
					$cote = "right";
				}else
				{
					echo '<img class="right" align="right" src="data:'.$row2['typeImageActu'].';base64,'.base64_encode($row2['contenuImageActu']).'" alt="'.$row2['nomImageActu'].'" title="'.$row2['nomImageActu'].'" >';
					$cote ="left";
				}
				$i= $i+1;
			}
			echo '<center>';
			echo '<h1>'.$row['titre'].'</h1></center>';
			echo '</br>';
			echo '&nbsp;&nbsp;<h4>'.$row['texte'].'</h4>';
			echo '</br>';
			
			$date = $row['dateArt'];
			list($year, $month, $day) = explode('-', $date);

			echo '<h4 align="'.$cote.'">écrit le : '.$day.'/'.$month.'/'.$year.' par '.$row['auteur'].'</h4>';
			echo '</div>';
			echo '<br/><br/>';
			$j=$j+1;
		}
		
		echo('<br/><br/><br/>');
		?>
		<center><input type="submit" value="Supprimer" class="btsupp" /></center>
	</form>
</div>