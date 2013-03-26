<?php
	session_start(); 
	$_SESSION = array();
	session_unset(); 
	session_destroy();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script>
	alert('Vous êtes déconnecté!');
	setTimeout("document.location='../index.php?page=accueil.php'", 500);
</script>