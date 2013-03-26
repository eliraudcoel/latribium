<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'include/conn.php';
?>
<style text="text/css" media="screen">	
	input[type=email]:valid{outline: 3px green solid;}
	input[type=number]:in-range{outline: 3px green solid;}
	
	input[type=email]:invalid{outline: 3px red dotted;}
	input[type=number]:out-of-range{outline: 3px red dotted;}
</style>
<div id="inscription">
	<form method="post" action="./include/recupDonneeInscription.php" enctype="multipart/form-data"><br/>
			<h4>Nom :</h4><input type="text" size="30" name="nom" required/>
			<h4>Prenom :</h4><input type="text" size="30" name="prenom" required/><br/><br/>
			<h4>Pseudo :</h4><input type="text" size="30" name="pseudo" required/>
			<h4>Mot de Passe :</h4><input type="password" size="30" name="pwd" required/><br/><br/>
			<h4>Votre email :</h4><input type="email" size="30" name="email" required/>
			<h4>Réecrivez votre email :</h4><input type="email" size="30" name="email2" required/><br/><br/>
			<h4>Votre statut:</h4><input type="radio" name="statut" value="eleve" checked> Elève&nbsp;&nbsp;<input type="radio" name="statut" value="prof"> Professeur
			</br></br>
			<div id="instrument">
				<?php
					echo('<h4>Votre instrument:</h4>');
					echo('</br><select name="instru" size="1" id="instru">');

					$reponse = $connexion->query("select idCat,catFamille,nomCat
											from categorieinstru
											group by idCat,catFamille,nomCat;");
					
					while($row = $reponse->fetch())
					{
						echo('<option value="'.$row['catFamille'].'" disabled="disabled">'.$row['catFamille'].'</option>');
						echo('<option value="'.$row['nomCat'].'" disabled="disabled">&nbsp;'.$row['nomCat'].'</option>');
						
						$reponse2 = $connexion->query("select categorieInstrument,nomInstrument  
											from instrument, categorieinstru 
											where instrument.categorieInstrument = categorieinstru.idCat
											and categorieinstru.idCat = ".$row['idCat']."
											group by nomInstrument;");
						
						while($row2 = $reponse2->fetch())
						{
							echo('<option value="'.$row2['nomInstrument'].'">&nbsp;'.$row2['nomInstrument'].'</option>');
						}

					}
					?>
					</select>
				<h4>Nombre d'année:</h4><input type="number" name="nombreAnnee" min="1" max="100" required/>
			</div>
			<h4>Profil sur le forum :</h4>
			</br>
			<h4>Choisissez votre avatar :</h4><input type="file" name="imageAvatar" id="imageAvatar" />(Taille max : 100Ko)<br />
			<h4>Signature :</h4><textarea cols="40" rows="4" name="signature" id="signature">La signature est limitée à 200 caractères</textarea>
		</br>
		<center><input type="submit" value="Valider" class="submit" />&nbsp;&nbsp;<input type="reset" value="Annuler" class="submit"/></center>
	</form>
</div>
<script>
	document.getElementById("Inscription").style.color="#DCDCDC";
</script>