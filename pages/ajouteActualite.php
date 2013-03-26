<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
<center>
	<form method="POST" action="include/uploadImageActu.php" enctype="multipart/form-data">
		</br>
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<?php
		echo ('<input id="hidden" type="hidden" name="pseudo" value="'.$_SESSION['pseudo'].'">');
		?>
		<h3>Photo : </h3></br><input type="file" name="imageActu" id="imageActu">
		<h3>Titre de votre actualité : </h3></br><input type="text" name="titreActu" id="titreActu">
		<h3>Description de l'actualité : </h3></br><textarea name="descActu" rows="5" cols="50"></textarea>
		</br><input type="submit" name="envoyer" class="bt" value="Ajouter l'actualité">
	</form>
</center>
<script>
	document.getElementById("Actualités").style.color="#DCDCDC";
</script>