<?php
//Test de connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=totoila_berceaud', 'totoila_berceaud', 'n9DTlUgIoAzm');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Forum</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
	   <script type="Text/JavaScript" src="scripts/bbcode.js"></script>
	   <script language="JavaScript" >
	   function golinks(where)
	   {self.location = where;}
	   </script>
	   <?php
	   include("includes/bbcode.php");
	   ?>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <?php include("includes/menu_accueil.php"); ?>
   <div id="centre" >
   <?php if(!isset($_GET['sId'])){
   $reponse = $bdd->prepare('SELECT forum FROM forums WHERE id=?');
   $reponse->execute(array($_GET['fId']));
   $donnees = $reponse->fetch();
   ?>
   <h2><?php echo htmlspecialchars($donnees['forum']) ?></h2>
   
   <p style="text-align:left; margin-left:10px" ><a href="archives_forum.php?co=o" >
   <img src="images/Templates/design_defaut/Boutons/retour.png" 
   onmouseover="this.src='images/Templates/design_defaut/Boutons/retour_mouseover.png';"
   onmouseout="this.src='images/Templates/design_defaut/Boutons/retour.png';" /></a></p>
			   
      <table id="table_forum" >
	  <caption>Sujets</caption>
   <tr>
      <th style="width:25%" >Dernière réponse</th>
      <th style="width:45% " >Titre</th>
	  <th>Créateur</th>
   </tr>
   <?php 
   $req = $bdd->prepare('SELECT pseudo, signature FROM joueurs WHERE id=? ');
   
   for($i=0; $i!=2; $i++){ //On fait l'opération deux fois. Une premiere fois pour placer les sujets importants en haut et la deuxième pour afficher le reste des sujets
   if($i==0){
      $reponse = $bdd->prepare('SELECT * FROM message_forum WHERE forum_id=? AND premier_message=\'1\' AND statut=\'Important\' AND archive=\'1\' ORDER BY date_dernier_message DESC ');
   }
   else if($i==1){
      $reponse = $bdd->prepare('SELECT * FROM message_forum WHERE forum_id=? AND premier_message=\'1\' AND statut!=\'Important\' AND archive=\'1\' ORDER BY date_dernier_message DESC ');
   }
   $reponse->execute(array($_GET['fId']));
   while($donnees_forum = $reponse->fetch()){
      $req->execute(array($donnees_forum['id_emmeteur']));
      $joueur = $req->fetch();
	  $req2 = $bdd->prepare('SELECT id_emmeteur FROM message_forum WHERE id_sujet=? ORDER BY id DESC');
	  $req2->execute(array($donnees_forum['id_sujet']));
	  $dernier_message = $req2->fetch();
	  $req2 = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
	  $req2->execute(array($dernier_message['id_emmeteur']));
	  $joueur_dernier_message = $req2->fetch();
	  echo '<tr>';
	  echo '<td>' . $joueur_dernier_message['pseudo'] . '<br />' . $donnees_forum['date_dernier_message'] . '</td>';
	  ?>
	  <td>
	     <a href="archives_forum_p.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>" ><?php echo htmlspecialchars($donnees_forum['sujet']); ?></a>
		 <?php
		 $req3 = $bdd->prepare('SELECT id FROM message_forum WHERE id_sujet=? ');
		 $req3->execute(array($donnees_forum['id_sujet']));
		 $nb_posts = 0;
		 while($nb = $req3->fetch()){
		    $nb_posts++;
		 }
		 $pages = $nb_posts / 15;
		 $pages = ceil($pages);
		 if($pages > 1){
		 $i2 = 0;
		 echo '<br />';
		 echo '<span class="infos_pages_forum_p">(Pages: ';
		 while($i2 < $pages){
			?>
			<a href="archives_forum_p.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>&amp;page=<?php echo $i2+1; ?>"><?php echo $i2+1; ?></a>
			<?php
			if($i2+1 != $pages){
			   echo ', ';
			}
			$i2++;
		 }
		 echo ')</span>';
		 }
		 ?>
	  </td>
	  <?php
      if($joueur['pseudo'] == NULL){echo '<td>Joueur supprimé</td></tr>';}
      else{echo '<td>'
      . htmlspecialchars($joueur['pseudo']) . '<br />Le ' . $donnees_forum['date'] . '</td></tr>';}
   }
   }
   ?></table><?php
   }
   else{
   //On détermine le bon sujet
   $reponse = $bdd->prepare('SELECT sujet, id, forum_id, id_sujet, statut FROM message_forum WHERE id_sujet=? AND premier_message=\'1\' AND archive=\'1\' ');
   $reponse->execute(array($_GET['sId']));
   $donnees = $reponse->fetch();
   //On détermine le bon forum
   $reponse_autre = $bdd->prepare('SELECT forum FROM forums WHERE id=?');
   $reponse_autre->execute(array($donnees['forum_id']));
   $donnees_autre = $reponse_autre->fetch();
   //On détermine combien de réponses dans le sujet
   $req = $bdd->prepare('SELECT id FROM message_forum WHERE id_sujet=? ');
   $req->execute(array($donnees['id_sujet']));
   $nb_reponses = 0;
   while($nb = $req->fetch()){
      $nb_reponses ++;
   }
   $nb_reponses /= 15;
   $nb_reponses = ceil($nb_reponses);
   ?>
   <h2><?php echo htmlspecialchars($donnees['sujet']); ?></h2>
   
   <p style="text-align:left; margin-left:10px" ><a <?php echo 'href="archives_forum_p.php?co=o&amp;fId=' . $donnees['forum_id'] . '"'?> >
   <img src="images/Templates/design_defaut/Boutons/retour.png" 
   onmouseover="this.src='images/Templates/design_defaut/Boutons/retour_mouseover.png';"
   onmouseout="this.src='images/Templates/design_defaut/Boutons/retour.png';" /></a></p>
   
   <?php
   if($nb_reponses > 1){
   ?>
   <select name="page" onchange="golinks(this.options[this.selectedIndex].value)" >
      <option value="" selected="selected" >Sauter vers la page...</option>
      <optgroup label="Pages" >
      <?php
	     for($i=1;$i!=($nb_reponses + 1);$i++){
		 ?>
		    <option name="<?php echo $i;?>" value="archives_forum_p.php?co=o&amp;sId=<?php echo $donnees['id_sujet'];?>&amp;page=<?php echo $i;?>" >
			   <?php echo $i;?>
			</option>
		 <?php
		 }
	  ?>
	  </optgroup>
   </select>
   <?php
   }
   ?>

   <table id="table_forum_p">
   <tr>
      <th>Réponses</th>
	  <th style="width:150px" >Par</th>
   </tr>
   <?php
   $numero = 0;
   $numero_max = 15;
   $numero_min = 0;
   $reponse = $bdd->prepare('SELECT * FROM message_forum WHERE id_sujet=? ORDER BY id ');
   if(isset($_GET['page'])){
      $numero_min = ($_GET['page'] * 15) - 15;
	  $numero_max = ($_GET['page'] * 15);
   }
   $reponse->execute(array($donnees['id_sujet']));
   $req = $bdd->prepare('SELECT pseudo, signature FROM joueurs WHERE id=? ');
   while($donnees_forum = $reponse->fetch() AND $numero != $numero_max){
      if($numero < $numero_min){
	     $numero++;
	  }
	  else{
      $req->execute(array($donnees_forum['id_emmeteur']));
      $joueur = $req->fetch();?>
      <?php
		 //Formater le texte avec le BBcode et htmlspecialchars
		 $donnees_forum['message'] = htmlspecialchars($donnees_forum['message']);
		 $donnees_forum['message'] = bbcode($donnees_forum['message']);
      ?>
   <tr>
      <td>
	     <p class="message_forum" >
		    <?php echo $donnees_forum['message'];?></p><p class="signature" ><?php echo htmlspecialchars($joueur['signature']); ?>
		 </p>
	  </td>
	  <td class="emmeteur_message_forum" ><?php
	     if($joueur['pseudo'] == NULL){echo 'Joueur supprimé</td>';}
         else{echo htmlspecialchars($joueur['pseudo']);}
		 echo '<br />' . $donnees_forum['date']; ?></td>
   </tr>
   <?php
   $numero++;
   }
   }
   ?>
   </table>
   <?php
   }
   $req->closeCursor();
   ?>
   </div>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>