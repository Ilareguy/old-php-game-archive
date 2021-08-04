<?php include("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Options</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
	  <?php include("includes/banniere.php"); ?>
   <div id="centre" >
   <?php 
   if(isset($_GET['changer'])){
   switch($_GET['changer']){
      case 'signature':
	     $req = $bdd->prepare("UPDATE joueurs SET signature=? WHERE id=? ");
		 $req->execute(array(stripslashes($_POST['signature']), $_SESSION['id_joueur']));
		 echo '<p class="succes" >Votre signature a �t� chang�e!</p>';
	  break;
	  case 'description_joueur':
	     $req = $bdd->prepare('UPDATE joueurs SET description_rp=? WHERE id=? ');
		 $req->execute(array($_POST['description'], $_SESSION['id_joueur']));
		 ?>
		 <p class="succes">
		    Votre desription a bien �t� chang�e !<br />
			Appuyez <a href="mon_clan.php?co=o">ici</a> pour revenir en arri�re<br />
		 </p>
		 <?php
	  break;
   }
   }
   else if(isset($_GET['action'])){
      switch($_GET['action']){
	  
	  case 'env_forum':
      if(empty($_POST['message_textarea'])){
      echo '<p style="color:red" >Vous devez entrer un message!</p>';
   }
   else{
   $reponse = $bdd->prepare('SELECT * FROM message_forum WHERE id_sujet=? ');
   $reponse->execute(array($_GET['sId']));
   $donnees = $reponse->fetch();
   $req = $bdd->prepare('INSERT INTO message_forum (forum_id ,sujet ,message ,id_emmeteur , premier_message, id_sujet, date)
   VALUES (:forum_id, :sujet, :message, :id_emmeteur, 0, :id_sujet, :date)');
    $req->execute(array(
	'forum_id' => $donnees['forum_id'],
	'sujet' => stripslashes($donnees['sujet']),
	'message' => stripslashes($_POST['message_textarea']),
	'id_emmeteur' => $_SESSION['id_joueur'],
	'id_sujet' => $donnees['id_sujet'],
	'date' => date('Y-m-d H:i:s')
	));
	$req = $bdd->prepare('UPDATE message_forum SET date_dernier_message=? WHERE id_sujet=? AND premier_message=\'1\' ');
	$req->execute(array(date('Y-m-d H:i:s'), $donnees['id_sujet']));
	
	//nb_messages_forum + 1
	$req = $bdd->prepare('SELECT `nb_messages_forum` FROM `joueurs` WHERE pseudo=? ');
	$req->execute(array($_SESSION['pseudo']));
	$nb_messages_forum = $req->fetch();
	$nb_messages_forum['nb_messages_forum'] += 1;
	$req = $bdd->prepare('UPDATE joueurs SET nb_messages_forum=? WHERE pseudo=? ');
	$req->execute(array($nb_messages_forum['nb_messages_forum'], $_SESSION['pseudo']));
   ?>
      <p class="succes" >
	     Votre message a bien �t� post�.<br />
		 <a href="<?php echo $_POST['url'];?>" >Revenir au forum</a>
	  </p>
   <?php
   }
   break;
   case 'env_forum_alliance':
      if(empty($_POST['message_textarea'])){
      echo '<p style="color:red" >Vous devez entrer un message!</p>';
   }
   else{
   $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $alliance = $req->fetch();
   $req = $bdd->prepare('SELECT id FROM alliances WHERE id=? ');
   $req->execute(array($alliance['id_alliance']));
   $alliance = $req->fetch();
   
   $reponse = $bdd->prepare('SELECT * FROM message_forum_alliance WHERE id_sujet=? ');
   $reponse->execute(array($_GET['sId']));
   $donnees = $reponse->fetch();
   $req = $bdd->prepare('INSERT INTO message_forum_alliance (id_alliance ,sujet ,message ,id_emmeteur , premier_message, id_sujet, date)
   VALUES (:id_alliance, :sujet, :message, :id_emmeteur, 0, :id_sujet, :date)');
    $req->execute(array(
	'id_alliance' => $alliance['id'],
	'sujet' => stripslashes($donnees['sujet']),
	'message' => stripslashes($_POST['message_textarea']),
	'id_emmeteur' => $_SESSION['id_joueur'],
	'id_sujet' => $donnees['id_sujet'],
	'date' => date('Y-m-d H:i:s')
	));
	$req = $bdd->prepare('UPDATE message_forum_alliance SET date_dernier_message=? WHERE id_sujet=? AND premier_message=\'1\' ');
	$req->execute(array(date('Y-m-d H:i:s'), $donnees['id_sujet']));
   ?>
      <p class="succes" >
	     Votre message a bien �t� post�.<br />
		 <a href="<?php echo $_POST['url'];?>" >Revenir au forum</a>
	  </p>
   <?php
   }
   break;
   
	     case 'creer_alliance': 
		 $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
		 if($joueur['id_alliance'] == 0){
		 ?>
		    <table id="table_creer_alliance" >
			   <tr>
			      <th style="width:63%" >Formulaire</th>
				  <th>Note</th>
			   </tr>
			   
			   <tr>
			      <td style="border:none" >
				  <div style="overflow:auto;max-height:400px;" >
				     <form method="post" action="options.php?co=o&amp;action=creer_alliance_confirmer" >
					    <fieldset>
						   <legend>Informations sur l'alliance</legend>
						   <label>Votre alliance doit avoir un nom <br />
						          Maximum 35 caract�res<br />
						      <input type="text" maxlength="35" name="nom" ></input><br /><br />
						   </label>
						   <label>La description RP de votre alliance<br />
						          Vous pourrez la remplir plus tard si vous le d�sirez<br />
						      <textarea name="description" rows="8" cols="50" ></textarea><br />
						   </label>
						   <input type="submit" name="valider" value="Cr�er mon alliance" ></input>
						</fieldset>
					 </form>
				  </div>
				  </td>
				  <td style="background-color:#9fa040" >
				  <div style="overflow:auto;max-height:400px;" >
				     <h4>Avant de cr�er l'alliance...</h4>
				     Pour �tre un bon dirigeant d'alliance, il faut avoir un but.<br />
					 Cette alliance pourra accueillir d'autres clans pour ainsi faire conna�tre votre puissance.<br />
					 Cr�er une alliance n'est pas une partie de plaisir. Il vous faudra la g�rer.<br />
					 Vous pourrez nommer d'autres membres qui pourront vous aider � la diriger.<br />
					 Si vous ne voulez plus �tre dirigeant de cette alliance, plus tard, vous pourrez nommer un autre membre comme dirigeant.<br />
				  </div>
				  </td>
			   </tr>
			</table>
	     <?php
		 }
		 else{
		    ?>
			<p class="erreur" >
			   Vous faites d�j� partie d'une alliance !<br />
			   Vous devez la quitter avant de vous en cr�er une<br />
			</p>
			<?php
		 }
		 $req->closeCursor();
		 break;
		 
		 case 'creer_alliance_confirmer':
		    $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($joueur['id_alliance'] != 0){
			   ?>
			   <p class="erreur">
			      Vous faites d�j� partie d'une alliance !<br />
				  Vous devez la quitter avant de vous en cr�er une<br />
			   </p>
			   <?php
			}
		    else if($_POST['nom'] == NULL){ ?>
			   <p class="erreur" >Vous devez absolument donner un nom � votre alliance.<br />
			   Par ce manque d'information, votre alliance n'a toujours pas �t� cr��e.<br />
			   <a href="JavaScript:window.history.go(-1)" >Revenez en arri�re pour compl�ter le champs qui manque.</a></p>
			   <?php
			}
			else{
			//On regarde si le nom de l'alliance existe d�j�
			$req = $bdd->prepare('SELECT nom FROM alliances WHERE nom=? ');
			$req->execute(array(stripslashes($_POST['nom'])));
			$nom = $req->fetch();
			if($nom['nom'] != NULL){echo '<p class="erreur" >Ce nom d\'alliance existe d�j�.<br />Veuillez en choisir un autre!</p>';}
			else{
			//On cr�e l'alliance
			   $req = $bdd->prepare('INSERT INTO alliances (nom, id_joueur_meneur, description, nb_joueurs) 
			   VALUES(:nom, :id_joueur_meneur, :description, \'1\')');
			   $req->execute(array(
			      'nom' => stripslashes($_POST['nom']),
				  'id_joueur_meneur' => $_SESSION['id_joueur'],
				  'description' => stripslashes($_POST['description'])
			   ));
			   //On trouve l'id de la nouvelle alliance
			   $req = $bdd->prepare('SELECT id FROM alliances WHERE nom=? ');
			   $req->execute(array(stripslashes($_POST['nom'])));
			   $alliance = $req->fetch();
			   
			   //On met le joueur dans l'alliance
			   $req = $bdd->prepare('UPDATE joueurs SET id_alliance=? WHERE id=? ');
			   $req->execute(array($alliance['id'], $_SESSION['id_joueur']));
			   
			   //On supprime les postulations dans les alliances
			   $req = $bdd->prepare('DELETE FROM alliances_postulations WHERE id_joueur=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   ?>
			   <p class="succes">
			      Vous avez cr�� votre alliance avec succ�s!<br />
			      L'alliance <?php echo stripslashes($_POST['nom']);?> appara�tra maintenant dans la liste des alliances et des joueurs pourront postuler pour vous joindre.<br />
			      <?php if($_POST['description'] == NULL){echo 'Puisque vous n\'avez toujours pas rempli la description RP de votre alliance, nous vous conseillons de le faire le plus t�t possible.<br />';}?>
				  <a href="alliances.php?co=o" >Vous pouvez commencer � g�rer votre alliance de ce pas!</a>
			      </p>
			      <?php
			   }
			}
		 break;
		 
		 case 'administration_alliance':
		    //On trouve les infos de l'alliance
		    $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$alliance = $req->fetch();
		    $req = $bdd->prepare('SELECT * FROM alliances WHERE id=? ');
			$req->execute(array($alliance['id_alliance']));
			$alliance = $req->fetch();
		    if($alliance['id_joueur_meneur'] != $_SESSION['id_joueur']){
			   header('Location: index.php');
			}
			else{
			   echo '<h2>Statut des changements de l\'alliance</h2>';
			   if($_POST['dissoudre'] == 'Je d�sire dissoudre mon alliance pour de bon'){
			      $req = $bdd->prepare('UPDATE joueurs SET id_alliance=\'0\' WHERE id_alliance=? ');
				  $req->execute(array($alliance['id']));
			      $req = $bdd->prepare('DELETE FROM alliances WHERE id=? ');
				  $req->execute(array($alliance['id']));
				  echo '<p>Votre alliance n\'est plus.<br />
				  Elle a correctement �t� supprim�e</p>';
			   }
			   else{
			   $req = $bdd->prepare('UPDATE alliances SET id_joueur_meneur=?,
			   id_joueur_porte_parole=?, id_joueur_maitre_de_guerre=?, id_joueur_autre1=?, id_joueur_autre2=?, 
			   statut_autre1=?, statut_autre2=?, description=?, message=? WHERE id=? ');
               $req->execute(array($_POST['id_joueur_premier_meneur'],
			   $_POST['id_joueur_porte_parole'], $_POST['id_joueur_maitre_de_guerre'], $_POST['statut_autre1'], $_POST['statut_autre2'],
			   stripslashes($_POST['nom_statut_autre1']), stripslashes($_POST['nom_statut_autre2']), 
			   stripslashes($_POST['description']), stripslashes($_POST['message']), $alliance['id']));
			   if($_POST['renvoyer'] != NULL){
			      //On renvoie le joueur de l'alliance
			      $req = $bdd->prepare('UPDATE joueurs SET id_alliance=\'0\' WHERE id=? ');
				  $req->execute(array($_POST['renvoyer']));
				  //On lui envoie un message
				  $message = 'Mes salutations,
				  
				  Ce pr�sent message est pour vous informer � propos de votre alliance.
				  J\'ai le regret de devoir vous annoncer que vous avez �t� expuls� de l\'alliance.
				  Pour avoir plus d\'informations, je vous propose de contacter le dirigeant de l\'alliance.
				  
				  Que gloire et victoires fassent partie du reste de votre journ�e!';
				  $req = $bdd->prepare('INSERT INTO messagerie (titre, message, destinateur, id_destinataire, message_lu, date) 
				  VALUES(:titre, :message, :destinateur, :id_destinataire, 0, :date)');
				  $req->execute(array(
				  'titre' => 'Alliance',
				  'message' => $message,
				  'destinateur' => 'Votre messager',
				  'id_destinataire' => $_POST['renvoyer'],
				  'date' => date('Y-m-d H:i:s')));
			   }
			   echo '<p class="succes" >Les changements ont �t� apport�s avec succ�s<br />';
			   if($alliance['id_joueur_meneur'] == $_SESSION['id_joueur']){
			      echo '<a href="JavaScript:window.history.go(-1)" >Revenir � l\'administration de l\'alliance</a>';
			   }
			   else{
			      echo '<a href="alliances.php?co=o" >Revenir � la page de l\'alliance</a></p>';
			   }
			}
			}
		 break;
		 
		 case 'postuler_alliance':
		    if(!isset($_POST['message'])){
		       $req = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
			   $req->execute(array($_GET['id']));
			   $nom = $req->fetch();
			   $req = $bdd->prepare('SELECT id FROM alliances_postulations WHERE id_joueur=? AND id_alliance=? ');
			   $req->execute(array($_SESSION['id_joueur'], $_GET['id']));
			   $verification = $req->fetch();
			   if($verification['id'] != NULL){
			      echo '<p class="erreur" >Vous avez d�j� postul� dans cette alliance!</p>';
			   }
			   else{ 
			      $req = $bdd->prepare('SELECT * FROM alliances WHERE id=? ');
			      $req->execute(array($_GET['id']));
			      $alliance = $req->fetch();
			   ?>
			      <form action="options.php?co=o&amp;action=postuler_alliance" method="post" >
				     <label>
					    <input type="hidden" name="id_alliance" value="<?php echo $alliance['id'];?>"></input>
					 </label>
			         <label>Laissez un message � l'alliance <?php echo $alliance['nom'];?> pour les convaincre de vous accepter<br />
				        <textarea name="message" rows="8" cols="70" ></textarea><br />
				     </label>
				     <label>
				        <input type="submit" value="Envoyer ma demande" name="Confirmer" ></input>
				     </label>
			      </form>
			   <?php
			   }
			}
			else{
			   $req = $bdd->prepare('INSERT INTO alliances_postulations (id_joueur, id_alliance, message) VALUES(:id_joueur, :id_alliance, :message)');
			   $req->execute(array(
			      'id_joueur' => $_SESSION['id_joueur'],
				  'id_alliance' => $_POST['id_alliance'],
				  'message' => stripslashes($_POST['message'])
			   ));
			   echo '<p class="succes" >Votre demande � bel et bien �t� envoy�e!<br />
			   Vous recevrez un message d�s que leur dirigeant vous acceptera ou vous refusera.</p>';
			}
		 break;
		 
		 case 'accepter_membre_alliance':
		    //On prend l'id de l'alliance
			$req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$id_alliance = $req->fetch();
		    //On regarde si le joueur est bien un dirigeant
			$req = $bdd->prepare('SELECT id_joueur_meneur, nom FROM alliances WHERE id=? ');//+ le nom de l'alliance
			$req->execute(array($id_alliance['id_alliance']));
			$meneurs = $req->fetch();
			if($meneurs['id_joueur_meneur'] == $_SESSION['id_joueur']){
			   //Si le joueur est bien un dirigeant
			   //On prend l'id du joueur qui a postul�
		       $req = $bdd->prepare('SELECT id_joueur FROM alliances_postulations WHERE id=? ');
			   $req->execute(array($_GET['id']));
			   $id_joueur = $req->fetch();
			   //On prend le pseudo du joueur qui a postul�
			   $req = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
			   $req->execute(array($id_joueur['id_joueur']));
			   $pseudo_joueur = $req->fetch();
			   //On regarde combien il y a de joueurs dans l'alliance
			   $req = $bdd->prepare('SELECT nb_joueurs FROM alliances WHERE id=? ');
			   $req->execute(array($id_alliance['id_alliance']));
			   $nb_joueurs = $req->fetch();
			   //On ajoute le joueur dans l'alliance si la limite de joueurs n'est pas atteinte
			   if($nb_joueurs['nb_joueurs'] < 30){ /***************************** 30 �tant la limite de joueurs dans une alliance ******************************************/
				  //On envoie un message au joueur accept�
				  $message = 'Mes salutations,
				  Ce pr�sent message est pour vous informer � propos d\'une demande de candidature
				  pour int�grer l\'alliance ' . $meneurs['nom'] . '.
				  
				  J\'ai l\'heureux plaisir de vous annoncer que votre clan et vous avez �t�s accept�s
				  dans cette alliance!
				  Vous faites maintenant partie de l\'�quipe!
				  
				  Que gloire et victoires fassent partie du reste de votre journ�e!';
				 $req = $bdd->prepare('INSERT INTO messagerie (titre, destinateur, id_destinataire, message, message_lu, date)
				 VALUES (:titre, :destinateur, :id_destinataire, :message, 0, :date)');
				 $req->execute(array(
				     'titre' => 'Alliance',
					 'destinateur' => 'Votre messager',
					 'id_destinataire' => $id_joueur['id_joueur'],
					 'message' => $message,
					 'date' => date('Y-m-d H:i:s')));
				  //On supprime toutes les requ�tes du joueur accept�
				  $req = $bdd->prepare('DELETE FROM alliances_postulations WHERE id_joueur=? ');
				  $req->execute(array($id_joueur['id_joueur']));
				  //On met le joueur dans l'alliance
				  $req = $bdd->prepare('UPDATE joueurs SET id_alliance=? WHERE id=? ');
				  $req->execute(array($id_alliance['id_alliance'], $id_joueur['id_joueur']));
				  //On modifie le nombre de joueur dans l'alliance
				  $nb_joueurs['nb_joueurs']++;
				  $req = $bdd->prepare('UPDATE alliances SET nb_joueurs=? WHERE id=? ');
				  $req->execute(array($nb_joueurs['nb_joueurs'], $id_alliance['id_alliance']));
				  
				  echo '<p class="succes" >' . htmlspecialchars($pseudo_joueur['pseudo']) . ' a bien �t� accept� dans votre alliance.<br />
				  Un message lui a �t� envoy� pour lui annoncer la nouvelle</p>';
			   }
			   else{
			      echo '<p class="erreur" >Votre alliance a d�j� atteinte la limite de joueurs.<br />
				  Pour accepter un autre membre, il vous faut d\'abord en renvoyer un</p>';
			   }
			}
			else{
			   header('Location: index.php');
			}
		 break;
		 
		 case 'refuser_membre_alliance':
		    //On prend l'id de l'alliance
			$req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$id_alliance = $req->fetch();
		    //On regarde si le joueur est bien un dirigeant
			$req = $bdd->prepare('SELECT id_joueur_meneur, nom FROM alliances WHERE id=? ');//+ le nom de l'alliance
			$req->execute(array($id_alliance['id_alliance']));
			$meneurs = $req->fetch();
			if($meneurs['id_joueur_meneur'] == $_SESSION['id_joueur']){
			   //On trouve l'id du joueur � refuser
			   $req = $bdd->prepare('SELECT id_joueur FROM alliances_postulations WHERE id=? ');
			   $req->execute(array($_GET['id']));
			   $id_joueur = $req->fetch();
			   //On lui envoie un message
			   $message = 'Mes salutations,
				  Ce pr�sent message est pour vous informer � propos d\'une demande de candidature
				  pour int�grer l\'alliance ' . $meneurs['nom'] . '.
				  
				  J\'ai le regret de vous annoncer que votre clan et vous avez �t�s refus�s
				  � entrer dans cette alliance...
				  Peu-�tre que la prochaine fois sera la bonne!
				  
				  Que gloire et victoires fassent partie du reste de votre journ�e!';
			   $req = $bdd->prepare('INSERT INTO messagerie (titre, message, destinateur, id_destinataire, message_lu, date)
			   VALUES (:titre, :message, :destinateur, :id_destinataire, 0, :date)');
			   $req->execute(array(
			      'titre' => 'Alliance',
				  'message' => $message,
				  'destinateur' => 'Votre messager',
				  'id_destinataire' => $id_joueur['id_joueur'],
				  'date' => date('Y-m-d H:i:s')));
				  
			   //On enl�ve sa demande de candidature
			   $req = $bdd->prepare('DELETE FROM alliances_postulations WHERE id=? ');
			   $req->execute(array($_GET['id']));
				  
			   echo '<p>Ce seigneur ne fera pas partie de votre alliance.<br />
			   Un message lui a �t� envoy� pour lui en informer</p>';
			}
			else{
			   header('Location: index.php');
			}
		 break;
		 
		 case 'quitter_alliance':
		    //On prend l'id de l'alliance
			$req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$id_alliance = $req->fetch();
		    //On regarde si le joueur est un dirigeant
			$req = $bdd->prepare('SELECT id_joueur_meneur, nom FROM alliances WHERE id=? ');//+ le nom de l'alliance
			$req->execute(array($id_alliance['id_alliance']));
			$meneurs = $req->fetch();
			if($meneurs['id_joueur_meneur'] == $_SESSION['id_joueur']){
			   header('Location: index.php');
			}
			else{
			   echo '<p>�tes-vous certain de vouloir quitter votre alliance?</p>';
			   echo '<p><a href="options.php?co=o&amp;action=quitter_alliance_confirm" >Oui, je veux quitter mon alliance</a></p>';
			   echo '<p><a href="alliances.php?co=o" >Non, je veux rester dans mon alliance</a></p>';
			}
		 break;
		 
		 case 'quitter_alliance_confirm':
		    //On prend l'id de l'alliance
			$req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$id_alliance = $req->fetch();
		    //On regarde si le joueur est un dirigeant
			$req = $bdd->prepare('SELECT id_joueur_meneur, nom, nb_joueurs FROM alliances WHERE id=? ');//+ le nom de l'alliance et le nombre de joueurs
			$req->execute(array($id_alliance['id_alliance']));
			$meneurs = $req->fetch();
			if($meneurs['id_joueur_meneur'] == $_SESSION['id_joueur']){
			   header('Location: index.php');
			}
			else{
			   $req = $bdd->prepare('UPDATE joueurs SET id_alliance=\'0\' WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $meneurs['nb_joueurs']--;
			   $req = $bdd->prepare('UPDATE alliances SET nb_joueurs=? WHERE id=? ');
			   $req->execute(array($meneurs['nb_joueurs'], $id_alliance['id_alliance']));
			   echo '<p>Vous ne faites maintenant plus partie de cette alliance</p>';
			}
		 break;
		 
		 case 'terminer_deplacement':
		    $req = $bdd->prepare('SELECT * FROM deplacements WHERE id=? ');
			$req->execute(array($_GET['id']));
			$deplacement = $req->fetch();
			$req = $bdd->prepare('SELECT activite_fin_timestamp FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($deplacement['id_joueur'] != $_SESSION['id_joueur']){
			   header('Location: index.php');
			}
			else if($joueur['activite_fin_timestamp'] > time()){
			   ?>
			   <p class="erreur" >
			      Vous ne pouvez pas vous installer car vous n'�tes toujours pas arriv�s � destination!<br />
			   </p>
			   <?php
			}
			else{
			   $req = $bdd->prepare('UPDATE joueurs SET activite=\'0\', pos_x=?, pos_y=? WHERE id=? ');
			   $req->execute(array($deplacement['x'], $deplacement['y'], $_SESSION['id_joueur']));
			   $req = $bdd->prepare('DELETE FROM deplacements WHERE id_joueur=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   ?>
			   <p class="succes" >
			      Votre clan s'est officiellement install� � la position <?php echo $deplacement['x'] . ', ' . $deplacement['y']; ?><br />
				  Vous pouvez maintenant faire des <a href="missions.php?co=o" >missions</a>, des <a href="carte.php?co=o" > combats</a> et m�me vous <a href="carte.php?co=o" >d�placer de nouveau</a><br />
			   </p>
			   <?php
			}
		 break;
		 case 'editer_message':
		    $req = $bdd->prepare('SELECT id_sujet, id_emmeteur, message FROM message_forum WHERE id=? ');
	        $req->execute(array($_GET['id']));
	        $emmeteur = $req->fetch();
			if($_SESSION['id_joueur'] == $emmeteur['id_emmeteur'] OR $_SESSION['statut_special'] == 'Webmaster'){
			   $req = $bdd->prepare('UPDATE message_forum SET message=? WHERE id=? ');
			   $req->execute(array(stripslashes($_POST['message_textarea']), $_GET['id']));
			   ?>
			   <p class="succes">
			      Le message a bien �t� �dit�.<br />
				  Appuyez <a href="forum_p.php?co=o&amp;sId=<?php echo $emmeteur['id_sujet']; ?>">ici</a> pour revenir vers le forum<br />
			   </p>
			   <?php
			}
			else{
	           //On ne peut l'�diter
		       ?>
		       <p class="erreur" id="editer">
		          Vous ne pouvez pas �diter ce message puisqu'il n'est pas � vous et que vous n'�tes pas mod�rateur<br />
		       </p>
		       <?php
	        }
		 break;
		 case 'editer_message_alliance':
		    $req = $bdd->prepare('SELECT id_sujet, id_emmeteur, message FROM message_forum_alliance WHERE id=? ');
	        $req->execute(array($_GET['id']));
	        $emmeteur = $req->fetch();
			if($_SESSION['id_joueur'] == $emmeteur['id_emmeteur'] OR $_SESSION['id_joueur'] == $alliance['id_joueur_meneur']){
			   $req = $bdd->prepare('UPDATE message_forum_alliance SET message=? WHERE id=? ');
			   $req->execute(array(stripslashes($_POST['message_textarea']), $_GET['id']));
			   ?>
			   <p class="succes">
			      Le message a bien �t� �dit�.<br />
				  Appuyez <a href="forum_alliance.php?co=o&amp;sId=<?php echo $emmeteur['id_sujet']; ?>">ici</a> pour revenir vers le forum<br />
			   </p>
			   <?php
			}
			else{
	           //On ne peut l'�diter
		       ?>
		       <p class="erreur" id="editer">
		          Vous ne pouvez pas �diter ce message puisqu'il n'est pas � vous et que vous n'�tes pas mod�rateur<br />
		       </p>
		       <?php
	        }
		 break;
	  }
	  }?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>
<?php
$req->closeCursor();
$reponse->closeCursor();
?>
