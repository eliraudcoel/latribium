<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
$titre="Poster";
include("./include/conn.php");
include("./include/debut.php");
//On récupère la valeur de la variable action
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

switch($action)
{
    //Premier cas : nouveau topic
    case "nouveautopic":
    //On passe le message dans une série de fonction
    $message = $_POST['message'];
    $mess = "Topic";
 
    //Pareil pour le titre
    $titre = $_POST['titre'];
 
    //ici seulement, maintenant qu'on est sur qu'elle existe, on récupère la valeur de la variable f
    $forum = (int) $_GET['f'];
    $temps = time();
 
    if (empty($message) || empty($titre))
    {
        echo'<p>Votre message ou votre titre est vide, 
        cliquez <a href="index.php?page=poster.php&action=nouveautopic&amp;f='.$forum.'">ici</a> pour recommencer</p>';
    }
    else //Si jamais le message n'est pas vide
    {
		//On entre le topic dans la base de donnée en laissant
        //le champ topic_last_post à 0
        $query=$connexion->prepare('INSERT INTO forum_topic
        (forum_id, topic_titre, topic_createur, topic_vu, topic_time, topic_genre)
        VALUES(:forum, :titre, :id, 1, :temps, :mess)');
        $query->bindValue(':forum', $forum, PDO::PARAM_INT);
        $query->bindValue(':titre', $titre, PDO::PARAM_STR);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':temps', $temps, PDO::PARAM_INT);
        $query->bindValue(':mess', $mess, PDO::PARAM_STR);
        $query->execute();
 
 
        $nouveautopic = $connexion->lastInsertId(); //Notre fameuse fonction !
        $query->CloseCursor(); 
 
        //Puis on entre le message
        $query=$connexion->prepare('INSERT INTO forum_post
        (post_createur, post_texte, post_time, topic_id, post_forum_id)
        VALUES (:id, :mess, :temps, :nouveautopic, :forum)');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':mess', $message, PDO::PARAM_STR);
        $query->bindValue(':temps', $temps,PDO::PARAM_INT);
        $query->bindValue(':nouveautopic', (int) $nouveautopic, PDO::PARAM_INT);
        $query->bindValue(':forum', $forum, PDO::PARAM_INT);
        $query->execute();
 
 
        $nouveaupost = $connexion->lastInsertId(); //Encore notre fameuse fonction !
        $query->CloseCursor(); 
		
		//On ajoute une ligne dans la table forum_topic_view
		$query=$connexion->prepare('INSERT INTO forum_topic_view 
		(tv_id, tv_topic_id, tv_forum_id, tv_post_id, tv_poste) 
		VALUES(:id, :topic, :forum, :post, :poste)');
		$query->bindValue(':id',$id,PDO::PARAM_INT);
		$query->bindValue(':topic',$nouveautopic,PDO::PARAM_INT);
		$query->bindValue(':forum',$forum ,PDO::PARAM_INT);
		$query->bindValue(':post',$nouveaupost,PDO::PARAM_INT);
		$query->bindValue(':poste','1',PDO::PARAM_STR);
		$query->execute();
		$query->CloseCursor();
 
 
        //Ici on update comme prévu la valeur de topic_last_post et de topic_first_post
        $query=$connexion->prepare('UPDATE forum_topic
        SET topic_last_post = :nouveaupost,
        topic_first_post = :nouveaupost
        WHERE topic_id = :nouveautopic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);    
        $query->bindValue(':nouveautopic', (int) $nouveautopic, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
 
        //Enfin on met à jour les tables forum_forum et utilisateur
        $query=$connexion->prepare('UPDATE forum_forum SET forum_post = forum_post + 1 ,forum_topic = forum_topic + 1, 
        forum_last_post_id = :nouveaupost
        WHERE forum_id = :forum');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);    
        $query->bindValue(':forum', (int) $forum, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
     
        $query=$connexion->prepare('UPDATE utilisateur SET post = post + 1 WHERE idUtil = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);    
        $query->execute();
        $query->CloseCursor();
		
        //Et un petit message
        echo'<p>Votre message a bien été ajouté!<br /><br />Cliquez <a href="index.php?page=indexForum.php">ici</a> pour revenir à l index du forum<br />
        Cliquez <a href="index.php?page=voirtopic.php&t='.$nouveautopic.'">ici</a> pour le voir</p>';
    }
    break; //Houra !
	
	//Deuxième cas : répondre
    case "repondre":
    $message = $_POST['message'];
 
    //ici seulement, maintenant qu'on est sur qu'elle existe, on récupère la valeur de la variable t
    $topic = (int) $_GET['t'];
    $temps = time();
 
    if (empty($message))
    {
        echo'<p>Votre message est vide, cliquez <a href="index.php?page=poster.php&action=repondre&amp;t='.$topic.'">ici</a> pour recommencer</p>';
    }
    else //Sinon, si le message n'est pas vide
    {
 
        //On récupère l'id du forum
        $query=$connexion->prepare('SELECT forum_id, topic_post FROM forum_topic WHERE topic_id = :topic');
        $query->bindValue(':topic', $topic, PDO::PARAM_INT);    
        $query->execute();
        $data=$query->fetch();
        $forum = $data['forum_id'];
 
        //Puis on entre le message
        $query=$connexion->prepare('INSERT INTO forum_post
        (post_createur, post_texte, post_time, topic_id, post_forum_id)
        VALUES(:id,:mess,:temps,:topic,:forum)');
        $query->bindValue(':id', $id, PDO::PARAM_INT);   
        $query->bindValue(':mess', $message, PDO::PARAM_STR);  
        $query->bindValue(':temps', $temps, PDO::PARAM_INT);  
        $query->bindValue(':topic', $topic, PDO::PARAM_INT);   
        $query->bindValue(':forum', $forum, PDO::PARAM_INT); 
        $query->execute();
 
        $nouveaupost = $connexion->lastInsertId();
        $query->CloseCursor(); 
		
 		//On update la table forum_topic_view
		$query=$connexion->prepare('UPDATE forum_topic_view 
		SET tv_post_id = :post, tv_poste = :poste
		WHERE tv_id = :id AND tv_topic_id = :topic');
		$query->bindValue(':post',$nouveaupost,PDO::PARAM_INT);
		$query->bindValue(':poste','1',PDO::PARAM_STR);
		$query->bindValue(':id',$id,PDO::PARAM_INT);
		$query->bindValue(':topic',$topic,PDO::PARAM_INT);
		$query->execute();
		$query->CloseCursor();
 
        //On change un peu la table forum_topic
        $query=$connexion->prepare('UPDATE forum_topic SET topic_post = topic_post + 1, topic_last_post = :nouveaupost WHERE topic_id =:topic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);   
        $query->bindValue(':topic', (int) $topic, PDO::PARAM_INT); 
        $query->execute();
        $query->CloseCursor();
		
        //Puis même combat sur les 2 autres tables
        $query=$connexion->prepare('UPDATE forum_forum SET forum_post = forum_post + 1 , forum_last_post_id = :nouveaupost WHERE forum_id = :forum');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);   
        $query->bindValue(':forum', (int) $forum, PDO::PARAM_INT); 
        $query->execute();
        $query->CloseCursor(); 
 
        $query=$connexion->prepare('UPDATE utilisateur SET post = post + 1 WHERE idUtil = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT); 
        $query->execute();
        $query->CloseCursor(); 
 
        //Et un petit message
        $nombreDeMessagesParPage = 15;
        $nbr_post = $data['topic_post']+1;
        $page = ceil($nbr_post / $nombreDeMessagesParPage);
        echo'<p>Votre message a bien été ajouté!<br /><br />
        Cliquez <a href="index.php?page=indexForum.php">ici</a> pour revenir à l index du forum<br />
        Cliquez <a href="index.php?page=voirtopic.php&t='.$topic.'&amp;pg='.$page.'#p_'.$nouveaupost.'">ici</a> pour le voir</p>';
    }//Fin du else
    break;
	
	case "edit": //Si on veut éditer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
  
    //On récupère le message
    $message = $_POST['message'];
 
    //Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin)
    $query=$connexion->prepare('SELECT post_createur, post_texte, post_time, topic_id, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data1 = $query->fetch();
    $topic = $data1['topic_id'];
 
    //On récupère la place du message dans le topic (pour le lien)
    $query = $connexion->prepare('SELECT COUNT(*) AS nbr FROM forum_post 
    WHERE topic_id = :topic AND post_time < '.$data1['post_time']);
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $data2=$query->fetch();
 
    if (!verif_auth($data1['auth_modo'])&& $data1['post_createur'] != $id)
    {
        // Si cette condition n'est pas remplie ça va barder :o
        erreur(ERR_AUTH_EDIT);    
    }
    else //Sinon ça roule et on continue
    {
        $query=$connexion->prepare('UPDATE forum_post SET post_texte =  :message WHERE post_id = :post');
        $query->bindValue(':message',$message,PDO::PARAM_STR);
        $query->bindValue(':post',$post,PDO::PARAM_INT);
        $query->execute();
        $nombreDeMessagesParPage = 15;
        $nbr_post = $data2['nbr']+1;
        $page = ceil($nbr_post / $nombreDeMessagesParPage);
        echo'<p>Votre message a bien été édité!<br /><br />
        Cliquez <a href="index.php?page=indexForum.php">ici</a> pour revenir à l index du forum<br />
        Cliquez <a href="index.php?page=voirtopic.php&t='.$topic.'&amp;page='.$page.'#p_'.$post.'">ici</a> pour le voir</p>';
        $query->CloseCursor();
    }
	break;
	
	case "delete": //Si on veut supprimer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
    $query=$connexion->prepare('SELECT post_createur, post_texte, forum_id, topic_id, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $topic = $data['topic_id'];
    $forum = $data['forum_id'];
    $poster = $data['post_createur'];
 
    
    //Ensuite on vérifie que le membre a le droit d'être ici 
    //(soit le créateur soit un modo/admin)
    if ($poster != $id)
    {
        // Si cette condition n'est pas remplie ça va barder :o
        erreur(ERR_AUTH_DELETE); 
    }
    else //Sinon ça roule et on continue
    {
         
        //Ici on vérifie plusieurs choses :
        //est-ce un premier post ? Dernier post ou post classique ?
  
        $query = $connexion->prepare('SELECT topic_first_post, topic_last_post FROM forum_topic
        WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $data_post=$query->fetch();
                
                
                
        //On distingue maintenant les cas
        if ($data_post['topic_first_post']==$post) //Si le message est le premier
        {
 
            //Il faut s'assurer que ce n'est pas une erreur
  
            echo'<p>Vous avez choisi de supprimer un post.
            Cependant ce post est le premier du topic. Voulez vous supprimer le topic ? <br />
            <a href="index.php?page=postok.php&action=delete_topic&amp;t='.$topic.'">oui</a> - <a href="index.php?page=voirtopic.php&t='.$topic.'">non</a>
            </p>';
            $query->CloseCursor();                     
        }
        elseif ($data_post['topic_last_post']==$post)  //Si le message est le dernier
        {
  
            //On supprime le post
            $query=$connexion->prepare('DELETE FROM forum_post WHERE post_id = :post');
            $query->bindValue(':post',$post,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();
            
            //On modifie la valeur de topic_last_post pour cela on
            //récupère l'id du plus récent message de ce topic
            $query=$connexion->prepare('SELECT post_id FROM forum_post WHERE topic_id = :topic 
            ORDER BY post_id DESC LIMIT 0,1');
            $query->bindValue(':topic',$topic,PDO::PARAM_INT);
            $query->execute();
            $data=$query->fetch();             
            $last_post_topic=$data['post_id'];
            $query->CloseCursor();
 
            //On fait de même pour forum_last_post_id
            $query=$connexion->prepare('SELECT post_id FROM forum_post WHERE post_forum_id = :forum
            ORDER BY post_id DESC LIMIT 0,1');
            $query->bindValue(':forum',$forum,PDO::PARAM_INT);
            $query->execute();
            $data=$query->fetch();             
            $last_post_forum=$data['post_id'];
            $query->CloseCursor();   
                    
            //On met à jour la valeur de topic_last_post
             
            $query=$connexion->prepare('UPDATE forum_topic SET topic_last_post = :last
            WHERE topic_last_post = :post');
            $query->bindValue(':last',$last_post_topic,PDO::PARAM_INT);
            $query->bindValue(':post',$post,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();
  
            //On enlève 1 au nombre de messages du forum et on met à       
            //jour forum_last_post
            $query=$connexion->prepare('UPDATE forum_forum SET forum_post = forum_post - 1, forum_last_post_id = :last
            WHERE forum_id = :forum');
            $query->bindValue(':last',$last_post_forum,PDO::PARAM_INT);
            $query->bindValue(':forum',$forum,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor(); 
                         
            //On enlève 1 au nombre de messages du topic
            $query=$connexion->prepare('UPDATE forum_topic SET  topic_post = topic_post - 1
            WHERE topic_id = :topic');
            $query->bindValue(':topic',$topic,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor(); 
                        
            //On enlève 1 au nombre de messages du membre
            $query=$connexion->prepare('UPDATE utilisateur SET  post = post - 1
            WHERE idUtil = :id');
            $query->bindValue(':id',$poster,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();  
                         
            //Enfin le message
            echo'<p>Le message a bien été supprimé !<br />
            Cliquez <a href="index.php?page=voirtopic.php&t='.$topic.'">ici</a> pour retourner au topic<br />
            Cliquez <a href="index.php?page=indexForum.php">ici</a> pour revenir à l index du forum</p>';
  
        }
        else // Si c'est un post classique
        {
  
            //On supprime le post
            $query=$connexion->prepare('DELETE FROM forum_post WHERE post_id = :post');
            $query->bindValue(':post',$post,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();
                        
            //On enlève 1 au nombre de messages du forum
            $query=$connexion->prepare('UPDATE forum_forum SET forum_post = forum_post - 1  WHERE forum_id = :forum');
            $query->bindValue(':forum',$forum,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor(); 
                         
            //On enlève 1 au nombre de messages du topic
            $query=$connexion->prepare('UPDATE forum_topic SET  topic_post = topic_post - 1
            WHERE topic_id = :topic');
            $query->bindValue(':topic',$topic,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor(); 
                        
            //On enlève 1 au nombre de messages du membre
            $query=$connexion->prepare('UPDATE utilisateur SET  post = post - 1
            WHERE idUtil = :id');
            $query->bindValue(':id',$data['post_createur'],PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();  
                         
            //Enfin le message
            echo'<p>Le message a bien été supprimé !<br />
            Cliquez <a href="index.php?page=voirtopic.php&t='.$topic.'">ici</a> pour retourner au topic<br />
            Cliquez <a href="index.php?page=indexForum.php">ici</a> pour revenir à l index du forum</p>';
        }
                
    } //Fin du else
	break;
	
	case "delete_topic":
    $topic = (int) $_GET['t'];
    $query=$connexion->prepare('SELECT forum_topic.forum_id, auth_modo
    FROM forum_topic
    LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id
    WHERE topic_id=:topic');
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $forum = $data['forum_id'];
  
    //Ensuite on vérifie que le membre a le droit d'être ici 
    //c'est-à-dire si c'est un modo / admin
  
	$query->CloseCursor();

	//On compte le nombre de post du topic
	$query=$connexion->prepare('SELECT topic_post FROM forum_topic WHERE topic_id = :topic');
	$query->bindValue(':topic',$topic,PDO::PARAM_INT);
	$query->execute();
	$data = $query->fetch();
	$nombrepost = $data['topic_post'] + 1;
	$query->CloseCursor();

	//On supprime le topic
	$query=$connexion->prepare('DELETE FROM forum_topic
	WHERE topic_id = :topic');
	$query->bindValue(':topic',$topic,PDO::PARAM_INT);
	$query->execute();
	$query->CloseCursor();
	
	//On enlève le nombre de post posté par chaque membre dans le topic
	$query=$connexion->prepare('SELECT post_createur, COUNT(*) AS nombre_mess FROM forum_post
	WHERE topic_id = :topic GROUP BY post_createur');
	$query->bindValue(':topic',$topic,PDO::PARAM_INT);
	$query->execute();

	while($data = $query->fetch())
	{
		$query=$connexion->prepare('UPDATE utilisateur
		SET post = post - :mess
		WHERE membre_id = :id');
		$query->bindValue(':mess',$data['nombre_mess'],PDO::PARAM_INT);
		$query->bindValue(':id',$data['post_createur'],PDO::PARAM_INT);
		$query->execute();
	}

	$query->CloseCursor();       
	//Et on supprime les posts !
	$query=$connexion->prepare('DELETE FROM forum_post WHERE topic_id = :topic');
	$query->bindValue(':topic',$topic,PDO::PARAM_INT);
	$query->execute();
	$query->CloseCursor(); 

	//Dernière chose, on récupère le dernier post du forum
	$query=$connexion->prepare('SELECT post_id FROM forum_post
	WHERE post_forum_id = :forum ORDER BY post_id DESC LIMIT 0,1');
	$query->bindValue(':forum',$forum,PDO::PARAM_INT);
	$query->execute();
	$data = $query->fetch();

	//Ensuite on modifie certaines valeurs :
	$query=$connexion->prepare('UPDATE forum_forum
	SET forum_topic = forum_topic - 1, forum_post = forum_post - :nbr, forum_last_post_id = :id
	WHERE forum_id = :forum');
	$query->bindValue(':nbr',$nombrepost,PDO::PARAM_INT);
	$query->bindValue(':id',$data['post_id'],PDO::PARAM_INT);
	$query->bindValue(':forum',$forum,PDO::PARAM_INT);
	$query->execute(); 
	$query->CloseCursor();

	//Enfin le message
	echo'<p>Le topic a bien été supprimé !<br />
	Cliquez <a href="index.php?page=indexForum.php">ici</a> pour revenir à l index du forum</p>';
	break;
	
	default;
    echo'<p>Cette action est impossible</p>';
} //Fin du Switch
?>
</div>
</body>
</html>