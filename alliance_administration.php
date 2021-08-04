<?php include("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Administration de l'alliance</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <div id="centre">
      <?php
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
		    ?>
			<h2>Administration de l'alliance</h2>
			
			<table id="table_postulation" >
			   <caption>Voici la liste des seigneurs qui voudraient intégrer votre alliance</caption>
			   <tr>
			      <th style="width:22%" >Joueur</th>
				  <th style="width:60%" >Message laissé</th>
				  <th>Actions</th>
			   </tr>
			   
			   <?php 
			      $req = $bdd->prepare('SELECT * FROM alliances_postulations WHERE id_alliance=? ');
				  $req->execute(array($alliance['id']));
				  $postulation_trouve = false;
				  while($donnees_post = $req->fetch()){
				  $postulation_trouve = true;
				  $req2 = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
				  $req2->execute(array($donnees_post['id_joueur']));
				  $pseudo = $req2->fetch();
			   ?>
			   <tr>
			      <td><a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $pseudo['pseudo'];?>"><?php echo $pseudo['pseudo']; ?></a></td>
				  <td>
				     <div style="overflow:auto;width:400px;height:140px;white-space:pre-line" >
					    <?php echo $donnees_post['message']; ?>
					 </div>
				  </td>
				  <td>
				     <a href="options.php?co=o&amp;action=accepter_membre_alliance&amp;id=<?php echo $donnees_post['id'];?>" >Accepter</a><br /><br />
					 <a href="options.php?co=o&amp;action=refuser_membre_alliance&amp;id=<?php echo $donnees_post['id'];?>">Refuser</a>
				  </td>
			   </tr>
			   <?php
			   }
			   if(!$postulation_trouve)
			   {echo '<tr><td colspan="3" >Aucune postulation pour le moment</td></tr>';}
			   ?>
			</table>
			
			   <form method="post" action="options.php?co=o&amp;action=administration_alliance" >
			      <table id="table_alliances" >
				     <tr>
				        <th>Élément</th>
					    <th>Valeur</th>
				     </tr>
				  
				     <tr>
				        <td>Nom de l'alliance</td>
					    <td><input type="text" size="50" name="nom" disabled="disabled" value="<?php echo $alliance['nom'];?>" ></input></td>
				     </tr>
					 
					 <tr>
					    <td>Description RP de l'alliance</td>
						<td><textarea name="description" rows="7" cols="50" ><?php echo $alliance['description']; ?></textarea></td>
					 </tr>
					 
					 <tr>
					    <td>Message aux membres de l'alliance</td>
						<td><textarea name="message" rows="7" cols="50" ><?php echo $alliance['message']; ?></textarea></td>
					 </tr>
					 
					 <?php if($alliance['id_joueur_meneur'] == $_SESSION['id_joueur']){ ?>
					 <tr>
					    <td>Premier dirigeant<br />
						Changer ce seigneur vous fera perdre le statut de dirigeant</td>
						<td>
						   <select name="id_joueur_premier_meneur" >
						      <?php 
							  //On trouve tous les joueurs de l'alliance
							  $req = $bdd->prepare('SELECT pseudo, id FROM joueurs WHERE id_alliance=? ');
							  $req->execute(array($alliance['id']));
							  echo '<optgroup label="Joueurs" >';
							  while($joueur = $req->fetch()){
							     if($joueur['id'] == $alliance['id_joueur_meneur']){$selected = 'selected="selected"';}
								 else{$selected = '';}
							     echo '<option ' . $selected . 'value="' . $joueur['id'] . '" >' . $joueur['pseudo'] . '</option>';
							  }
							  ?>
							  echo '</optgroup>';
						   </select>
						</td>
					 </tr>
					 <?php
					 }
					 ?>
					 <tr>
					    <td>Porte parole</td>
						<td>
						   <select name="id_joueur_porte_parole" >
						      <?php 
							  //On trouve tous les joueurs de l'alliance
							  $req = $bdd->prepare('SELECT pseudo, id FROM joueurs WHERE id_alliance=? ');
							  $req->execute(array($alliance['id']));
							  echo '<option value="" > </option>';
							  echo '<optgroup label="Joueurs" >';
							  while($joueur = $req->fetch()){
							     if($joueur['id'] == $alliance['id_joueur_porte_parole']){$selected = 'selected="selected"';}
								 else{$selected = '';}
							     echo '<option ' . $selected . 'value="' . $joueur['id'] . '" >' . $joueur['pseudo'] . '</option>';
							  }
							  echo '</optgroup>';
							  ?>
						   </select>
						</td>
					 </tr>
					 <tr>
					    <td>Maitre de guerre</td>
						<td>
						   <select name="id_joueur_maitre_de_guerre" >
						      <?php 
							  //On trouve tous les joueurs de l'alliance
							  $req = $bdd->prepare('SELECT pseudo, id FROM joueurs WHERE id_alliance=? ');
							  $req->execute(array($alliance['id']));
							  echo '<option value="" > </option>';
							  echo '<optgroup label="Joueurs" >';
							  while($joueur = $req->fetch()){
							     if($joueur['id'] == $alliance['id_joueur_maitre_de_guerre']){$selected = 'selected="selected"';}
								 else{$selected = '';}
							     echo '<option ' . $selected . 'value="' . $joueur['id'] . '" >' . $joueur['pseudo'] . '</option>';
							  }
							  echo '</optgroup>';
							  ?>
						   </select>
						</td>
					 </tr>
					 <tr>
					    <td><?php echo $alliance['statut_autre1']; ?><br />
						Nom du statut personnalisé 1</td>
						<td>
						   <select name="statut_autre1" >
						      <?php 
							  //On trouve tous les joueurs de l'alliance
							  $req = $bdd->prepare('SELECT pseudo, id FROM joueurs WHERE id_alliance=? ');
							  $req->execute(array($alliance['id']));
							  echo '<option value="" > </option>';
							  echo '<optgroup label="Joueurs" >';
							  while($joueur = $req->fetch()){
							     if($joueur['id'] == $alliance['id_joueur_autre1']){$selected = 'selected="selected"';}
								 else{$selected = '';}
							     echo '<option ' . $selected . 'value="' . $joueur['id'] . '" >' . $joueur['pseudo'] . '</option>';
							  }
							  echo '</optgroup>';
							  ?>
						   </select>
						   <br /><input name="nom_statut_autre1" type="text" size="30" maxlength="40" value="<?php echo $alliance['statut_autre1']; ?>" ></input>
						</td>
					 </tr>
					 <tr>
					    <tr>
					    <td><?php echo $alliance['statut_autre2']; ?><br />
						Nom du statut personnalisé 2</td>
						<td>
						   <select name="statut_autre2" >
						      <?php 
							  //On trouve tous les joueurs de l'alliance
							  $req = $bdd->prepare('SELECT pseudo, id FROM joueurs WHERE id_alliance=? ');
							  $req->execute(array($alliance['id']));
							  echo '<option value="" > </option>';
							  echo '<optgroup label="Joueurs" >';
							  while($joueur = $req->fetch()){
							     if($joueur['id'] == $alliance['id_joueur_autre2']){$selected = 'selected="selected"';}
								 else{$selected = '';}
							     echo '<option ' . $selected . 'value="' . $joueur['id'] . '" >' . $joueur['pseudo'] . '</option>';
							  }
							  echo '</optgroup>';
							  ?>
						   </select>
						   <br /><input name="nom_statut_autre2" type="text" size="30" value="<?php echo $alliance['statut_autre2']; ?>" ></input>
						</td>
					 </tr>
					 
					 <tr>
					    <td colspan="2" ><input type="submit" value="Valider les changements" ></input></td>
					 </tr>
					 
					 <tr>
					    <td>Renvoyer un membre de l'alliance</td>
						<td>
						  <select name="renvoyer" >
						     <?php
							    $req = $bdd->prepare('SELECT pseudo, id FROM joueurs WHERE id_alliance=? ');
							    $req->execute(array($alliance['id']));
							    echo '<option value="" > </option>';
							    echo '<optgroup label="Joueurs" >';
							    while($joueur = $req->fetch()){
								   if($alliance['id_joueur_meneur'] == $joueur['id']){
								   }
								   else{
							          echo '<option ' . $selected . 'value="' . $joueur['id'] . '" >' . $joueur['pseudo'] . '</option>';
								   }
							    }
							 ?>
						  </select>
						</td>
					 </tr>
					 
					 <?php if($alliance['id_joueur_meneur'] == $_SESSION['id_joueur']){ ?>
					 <tr>
					    <td>Dissoudre l'alliance<br />
						Pour dissoudre votre alliance, veuillez entrer la phrase suivante dans l'espace prévu à cet effet:<br />
						«Je désire dissoudre mon alliance pour de bon»</td>
						<td><input type="text" name="dissoudre" size="30" ></input></td>
					 </tr>
					 <?php
					 }
					 ?>
			      </table>
			   </form>
			<?php
		 }
	  ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>