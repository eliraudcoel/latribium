<?php
function code($texte)
{
//Smileys
$texte = str_replace(':D', '<img src="./img/smileys/heureux.gif" title="heureux" alt="heureux" />', $texte);
$texte = str_replace(':lol:', '<img src="./img/smileys/lol.gif" title="lol" alt="lol" />', $texte);
$texte = str_replace(':triste:', '<img src="./img/smileys/triste.gif" title="triste" alt="triste" />', $texte);
$texte = str_replace(':frime:', '<img src="./img/smileys/cool.gif" title="cool" alt="cool" />', $texte);
$texte = str_replace(':s', '<img src="./img/smileys/confus.gif" title="confus" alt="confus" />', $texte);
$texte = str_replace(':O', '<img src="./img/smileys/choc.gif" title="choc" alt="choc" />', $texte);
$texte = str_replace(':question:', '<img src="./img/smileys/question.gif" title="?" alt="?" />', $texte);
$texte = str_replace(':exclamation:', '<img src="./img/smileys/exclamation.gif" title="!" alt="!" />', $texte);

$texte = str_replace(':arrow:', '<img src="./img/smileys/arrow.gif" title="en avant" alt="en avant" />', $texte);
$texte = str_replace(':cry:', '<img src="./img/smileys/pleure.gif" title="pleurer" alt="pleurer" />', $texte);
$texte = str_replace(':biggrin:', '<img src="./img/smileys/biggrin.gif" title="rire" alt="rire" />', $texte);
$texte = str_replace(':geek:', '<img src="./img/smileys/geek.gif" title="geek" alt="geek" />', $texte);
$texte = str_replace(':surprise:', '<img src="./img/smileys/surpris.gif" title="surpris" alt="surpris" />', $texte);
$texte = str_replace(':ugeek:', '<img src="./img/smileys/ugeek.gif" title="ugeek" alt="ugeek" />', $texte);
$texte = str_replace(';)', '<img src="./img/smileys/clin_doeil.gif" title="clin d\'oeil" alt="clin d\'oeil" />', $texte);
$texte = str_replace(':evil:', '<img src="./img/smileys/evil.gif" title="diable" alt="diable" />', $texte);
$texte = str_replace(':idea:', '<img src="./img/smileys/idee.gif" title="idée" alt="idée" />', $texte);
$texte = str_replace(':x', '<img src="./img/smileys/enerver.gif" title="&eacute;nerver" alt="&eacute;nerver" />', $texte);
$texte = str_replace(':mrgreen:', '<img src="./img/smileys/mrgreen.gif" title="M.Vert" alt="M.Vert" />', $texte);
$texte = str_replace(':/', '<img src="./img/smileys/neutre.gif" title="neutre" alt="neutre" />', $texte);
$texte = str_replace(':p', '<img src="./img/smileys/razz.gif" title="razz" alt="razz" />', $texte);
$texte = str_replace(':oops:', '<img src="./img/smileys/rougir.gif" title="rougir" alt="rougir" />', $texte);
$texte = str_replace(':roll:', '<img src="./img/smileys/roulle_yeux.gif" title="rouller des yeux" alt="rouller des yeux" />', $texte);
$texte = str_replace(':twisted:', '<img src="./img/smileys/tordu.gif" title="tordu" alt="tordu" />', $texte);
 
//Mise en forme du texte
//gras
$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte); 
//italique
$texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $texte);
//souligné
$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);
//lien
$texte = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $texte);
//quote
$texte = preg_replace('`\[quote\](.+)\[/quote\]`isU', '<div id="quote">$1</div>', $texte);
 //etc., etc.
 
//On retourne la variable texte
return $texte;
}
?>