<?php 
$hote='mysql.hostinger.fr';
$nom_bd='u464557778_latrib';
$utilisateur='u464557778_emma';
$mot_passe='melanie1806';
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