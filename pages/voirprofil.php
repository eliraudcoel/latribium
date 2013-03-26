<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
$titre="Profil";
include("./include/conn.php");
include("./include/debut.php");
//On récupère la valeur de nos variables passées par URL
$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'consulter';
$membre = isset($_GET['m'])?(int) $_GET['m']:'';

//On regarde la valeur de la variable $action
switch($action)
{
    //Si c'est "consulter"
    case "consulter":
	 
       //On récupère les infos du membre
       $query=$connexion->prepare('SELECT pseudo, avatar,
       email, signature, post, dateInscription FROM utilisateur WHERE idUtil =:membre');
       $query->bindValue(':membre',$membre, PDO::PARAM_INT);
       $query->execute();
       $data=$query->fetch();
	   
	   if($pseudo == $data['pseudo'])
	   {
		echo'<a href="./index.php?page=voirprofil.php&m='.stripslashes(htmlspecialchars($data['idUtil'])).'&amp;action=modifier" class="btajout">Modifier votre profil</a>';
	   }
 
       //On affiche les infos sur le membre
       echo '<p><a href="index.php?page=indexForum.php">Index du forum</a> --> 
       profil de '.stripslashes(htmlspecialchars($data['pseudo']));
       echo'<h1>Profil de '.stripslashes(htmlspecialchars($data['pseudo'])).'</h1>';
        
       echo'<img src="./avatars/'.$data['avatar'].'"
       alt="Ce membre n a pas d avatar" class="avatar"/>';
        
       echo'<p><strong>Adresse E-Mail : </strong>
       <a href="mailto:'.stripslashes($data['email']).'">
       '.stripslashes(htmlspecialchars($data['email'])).'</a><br />';
  
       echo'Ce membre est inscrit depuis le
       <strong>'.date('d/m/Y',$data['dateInscription']).'</strong>
       et a posté <strong>'.$data['post'].'</strong> messages
       <br /><br />';
	   '</p>';
       $query->CloseCursor();
       break;
	   
	//Si on choisit de modifier son profil
    case "modifier":
    if (empty($_POST['sent'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
    {
        //On commence par s'assurer que le membre est connecté
        if ($id==0) erreur(ERR_IS_NOT_CO);
 
        //On prend les infos du membre
        $query=$connexion->prepare('SELECT pseudo, avatar, email, signature, post, dateInscription
        FROM utilisateur WHERE idUtil=:id');
        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $data=$query->fetch();
        echo '<p><a href="index.php?page=indexForum.php">Index du forum</a> --> Modification du profil';
        echo '<h1>Modifier son profil</h1>';
         
        echo '<form method="post" action="index.php?page=voirprofil.php&action=modifier" enctype="multipart/form-data">
        
  
        <fieldset><legend>Identifiants</legend>
        Pseudo : <strong>'.stripslashes(htmlspecialchars($data['pseudo'])).'</strong><br />       
        <label for="password">Nouveau mot de Passe :</label>
        <input type="password" name="password" id="password" /><br />
        <label for="confirm">Confirmer le mot de passe :</label>
        <input type="password" name="confirm" id="confirm"  />
        </fieldset>
  
        <fieldset><legend>Contacts</legend>
        <label for="email">Votre adresse E_Mail :</label>
        <input type="text" name="email" id="email"
        value="'.stripslashes($data['email']).'" /><br />
  
        <fieldset><legend>Profil sur le forum</legend>
        <label for="avatar">Changer votre avatar :</label>
        <input type="file" name="avatar" id="avatar" />
        (Taille max : 10 ko)<br /><br />
        <label><input type="checkbox" name="delete" value="Delete" />
        Supprimer l avatar</label>
        Avatar actuel :
        <img src="./avatars/'.$data['avatar'].'"
        alt="pas d avatar" />
      
        <br /><br />
        <label for="signature">Signature :</label>
        <textarea cols="40" rows="4" name="signature" id="signature">
        '.stripslashes($data['signature']).'</textarea>
      
      
        </fieldset>
        <p>
        <input type="submit" value="Modifier son profil" />
        <input type="hidden" id="sent" name="sent" value="1" />
        </p></form>';
        $query->CloseCursor();   
    }   
    else //Cas du traitement
    {
		 //On déclare les variables 
	 
		$mdp_erreur = NULL;
		$email_erreur1 = NULL;
		$email_erreur2 = NULL;
		$msn_erreur = NULL;
		$signature_erreur = NULL;
		$avatar_erreur = NULL;
		$avatar_erreur1 = NULL;
		$avatar_erreur2 = NULL;
		$avatar_erreur3 = NULL;
	 
		//Encore et toujours notre belle variable $i :p
		$i = 0;
		$temps = time(); 
		$signature = $_POST['signature'];
		$email = $_POST['email'];
		$msn = $_POST['msn'];
		$website = $_POST['website'];
		$localisation = $_POST['localisation'];
		$pass = md5($_POST['password']);
		$confirm = md5($_POST['confirm']);
	 
	 
		//Vérification du mdp
		if ($pass != $confirm || empty($confirm) || empty($pass))
		{
			 $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
			 $i++;
		}
	 
		//Vérification de l'adresse email
		//Il faut que l'adresse email n'ait jamais été utilisée (sauf si elle n'a pas été modifiée)
	 
		//On commence donc par récupérer le mail
		$query=$connexion->prepare('SELECT email FROM utilisateur WHERE idUtil =:id'); 
		$query->bindValue(':id',$id,PDO::PARAM_INT);
		$query->execute();
		$data=$query->fetch();
		if (strtolower($data['email']) != strtolower($email))
		{
			//Il faut que l'adresse email n'ait jamais été utilisée
			$query=$connexion->prepare('SELECT COUNT(*) AS nbr FROM utilisateur WHERE email =:mail');
			$query->bindValue(':mail',$email,PDO::PARAM_STR);
			$query->execute();
			$mail_free=($query->fetchColumn()==0)?1:0;
			$query->CloseCursor();
			if(!$mail_free)
			{
				$email_erreur1 = "Votre adresse email est déjà utilisé par un membre";
				$i++;
			}
	 
			//On vérifie la forme maintenant
			if (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
			{
				$email_erreur2 = "Votre nouvelle adresse E-Mail n'a pas un format valide";
				$i++;
			}
		}
	 
		//Vérification de la signature
		if (strlen($signature) > 200)
		{
			$signature_erreur = "Votre nouvelle signature est trop longue";
			$i++;
		}
	  
	  
		//Vérification de l'avatar
	  
		if (!empty($_FILES['avatar']['size']))
		{
			//On définit les variables :
			$maxsize = 30072; //Poid de l'image
			$maxwidth = 100; //Largeur de l'image
			$maxheight = 100; //Longueur de l'image
			//Liste des extensions valides
			$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' );
	  
			if ($_FILES['avatar']['error'] > 0)
			{
			$avatar_erreur = "Erreur lors du tranfsert de l'avatar : ";
			}
			if ($_FILES['avatar']['size'] > $maxsize)
			{
			$i++;
			$avatar_erreur1 = "Le fichier est trop gros :
			(<strong>".$_FILES['avatar']['size']." Octets</strong>
			contre <strong>".$maxsize." Octets</strong>)";
			}
	  
			$image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
			if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
			{
			$i++;
			$avatar_erreur2 = "Image trop large ou trop longue :
			(<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
			<strong>".$maxwidth."x".$maxheight."</strong>)";
			}
	  
			$extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
			if (!in_array($extension_upload,$extensions_valides) )
			{
					$i++;
					$avatar_erreur3 = "Extension de l'avatar incorrecte";
			}
		}
		
		echo '<p><a href="index.php?page=indexForum.php">Index du forum</a> --> Modification du profil';
		echo '<h1>Modification d\'un profil</h1>';
	 
	  
		if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
		{
			if (!empty($_FILES['avatar']['size']))
			{
					$nomavatar=move_avatar($_FILES['avatar']);
					$query=$connexion->prepare('UPDATE utilisateur
					SET avatar = :avatar 
					WHERE idUtil = :id');
					$query->bindValue(':avatar',$nomavatar,PDO::PARAM_STR);
					$query->bindValue(':id',$id,PDO::PARAM_INT);
					$query->execute();
					$query->CloseCursor();
			}
	  
			//Une nouveauté ici : on peut choisis de supprimer l'avatar
			if (isset($_POST['delete']))
			{
					$query=$connexion->prepare('UPDATE utilisateur
			SET avatar=0 WHERE idUtil = :id');
					$query->bindValue(':id',$id,PDO::PARAM_INT);
					$query->execute();
					$query->CloseCursor();
			}
	  
			echo'<h1>Modification termin&eacute;e</h1>';
			echo'<p>Votre profil a été modifié avec succés !</p>';
			echo'<p>Cliquez <a href="index.php?page=indexForum.php">ici</a> 
			pour revenir à la page d accueil</p>';
	  
			//On modifie la table
	  
			$query=$connexion->prepare('UPDATE utilisateur
			SET  mdp = :mdp, email=:mail, signature=:sign WHERE idUtil=:id');
			$query->bindValue(':mdp',$pass,PDO::PARAM_INT);
			$query->bindValue(':mail',$email,PDO::PARAM_STR);
			$query->bindValue(':sign',$signature,PDO::PARAM_STR);
			$query->bindValue(':id',$id,PDO::PARAM_INT);
			$query->execute();
			$query->CloseCursor();
		}
		else
		{
			echo'<h1>Modification interrompue</h1>';
			echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';
			echo'<p>'.$i.' erreur(s)</p>';
			echo'<p>'.$mdp_erreur.'</p>';
			echo'<p>'.$email_erreur1.'</p>';
			echo'<p>'.$email_erreur2.'</p>';
			echo'<p>'.$msn_erreur.'</p>';
			echo'<p>'.$signature_erreur.'</p>';
			echo'<p>'.$avatar_erreur.'</p>';
			echo'<p>'.$avatar_erreur1.'</p>';
			echo'<p>'.$avatar_erreur2.'</p>';
			echo'<p>'.$avatar_erreur3.'</p>';
			echo'<p> Cliquez <a href="index.php?page=voirprofil.php&action=modifier">ici</a> pour recommencer</p>';
		}
	} //Fin du else
    break;
  
default; //Si jamais c'est aucun de ceux-là c'est qu'il y a eu un problème :o
echo'<p>Cette action est impossible</p>';
  
} //Fin du switch
?>
</div>
</body>
</html>