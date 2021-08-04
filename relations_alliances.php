<?php include("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Relations aux alliances</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
	   <script language="JavaScript" >
	   function golinks(where)
	   {self.location = where;}
	   </script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <div id="centre">
      <?php
	  $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  $joueur = $req->fetch();
	  $req = $bdd->prepare('SELECT id_joueur_meneur, id_joueur_maitre_de_guerre FROM alliances WHERE id=? ');
	  $req->execute(array($joueur['id_alliance']));
	  $alliance = $req->fetch();
	  ?>
      <h2>Relations avec les autres alliances</h2>
	  <?php
	  if($alliance['id_joueur_meneur'] != $_SESSION['id_joueur'] AND $alliance['id_joueur_maitre_de_guerre'] != $_SESSION['id_joueur']){
	     ?>
		 <p class="erreur">
		    Pour avoir accès à cette page, vous devez être le maître de guerre ou le meneur de votre alliance<br />
		 </p>
		 <?php
	  }
	  else{
	     if(isset($_GET['a'])){
		    switch($_GET['a']){
			   case 'demande_pacte':
			      ?>
				  <form action="relations_alliances.php?co=o&amp;a=demande_pacte_confirm&amp;id_alliance=<?php echo $_GET['id_alliance']; ?>" method="post">
				     <label>Type de pacte<br />
					    <select name="type_pacte">
						   <option value="" selected="selected">Choisissez...</option>
						   <optgroup label="Types">
						      <option value="1">1: Pacte Total (PT)</option>
							  <option value="2">2: Pacte Non-Agression (PNA)</option>
							  <option value="3">3: Pacte Commercial (PC)</option>
						   </optgroup>
						</select>
					 <br /></label>
					 <label>Laissez un message à l'alliance avec laquelle vous souhaitez faire un pacte<br />
					    <textarea name="message" rows="10" cols="50" ></textarea>
					 <br /><br /></label>
					 <input type="submit" name="Envoyer" value="Envoyer la demande de pacte"/>
				  </form>
				  <hr />
				  <?php
			   break;
			   case 'demande_pacte_confirm':
			      $req = $bdd->prepare('SELECT id FROM pactes_postulations WHERE id_alliance=? AND id_alliance_cible=? AND type=? ');
				  $req->execute(array($joueur['id_alliance'], $_GET['id_alliance'], $_POST['type_pacte']));
				  $verif = $req->fetch();
				  $req = $bdd->prepare('SELECT id FROM pactes WHERE id_alliance_1=? AND id_alliance_2=? AND type=? ');
				  $req->execute(array($joueur['id_alliance'], $_GET['id_alliance'], $_POST['type_pacte']));
				  $verif2 = $req->fetch();
				  $req = $bdd->prepare('SELECT id FROM pactes WHERE id_alliance_1=? AND id_alliance_2=? AND type=? ');
				  $req->execute(array($_GET['id_alliance'], $joueur['id_alliance'], $_POST['type_pacte']));
				  $verif3 = $req->fetch();
				  if($verif['id'] != NULL){
				     ?>
					 <p class="erreur">
					    Vous avez déjà fait une demande de pacte du genre avec cette alliance!<br />
					 </p>
					 <?php
				  }
				  else if($verif2['id'] != NULL){
				     ?>
					 <p class="erreur">
					    Vous avez déjà un pacte du genre avec cette alliance!<br />
					 </p>
					 <?php
				  }
				  else if($verif3['id'] != NULL){
				     ?>
					 <p class="erreur">
					    Vous avez déjà un pacte du genre avec cette alliance!<br />
					 </p>
					 <?php
				  }
			      else if($_POST['type_pacte'] == ""){
				     ?>
					 <p class="erreur">
					    Vous devez choisir un type de pacte!<br />
					 </p>
					 <?php
				  }
				  else{
				     $req = $bdd->prepare('INSERT INTO pactes_postulations (id_alliance, id_alliance_cible, message, type)
					 VALUES (:id_alliance, :id_alliance_cible, :message, :type)');
					 $req->execute(array(
					 'id_alliance' => $joueur['id_alliance'],
					 'id_alliance_cible' => $_GET['id_alliance'],
					 'message' => stripslashes(htmlspecialchars($_POST['message'])),
					 'type' => $_POST['type_pacte']));
					 ?>
					 <p class="succes">
					    Votre demande a bien été envoyée avec succès<br />
					 </p>
					 <?php
				  }
			   break;
			   case 'signer_demande_pacte':
			      $req = $bdd->prepare('SELECT * FROM pactes_postulations WHERE id=? ');
				  $req->execute(array($_GET['id']));
				  $postulation = $req->fetch();
				  $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $joueur = $req->fetch();
				  $req = $bdd->prepare('SELECT id_joueur_meneur, id_joueur_maitre_de_guerre, nom FROM alliances WHERE id=? ');
				  $req->execute(array($joueur['id_alliance']));
				  $alliance = $req->fetch();
				  $req = $bdd->prepare('SELECT id_joueur_meneur FROM alliances WHERE id=? ');
				  $req->execute(array($postulation['id_alliance']));
				  $autre_alliance = $req->fetch();
				  if($_SESSION['id_joueur'] != $alliance['id_joueur_meneur'] 
				  AND $_SESSION['id_joueur'] != $alliance['id_joueur_maitre_de_guerre']){
				     ?>
					 <p class="erreur">
					    Vous n'avez pas les droits pour signer une demande de pacte<br />
					 </p>
					 <?php
				  }
				  else if($postulation['id'] == NULL){
				     ?>
					 <p class="erreur">
					    Cette demande de pacte n'existe plus!<br />
					 </p>
					 <?php
				  }
				  else{
				     //On ajoute le pacte
				     $req = $bdd->prepare('INSERT INTO pactes (id_alliance_1, id_alliance_2, type)
					 VALUES (:id_alliance_1, :id_alliance_2, :type)');
					 $req->execute(array(
					 'id_alliance_1' => $postulation['id_alliance'],
					 'id_alliance_2' => $postulation['id_alliance_cible'],
					 'type' => $postulation['type']));
					 
					 //On envoie un message au meneur de l'autre alliance
					 $message = "Mes salutations,
					 
					 Ce présent message est pour vous annoncer que votre demande de pacte avec ". $alliance['nom'] ." a été signée!
					 Vous avez donc officiellement un pacte avec cette alliance.
					 
					 Que gloire et victoires prennent part du reste de votre journée!";
					 
					 $req = $bdd->prepare('INSERT INTO messagerie (titre, id_destinataire, destinateur, message, message_lu, date, type)
					 VALUES (\'Pacte\', :id_destinataire, \'Votre messager\', :message, \'0\', :date, \'0\' )');
					 $req->execute(array(
					 'id_destinataire' => $autre_alliance['id_joueur_meneur'],
					 'message' => $message,
					 'date' => date('Y-m-d H:i:s')));
					 
					 //On supprime la demande
					 $req = $bdd->prepare('DELETE FROM pactes_postulations WHERE id=? ');
					 $req->execute(array($_GET['id']));
					 
					 ?>
					 <p class="succes">
					    Félicitations!<br />
						Vous avez officiellement un pacte avec cette alliance!<br />
					 </p>
					 <hr />
					 <?php
				  }
			   break;
			   case 'refuser_demande_pacte':
			      $req = $bdd->prepare('SELECT * FROM pactes_postulations WHERE id=? ');
				  $req->execute(array($_GET['id']));
				  $postulation = $req->fetch();
				  $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $joueur = $req->fetch();
				  $req = $bdd->prepare('SELECT id_joueur_meneur, id_joueur_maitre_de_guerre, nom FROM alliances WHERE id=? ');
				  $req->execute(array($joueur['id_alliance']));
				  $alliance = $req->fetch();
				  $req = $bdd->prepare('SELECT id_joueur_meneur FROM alliances WHERE id=? ');
				  $req->execute(array($postulation['id_alliance']));
				  $autre_alliance = $req->fetch();
				  if($_SESSION['id_joueur'] != $alliance['id_joueur_meneur'] 
				  AND $_SESSION['id_joueur'] != $alliance['id_joueur_maitre_de_guerre']){
				     ?>
					 <p class="erreur">
					    Vous n'avez pas les droits pour refuser une demande de pacte<br />
					 </p>
					 <?php
				  }
				  else if($postulation['id'] == NULL){
				     ?>
					 <p class="erreur">
					    Cette demande de pacte n'existe plus!<br />
					 </p>
					 <?php
				  }
				  else if($postulation['id_alliance'] != $joueur['id_alliance'] AND $postulation['id_alliance_cible'] != $joueur['id_alliance']){
				     ?>
					 <p class="erreur">
					    Cette demande de pacte n'est en aucun lien avec votre alliance<br />
					 </p>
					 <?php
				  }
				  else{
					 //On envoie un message au meneur de l'autre alliance
					 $message = "Mes salutations,
					 
					 Ce présent message est pour vous annoncer que votre demande de pacte avec ". $alliance['nom'] ." n'a pas été signée...
					 Ce pacte n'a alors pas été mis sur pied.";
					 
					 $req = $bdd->prepare('INSERT INTO messagerie (titre, id_destinataire, destinateur, message, message_lu, date, type)
					 VALUES (\'Pacte\', :id_destinataire, \'Votre messager\', :message, \'0\', :date, \'0\' )');
					 $req->execute(array(
					 'id_destinataire' => $autre_alliance['id_joueur_meneur'],
					 'message' => $message,
					 'date' => date('Y-m-d H:i:s')));
					 
					 //On supprime la demande
					 $req = $bdd->prepare('DELETE FROM pactes_postulations WHERE id=? ');
					 $req->execute(array($_GET['id']));
					 ?>
					 <p class="succes">
					    Vous avez refusé cette demande de pacte<br />
						Un message a été envoyé au meneur de l'autre alliance pour lui annoncer la nouvelle<br />
					 </p>
					 <hr />
					 <?php
				  }
			   break;
			   case 'rompre':
			      $req = $bdd->prepare('SELECT * FROM pactes WHERE id=? ');
				  $req->execute(array($_GET['id']));
				  $pacte = $req->fetch();
				  $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $joueur = $req->fetch();
				  $req = $bdd->prepare('SELECT id_joueur_meneur, id_joueur_maitre_de_guerre, nom FROM alliances WHERE id=? ');
				  $req->execute(array($joueur['id_alliance']));
				  $alliance = $req->fetch();
				  
				  $id_autre_alliance;
				  if($pacte['id_alliance_1'] == $joueur['id_alliance']){
				     $id_autre_alliance = $pacte['id_alliance_2'];
				  }
				  else{
				     $id_autre_alliance = $pacte['id_alliance_1'];
				  }
				  
				  $req = $bdd->prepare('SELECT id_joueur_meneur FROM alliances WHERE id=? ');
				  $req->execute(array($id_autre_alliance));
				  $autre_alliance = $req->fetch();
				  if($_SESSION['id_joueur'] != $alliance['id_joueur_meneur'] 
				  AND $_SESSION['id_joueur'] != $alliance['id_joueur_maitre_de_guerre']){
				     ?>
					 <p class="erreur">
					    Vous n'avez pas les droits pour rompre un pacte<br />
					 </p>
					 <?php
				  }
				  else if($pacte['id_alliance_1'] != $joueur['id_alliance'] AND $pacte['id_alliance_2'] != $joueur['id_alliance']){
				     ?>
					 <p class="erreur">
					    Ce pacte n'est en aucun lien avec votre alliance<br />
					 </p>
					 <?php
				  }
				  else{
				     //Tout est OK: on enlève le pacte
					 $req = $bdd->prepare('DELETE FROM pactes WHERE id=? ');
					 $req->execute(array($_GET['id']));
					 
					 //On envoie un message au meneur de l'autre alliance
					 $message = "Mes salutations,
					 
					 Ce présent message est pour vous annoncer que votre pacte avec ". $alliance['nom'] ." a été rompu!
					 Ce pacte ne prend alors plus part de vos alliances.";
					 
					 $req = $bdd->prepare('INSERT INTO messagerie (titre, id_destinataire, destinateur, message, message_lu, date, type)
					 VALUES (\'Pacte rompu\', :id_destinataire, \'Votre messager\', :message, \'0\', :date, \'0\' )');
					 $req->execute(array(
					 'id_destinataire' => $autre_alliance['id_joueur_meneur'],
					 'message' => $message,
					 'date' => date('Y-m-d H:i:s')));
					 
					 ?>
					 <p class="succes">
					    Ce pacte a été rompu.<br />
						Un message a été envoyé au meneur de l'autre alliance pour lui annoncer la nouvelle<br />
					 </p>
					 <?php
				  }
			   break;
			}
		 }
		 ?>
		 <p>
		    Voici une liste des pactes actuels avec votre alliance<br />
		 </p>
			<table>
			   <tr>
			      <th>Alliance</th>
				  <th>Type de pacte</th>
				  <th>Actions</th>
			   </tr>
			   <?php
			   $demande_trouvee = false;
			   $req = $bdd->prepare('SELECT * FROM pactes WHERE id_alliance_1=? OR id_alliance_2=? ');
	           $req->execute(array($joueur['id_alliance'], $joueur['id_alliance']));
	           while($pactes = $req->fetch()){
			      $demande_trouvee = true;
			      ?>
				  <tr>
				        <?php
						$req2 = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
		                if($pactes['id_alliance_1'] != $joueur['id_alliance']){
		                   $req2->execute(array($pactes['id_alliance_1']));
		                }
		                else{
		                   $req2->execute(array($pactes['id_alliance_2']));
		                }
						$alliance = $req2->fetch();
						?>
				     <td>
					    <a href="fiche_alliance.php?co=o&amp;alliance=<?php echo $alliance['nom']; ?>"><?php echo $alliance['nom']; ?></a>
					 </td>
					 <td>
					    <?php
						switch($pactes['type']){
						   case 1:
						      echo '1: Pacte Total (PT)';
						   break;
						   case 2:
						      echo '2: Pacte Non-Agression (PNA)';
						   break;
						   case 3:
						      echo '3: Pacte Commercial (PC)';
						   break;
						   default:
						      echo 'Pacte';
						}
						?>
					 </td>
					 <td>
					    <select name="action" onchange="golinks(this.options[selectedIndex].value)">
						   <option selected="selected">Sélectionnez...</option>
						   <optgroup label="Actions">
						      <option value="relations_alliances.php?co=o&amp;a=rompre&amp;id=<?php echo $pactes['id']; ?>">Rompre le pacte</option>
						   </optgroup>
						</select>
					 </td>
				  </tr>
				  <?php
			   }
			   if(!$demande_trouvee){
			   ?>
			   <tr>
			      <td colspan="3">Aucun pacte!</td>
			   </tr>
			   <?php
			   }
			   ?>
			</table>
			<hr />
			<p>
			   voici une liste des demandes de pacte qui vous ont été envoyées<br />
			</p>
			<table>
			   <tr>
			      <th>Alliance</th>
				  <th>Message laissé</th>
				  <th>Actions</th>
			   </tr>
			   <?php
			   $demande_trouvee = false;
			   $req = $bdd->prepare('SELECT * FROM pactes_postulations WHERE id_alliance_cible=? ');
			   $req->execute(array($joueur['id_alliance']));
			   while($donnees = $req->fetch()){
			      $demande_trouvee = true;
				  $req2 = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
				  $req2->execute(array($donnees['id_alliance']));
				  $nom = $req2->fetch();
				  ?>
				  <tr>
				     <td>
					    <a href="fiche_alliance.php?co=o&amp;alliance=<?php echo $nom['nom']; ?>"><?php echo $nom['nom']; ?></a>
					 </td>
					 <td>
					    <div style="overflow:auto;width:400px;height:140px;white-space:pre-line" >
					       <?php echo $donnees['message']; ?>
					    </div>
					 </td>
					 <td>
					    <a href="relations_alliances.php?co=o&amp;a=signer_demande_pacte&amp;id=<?php echo $donnees['id']; ?>">Signer le pacte</a><br />
						<br />
						<a href="relations_alliances.php?co=o&amp;a=refuser_demande_pacte&amp;id=<?php echo $donnees['id']; ?>">Refuser le pacte</a>
					 </td>
				  </tr>
				  <?php
			   }
			   if(!$demande_trouvee){
			      ?>
				  <tr>
				     <td colspan="3">Aucune demande de pacte!</td>
				  </tr>
				  <?php
			   }
			   ?>
			</table>
		 <?php
	  }
	  ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>