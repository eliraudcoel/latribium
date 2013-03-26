<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
$titre="Poster";
$balises = true;
include("./include/conn.php");
include("./include/debut.php");

//Qu'est ce qu'on veut faire ? poster, répondre ou éditer ?
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
 
//Si on veut poster un nouveau topic, la variable f se trouve dans l'url,
//On récupère certaines valeurs
if (isset($_GET['f']))
{
    $forum = (int) $_GET['f'];
    $query= $connexion->prepare('SELECT forum_id,forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_forum WHERE forum_id =:forum');
    $query->bindValue(':forum',$forum,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
    echo '<p><a href="index.php?page=indexForum.php">Index du forum</a> --> 
    <a href="index.php?page=voirforum.php&f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
    --> Nouveau topic</p>';
 
  
}
  
//Sinon c'est un nouveau message, on a la variable t et
//On récupère f grâce à une requête
elseif (isset($_GET['t']))
{
    $topic = (int) $_GET['t'];
    $query=$connexion->prepare('SELECT topic_titre, forum_topic.forum_id,
    forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_topic
    LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
    WHERE topic_id =:topic');
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
    $forum = $data['forum_id'];  
 
    echo '<p><a href="index.php?page=indexForum.php">Index du forum</a> --> 
    <a href="index.php?page=voirforum.php&f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
    --> <a href="index.php?page=voirtopic.php&t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
    --> R&eacute;pondre</p>';
}
  
//Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
//On ne connait que le post, il faut chercher le reste
elseif (isset ($_GET['p']))
{
    $post = (int) $_GET['p'];
    $query=$connexion->prepare('SELECT post_createur, forum_post.topic_id, topic_titre, forum_topic.forum_id,
    forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_post
    LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
    LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
    WHERE forum_post.post_id =:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
 
    $topic = $data['topic_id'];
    $forum = $data['forum_id'];
  
    echo '<p><a href="index.php?page=indexForum.php">Index du forum</a> --> 
    <a href="index.php?page=voirforum.php&f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
    --> <a href="index.php?page=voirtopic.php&t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
    --> Mod&eacute;rer un message</p>';
}
$query->CloseCursor(); 
 
switch($action)
{
case "repondre": //Premier cas on souhaite répondre
?>
<h1>Poster une réponse</h1>
  
<form method="post" action="index.php?page=postok.php&action=repondre&amp;t=<?php echo $topic ?>" name="formulaire">
  
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
<input type="button" id="soulign&eacute;" name="soulign&eacute;" value="Soulign&eacute;" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="./img/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(' :D ');return(false)" />
<img src="./img/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(' :lol: ');return(false)" />
<img src="./img/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(' :triste: ');return(false)" />
<img src="./img/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(' :frime: ');return(false)" />
<img src="./img/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(' :s ');return(false)" />
<img src="./img/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(' :o ');return(false)" />
<img src="./img/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(' :interrogation: ');return(false)" />
<img src="./img/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(' :exclamation: ');return(false)" />
<img src="./img/smileys/arrow.gif" title="en avant" alt="en avant" onClick="javascript:smilies(' :arrow: ');return(false)" />
<img src="./img/smileys/pleure.gif" title="pleurer" alt="pleurer" onClick="javascript:smilies(' :cry: ');return(false)" />
<img src="./img/smileys/biggrin.gif" title="rire" alt="rire" onClick="javascript:smilies(' :biggrin: ');return(false)" />
<img src="./img/smileys/geek.gif" title="geek" alt="geek" onClick="javascript:smilies(' :geek: ');return(false)" />
<img src="./img/smileys/surpris.gif" title="surpris" alt="surpris" onClick="javascript:smilies(' :surprise: ');return(false)" />
<img src="./img/smileys/ugeek.gif" title="ugeek" alt="ugeek" onClick="javascript:smilies(' :ugeek: ');return(false)" />
<img src="./img/smileys/clin_doeil.gif" title="clin d'oeil" alt="clin d'oeil" onClick="javascript:smilies(' ;) ');return(false)" />
<img src="./img/smileys/evil.gif" title="diable" alt="diable" onClick="javascript:smilies(' :evil: ');return(false)" />
<img src="./img/smileys/idee.gif" title="id&eacute;e" alt="id&eacute;e" onClick="javascript:smilies(' :idea: ');return(false)" />
<img src="./img/smileys/enerver.gif" title="&eacute;nerver" alt="&eacute;nerver" onClick="javascript:smilies(' :x ');return(false)" />
<img src="./img/smileys/mrgreen.gif" title="M.Vert" alt="M.Vert" onClick="javascript:smilies(' :mrgreen: ');return(false)" />
<img src="./img/smileys/neutre.gif" title="neutre" alt="neutre" onClick="javascript:smilies(' :/ ');return(false)" />
<img src="./img/smileys/razz.gif" title="razz" alt="razz" onClick="javascript:smilies(' :p ');return(false)" />
<img src="./img/smileys/rougir.gif" title="rougir" alt="rougir" onClick="javascript:smilies(' :oops: ');return(false)" />
<img src="./img/smileys/roulle_yeux.gif" title="rouller des yeux" alt="rouller des yeux" onClick="javascript:smilies(' :roll: ');return(false)" />
<img src="./img/smileys/tordu.gif" title="tordu" alt="tordu" onClick="javascript:smilies(' :twisted: ');return(false)" />
</fieldset>
  
<fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" name="message"></textarea></fieldset>
  
<input type="submit" name="submit" value="Envoyer" />
<input type="reset" name = "Effacer" value = "Effacer"/>
</p></form>
<?php
break;
  
case "nouveautopic":
?>
  
<h1>Nouveau topic</h1>
<form method="post" action="index.php?page=postok.php&action=nouveautopic&amp;f=<?php echo $forum ?>" name="formulaire">
  
<fieldset><legend>Titre</legend>
<input type="text" size="80" id="titre" name="titre" /></fieldset>
  
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
<input type="button" id="soulign&eacute;" name="soulign&eacute;" value="Soulign&eacute;" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="./img/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
<img src="./img/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
<img src="./img/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
<img src="./img/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
<img src="./img/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
<img src="./img/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
<img src="./img/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(':interrogation:');return(false)" />
<img src="./img/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(':exclamation:');return(false)" />
<img src="./img/smileys/arrow.gif" title="en avant" alt="en avant" onClick="javascript:smilies(' :arrow: ');return(false)" />
<img src="./img/smileys/pleure.gif" title="pleurer" alt="pleurer" onClick="javascript:smilies(' :cry: ');return(false)" />
<img src="./img/smileys/biggrin.gif" title="rire" alt="rire" onClick="javascript:smilies(' :biggrin: ');return(false)" />
<img src="./img/smileys/geek.gif" title="geek" alt="geek" onClick="javascript:smilies(' :geek: ');return(false)" />
<img src="./img/smileys/surpris.gif" title="surpris" alt="surpris" onClick="javascript:smilies(' :surprise: ');return(false)" />
<img src="./img/smileys/ugeek.gif" title="ugeek" alt="ugeek" onClick="javascript:smilies(' :ugeek: ');return(false)" />
<img src="./img/smileys/clin_doeil.gif" title="clin d'oeil" alt="clin d'oeil" onClick="javascript:smilies(' ;) ');return(false)" />
<img src="./img/smileys/evil.gif" title="diable" alt="diable" onClick="javascript:smilies(' :evil: ');return(false)" />
<img src="./img/smileys/idee.gif" title="id&eacute;e" alt="id&eacute;e" onClick="javascript:smilies(' :idea: ');return(false)" />
<img src="./img/smileys/enerver.gif" title="&eacute;nerver" alt="&eacute;nerver" onClick="javascript:smilies(' :x ');return(false)" />
<img src="./img/smileys/mrgreen.gif" title="M.Vert" alt="M.Vert" onClick="javascript:smilies(' :mrgreen: ');return(false)" />
<img src="./img/smileys/neutre.gif" title="neutre" alt="neutre" onClick="javascript:smilies(' :/ ');return(false)" />
<img src="./img/smileys/razz.gif" title="razz" alt="razz" onClick="javascript:smilies(' :p ');return(false)" />
<img src="./img/smileys/rougir.gif" title="rougir" alt="rougir" onClick="javascript:smilies(' :oops: ');return(false)" />
<img src="./img/smileys/roulle_yeux.gif" title="rouller des yeux" alt="rouller des yeux" onClick="javascript:smilies(' :roll: ');return(false)" />
<img src="./img/smileys/tordu.gif" title="tordu" alt="tordu" onClick="javascript:smilies(' :twisted: ');return(false)" />
</fieldset>
  
<fieldset><legend>Message</legend>
<textarea cols="80" rows="8" id="message" name="message"></textarea>
</fieldset>
<p>
<input type="submit" name="submit" value="Envoyer" />
<input type="reset" name = "Effacer" value = "Effacer" /></p>
</form>
<?php
break;

case "edit": //Si on veut éditer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
    echo'<h1>Edition</h1>';
  
    //On lance enfin notre requête
  
    $query=$connexion->prepare('SELECT post_createur, post_texte, auth_modo FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
 
    $text_edit = $data['post_texte']; //On récupère le message
 
    //Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin) 
    if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
    {
        // Si cette condition n'est pas remplie ça va barder :o
        erreur(ERR_AUTH_EDIT);
    }
    else //Sinon ça roule et on affiche la suite
    {
        //Le formulaire de postage
        ?>
        <form method="post" action="postok.php?action=edit&amp;p=<?php echo $post ?>" name="formulaire">
        <fieldset><legend>Mise en forme</legend>
        <input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
        <input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
        <input type="button" id="soulign&eacute;" name="soulign&eacute;" value="Soulign&eacute;" onClick="javascript:bbcode('[s]', '[/s]');return(false)"/>
        <input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
        <br /><br />
        <img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
        <img src="./images/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
        <img src="./images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
        <img src="./images/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
        <img src="./images/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
        <img src="./images/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
        <img src="./images/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
        <img src="./images/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(':interrogation:');return(false)" />
        <img src="./images/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(':exclamation:');return(false)" />
        </fieldset>
  
        <fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" name="message"><?php echo $text_edit ?>
        </textarea>
        </fieldset>
        <p>
        <input type="submit" name="submit" value="Editer !" />
        <input type="reset" name = "Effacer" value = "Effacer"/></p>
        </form>
        <?php
    }
break; //Fin de ce cas :o
  
case "delete": //Si on veut supprimer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
    //Ensuite on vérifie que le membre a le droit d'être ici
    echo'<h1>Suppression</h1>';
    $query=$connexion->prepare('SELECT post_createur, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id= :post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
  
    if ($data['post_createur'] != $id)
    {
        // Si cette condition n'est pas remplie ça va barder :o
        erreur(ERR_AUTH_DELETE); 
    }
    else //Sinon ça roule et on affiche la suite
    {
        echo'<p>&Ecirc;tes vous certains de vouloir supprimer ce post ?</p>';
        echo'<p><a href="index.php?page=postok.php&action=delete&amp;p='.$post.'">Oui</a> ou <a href="index.php?page=indexForum.php">Non</a></p>';
    }
    $query->CloseCursor();
break;

default: //Si jamais c'est aucun de ceux là c'est qu'il y a eu un problème :o
echo'<p>Cette action est impossible</p>';
} //Fin du switch
?>
</div>
</body>
</html>