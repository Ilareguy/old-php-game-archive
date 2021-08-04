<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Forum d'alliance</title>
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
   <?php
   $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $alliance = $req->fetch();
   if($alliance['id_alliance'] != 0){
   $req = $bdd->prepare('SELECT id, id_joueur_meneur, id_joueur_maitre_de_guerre, id_joueur_porte_parole FROM alliances WHERE id=? ');
   $req->execute(array($alliance['id_alliance']));
   $alliance = $req->fetch();
   ?>
   <div id="centre" >
   <?php 
   if(!isset($_GET['sId'])){
   ?>
   <h2>Forum de l'alliance</h2>
   <p style="text-align:left; margin-left:10px" ><a <?php echo 'href="forum_alliance.php?co=o&amp;a=cre#creer"'; ?>>
               <img src="images/Templates/design_defaut/Boutons/nouveau_sujet.png" 
               onmouseover="this.src='images/Templates/design_defaut/Boutons/nouveau_sujet_mouseover.png';" 
               onmouseout="this.src='images/Templates/design_defaut/Boutons/nouveau_sujet.png';" /></a></p>
			   
      <table id="table_forum" >
	  <caption>Sujets</caption>
   <tr>
      <th style="width:25%" >Dernière réponse</th>
	  <th>Statut</th>
      <th style="width:45% " >Titre</th>
	  <th>Créateur</th>
   </tr>
   <?php 
   $req = $bdd->prepare('SELECT pseudo, signature FROM joueurs WHERE id=? ');
   
   for($i=0; $i!=2; $i++){ //On fait l'opération deux fois. Une premiere fois pour placer les sujets importants en haut et la deuxième pour afficher le reste des sujets
   if($i==0){
      $reponse = $bdd->prepare('SELECT * FROM message_forum_alliance WHERE id_alliance=? AND premier_message=\'1\' AND statut=\'Important\' ORDER BY date_dernier_message DESC ');
   }
   else if($i==1){
      $reponse = $bdd->prepare('SELECT * FROM message_forum_alliance WHERE id_alliance=? AND premier_message=\'1\' AND statut!=\'Important\' ORDER BY date_dernier_message DESC ');
   }
   $reponse->execute(array($alliance['id']));
   while($donnees_forum = $reponse->fetch()){
      $req->execute(array($donnees_forum['id_emmeteur']));
      $joueur = $req->fetch();
	  $req2 = $bdd->prepare('SELECT id_emmeteur FROM message_forum_alliance WHERE id_sujet=? ORDER BY id DESC');
	  $req2->execute(array($donnees_forum['id_sujet']));
	  $dernier_message = $req2->fetch();
	  $req2 = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
	  $req2->execute(array($dernier_message['id_emmeteur']));
	  $joueur_dernier_message = $req2->fetch();
	  echo '<tr>';
	  echo '<td><a href="fiche_joueur.php?co=o&amp;pseudo=' . $joueur_dernier_message['pseudo'] . '">' . $joueur_dernier_message['pseudo'] . '</a><br />' . $donnees_forum['date_dernier_message'] . '</td>';
	  switch($donnees_forum['statut']){
	     case 'Bloqué':
		    ?>
			<td style="background-color:brown" >
			   Bloqué
			</td>
			<td>
			   <a href="forum_alliance.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>" ><?php echo htmlspecialchars($donnees_forum['sujet']); ?></a>
			</td>
			<?php
		 break;
		 
		 case 'Important':
		    ?>
			<td style="background-color:white" >
			   Important
			</td>
			<td>
			   <a href="forum_alliance.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>" ><?php echo htmlspecialchars($donnees_forum['sujet']); ?></a>
			</td>
			<?php
		 break;
		 
		 case 'Refusé':
		    ?>
			<td style="background-color:#ad0000" >
			   Refusé
			</td>
			<td>
			   <a href="forum_alliance.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>" ><?php echo htmlspecialchars($donnees_forum['sujet']); ?></a>
			</td>
			<?php
		 break;
		 
		 case 'Accepté':
		    ?>
			<td style="background-color:green" >
			   Accepté
			</td>
			<td>
			   <a href="forum_alliance.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>" ><?php echo htmlspecialchars($donnees_forum['sujet']); ?></a>
			</td>
			<?php
		 break;
		 
	     default:
		    ?>
			<td>
			   Normal
			</td>
			<td>
			   <a href="forum_alliance.php?co=o&amp;sId=<?php echo $donnees_forum['id_sujet']; ?>" ><?php echo htmlspecialchars($donnees_forum['sujet']); ?></a>
			</td>
			<?php
	  }
      if($joueur['pseudo'] == NULL){echo '<td>Joueur supprimé</td></tr>';}
      else{echo '<td>' . '<a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($joueur['pseudo']) . '" >'
      . htmlspecialchars($joueur['pseudo']) . '</a><br />Le ' . $donnees_forum['date'] . '</td></tr>';}
   }
   }
   ?></table><?php
   }
   else{
   //On détermine le bon sujet
   $reponse = $bdd->prepare('SELECT sujet, id, id_sujet, statut FROM message_forum_alliance WHERE id_sujet=? AND premier_message=\'1\' ');
   $reponse->execute(array($_GET['sId']));
   $donnees = $reponse->fetch();
   //On détermine combien de réponses dans le sujet
   $req = $bdd->prepare('SELECT id FROM message_forum_alliance WHERE id_sujet=? ');
   $req->execute(array($donnees['id_sujet']));
   $nb_reponses = 0;
   while($nb = $req->fetch()){
      $nb_reponses ++;
   }
   $nb_reponses /= 15;
   $nb_reponses = ceil($nb_reponses);
   ?>
   <h2><?php echo htmlspecialchars($donnees['sujet']); ?></h2>
   <?php
   if($nb_reponses > 1){
   ?>
   <select name="page" onchange="golinks(this.options[this.selectedIndex].value)" >
      <option value="" selected="selected" >Sauter vers la page...</option>
      <optgroup label="Pages" >
      <?php
	     for($i=1;$i!=($nb_reponses + 1);$i++){
		 ?>
		    <option name="<?php echo $i;?>" value="forum_alliance.php?co=o&amp;sId=<?php echo $donnees['id_sujet'];?>&amp;page=<?php echo $i;?>" >
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
   
   <p style="text-align:left; margin-left:10px" ><a <?php echo 'href="forum_alliance.php?co=o"'?> >
   <img src="images/Templates/design_defaut/Boutons/retour.png" 
   onmouseover="this.src='images/Templates/design_defaut/Boutons/retour_mouseover.png';"
   onmouseout="this.src='images/Templates/design_defaut/Boutons/retour.png';" /></a></p>
   
   <?php if($donnees['statut'] != 'Bloqué'){ ?>
   <p style="text-align:left; margin-left:10px" ><a <?php echo 'href="forum_alliance.php?co=o&amp;sId=' . $donnees['id_sujet'] . '&amp;a=rep#repondre"'?> >
   <img src="images/Templates/design_defaut/Boutons/repondre_sujet.png" 
   onmouseover="this.src='images/Templates/design_defaut/Boutons/repondre_sujet_mouseover.png';"
   onmouseout="this.src='images/Templates/design_defaut/Boutons/repondre_sujet.png';" /></a></p>
   <?php } ?>

   <table id="table_forum_p" >
   <?php if($_SESSION['id_joueur'] == $alliance['id_joueur_meneur']){?>
      <tr>
	     <td>Changer le statut: </td>
		 <td>
		    <select onchange="golinks(this.options[this.selectedIndex].value)" >
			   <option selected="selected" ></option>
			   <optgroup label="Statuts de message" >
			      <option value="<?php echo 'forum_alliance.php?co=o&amp;a=modif&amp;sId=' . $donnees['id_sujet'] . '&amp;statut=0'?>" >Normal</option>
			      <option value="<?php echo 'forum_alliance.php?co=o&amp;a=modif&amp;sId=' . $donnees['id_sujet'] . '&amp;statut=1'?>" >[Important]</option>
			      <option value="<?php echo 'forum_alliance.php?co=o&amp;a=modif&amp;sId=' . $donnees['id_sujet'] . '&amp;statut=2'?>" >[Bloqué]</option>
			      <option value="<?php echo 'forum_alliance.php?co=o&amp;a=modif&amp;sId=' . $donnees['id_sujet'] . '&amp;statut=3'?>" >[Accepté]</option>
			      <option value="<?php echo 'forum_alliance.php?co=o&amp;a=modif&amp;sId=' . $donnees['id_sujet'] . '&amp;statut=4'?>" >[Refusé]</option>
			   </optgroup>
			</select>
		 </td>
	  </tr>
   <?php 
   }
   ?>
   <tr>
      <th>Réponses</th>
	  <th style="width:150px" >Par</th>
   </tr>
   <?php
   $numero = 0;
   $numero_max = 15;
   $numero_min = 0;
   $reponse = $bdd->prepare('SELECT * FROM message_forum_alliance WHERE id_sujet=? ORDER BY id ');
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
	     <?php
		 if($donnees_forum['id_emmeteur'] == $_SESSION['id_joueur']){
		 ?>
	     <a href="forum_alliance.php?co=o&amp;a=editer_message&amp;
		 sId=<?php echo $_GET['sId']?>&amp;id=<?php echo $donnees_forum['id']; ?>#editer">Éditer mon message</a>
		 <?php
		 }
		 ?>
	     <p class="message_forum" >
		    <?php echo $donnees_forum['message'];?></p><p class="signature" ><?php echo htmlspecialchars($joueur['signature']); ?>
		 </p>
	  </td>
	  <td class="emmeteur_message_forum" ><?php
	     if($joueur['pseudo'] == NULL){echo 'Joueur supprimé</td>';}
         else{echo '<a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($joueur['pseudo']) . '" >'
         . $joueur['pseudo'] . '</a>';}
		 echo '<br />' . $donnees_forum['date']; ?></td>
   </tr>
   <?php
   $numero++;
   }
   }
   ?>
   </table>
   <?php if($donnees['statut'] != 'Bloqué'){ ?>
   <p style="text-align:left; margin-left:10px" ><a <?php echo 'href="forum_alliance.php?co=o&amp;sId=' . $donnees['id_sujet'] . '&amp;a=rep#repondre"'?> >
   <img src="images/Templates/design_defaut/Boutons/repondre_sujet.png" 
   onmouseover="this.src='images/Templates/design_defaut/Boutons/repondre_sujet_mouseover.png';"
   onmouseout="this.src='images/Templates/design_defaut/Boutons/repondre_sujet.png';" /></a></p>
   <?php }
   }
   $req->closeCursor();
   }
   else{
      ?>
	  <div id="centre">
	  <p class="erreur">
	     Vous n'avez pas accès au forum d'alliance puisque vous ne faites partie d'aucune alliance<br />
	  </p>
	  </div>
	  <?php
   }
     //On regarde s'il y a action à faire
   if(isset($_GET['a'])){
   switch($_GET['a']){
   case 'rep':
   if($donnees['statut'] == 'Bloqué'){
      ?>
	  <p class="erreur">
	     Ce sujet est bloqué.<br />
		 Vous ne pouvez pas y répondre<br />
	  </p>
	  <?php
   }
   ?>
   <p id="repondre">
   </p>
   <hr />
   <table class="table_invisible">
   <form name="formulaire_message" action="<?php echo 'options.php?co=o&amp;sId=' . $donnees['id_sujet'] . '&amp;action=env_forum_alliance';?>" method="post">
   <input type="hidden" name="url" value="<?php echo 'forum_alliance.php?co=o&amp;sId=' . $donnees['id_sujet'];?>" />
   
      <tr>
	     <td>
            <label>Inscrivez votre message<br />
            <textarea name="message_textarea" rows="10" cols="50" ></textarea>
            <br /><br /></label>
			<input type="submit" name="boutonEnvoyer" value="Envoyer le message" style="margin-bottom: 40px;" ></input>
	     </td>
		 <td style="width:30%;background-color:#9fa040">
		    <?php include("includes/formatage_texte.php"); ?>
		 </td>
	  <tr>
   </form>
   </table>
   <?php
   break;
   
   case 'cre':
   ?>
   <p id="creer">
   </p>
   <hr />
   <table class="table_invisible">
   <form name="formulaire_message" action="<?php echo 'forum_alliance.php?co=o&amp;a=cre_env';?>#statut_envoie" method="post" >
         <label>
         <?php if($_SESSION['id_joueur'] == $alliance['id_joueur_meneur']){
		 ?>
		 <tr>
            <td colspan="2">
               <label>Statut<br />
               <select name="statut" >
	              <option value="Normal" >Normal</option>
	              <option value="Important" >[Important]</option>
		          <option value="Bloqué" >[Bloqué]</option>
	           </select><br /><br />
               </label>
	        </td>
         </tr>
         <?php
         }
         else{
            ?> <input type="hidden" name="statut" value="Normal" ></input> <?php
            }
         ?>
   <tr>
      <td>
         </label>Donnez un titre à votre sujet<br />
         <input type="text" name="titre" maxlength="40" ><br /></input><br />
	  </td>
	  <td style="width:30%;background-color:#9fa040" rowspan="2" >
	     <?php include("includes/formatage_texte.php"); ?>
	  </td>
   </tr>
   <tr>
      <td>
         <label>Inscrivez votre message<br />Ce message sera le tout premier du sujet
         <textarea name="message_textarea" rows="10" cols="50" ></textarea>
         <br /><br /></label>
	  </td>
   </tr>
   <tr>
      <td>
         <label>
         <input type="submit" name="boutonEnvoyer" value="Envoyer le message" style="margin-bottom: 40px;" ></input>
         </label>
	  </td>
   </tr>
   </form>
   </table>
   <?php
   break;
   
   case 'cre_env':
   if(!empty($_POST['message_textarea']) AND !empty($_POST['titre'])){
   $req = $bdd->query('SELECT id_sujet FROM message_forum_alliance ORDER BY id_sujet DESC');
   $donnees = $req->fetch();//On détermine le plus grand id_sujet
   $req = $bdd->prepare('INSERT INTO message_forum_alliance (id_alliance ,sujet ,message ,id_emmeteur ,premier_message, statut, id_sujet, date, date_dernier_message)
   VALUES (:id_alliance, :sujet, :message, :id_emmeteur, 1, :statut, :id_sujet, :date, :date_dernier_message)');
   $req->execute(array(
	'id_alliance' => $alliance['id'],
	'sujet' => stripslashes($_POST['titre']),
	'message' => stripslashes($_POST['message_textarea']),
	'id_emmeteur' => $_SESSION['id_joueur'],
	'statut' => $_POST['statut'],
	'id_sujet' => ($donnees['id_sujet'] += 1),
	'date' => date('Y-m-d H:i:s'),
	'date_dernier_message' => date('Y-m-d H:i:s')
	));
	?>
	<hr />
	<p class="succes" id="statut_envoie">
	   Le sujet a bien été posté !<br />
	   Appuyez <a href="forum_alliance.php?co=o&fId=<?php echo $_GET['fId']; ?>">ici</a> pour actualiser le forum<br />
	</p>
	<?php
   }
   else{
      ?>
	  <hr />
	  <p class="erreur" id="statut_envoie">
	     Votre post n'a pas été créé car il manque des informations au formulaire.<br />
		 Appuyez <a href="JavaScript:window.history.go(-1)">ici</a> pour revenir au formulaire<br />
	  </p>
	  <?php
   }
   break;
   
   case 'modif':
   switch($_GET['statut']){
      case '0':
	     $statut = 'Normal';
	  break;
	  
	  case '1':
	     $statut = 'Important';
	  break;
	  
	  case '2':
	     $statut = 'Bloqué';
	  break;
	  
	  case '3':
	     $statut = 'Accepté';
	  break;
	  
	  case '4':
	     $statut = 'Refusé';
	  break;
	  
	  default:
	     $statut = 'Normal';
   }
      $req = $bdd->prepare('UPDATE message_forum_alliance SET statut=? WHERE id_sujet=? AND premier_message=\'1\' ');
	  $req->execute(array($statut, $_GET['sId']));
	  ?>
	  <p class="succes">
	     Le statut a été changé avec succès.<br />
		 Appuyez <a href="forum_alliance.php?co=o&amp;sId=<?php echo $_GET['sId']; ?>">ici</a> pour actualiser la page<br />
	  </p>
	  <?php
   break;
   case 'editer_message':
      $req = $bdd->prepare('SELECT id_emmeteur, message FROM message_forum_alliance WHERE id=? ');
	  $req->execute(array($_GET['id']));
	  $emmeteur = $req->fetch();
	  if($_SESSION['id_joueur'] == $emmeteur['id_emmeteur'] OR $_SESSION['statut_special'] == 'Webmaster'){
	     //On peut l'éditer
		 ?>
		 <p id="editer">
		 </p>
		 <hr />
		 <table class="table_invisible">
            <form name="formulaire_message" action="<?php echo 'options.php?co=o&amp;action=editer_message_alliance&amp;id=' . $_GET['id'];?>" method="post" >
               <tr>
                  <td>
                     <label>Inscrivez votre message<br />
                        <textarea name="message_textarea" rows="10" cols="50"><?php echo $emmeteur['message']; ?></textarea>
                     <br /><br /></label>
	              </td>
				  <td style="width:30%;background-color:#9fa040" rowspan="2" >
	                 <?php include("includes/formatage_texte.php"); ?>
	              </td>
               </tr>
               <tr>
                  <td>
                        <input type="submit" name="boutonEnvoyer" value="Éditer le message" style="margin-bottom: 40px;" ></input>
	              </td>
               </tr>
            </form>
         </table>
		 <?php
	  }
	  else{
	     //On ne peut l'éditer
		 ?>
		 <hr />
		 <p class="erreur" id="editer">
		    Vous ne pouvez pas éditer ce message puisqu'il n'est pas à vous et que vous n'êtes pas modérateur<br />
		 </p>
		 <?php
	  }
   break;
   }
   }
   ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>