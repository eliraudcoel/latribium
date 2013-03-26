<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<div id="global">
		<head>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
			<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<link href="css/site.css" rel="stylesheet" type="text/css" />
			<link href="css/popupCss.css" rel="stylesheet" type="text/css" />
			<header>
				<nav id="menu">
					<?php
						include "/include/menu.php";
					?>
				</nav>	
			</header>
		</head>
		<body>
			<div id="boxes">
				<!-- Start of Login Dialog -->
				<form action="include/auth.php" method="post">	
					<div id="dialog1" class="window">
					<form action="auth.php" method="post">
					  <div class="d-header">
						<input name="login" id="login" type="text" value="Pseudo" onclick="this.value=''"/><br/>
						<input name="pass" id="pass" type="password" value="Mot de Passe" onclick="this.value=''"/>    
					  </div>
					  <div class="d-blank"></div>
					  <div class="d-login"><input type="image" alt="Login" title="Login" src="/PPEweb/public/img/login-button.png"/></div>
					</div>
				</form>
				<!-- End of Login Dialog -->
				<!-- Mask to cover the whole screen -->
				  <div id="mask"></div>
			</div>
			<div id="message">
			</div>
			</br></br>
			<div id="article">
			
				<?php					
					if(!isset($_GET["page"]))
					{
						include "/pages/accueil.php";
					}
					else
					{
						include "/pages/".$_GET["page"];
					}
				?>
			</div>
		</body>
		<footer>
		</footer>
	</div>
</html>