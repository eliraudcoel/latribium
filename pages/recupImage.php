<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
<?php
		include 'include/conn.php';
		$id=$_GET['id_img'];
		$requete2 = $connexion-> query("SELECT * from images where idImage='".$id."'");
		while($row2 = $requete2->fetch())
		{
			header("Content-type: ".$row2['typeImage']."");
			echo($row2['contenuImage']);
		}
?>