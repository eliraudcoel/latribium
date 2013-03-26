<?php
//Cette fonction doit être appelée avant tout code html
if(!isset($_SESSION)) 
{ 
	session_start(); 
}

if(!isset($_SESSION['pseudo']))
{
	echo('<script>alert(\'Vous ne vous êtes pas identifié! Vous ne pouvez pas accéder à cette partie du site... Inscrivez-vous!\');');
	echo('setTimeout("document.location=\'./index.php?page=accueil.php\'", 500);</script>');
}
else
{
 
	//On donne ensuite un titre à la page, puis on appelle notre fichier debut.php
	$titre = "Index du forum";
	include("./include/conn.php");
	include("./include/debut.php");

	echo'<a href ="index.php?page=indexForum.php">Index du forum</a>';
	?>
	<div id="forum">
	</br>
	<?php
	//Initialisation de deux variables
	$totaldesmessages = 0;
	$categorie = NULL;

	//Cette requête permet d'obtenir tout sur le forum
	$query=$connexion->prepare('SELECT cat_id, cat_nom, 
	forum_forum.forum_id, forum_name, forum_desc, forum_post, forum_topic, auth_view, forum_topic.topic_id,  forum_topic.topic_post, 
	post_id, post_time, post_createur, pseudo, idUtil
	FROM forum_categorie
	LEFT JOIN forum_forum ON forum_categorie.cat_id = forum_forum.forum_cat_id
	LEFT JOIN forum_post ON forum_post.post_id = forum_forum.forum_last_post_id
	LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
	LEFT JOIN utilisateur ON utilisateur.idUtil = forum_post.post_createur
	WHERE auth_view <= :lvl 
	ORDER BY cat_ordre, forum_ordre DESC');
	$query->bindValue(':lvl',$lvl,PDO::PARAM_INT);
	$query->execute();
	?>
	<table>
	<?php
	//Début de la boucle
	while($data = $query->fetch())
	{
		//On affiche chaque catégorie
		if( $categorie != $data['cat_id'] )
		{
			//Si c'est une nouvelle catégorie on l'affiche
			
			$categorie = $data['cat_id'];
			?>
			<tr>
			<th></th>
			<th class="titre"><strong><?php echo stripslashes(htmlspecialchars($data['cat_nom'])); ?>
			</strong></th>             
			<th class="nombremessages"><strong>Sujets</strong></th>       
			<th class="nombresujets"><strong>Messages</strong></th>       
			<th class="derniermessage"><strong>Dernier message</strong></th>   
			</tr>
			<?php
					
		}
	 
		//Ici, on met le contenu de chaque catégorie

		// Ce super echo de la mort affiche tous
		// les forums en détail : description, nombre de réponses etc...
	 
		echo'<tr><td><img src="./img/icon_forum/forum_read.gif" alt="message" /></td>
		<td class="titre"><strong>
		<a href="index.php?page=voirforum.php&f='.$data['forum_id'].'">
		'.stripslashes(htmlspecialchars($data['forum_name'])).'</a></strong>
		<br />'.nl2br(stripslashes(htmlspecialchars($data['forum_desc']))).'</td>
		<td class="nombresujets">'.$data['forum_topic'].'</td>
		<td class="nombremessages">'.$data['forum_post'].'</td>';
	 
		// Deux cas possibles :
		// Soit il y a un nouveau message, soit le forum est vide
		if (!empty($data['forum_post']))
		{
			 //Selection dernier message
		 $nombreDeMessagesParPage = 15;
			 $nbr_post = $data['topic_post'] +1;
		 $page = ceil($nbr_post / $nombreDeMessagesParPage);
			  
			 echo'<td class="derniermessage">
			 '.date('H\hi \l\e d/M/Y',$data['post_time']).'<br />
			 <a href="index.php?page=voirprofil.php&m='.stripslashes(htmlspecialchars($data['idUtil'])).'&amp;action=consulter">'.$data['pseudo'].'  </a>
			 <a href="index.php?page=voirtopic.php&t='.$data['topic_id'].'&amp;pg='.$page.'#p_'.$data['post_id'].'">
			 <img src="./img/icon_forum/go.gif" alt="go" /></a></td></tr>';
	 
		 }
		 else
		 {
			 echo'<td class="nombremessages">Pas de message</td></tr>';
		 }
	 
		 //Cette variable stock le nombre de messages, on la met à jour
		 $totaldesmessages += $data['forum_post'];
	 
		 //On ferme notre boucle et nos balises
	} //fin de la boucle
	$query->CloseCursor();
	echo '</table></div>';

	//Le pied de page ici :
	echo'<div id="footer">
	<h2>
	Qui est en ligne ?
	</h2>
	';
	 
	//On compte les membres
	$TotalDesMembres = $connexion->query('SELECT COUNT(*) FROM utilisateur')->fetchColumn();
	$query->CloseCursor();   
	$query = $connexion->query('SELECT pseudo, idUtil FROM utilisateur ORDER BY idUtil DESC LIMIT 0, 1');
	$data = $query->fetch();
	$derniermembre = stripslashes(htmlspecialchars($data['pseudo']));
	 
	echo'<p>Le total des messages du forum est <strong>'.$totaldesmessages.'</strong>.<br />';
	echo'Le site et le forum comptent <strong>'.$TotalDesMembres.'</strong> membres.<br />';
	echo'Le dernier membre est <a href="voirprofil.php?m='.$data['idUtil'].'&amp;action=consulter">'.$derniermembre.'</a>.</p>';
	$query->CloseCursor();
}
	?>
</div>
</div>
	<script>
		document.getElementById("Forum").style.color="#DCDCDC";
	</script>
</body>
</html>