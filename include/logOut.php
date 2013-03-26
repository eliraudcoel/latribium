<?php
	session_start(); 
	$_SESSION = array();
	session_unset(); 
	session_destroy();
?>
<script language='JavaScript'>
	alert('Vous êtes déconnecté!');
	setTimeout("document.location='../index.php?page=accueil.php'", 500);
</script>