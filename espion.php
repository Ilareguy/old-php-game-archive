<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Espionnage</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
	   <?php
	      function puissance ($nbr, $puissance)
             {
                for ($total = $nbr; $puissance > 1; $puissance--)
                   $total = $total * $nbr;
                return ($total); 
             } 

	   ?>
	   <?php
	      function distance_carte($x1, $y1, $x2, $y2){ //Calcul de distance entre deux points
		     //sqrt = Racine carrée
		     $distance = 0;
		     $distance = sqrt(puissance(($y2 - $y1), 2) + puissance(($x2 - $x1), 2));
			 return number_format($distance, 2);
		  }
	   ?>
   </head>
   <body>
      <?php include("includes/banniere.php"); ?>
   <div id="centre">
      <h2>Espionnage</h2>
      <?php
		 $req = $bdd->prepare('SELECT facteur_vitesse, espions, pos_x, pos_y, niveau, id_alliance FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
		 $req = $bdd->prepare('SELECT pseudo, pos_x, pos_y, niveau, id_alliance FROM joueurs WHERE id=? ');
		 $req->execute(array($_GET['id']));
		 $cible = $req->fetch();
			if(!isset($_GET['confirm'])){
			if($joueur['espions'] == 0 ){
		 ?>
		 <p class="erreur" >
		    Vous n'avez actuellement aucun espion.<br />
			Pour envoyer un ordre d'espionnage, il est nécessaire d'en avoir au moins un
		 </p>
		 <?php
		 }
		 else{
		    if(distance_carte($joueur['pos_x'], $joueur['pos_y'], $cible['pos_x'], $cible['pos_y']) > ($joueur['facteur_vitesse'] * 2)){ //2 étant la valeure minimale d'attaque et d'espionnage
		 ?>
		 <p class="erreur" >
		    La distance séparant votre cible et vous est trop grande.<br />
			Essayez d'augmenter votre facteur vitesse!
		 </p>
		 <?php
		 }
		 else if($joueur['id_alliance'] == $cible['id_alliance'] AND $joueur['id_alliance'] != 0){
		    ?>
			<p class="erreur">
			   Vous ne pouvez pas lancer de mission d'espionnage sur ce clan car vous faites 
			   partie de la même alliance !<br />
			</p>
			<?php
		 }
		 else if($joueur['niveau'] > $cible['niveau']){
		    ?>
			<p class="erreur">
			   Votre cible a un niveau plus bas que le vôtre.<br />
			   Vous ne pouvez donc pas l'espionner !<br />
			</p>
			<?php
		 }
		 else{
		 ?>
		    <p>Votre cible: <a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $cible['pseudo']; ?>" ><?php echo $cible['pseudo']; ?></a></p>
			<form method="post" action="espion.php?co=o&amp;id=<?php echo $_GET['id'];?>&amp;confirm=1" >
			   <table>
			      <caption>Vous vous apprêtez à envoyer un espion en mission.</caption>
				  <tr>
				     <th colspan="2" >Informations sur la mission</th>
				  </tr>
				  <tfoot>
				     <tr>
				        <th style="width:60%" ></th>
					    <th></th>
				     </tr>
				  </tfoot>
				  <tr>
				     <td title="Plus vous en envoyez, plus vous pouvez obtenir d'informations sur votre cible. En revanche, plus vous avez de chances de perdre un nombre plus important d'espions." >
					    Combien d'espions envoyer?
					 </td>
					 <td style="text-align:left" >
					    <select name="nombre_espions" >
						<optgroup label="Espions" >
					    <?php
						   for($i=1; $i!=($joueur['espions'] + 1); $i++){
						      echo '<option value="' . $i . '" >' . $i . '</option>';
						   }
						?>
						</optgroup>
						</select>
					 </td>
				  </tr>
				  <tr>
				     <td title="Plus vous donnez d'options, plus la tâche sera difficile" >
					    Quels informations recherchez-vous?
					 </td>
					 <td style="text-align:left" >
					    <label><input type="checkbox" name="espionne_clan" />Son clan<br /></label>
						<label><input type="checkbox" name="espionne_force_offense" />Sa force offensive<br /></label>
						<label><input type="checkbox" name="espionne_force_defense" />Sa force défensive<br /></label>
						<label><input type="checkbox" name="espionne_espions" />Ses espions<br /></label>
						<label><input type="checkbox" name="espionne_reserves" />Ses réserves<br /></label>
					 </td>
				  </tr>
				  <tr>
				     <td colspan="2" >
					    <input type="submit" name="Envoyer" value="Donner l'ordre" />
					 </td>
				  </tr>
			   </table>
			</form>
			<?php
			}
			}
			}
			else if($_GET['confirm'] == 1){
			   $req = $bdd->prepare('SELECT activite, pos_x, pos_y, facteur_vitesse, espions FROM joueurs WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $joueur = $req->fetch();
			   if($joueur['activite'] != 2 AND $joueur['activite'] != 4){ //Si le joueur n'est pas en déplacement
			   $req = $bdd->prepare('SELECT pos_x, pos_y FROM joueurs WHERE id=? ');
			   $req->execute(array($_GET['id']));
			   $cible = $req->fetch();
			   $distance = distance_carte($joueur['pos_x'], $joueur['pos_y'], $cible['pos_x'], $cible['pos_y']);
			   $temps = 0;
			   $temps += (($distance * 14400) / $joueur['facteur_vitesse']);
		       $temps = $temps / 60;
		       $temps = round($temps);
			   $espionne_clan = 0;
			   $espionne_force_offensive = 0;
			   $espionne_force_defensive = 0;
			   $espionne_espions = 0;
			   $espionne_reserves = 0;
			   $timestamp_arrive = time();
			   $timestamp_arrive += (($distance * 14400) / $joueur['facteur_vitesse']);
			   if(isset($_POST['espionne_clan'])){$espionne_clan = 1;}
			   if(isset($_POST['espionne_force_offense'])){$espionne_force_offensive = 1;}
			   if(isset($_POST['espionne_force_defense'])){$espionne_force_defensive = 1;}
			   if(isset($_POST['espionne_espions'])){$espionne_espions = 1;}
			   if(isset($_POST['espionne_reserves'])){$espionne_reserves = 1;}
			   //Envoie des espions
			   $req = $bdd->prepare('INSERT INTO espionnages (id_joueur, id_cible, espions, espionne_clan,
			   espionne_force_offensive, espionne_force_defensive, espionne_espions, espionne_reserves, espionnage_termine,
			   timestamp_envoie, timestamp_arrive, pos_x, pos_y) 
			   VALUES (:id_joueur, :id_cible, :espions, :espionne_clan,
			   :espionne_force_offensive, :espionne_force_defensive, :espionne_espions, :espionne_reserves, \'0\', 
			   :timestamp_envoie, :timestamp_arrive, :pos_x, :pos_y)' );
			   $req->execute(array(
			   'id_joueur' => $_SESSION['id_joueur'],
			   'id_cible' => $_GET['id'],
			   'espions' => $_POST['nombre_espions'],
			   'espionne_clan' => $espionne_clan,
			   'espionne_force_offensive' => $espionne_force_offensive,
			   'espionne_force_defensive' => $espionne_force_defensive,
			   'espionne_espions' => $espionne_espions,
			   'espionne_reserves' => $espionne_reserves,
			   'timestamp_envoie' => time(),
			   'timestamp_arrive' => $timestamp_arrive,
			   'pos_x' => $cible['pos_x'], 
			   'pos_y' => $cible['pos_y'] ));
			   //On enlève les espions envoyés
			   $req = $bdd->prepare('UPDATE joueurs SET espions=? WHERE id=? ');
			   $req->execute(array(($joueur['espions'] - $_POST['nombre_espions']), $_SESSION['id_joueur']));
			   //On actualise L,activité du joueur
			   if($joueur['activite'] == 1){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'-13\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			   }
			   else if($joueur['activite'] == 0){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'3\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			   }
			   else if($joueur['activite'] == -13){
			   }
			   ?>
			   <p class="succes" >
			      Votre mission d'infiltration a bien été lancée.<br />
				  Arrivée des espions dans <?php echo $temps;?> minutes
			   </p>
			   <?php
			   }
			   else{
			      ?>
				  <p class="erreur" >
				     Vous ne pouvez envoyer d'espions car votre clan est actuellement en train de se déplacer!<br />
				  </p>
				  <?php
			   }
			}
			else{
			if(isset($_GET['a'])){
			if($_GET['a'] == "espionnage"){
			   //Espionnage
			   $req = $bdd->prepare('SELECT * FROM espionnages WHERE id=? ');
			   $req->execute(array($_GET['id_espionnage']));
			   $espionnage = $req->fetch();
			   $req = $bdd->prepare('SELECT activite, pos_x, pos_y FROM joueurs WHERE id=? ');
			   $req->execute(array($espionnage['id_cible']));
			   $cible = $req->fetch();
			   if($espionnage['id_joueur'] != $_SESSION['id_joueur']){
			      ?>
				  <p class="erreur" >
				     Cette mission d'espionnage n'est pas vôtre!<br />
				  </p>
				  <?php
			   }
			   else if($espionnage['timestamp_arrive'] > time()){
			      ?>
				  <p class="erreur" >
				     Vos espions ne sont toujours pas arrivés à destination!<br />
				  </p>
				  <?php
			   }
				  else if($cible['activite'] == 2 OR $cible['activite'] == 4 OR $cible['pos_x'] != $espionnage['pos_x'] OR
				  $cible['pos_y'] != $espionnage['pos_y']){
				     ?>
					 <h4>Espionnage échoué</h4>
					 <p>
					    Vos espions sont malheureux de vous apprendre que votre cible n'est plus sur cette position.<br />
						De ce fait, vos espions revienent immédiatement.<br />
					 </p>
					 <?php
					 $req = $bdd->prepare('SELECT espions FROM joueurs WHERE id=? ');
					 $req->execute(array($_SESSION['id_joueur']));
					 $joueur = $req->fetch();
					 $req = $bdd->prepare('UPDATE espionnages SET espionnage_termine=\'1\' WHERE id=? ');
			         $req->execute(array($_GET['id_espionnage']));
					 $req = $bdd->prepare('UPDATE joueurs SET espions=? WHERE id=? ');
			         $req->execute(array(($joueur['espions'] + $espionnage['espions']), $_SESSION['id_joueur']));
					 
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
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'0\' WHERE id=? ');
			      $req->execute(array($_SESSION['id_joueur']));
			   }
			}
			else if($joueur['activite'] == -13){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'1\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			}
				  }
			   else{
			   $req = $bdd->prepare('SELECT facteur_furtif, espions FROM joueurs WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $joueur = $req->fetch();
			   $req = $bdd->prepare('SELECT infanterie, assassin, archer, pseudo, nom_clan, points_attaque, points_defense, argent, id FROM joueurs WHERE id=? ');
			   $req->execute(array($espionnage['id_cible']));
			   $cible = $req->fetch();
			   $difficulte = 0;
			   $espions_morts = 0;
			   $tour = 1;
			   $chiffre = 0;
			   $espions = $espionnage['espions'];
			   $infos_recherches = 0;
			   $infos_trouves = 0;
			   $infos_clan = false;
			   $infos_force_offensive = false;
			   $infos_force_defensive = false;
			   $infos_espions = false;
			   $infos_reserves = false;
			   if($espionnage['espionne_clan'] == 1){$difficulte += 2; $infos_recherches ++;}
			   if($espionnage['espionne_force_offensive'] == 1){$difficulte += 2; $infos_recherches ++;}
			   if($espionnage['espionne_force_defensive'] == 1){$difficulte += 3; $infos_recherches ++;}
			   if($espionnage['espionne_espions'] == 1){$difficulte += 2; $infos_recherches ++;}
			   if($espionnage['espionne_reserves'] == 1){$difficulte += 4; $infos_recherches ++;}
			   $chances = $difficulte * 10;
			   $tier_chances = ($chances / 3) * $joueur['facteur_furtif'];
			   $tier_chances = round($tier_chances);
			   while($tour < ($espions + 1) AND $infos_trouves < $infos_recherches){ //Tant qu'on a des espions et qu'on a pas toutes les infos recherchés
			      $chiffre = rand(0, $chances);
				  if($chiffre <= $tier_chances){
				     if($espionnage['espionne_clan'] == 1 AND !$infos_clan){$infos_clan = true;}
					 else if($espionnage['espionne_force_offensive'] == 1 AND !$infos_force_offensive){$infos_force_offensive = true;}
					 else if($espionnage['espionne_force_defensive'] == 1 AND !$infos_force_defensive){$infos_force_defensive = true;}
					 else if($espionnage['espionne_espions'] == 1 AND !$infos_espions){$infos_force_espions = true;}
					 else if($espionnage['espionne_reserves'] == 1 AND !$infos_reserves){$infos_reserves = true;}
				     $infos_trouves ++;
				  }
				  else{
				     $espions_morts ++;
				  }
				  $tour ++;
			   }
			   ?>
			      <table id="table_espionnage" >
				     <tr>
					    <th colspan="2" >Espionnage</th>
					 </tr>
					 <tfoot>
					 <tr>
					    <th style="width:60%" ></th>
						<th></th>
					 </tr>
					 </tfoot>
					 <tr>
					    <td><strong>Niveau de difficulté de la mission</strong></td>
						<td><strong><?php echo $difficulte;?>/13</strong></td>
					 </tr>
					 <tr>
					    <td>Nombre d'espions envoyés</td>
						<td><?php echo $espions; ?></td>
					 </tr>
					 <tr>
					    <td><span class="succes" >Espions revenus</span></td>
						<td><span class="succes" ><?php echo ($espions - $espions_morts);?></span></td>
					 </tr>
					 <tr>
					    <td><span class="erreur" >Espions mort ou capturés</span></td>
						<td><span class="erreur" ><?php echo $espions_morts;?></span></td>
					 </tr>
				  </table>
				  <?php
				  if($espions_morts < $espionnage['espions']){
				  ?>
				  <p>
				  <hr />
				  </p>
				  <h4>Voici le rapport généré par vos espions qui sont revenus vivants</h4>
				  <p style="text-align:left" >
				  <br />
				  <br />
				  Début de la mission. Nous sommes maintenant incognitos<br />
				  parmis <?php echo $cible['pseudo']; ?>, meneur de <?php echo $cible['nom_clan']; ?>...<br />
				  <?php
				  if($infos_clan AND ($espionnage['espionne_clan'] == 1)){
				     ?>
					 Nous avons réussis à obtenir des informations sur le clan adverse!<br />
					 Nous pouvons compter:<br />
					 <?php echo $cible['infanterie']; ?> membres d'infanterie,<br />
					 <?php echo $cible['assassin']; ?> assassins et<br />
					 <?php echo $cible['archer']; ?> archers<br />
					 <?php
				  }
				  if($infos_force_offensive AND ($espionnage['espionne_force_offensive'] == 1)){
				     ?>
					 Vous vouliez connaître le force offensive de l'ennemis.<br />
					 Selon nos calculs, ses points d'attaque seraient de <?php echo $cible['points_attaque']; ?><br />
					 <?php
				  }
				  if($infos_force_defensive AND ($espionnage['espionne_force_defensive'] == 1)){
				     ?>
					 Nous avons fait notre possible pour déterminer la force défensive de notre cible.<br />
					 Voici où nous en sommes arrivés: <?php echo $cible['points_defense']; ?> points de défense.<br />
					 <?php
				  }
				  if($infos_espions AND ($espionnage['espionne_espions'] == 1)){
				     ?>
					 Nous avons dû nous faire très discret pour obtenir l'information suivante:<br />
					 Le clan de <?php echo $cible['pseudo']; ?> possède <?php echo $cible['espions']; ?> espions.<br />
					 <?php
				  }
				  if($infos_reserves AND ($espionnage['espionne_reserves'] == 1)){
				     ?>
					 Obtenir cette information n'a pas été très facile!<br />
					 Sans compter l'argent que notre cible a pû cacher, ses réserves en or<br />
					 s'évalueraient à exactement <?php echo $cible['argent']; ?> pièces d'or.<br />
					 <?php
				  }
				  ?>
				  <br />
				  Notre mission terminée, nous revenons maintenant au près de notre chef.<br />
				  </p>
			   <?php
			   }
			   else{
			      ?>
				  <h3>Aucun de vos espions n'est revenu</h3>
				  <p style="text-align:left" >
				  <strong>Votre messager viens vous informer d'une mauvaise nouvelle.</strong><br />
				  Vos espions que vous aviez envoyés chez <?php echo $cible['pseudo']; ?> ne sont jamais revenus...<br />
				  Qui sait ce qu'il aurait pû leur arriver! Ils sont soit morts, soit retenus prisonniers.<br />
				  </p>
				  <?php
			   }
			   if($espions_morts != 0){//Envoie du message à la cible
			      $message = 
				  'Bonjour messire,
			   
			      J\'ai une bonne et une mauvaise nouvelle pour vous.
			      La mauvaise: nous avons découvert des espions en provenance de chez ' . $_SESSION['pseudo'] . '
			      qui rôdaient sur notre territoire. Heureusement, nous avons pû faire en sorte que ' . $espions_morts . ' d\'entre
			      eux ne puissent pas revenir chez eux. Ils ont étés éliminés!
			   
			      Que gloire et victoires fassent partie du reste de votre journée!';
			   
			      $req = $bdd->prepare('INSERT INTO messagerie (titre, destinateur, id_destinataire, message, message_lu, type, date)
				  VALUES (\'Espions ennemis\', \'Votre messager\', :id_destinataire, :message, \'0\', \'2\', :date) ');
				  $req->execute(array(
				  'id_destinataire' => $cible['id'],
				  'message' => $message,
				  'date' => date('Y-m-d H:i:s')));
			   }
			   $req = $bdd->prepare('UPDATE espionnages SET espionnage_termine=\'1\' WHERE id=? ');
			   $req->execute(array($_GET['id_espionnage']));
			   //On redonne les espions survivants
			   $req = $bdd->prepare('UPDATE joueurs SET espions=? WHERE id=? ');
			   $req->execute(array((($espions - $espions_morts) + $joueur['espions']), $_SESSION['id_joueur']));
			   //On ajoute les espions morts
			   $req = $bdd->prepare('SELECT espions_perdus FROM joueurs WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $espions_perdus = $req->fetch();
			   $req = $bdd->prepare('UPDATE joueurs SET espions_perdus=? WHERE id=? ');
			   $req->execute(array(($espions_perdus['espions_perdus'] + $espions_morts), $_SESSION['id_joueur']));
			   
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
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'3\' WHERE id=? ');
			      $req->execute(array($_SESSION['id_joueur']));
			   }
			}
			else if($joueur['activite'] == -13){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'1\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			}
			
			else{
			   $req = $bdd->prepare('UPDATE joueurs SET activite=\'0\' WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			}
			   }
			   }
			}
			}
	  ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>
<?php
$req->closeCursor();
?>