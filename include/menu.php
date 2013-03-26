<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
<head>
	<link href="css/popupCss.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>
	function connexion()
	{
		$(document).ready(function() {	
			
			//Put in the DIV id you want to display
			launchWindow('#dialog1');
			
			//if close button is clicked
			$('.window #close').click(function () {
				$('#mask').hide();
				$('.window').hide();
			});		
			
			//if mask is clicked
			$('#mask').click(function () {
				$(this).hide();
				$('.window').hide();
			});			
			

			$(window).resize(function () {
			 
				var box = $('#boxes .window');
		 
				//Get the screen height and width
				var maskHeight = $(document).height();
				var maskWidth = $(window).width();
			  
				//Set height and width to mask to fill up the whole screen
				$('#mask').css({'width':maskWidth,'height':maskHeight});
					   
				//Get the window height and width
				var winH = $(window).height();
				var winW = $(window).width();

				//Set the popup window to center
				box.css('top',  winH/2 - box.height()/2);
				box.css('left', winW/2 - box.width()/2);
			 
			});	
			
		});
	}

	function launchWindow(id) {
		
			//Get the screen height and width
			var maskHeight = $(document).height();
			var maskWidth = $(window).width();
		
			//Set heigth and width to mask to fill up the whole screen
			$('#mask').css({'width':maskWidth,'height':maskHeight});
			
			//transition effect		
			$('#mask').fadeIn(1000);	
			$('#mask').fadeTo("slow",0.8);	
		
			//Get the window height and width
			var winH = $(window).height();
			var winW = $(window).width();
				  
			//Set the popup window to center
			$(id).css('top',  winH/2-$(id).height());
			$(id).css('left', winW/2-$(id).width()/2);
		
			//transition effect
			$(id).fadeIn(2000); 
		

	}
</script>
</head>
<body>
	<center>
		<?php
			include 'conn.php';
			$i = 1;
			
			for($i=1;$i<=6;$i++)
			{
				$reponse = $connexion->query("select MenuNom,RefMenu from menu where idMenu='$i';");
				echo('<menu type="toolbar" id="navigation">');
				while($row = $reponse->fetch())
				{
					echo('<a id="'.$row['MenuNom'].'" href="index.php?page='.$row['RefMenu'].'">&nbsp;&nbsp;'.$row['MenuNom'].'</a>');
				}
			}

			if(isset($_SESSION['pseudo'])){ 
				echo('<form action="include/logout.php"><input type="image" id="deconnexion" src="/PPEweb/public/img/BoutonDeconnexion.jpg" name="deconnexion" alt="Deconnexion"/></form>');
			}
			else{ 
				echo('&nbsp;&nbsp;<input type="image" id="connexion" onclick="connexion();" src="/PPEweb/public/img/BoutonConnexion.jpg" name="connexion" alt="Connexion"/>');
			}
			echo('</menu>');
		?>	
	</center>
</body>