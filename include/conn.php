<?php 
$hote='localhost';
$nom_bd='siteviolon';
$utilisateur='root';
$mot_passe='';
try
{
    $connexion = new PDO('mysql:host='.$hote.';dbname='.$nom_bd, $utilisateur, $mot_passe);
}
catch(Exception $e)
{
	echo 'Une erreur est survenue !';
	die();
}
?>