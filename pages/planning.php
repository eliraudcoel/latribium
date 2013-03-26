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
		<center>
		<h1> Tous à vos agendas!</h1>
		<div id="ajouterAgenda">
			<a href="/PPEweb/public/index.php?page=ajouteAgenda.php" class="btajout">Ajouter un calendrier</a>
			<a href="/PPEweb/public/index.php?page=suppAgenda.php" class="btsupp">Supprimer vos actualités</a>
		</div>
		<div id="agenda">
			<?php
				include 'include/conn.php';
				$requete = $connexion-> query("SELECT * from agenda");
				$i =1;
				while($row = $requete->fetch())
				{
					echo('<form method="post" action="include/getUpload.php"><br/>');
					echo('<input type="hidden" value="'.$row['idAgenda'].'" name="idFichier"/>');
					echo('<h4>'.$row['nomAgenda'].'</h4> <input type="image" id="telecharge'.$i.'" src="/PPEweb/public/img/logoTelecharge.png" name="'.$row['nomAgenda'].'" alt="telecharge'.$i.'"/>');
					echo('</form>');
					$i = $i+1;
				}
		echo('</div>');
		echo('</center>');
	}
			?>
<script>
	document.getElementById("Planning").style.color="#DCDCDC";
</script>
<?php
	if(isset($_SESSION['pseudo']))
	{
		$req = $connexion -> query ("Select statut as statut from utilisateur where pseudo='".$_SESSION['pseudo']."'");
		$rang = $req -> fetch();
		$rangPseudo = $rang["statut"];
		if($rangPseudo == "eleve")
			{ ?><script>document.getElementById("ajouterAgenda").style.visibility="hidden";</script><?php }
		else
			{ ?><script>document.getElementById("ajouterAgenda").style.visibility="visible";</script><?php }
	}
?>