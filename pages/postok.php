<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
$titre="Poster";
include("/include/conn.php");
include("/include/debut.php");
//On r�cup�re la valeur de la variable action
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

switch($action)
{
    //Premier cas : nouveau topic
    case "nouveautopic":
    //On passe le message dans une s�rie de fonction
    $message = $_POST['message'];
    $mess = "Topic";
 
    //Pareil pour le titre
    $titre = $_POST['titre'];
 
    //ici seulement, maintenant qu'on est sur qu'elle existe, on r�cup�re la valeur de la variable f
    $forum = (int) $_GET['f'];
    $temps = time();
 
    if (empty($message) || empty($titre))
    {
        echo'<p>Votre message ou votre titre est vide, 
        cliquez <a href="/PPEweb/public/index.php?page=poster.php&action=nouveautopic&amp;f='.$forum.'">ici</a> pour recommencer</p>';
    }
    else //Si jamais le message n'est pas vide
    {
		//On entre le topic dans la base de donn�e en laissant
        //le champ topic_last_post � 0
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
 
 
        //Ici on update comme pr�vu la valeur de topic_last_post et de topic_first_post
        $query=$connexion->prepare('UPDATE forum_topic
        SET topic_last_post = :nouveaupost,
        topic_first_post = :nouveaupost
        WHERE topic_id = :nouveautopic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);    
        $query->bindValue(':nouveautopic', (int) $nouveautopic, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
 
        //Enfin on met � jour les tables forum_forum et forum_membres
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
        echo'<p>Votre message a bien �t� ajout�!<br /><br />Cliquez <a href="/PPEweb/public/index.php?page=indexForum.php">ici</a> pour revenir � l index du forum<br />
        Cliquez <a href="/PPEweb/public/index.php?page=voirtopic.php&t='.$nouveautopic.'">ici</a> pour le voir</p>';
    }
    break; //Houra !
	
	//Deuxi�me cas : r�pondre
    case "repondre":
    $message = $_POST['message'];
 
    //ici seulement, maintenant qu'on est sur qu'elle existe, on r�cup�re la valeur de la variable t
    $topic = (int) $_GET['t'];
    $temps = time();
 
    if (empty($message))
    {
        echo'<p>Votre message est vide, cliquez <a href="/PPEweb/public/index.php?page=poster.php&action=repondre&amp;t='.$topic.'">ici</a> pour recommencer</p>';
    }
    else //Sinon, si le message n'est pas vide
    {
 
        //On r�cup�re l'id du forum
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
 
        //On change un peu la table forum_topic
        $query=$connexion->prepare('UPDATE forum_topic SET topic_post = topic_post + 1, topic_last_post = :nouveaupost WHERE topic_id =:topic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);   
        $query->bindValue(':topic', (int) $topic, PDO::PARAM_INT); 
        $query->execute();
        $query->CloseCursor(); 
 
        //Puis m�me combat sur les 2 autres tables
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
        echo'<p>Votre message a bien �t� ajout�!<br /><br />
        Cliquez <a href="/PPEweb/public/index.php?page=indexForum.php">ici</a> pour revenir � l index du forum<br />
        Cliquez <a href="/PPEweb/public/index.php?page=voirtopic.php&t='.$topic.'&amp;pg='.$page.'#p_'.$nouveaupost.'">ici</a> pour le voir</p>';
    }//Fin du else
    break;
	default;
    echo'<p>Cette action est impossible</p>';
} //Fin du Switch
?>
</div>
</body>
</html>