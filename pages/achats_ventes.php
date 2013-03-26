<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	
	if(!isset($_SESSION['pseudo']))
	{
		echo('<script>alert(\'Vous ne vous êtes pas identifié! Vous ne pouvez pas accéder à cette partie du site... Inscrivez-vous!\');');
		echo('setTimeout("document.location=\'./index.php?page=accueil.php\'", 500);</script>');
	}
	else
	{
		?>
		<div id="ajouteAnnonce">
			<a href="./index.php?page=ajouteAnnonce.php" class="btajout">Ajouter une annonce</a>
			<?php echo('<a href="./index.php?page=suppAnnonce.php&pseudo='.$_SESSION['pseudo'].'" class="btsupp">Supprimer vos annonces</a>'); ?>
		</div>
		</br></br></br>
		<center>
		<div id="annonces">
		<?php
				include 'include/conn.php';
				$j = 0;
				$i = 0;
				$cote = "right";
				$spage = $_GET["spage"];
				$reqcount = $connexion -> query ("Select count(idVente) as count from articleventes");
				$lcount = $reqcount -> fetch();
				$count= $lcount["count"];
				$requete = $connexion-> query("SELECT * FROM articleventes WHERE idVente>(".$count."-(".$spage."*5)) OR idVente<=(".$count."-((".$spage."-1)*5)) ORDER BY idVente DESC");
				$sup = 0;
				while ($row = $requete->fetch() and $sup <5)
				{
					$sup = $sup + 1;
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
					echo'<div id="ajouteAnnonce">';
					echo'<a href=./index.php?page=envoieDemandeAchat.php&pseudo='.$row['auteurVente'].'&idAnnonce='.$row['idVente'].' class="btajout">Je suis intéressé(e)!</a></div>';
					echo '</div>';
					echo '<br/><br/>';
					$j=$j+1;
				}

				echo('<br/><br/><br/>');
				if ($count > 5)
				{
						$nmbre=($count/5)+1;
						$sup=1;
						echo('<center>');
						while($sup<$nmbre)
						{
							if ($sup % 20 == 1)
							{
								echo ('<br/>');
							}
							echo('<a id="page'.$sup.'" class="page" href=index.php?page=achats_ventes.php&spage='.$sup.'>'.$sup.' </a>');
							$sup = $sup+1;
						}
						echo('</center>');
				}
		?>
		</div>
		</center>
		<script>
			document.getElementById("Achat/Ventes").style.color="#DCDCDC";
		</script>
		<?php
	}
?>