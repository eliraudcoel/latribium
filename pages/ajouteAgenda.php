<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
<form method="POST" action="include/upload.php" enctype="multipart/form-data">
	</br>
    <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
    <h3>Fichier : </h3></br><input type="file" name="fichierAgenda" id="fichierAgenda">
	</br><h3>Nom de votre fichier : </h3>&nbsp;&nbsp;<input type="text" size="30" name="nomFichier" /><br/>
    </br><input type="submit" name="envoyer" class="bt" value="Ajouter le fichier">
</form>
<script>
	document.getElementById("Planning").style.color="#DCDCDC";
</script>