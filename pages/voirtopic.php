<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
$titre="Voir un sujet";
include("/include/conn.php");
include("/include/debut.php");
include("/include/bbcode.php"); //On verra plus tard ce qu'est ce fichier
  
//On r�cup�re la valeur de t
$topic = (int) $_GET['t'];
  
//A partir d'ici, on va compter le nombre de messages pour n'afficher que les 15 premiers
$query=$connexion->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id, topic_last_post,
forum_name, auth_view, auth_topic, auth_post 
FROM forum_topic 
LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id 
WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
$forum=$data['forum_id']; 
$totalDesMessages = $data['topic_post'] + 1;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

echo '<p><i>Vous �tes ici</i> : <a href="/PPEweb/public/index.php?page=indexForum.php">Index du forum</a> --> 
<a href="/PPEweb/public/index.php?page=voirforum.php&f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
 --> <a href="/PPEweb/public/index.php?page=voirtopic.php&t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
echo '<h1>'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br /><br />';

//Nombre de pages
$page = (isset($_GET['pg']))?intval($_GET['pg']):1;
 
//On affiche les pages 1-2-3 etc...
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
    echo $i;
    }
    else
    {
    echo '<a href="/PPEweb/public/index.php?page=voirtopic.php&t='.$topic.'&pg='.$i.'">
    ' . $i . '</a> ';
    }
}
echo'</p>';
  
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;
 
  
//On affiche l'image r�pondre
echo'<a href="/PPEweb/public/index.php?page=poster.php&action=repondre&amp;t='.$topic.'">
<img src="./img/icon_forum/repondre.gif" alt="R�pondre" title="R�pondre � ce topic" /></a>';
  
//On affiche l'image nouveau topic
echo'<a href="/PPEweb/public/index.php?page=poster.php&action=nouveautopic&amp;f='.$data['forum_id'].'">
<img src="./img/icon_forum/nouveau.gif" alt="Nouveau topic" title="Poster un nouveau topic" /></a>';
$query->CloseCursor(); 
//Enfin on commence la boucle !
$query=$connexion->prepare('SELECT post_id , post_createur , post_texte , post_time ,
idUtil, pseudo, dateInscription, avatar, post, signature
FROM forum_post
LEFT JOIN utilisateur ON utilisateur.idUtil = forum_post.post_createur
WHERE topic_id =:topic
ORDER BY post_id
LIMIT :premier, :nombre');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();
  
//On v�rifie que la requ�te a bien retourn� des messages
if ($query->rowCount()<1)
{
        echo'<p>Il n y a aucun post sur ce topic, v�rifiez l url et reessayez</p>';
}
else
{
        //Si tout roule on affiche notre tableau puis on remplit avec une boucle
        ?><table>
        <tr>
        <th class="vt_auteur"><strong>Auteurs</strong></th>             
        <th class="vt_mess"><strong>Messages</strong></th>       
        </tr>
        <?php
        while ($data = $query->fetch())
        {
//On commence � afficher le pseudo du cr�ateur du message :
         //On v�rifie les droits du membre
         //(partie du code comment�e plus tard)
         echo'<tr><td><strong>
         <a href="/PPEweb/public/index.php?page=voirprofil.php&m='.$data['idUtil'].'&amp;action=consulter">
         '.stripslashes(htmlspecialchars($data['pseudo'])).'</a></strong></td>';
            
         /* Si on est l'auteur du message, on affiche des liens pour
         Mod�rer celui-ci.
         Les mod�rateurs pourront aussi le faire, il faudra donc revenir sur
         ce code un peu plus tard ! */    
    
         if ($id == $data['post_createur'])
         {
         echo'<td id=p_'.$data['post_id'].'>Post� � '.date('H\hi \l\e d M y',$data['post_time']).'
         <a href="/PPEweb/public/index.php?page=poster.php&p='.$data['post_id'].'&amp;action=delete">
         <img src="./img/icon_forum/supprimer.gif" alt="Supprimer"
         title="Supprimer ce message" /></a>   
         <a href="/PPEweb/public/index.php?page=poster.php&p='.$data['post_id'].'&amp;action=edit">
         <img src="./img/icon_forum/editer.gif" alt="Editer"
         title="Editer ce message" /></a></td></tr>';
         }
         else
         {
         echo'<td>
         Post� � '.date('H\hi \l\e d M y',$data['post_time']).'
         </td></tr>';
         }
        
         //D�tails sur le membre qui a post�
         echo'<tr><td>
         <img src="./img/avatars/'.$data['avatar'].'" alt="" />
         <br />Membre inscrit le '.date('d/m/Y',$data['dateInscription']).'
         <br />Messages : '.$data['post'].'<br /></td>';
                
         //Message
         echo'<td>'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'
         <br /><hr />'.code(nl2br(stripslashes(htmlspecialchars($data['signature'])))).'</td></tr>';
         } //Fin de la boucle ! \o/
         $query->CloseCursor();
          ?>
</table>
<?php
        echo '<p>Page : ';
        for ($i = 1 ; $i <= $nombreDePages ; $i++)
        {
                if ($i == $page) //On affiche pas la page actuelle en lien
                {
                echo $i;
                }
                else
                {
                echo '<a href="/PPEweb/public/index.php?page=voirtopic.php&t='.$topic.'&amp;pg='.$i.'">
                ' . $i . '</a> ';
                }
        }
        echo'</p>';
        
        //On ajoute 1 au nombre de visites de ce topic
        $query=$connexion->prepare('UPDATE forum_topic
        SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
 
} //Fin du if qui v�rifiait si le topic contenait au moins un message
?>          
</div>
</body>
</html>