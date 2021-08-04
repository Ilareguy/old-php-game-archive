<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Missions</title>
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
      <h2>Missions</h2>
	  <h5>Obtenez plus de missions en montant de niveau</h5>
	  <p>
	     Accomplissez des missions pour obtenir de l'or, des lots aléatoires et de l'expérience.<br />
	  </p>
	  <?php
	     if(isset($_GET['faire'])){
		    $req = $bdd->prepare('SELECT niveau_requis, temps FROM missions WHERE id=? ');
			$req->execute(array($_GET['faire']));
			$donnees = $req->fetch();
			$req = $bdd->prepare('SELECT activite, niveau FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($joueur['niveau'] < $donnees['niveau_requis']){
			   ?>
			   <p class="erreur" >
			      Vous n'avez pas le niveau requis pour commencer cette mission!<br />
			   </p>
			   <?php
			}
			else if($joueur['activite'] == 2){
			   ?>
			   <p class="erreur" >
			      Votre clan est en train de se déplacer.<br />
				  Revenez quand vous ne serez plus en train de vous déplacer!<br />
			   </p>
			   <?php
			}
			else if($joueur['activite'] == 1){
			   ?>
			   <p class="erreur" >
			      Vous êtes déjà en train de faire une mission!<br />
			   </p>
			   <?php
			}
			else{
			   //On actualise l'activité du joueur
			$req = $bdd->prepare('SELECT id FROM attaques WHERE id_joueur=? AND combat_termine=\'0\' ');
			$req->execute(array($_SESSION['id_joueur']));
			$combat = $req->fetch();
			$req = $bdd->prepare('SELECT id FROM espionnages WHERE id_joueur=? AND espionnage_termine=\'0\' ');
			$req->execute(array($_SESSION['id_joueur']));
			$espionnage = $req->fetch();
			$req = $bdd->prepare('SELECT activite FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($combat['id'] != NULL OR $espionnage['id'] != NULL){
			   if($joueur['activite'] == -13){
			   }
			   else{
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'-13\' WHERE id=? ');
			      $req->execute(array($_SESSION['id_joueur']));
			   }
			}
			else{
			   $req = $bdd->prepare('UPDATE joueurs SET activite=\'1\' WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			}
			   $req = $bdd->prepare('UPDATE joueurs SET activite_fin_timestamp=? WHERE id=? ');
			   $req->execute(array((time() + $donnees['temps']), $_SESSION['id_joueur']));
			   $req = $bdd->prepare('INSERT INTO missions_en_cours (id_joueur, id_mission)
			   VALUES (:id_joueur, :id_mission) ');
			   $req->execute(array(
			   'id_joueur' => $_SESSION['id_joueur'],
			   'id_mission' => $_GET['faire']));
			   
			   $temps = $donnees['temps'] / 60;
			   ?>
			   <p class="succes" >
			      Votre clan et vous commencez cette mission.<br />
				  Elle sera terminée dans <?php echo $temps; ?> minutes
			   </p>
			   <?php
			}
		 }
		 if(isset($_GET['terminer'])){
		    $req = $bdd->prepare('SELECT id_mission FROM missions_en_cours WHERE id_joueur=? AND id_mission=? ');
			$req->execute(array($_SESSION['id_joueur'], $_GET['terminer']));
			$donnees = $req->fetch();
			$req = $bdd->prepare('SELECT activite_fin_timestamp FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($donnees['id_mission'] == NULL){
			   ?>
			      <p class="erreur" >
				     Vous n'étiez pas en train de faire cette mission!<br />
				  </p>
			   <?php
			}
			else if(time() < $joueur['activite_fin_timestamp']){
			   ?>
			   <p class="erreur" >
			      Vous n'avez pas encore terminé cette mission!<br />
				  Attendez de l'avoir terminée et revenez<br />
			   </p>
			   <?php
			}
			else{
			   $req = $bdd->prepare('SELECT argent_gagne, xp_gagne FROM missions WHERE id=? ');
			   $req->execute(array($donnees['id_mission']));
			   $mission = $req->fetch();
			   $req = $bdd->prepare('SELECT missions_faites, argent, experience, places_inventaire FROM joueurs WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $joueur = $req->fetch();
			   //Ajoute l'argent et les XPs
			   $req = $bdd->prepare('UPDATE joueurs SET argent=?, experience=? WHERE id=? ');
			   $req->execute(array(($mission['argent_gagne'] + $joueur['argent']),
			   ($mission['xp_gagne'] + $joueur['experience']),
			   $_SESSION['id_joueur']));
			   
			   //Objet aléatoire SI l'inventaire n'est pas plein
			   $req = $bdd->prepare('SELECT id FROM inventaire WHERE id_joueur=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $objets_inventaire = 0;
			   $inventaire_plein = false;
			   while($inv = $req->fetch()){
			      $objets_inventaire++;
			   }
			   if($objets_inventaire < $joueur['places_inventaire']){
			   $rec = array();
			   $chances = array();
			   $nb_rec = 0;
			   $req = $bdd->prepare('SELECT chances, id_objet FROM missions_lots WHERE id_mission=? ');
			   $req->execute(array($_GET['terminer']));
			   while($recompense = $req->fetch()){
			      for($i=1;$i!=($recompense['chances'] + 1);$i++){
			      $nb_rec++;
				  $rec[$nb_rec] = $recompense['id_objet'];
				  }
			   }
			   $chiffre = rand(1, $nb_rec);
			   $req = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
			   $req->execute(array($rec[$chiffre]));
			   $objet = $req->fetch();
			   
			   //On ajoute l'objet dans l'inventaire
			   $req = $bdd->prepare('INSERT INTO inventaire (id_joueur, id_objet)
			   VALUES (:id_joueur, :id_objet) ');
			   $req->execute(array(
			   'id_joueur' => $_SESSION['id_joueur'],
			   'id_objet' => $rec[$chiffre]));
			   }
			   else{
			      $inventaire_plein = true;
			   }
			   
			   //On ajoute une mission faite
			   $req = $bdd->prepare('UPDATE joueurs SET missions_faites=? WHERE id=? ');
			   $req->execute(array(($joueur['missions_faites'] + 1), $_SESSION['id_joueur']));
			   //On actualise l'activité du joueur
			$req = $bdd->prepare('SELECT id FROM attaques WHERE id_joueur=? AND combat_termine=\'0\' ');
			$req->execute(array($_SESSION['id_joueur']));
			$combat = $req->fetch();
			$req = $bdd->prepare('SELECT id FROM espionnages WHERE id_joueur=? AND espionnage_termine=\'0\' ');
			$req->execute(array($_SESSION['id_joueur']));
			$espionnage = $req->fetch();
			$req = $bdd->prepare('SELECT activite FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($combat['id'] != NULL OR $espionnage['id'] != NULL){
			   $req = $bdd->prepare('UPDATE joueurs SET activite=\'3\' WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			}
			else if($joueur['activite'] == -13){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'1\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			}
			else{
			   $req = $bdd->prepare('UPDATE joueurs SET activite=\'0\' WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			}
			//On supprime la mission des missions_en_cours
			$req = $bdd->prepare('DELETE FROM missions_en_cours WHERE id_joueur=? ');
			$req->execute(array($_SESSION['id_joueur']));
			   
			   ?>
			   <p class="succes" >
			      Vous avez bien terminé cette mission!<br />
				  Votre clan à remporté <?php echo $mission['argent_gagne'];?> pièces d'or et <?php echo $mission['xp_gagne']; ?> points d'expérience!<br />
				  <?php
				  if($inventaire_plein){
				     ?>
					 Malheureusement, vous n'avez pu trouver d'objet aléatoire car votre inventaire est plein...<br />
					 <?php
				  }
				  else{
				     ?>
					 Durant votre mission, votre clan a découvert un objet aléatoire qui a été placé dans votre inventaire:<br />
					 <?php echo $objet['nom'];?>
					 <br />
					 <?php
				  }
				  ?>
			   </p>
			   <?php
			}
		 }
	  ?>
	  <table>
	     <tr>
		    <th colspan="2" >Liste des missions</th>
		 </tr>
		 <tr>
		    <td>
			   <select name="niveau" onchange="golinks(this.options[this.selectedIndex].value)" >
			      <option value="" selected="selected" >Filtre de niveaux</option>
				  <optgroup label="Niveaux" >
				     <?php
					 $req = $bdd->prepare('SELECT niveau FROM joueurs WHERE id=? ');
			         $req->execute(array($_SESSION['id_joueur']));
			         $joueur = $req->fetch();
			
					 if(!isset($_GET['page'])){
					    for($i=0;$i!=($joueur['niveau']+1);$i++){ //20 niveaux maximum incluant le 0
					    ?>
						<option value="missions.php?co=o&amp;niveau=<?php echo $i; ?>" ><?php echo $i; ?></option>
						<?php
					    }
					 }
					 else{
					    for($i=0;$i!=($joueur['niveau']+1);$i++){ //20 niveaux maximum incluant le 0
					       ?>
						   <option value="missions.php?co=o&amp;niveau=<?php echo $i; ?>&amp;page=<?php echo $_GET['page']; ?>" ><?php echo $i; ?></option>
						   <?php
					    }
					 }
					 ?>
				  </optgroup>
			   </select>
			</td>
			<td style="width:60%" >
			   <select name="page" onchange="golinks(this.options[this.selectedIndex].value)" >
			      <option value="" selected="selected" >Aller à la page</option>
				  <?php
				     //On détermine le nombre de pages
					 if(!isset($_GET['niveau'])){
					    $req = $bdd->query('SELECT id FROM missions');
					 }
					 else{
					    $req = $bdd->prepare('SELECT id FROM missions WHERE niveau_requis=? ');
						$req->execute(array($_GET['niveau']));
					 }
					 $nb_missions = 0;
					 while($donnees = $req->fetch()){
					    $nb_missions++;
					 }
					 $nb_missions /= 15;
					 $nb_missions = ceil($nb_missions);
				  ?>
				  <optgroup label="Pages" >
				     <?php for($i=1;$i!=($nb_missions + 1);$i++){
					    if(!isset($_GET['niveau'])){
						   ?>
						   <option value="missions.php?co=o&amp;page=<?php echo $i; ?>" ><?php echo $i; ?></option>
						   <?php
						}
						else{
						   ?>
						   <option value="missions.php?co=o&amp;niveau=<?php echo $_GET['niveau']; ?>&amp;page=<?php echo $i; ?>" ><?php echo $i; ?></option>
						   <?php
						}
					 }?>
				  </optgroup>
			   </select>
			</td>
		 </tr>
		 <?php
		    $numero = 0;
			$numero_max = 15;
			$numero_min = 0;
			$niveau_requis = 0;
			   if(isset($_GET['page'])){
			      $numero_min = ($_GET['page'] * 15) - 15;
		          $numero_max = ($_GET['page'] * 15);
			   }
			   if(isset($_GET['niveau'])){
			      $niveau_requis = $_GET['niveau'];
			   }
			$req = $bdd->prepare('SELECT * FROM missions WHERE niveau_requis=?');
			$req->execute(array($niveau_requis));
			while($donnees = $req->fetch() AND $numero != $numero_max){
			   if($numero < $numero_min){
		       $numero++;
			   }
			   else{
			   ?>
			   <tr>
			      <td class="gauche" >
				  <div style="max-height:220px;overflow:auto;" >
				     <ins><?php echo $donnees['nom']; ?></ins><br />
					 <a href="missions.php?co=o&amp;faire=<?php echo $donnees['id']; ?>" >Commencer la mission!</a><br />
					 En terminant cette mission, vous obtiendrez:<br />
					 <ul>
					    <li><?php echo $donnees['argent_gagne']; ?> pièces d'or</li>
						<li><?php echo $donnees['xp_gagne']; ?> points d'expérience</li>
					 </ul>
					 Comme pour toutes les missions, vous avez des chances d'obtenir des objets aléatoires
				  </div>
				  </td>
				  <td>
				  <div style="max-height:220px;overflow:auto;" >
				     <?php
					 $temps = $donnees['temps'];
					 $temps /= 60;
					 ?>
				     <strong>Terminer cette mission vous prendra <?php echo $temps; ?> minutes.</strong><br />
				     <?php echo $donnees['description']; ?>
				  </div>
				  </td>
			   </tr>
			   <?php
			   $numero++;
			   }
			}
		 ?>
	  </table>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>
<?php
   $req->closeCursor();
?>