<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
<center>
	<form method="POST" action="include/uploadImage.php" enctype="multipart/form-data">
		</br>
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<?php
		echo ('<input id="hidden" type="hidden" name="pseudo" value="'.$_SESSION['pseudo'].'">');
		?>
		<h3>Photo : </h3></br><input type="file" name="imageAnnonce" id="imageAnnonce">
		<h3>Titre de votre annonce : </h3></br><input type="text" name="titreAnnonce" id="titreAnnonce">
		<h3>Prix de l'article : </h3></br><input type="text" placeholder="Ex: 15.20" name="prixAnnonce" id="prixAnnonce"> €
		<h3>Description de votre annonce : </h3></br><textarea name="descAnnonce" rows="5" cols="30"></textarea>
		</br><input type="submit" name="envoyer" class="bt" value="Ajouter l'annonce">
	</form>
</center>
<script>
	document.getElementById("Achat/Ventes").style.color="#DCDCDC";
</script>