<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>

<script src="js/jquery.eventCalendar.js" type="text/javascript"></script>

<!-- Core CSS File. The CSS code needed to make eventCalendar works -->
<link rel="stylesheet" href="css/eventCalendar.css">
<!-- Theme CSS file: it makes eventCalendar nicer -->
<link rel="stylesheet" href="css/eventCalendar_theme_responsive.css">


<div type="hidden" id="message">
	<?php if(isset($_SESSION['pseudo'])){echo('<h2>Bonjour '.$_SESSION['pseudo'].' !</h2>');}?>
</div>
</br>
<div id="ajouteCalendrier">
	<a href="/PPEweb/public/index.php?page=ajouteEvenement.php" class="btajout">Ajouter un évenement</a>
	<a href="/PPEweb/public/index.php?page=suppEvenement.php" class="btsupp">Supprimer un évenement</a>
</div>
</br></br>
<div id="calendrier">
</div>
<script>
	$(document).ready(function() {
		$("#calendrier").eventCalendar({
			eventsjson: 'include/events.json.php',
		});
	});
	
	document.getElementById("Actualités").style.color="#DCDCDC";
</script>

<div id="actu">
	<div id="ajouterActu">
		<a href="/PPEweb/public/index.php?page=ajouteActualite.php" class="btajout">Ajouter une actualité</a>
		<?php echo('<a href="/PPEweb/public/index.php?page=suppActualite.php&pseudo='.$_SESSION['pseudo'].'" class="btsupp">Supprimer vos actualités</a>'); ?>
	</div>
	</br>
	<div id="actualites">
		<?php
			include 'include/conn.php';
			$j = 0;
			$i = 0;
			$cote = "right";
			$spage = $_GET["spage"];
			
			$reqcount = $connexion -> query ("Select count(idArticle) as count from article");
			$lcount = $reqcount -> fetch();
			$count= $lcount["count"];
			
			$requete = $connexion-> query("SELECT * FROM article WHERE idArticle>(".$count."-(".$spage."*5)) OR idArticle<=(".$count."-((".$spage."-1)*5)) ORDER BY idArticle DESC");
			$sup = 0;
			while ($row = $requete->fetch() and $sup <5)
			{
				$sup = $sup + 1;
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
						echo('<a id="page'.$sup.'" class="page" href=index.php?page=actualites.php&spage='.$sup.'>'.$sup.' </a>');
						$sup = $sup+1;
					}
					echo('</center>');
			}

		?>
	</div>
</div>
<?php
	if(isset($_SESSION['pseudo']))
	{
		?><script>document.getElementById("message").style.visibility="visible";</script><?php
		$req = $connexion -> query ("Select statut as statut from utilisateur where pseudo='".$_SESSION['pseudo']."'");
		$rang = $req -> fetch();
		$rangPseudo = $rang["statut"];
		if($rangPseudo == "eleve")
		{ ?><script>document.getElementById("ajouteCalendrier").style.visibility="hidden";</script><?php }
		else
		{ ?><script>document.getElementById("ajouteCalendrier").style.visibility="visible";</script><?php }
	}
	else
	{
		?><script>
			document.getElementById("message").style.visibility="hidden";
			document.getElementById("ajouteCalendrier").style.visibility="hidden";
			document.getElementById("ajouterActu").style.visibility="hidden";
		</script><?php
	}
?>