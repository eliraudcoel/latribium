<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	include 'conn.php';
	$pseudo=$_POST["login"];
	$pwd=$_POST["pass"];

	$requeteVerifLogin = $connexion-> query("SELECT pseudo from utilisateur WHERE pseudo='".$pseudo."'");
	$reqSqlVerifLogin = $requeteVerifLogin->fetch();
	
	if($reqSqlVerifLogin)
	{
		$requeteVerifPdw = $connexion-> query("SELECT mdp from utilisateur WHERE pseudo='".$pseudo."'");
		$reqSqlVerifPwd = $requeteVerifPdw->fetch();
		
		if($reqSqlVerifPwd)
		{
			if($reqSqlVerifPwd['mdp'] == $pwd)
			{
				$stmt = $connexion->prepare("SELECT actif FROM utilisateur WHERE pseudo like :pseudo ");
				if($stmt->execute(array(':pseudo' => $pseudo))  && $row = $stmt->fetch())
				{
					$actif = $row['actif'];
				}

				if($actif == '1')
				{
					 $_SESSION['pseudo'] = $pseudo;
					?>
						<script language='JavaScript'>setTimeout("document.location='../index.php?page=actualites.php&spage=1'", 500);</script>
					<?php
				}
				else
				{
					?>
					<script language='JavaScript'>
						alert('Voous n\'avez pas validé votre adresse email!');
						window.onload=function(){setTimeout(function(){history.back()},500);}
					</script>
					<?php
				}
			}
			else{
				?>
					<script language='JavaScript'>
					alert('Votre mot de passe est incorrect recommencez!');
					window.onload=function(){setTimeout(function(){history.back()},500);}
					</script>
				<?php
			}
		}else
		{
				?>
					<script language='JavaScript'>
					alert('Erreur ! Votre connexion ne peut être établie...');
					window.onload=function(){setTimeout(function(){history.back()},500);}
					</script>
				<?php
		}
	}
	else{
		?>
			<script language='JavaScript'>
			alert('Votre pseudo est incorrect recommencez!');
			window.onload=function(){setTimeout(function(){history.back()},500);}
			</script>
		<?php
	}
?>