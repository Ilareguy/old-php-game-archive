<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Combat</title>
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
      <h2>Combat</h2>
      <?php
	     //On prend les informations des deux joueurs
		 $req = $bdd->prepare('SELECT victoires, defaites, or_vole_total, argent, facteur_combat, points_attaque, 
		 points_defense, facteur_vitesse, pos_x, pos_y, infanterie, assassin, archer,
		 id_alliance, niveau FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
		 $req = $bdd->prepare('SELECT victoires, defaites, argent, facteur_combat, points_attaque, 
		 points_defense, pseudo, pos_x, pos_y, compte_active, id_alliance, niveau FROM joueurs WHERE id=? ');
		 $req->execute(array($_GET['id']));
		 $cible = $req->fetch();
		 $distance = distance_carte($joueur['pos_x'], $joueur['pos_y'], $cible['pos_x'], $cible['pos_y']);
		 $req = $bdd->prepare('SELECT id FROM attaques WHERE timestamp_arrivee>? AND id_joueur=? AND id_cible=? ');
		 $req->execute(array((time() - 72000), $_SESSION['id_joueur'], $_GET['id']));
		 $combat_precedent = $req->fetch();
		 echo $combat_precedent['id'];
		 $req = $bdd->prepare('SELECT timestamp_premiere_connexion FROM joueurs WHERE id=? ');
		 $req->execute(array($_GET['id']));
		 $premiere_connexion_cible = $req->fetch();
	  ?>
      <p>Votre cible: <a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $cible['pseudo'];?>" ><?php echo htmlspecialchars($cible['pseudo']); ?></a></p>
	  <?php
		 if(!isset($_GET['a'])){
	     if($distance > (2 * $joueur['facteur_vitesse'])){ //2 étant la valeure minimale d'attaque et d'espionnage
		    ?>
			<p class="erreur" >Malheureusement, la distance séparant votre cible et vous est trop élevée.<br />
			Les <a href="ameliorations.php?co=o" >améliorations</a> sont la meilleure façon d'augmenter votre facteur vitesse, <br />
			ce qui vous permettera de pouvoir lancer des attaques sur de plus grandes distances.</p>
			<?php
		 }
		 else if($joueur['id_alliance'] == $cible['id_alliance'] AND $joueur['id_alliance'] != 0){
		    ?>
			<p class="erreur">
			   Vous ne pouvez pas lancer d'assaut sur ce clan car vous faites 
			   partie de la même alliance!<br />
			</p>
			<?php
		 }
		 else if($joueur['niveau'] != $cible['niveau']){
		    ?>
			<p class="erreur">
			   Vous devez avoir le même niveau que votre cible pour l'attaquer<br />
			</p>
			<?php
		 }
		 else if($combat_precedent['id'] != NULL){
		    ?>
			<p class="erreur">
			   Vous avez déjà lancé une attaque sur ce clan dans les 20 dernière heures.<br />
			   Vous devez patienter avant de l'attaquer de nouveau<br />
			</p>
			<?php
		 }
		 else if((time() - $premiere_connexion_cible['timestamp_premiere_connexion']) < 604800){
		    ?>
			<p class="erreur">
			   Ce clan vient de voir le jour et ne peut donc pas être attaqué immédiatement<br />
			</p>
			<?php
		 }
		 else{
		 //Formulaire d'attaque ici
		 $req = $bdd->prepare('SELECT infanterie, assassin, archer FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $donnees = $req->fetch();
		 $temps = (time() + ($distance * 7200)) - time();
		 $temps = $temps / $joueur['facteur_vitesse'];
		 $temps = $temps / 60;
		 $temps = round($temps, 0);
		 ?>
		    <p class="information" >
			   Temps de déplacement d'ici à votre cible: <?php echo $temps;?> minutes
			</p>
		    <table>
		    <form method="post" action="combat.php?co=o&amp;a=confirmer&amp;id=<?php echo $_GET['id'];?>" >
			   <tr>
			      <th style="width:70%" ></th>
				  <th></th>
			   </tr>
			      <td>
				     Note
				  </td>
				  <td>
				     <div style="max-height:80px;overflow:auto" >
					    Plus vous envoyez de membres de clan, plus votre puissance sera divisée.<br />
						Le temps que vos troupent ne seront pas avec vous, vos points d'attaque et de défense seront diminués temporairement.
				     </div>
				  </td>
			   <tr>
			   </tr>
			   <tr>
			      <td>
				     Membres d'infanterie à envoyer
				  </td>
				  <td>
				     <select name="infanterie" >
					    <optgroup label="Infanterie" >
					    <?php
						for($i = 0;$i != ($donnees['infanterie'] + 1);$i ++){ 
						?>
						<option value="<?php echo $i;?>" >
						   <?php
						   echo $i;
						   ?>
						</option>
						<?php
						}
						?>
						</optgroup>
					 </select>
				  </td>
			   </tr>
			   <tr>
			      <td>
				     Assassins à envoyer
				  </td>
				  <td>
				     <select name="assassin" >
					    <optgroup label="Assassins" >
					    <?php
						for($i = 0;$i != ($donnees['assassin'] + 1);$i ++){ 
						?>
						<option value="<?php echo $i;?>" >
						   <?php
						   echo $i;
						   ?>
						</option>
						<?php
						}
						?>
						</optgroup>
					 </select>
				  </td>
			   </tr>
			   <tr>
			      <td>
				     Archers à envoyer
				  </td>
				  <td>
				     <select name="archer" >
					    <optgroup label="Archers" >
					    <?php
						for($i = 0;$i != ($donnees['archer'] + 1);$i ++){ 
						?>
						<option value="<?php echo $i;?>" >
						   <?php
						   echo $i;
						   ?>
						</option>
						<?php
						}
						?>
						</optgroup>
					 </select>
				  </td>
			   </tr>
			   <tr>
			      <td colspan="2" ><input type="submit" value="Lancer l'attaque" /></td>
			   </tr>
			</form>
			</table>
		 <?php
		 }
		 }
		 else{
			switch($_GET['a']){
			case 'confirmer':
			   if(isset($_POST['infanterie']) AND isset($_POST['assassin']) AND isset($_POST['archer'])){
			   $req = $bdd->prepare('SELECT activite, facteur_vitesse, infanterie, assassin, archer, points_attaque, points_defense, armes_infanterie, armes_assassin, armes_archer FROM joueurs WHERE id=? ');
			   $req->execute(array($_SESSION['id_joueur']));
			   $donnees_clan = $req->fetch();
			   $req = $bdd->prepare('SELECT pos_x, pos_y FROM joueurs WHERE id=? ');
			   $req->execute(array($_GET['id']));
			   $cible = $req->fetch();
			   if($donnees_clan['activite'] == 2){
			      ?>
				  <p class="erreur" >
				     Vous ne pouvez envoyer de troupes car votre clan est actuellement en train de se déplacer!<br />
				  </p>
				  <?php
			   }
			   else if($donnees_clan['points_attaque'] == 0 OR $donnees_clan['points_defense'] == 0){
			      ?>
				  <p class="erreur" >
				     Vous n'avez aucun point d'attaque ou de défense!<br />
					 Il vous est donc impossible de lancer une attaque<br />
				  </p>
				  <?php
			   }
			   else if($_POST['infanterie'] + $_POST['assassin'] + $_POST['archer'] == 0){
			      ?>
				  <p class="erreur" >
				     Vous devez envoyer au moins un membre de clan!<br />
					 Il vous est donc impossible de lancer une attaque<br />
				  </p>
				  <?php
			   }
			   else{
			   //Les armes
			   $armes_infanterie = $donnees_clan['armes_infanterie'];
			   if($armes_infanterie <= $_POST['infanterie']){
			      $armes_infanterie = 0;
			   }
			   else{
			      $armes_infanterie -= $_POST['infanterie'];
			   }
			   $armes_infanterie_envoyes = $_POST['infanterie'];
			   if($armes_infanterie_envoyes > $donnees_clan['armes_infanterie']){
			      $armes_infanterie_envoyes = $donnees_clan['armes_infanterie'];
			   }
			   
			   $armes_assassin = $donnees_clan['armes_assassin'];
			   if($armes_assassin <= $_POST['assassin']){
			      $armes_assassin = 0;
			   }
			   else{
			      $armes_assassin -= $_POST['assassin'];
			   }
			   $armes_assassin_envoyes = $_POST['assassin'];
			   if($armes_assassin_envoyes > $donnees_clan['armes_assassin']){
			      $armes_assassin_envoyes = $donnees_clan['armes_assassin'];
			   }
			   
			   $armes_archer = $donnees_clan['armes_archer'];
			   if($armes_archer <= $_POST['archer']){
			      $armes_archer = 0;
			   }
			   else{
			      $armes_archer -= $_POST['archer'];
			   }
			   $armes_archer_envoyes = $_POST['archer'];
			   if($armes_archer_envoyes > $donnees_clan['armes_archer']){
			      $armes_archer_envoyes = $donnees_clan['armes_archer'];
			   }
			   
			   $membre_clan_total = $donnees_clan['infanterie'] + $donnees_clan['assassin'] + $donnees_clan['archer'];
			   $membre_clan_total_envoyes = $_POST['infanterie'] + $_POST['assassin'] + $_POST['archer'];
			   $points_attaque = ($donnees_clan['points_attaque'] / $membre_clan_total); //Points d'attaque PAR membre de clan
			   $points_attaque *= $membre_clan_total_envoyes;
			   $points_defense = ($donnees_clan['points_defense'] / $membre_clan_total); //Points de defense PAR membre de clan
			   $points_defense *= $membre_clan_total_envoyes;
			   $points_attaque = round($points_attaque);
			   $points_defense = round($points_defense);
			   $temps = (time() + ($distance * 14400)) - time();
		       $temps = $temps / $donnees_clan['facteur_vitesse'];
		       $temps = $temps / 60;
		       $temps = round($temps, 0);
			   //On ajoute l'attaque dans la base de données
			   $req = $bdd->prepare('INSERT INTO attaques (id_joueur, id_cible, infanterie, assassins, archers, points_attaque, 
			   points_defense, timestamp_envoie, timestamp_arrivee, combat_termine, pos_x_cible, pos_y_cible, armes_infanterie,
			   armes_assassin, armes_archer)
			   VALUES (:id_joueur, :id_cible, :infanterie, :assassins, :archers, :points_attaque, :points_defense, 
			   :timestamp_envoie, :timestamp_arrivee, \'0\', :pos_x_cible, :pos_y_cible, :armes_infanterie,
               :armes_assassin, :armes_archer )');
			   $arrivee = time();
			   $arrivee += (($distance * 14400) / $donnees_clan['facteur_vitesse']); //7200 secondes, soit 2 heures par case sur la carte
			   $req->execute(array(
			   'id_joueur' => $_SESSION['id_joueur'],
			   'id_cible' => $_GET['id'],
			   'infanterie' => $_POST['infanterie'],
			   'assassins' => $_POST['assassin'],
			   'archers' => $_POST['archer'],
			   'points_attaque' => $points_attaque,
			   'points_defense' => $points_defense,
			   'timestamp_envoie' => time(),
			   'timestamp_arrivee' => $arrivee,
			   'pos_x_cible' => $cible['pos_x'],
			   'pos_y_cible' => $cible['pos_y'],
			   'armes_infanterie' => $armes_infanterie_envoyes,
			   'armes_assassin' => $armes_assassin_envoyes,
			   'armes_archer' => $armes_archer_envoyes ));
			   //On enlève les membres de clan et points au joueur
			   $req = $bdd->prepare('UPDATE joueurs SET infanterie=?, assassin=?, archer=?, points_attaque=?, points_defense=? WHERE id=? ');
			   $req->execute(array(
			   ($donnees_clan['infanterie'] - $_POST['infanterie']),
			   ($donnees_clan['assassin'] - $_POST['assassin']),
			   ($donnees_clan['archer'] - $_POST['archer']),
			   ($donnees_clan['points_attaque'] - $points_attaque),
			   ($donnees_clan['points_defense'] - $points_defense),
			   $_SESSION['id_joueur']));
			   //On enlève les armes au joueur
			   $req = $bdd->prepare('UPDATE joueurs SET armes_infanterie=?, armes_assassin=?, armes_archer=? WHERE id=? ');
			   $req->execute(array(
			   $armes_infanterie,
			   $armes_assassin,
			   $armes_archer,
			   $_SESSION['id_joueur']));
			   //On ajoute l'activité
			   if($donnees_clan['activite'] == 1){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'-13\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			   }
			   else if($donnees_clan['activite'] == 0){
			      $req = $bdd->prepare('UPDATE joueurs SET activite=\'3\' WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
			   }
			   else if($donnees_clan['activite'] == -13){
			   }
			   $req->closeCursor();
			   ?>
			   <p class="succes" >
			      Vos troupes sont maintenant en route vers l'ennemis!<br />
				  Lorsque qu'elles seront arrivés, vous serez amené à donner l'ordre d'assaut.<br />
				  Arrivée dans <?php echo $temps;?> minutes
			   </p>
			   <?php
			   }
			   }
			break;
			
			case 'combat':
			if(!isset($_GET['id_combat'])){header('Location: index.php');}
			$req = $bdd->prepare('SELECT * FROM attaques WHERE id=? ');
			$req->execute(array($_GET['id_combat']));
			$donnees_combat = $req->fetch();
			$req = $bdd->prepare('SELECT facteur_combat, argent, or_vole_total, victoires, defaites, infanterie, assassin, archer, points_defense, points_attaque, armes_infanterie, armes_assassin, armes_archer FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			$req = $bdd->prepare('SELECT victoires, defaites, argent, facteur_combat, points_defense, points_attaque FROM joueurs WHERE id=? ');
			$req->execute(array($donnees_combat['id_cible']));
			$cible = $req->fetch();
			if(time() < $donnees_combat['timestamp_arrivee']){
			   ?>
			   <p class="erreur" >
			   Vos troupes ne sont toujours pas arrivés chez votre cible!<br />
			   Vous devez attendre qu'elles soient arrivées avant de lancer l'assaut
			   </p>
			   <?php
			}
			else if($donnees_combat['combat_termine'] == 1){//Si le combat a déjà été effectué
			   ?>
			   <p class="erreur">
			      Ce combat a déjà été effectué!<br />
			   </p>
			   <?php
			}
			else if($donnees_combat['id_joueur'] != $_SESSION['id_joueur']){header('Location: index.php');}
			
			else{
		    //Combat
			$id_gagnant = NULL;
			$or_vole = NULL;
			$tours = 0;
			$degats_max = 0;
			//Attaquant
			$attaquant['vie'] = ($donnees_combat['points_defense'] * 3);
			$attaquant['vie_depart'] = $attaquant['vie'];
			$attaquant['attaque_max'] = ($donnees_combat['points_attaque'] * 1.10);
			$attaquant['attaque_max'] *= $joueur['facteur_combat'];
			$attaquant['attaque_minimum'] = ($donnees_combat['points_attaque'] / 2);
			$attaquant['degats'] = 0;
			//Défenseur
			$defenseur['vie'] = (($cible['points_defense'] * $cible['facteur_combat']) * 3);
			$defenseur['attaque_max'] = $cible['points_defense'] * 1.10;
			$defenseur['attaque_minimum'] = $cible['points_defense'] / 2;
			$defenseur['degats'] = 0;
			//Premier tour, où y'a les bonus de départ et tout

			/*
			      Celui qui a le plus de points d'attaque et de défense a plus de chances de gagner,
				  mais si les points sont presque égaux, les rand() entreront bien plus en jeu
				  que si la différence de points est grande.
				  Attaquant:
				     OK: Sa vie = ses points de défense * 3;
				     OK: Attaque max = ses points d'attaque + 10%;
					 OK: Attaque minimum = Moitié de ses points d'attaque;
					 OK: Facteur combat sur son attaque max;
				  Défenseur: 
				     OK: Sa vie = ses points de défense au départ * 3;
				     OK: Attaque max = ses points de défense + 10%;
					 OK: Attaque minimum = Moitié de ses points de défense;
					 OK: Facteur combat sur sa vie;
			   */
			   //Boucle du combat: Tant que les joueurs sont «en vie»
			   $combat_termine = false;
			   while(!$combat_termine){
			      $attaquant['degats'] = rand($attaquant['attaque_minimum'], $attaquant['attaque_max']);
				  $defenseur['vie'] -= $attaquant['degats'];
				  if($defenseur['vie'] <= 0){
				     $id_gagnant = $_SESSION['id_joueur'];
					 $combat_termine = true;
					 $or_vole = rand(0, ($cible['argent'] / 3));
				  }
				  else{
				     $defenseur['degats'] = rand($defenseur['attaque_minimum'], $defenseur['attaque_max']);
					 $attaquant['vie'] -= $defenseur['degats'];
					 if($attaquant['vie'] <= 0){
					    $id_gagnant = $donnees_combat['id_cible'];
						$combat_termine = true;
					 }
				  }
				  if($attaquant['degats'] > $degats_max){
				     $degats_max = $attaquant['degats'];
				  }
				  $tours ++;
			   }
			   $xp_gagne = 0;
			if($id_gagnant == $_SESSION['id_joueur']){
			   $xp_gagne = 5 + ($tours / 2);
			}else{
			   $xp_gagne = 2 + ($tours / 2);
			}
			$xp_gagne = round($xp_gagne);
			//Rapport
			?>
			   <table>
			      <tr>
				     <th style="width:50%" colspan="2" >Statistiques</th>
				  </tr>
				  <tr>
				     <td><strong>Statut de la bataille</strong></td>
					 <td>
					    <strong>
					    <?php
						   if($id_gagnant == $_SESSION['id_joueur']){
						      echo '<span class="succes" >Victoire!</span>';
						   }
						   else{
						      echo '<span class="attention" >Défaite...</span>';
						   }
						?>
						</strong>
					 </td>
				  </tr>
				  <tr>
				     <td>Votre capital défensif</td>
					 <td><?php echo $attaquant['vie_depart']; ?></td>
				  </tr>
				  <tr>
				     <td>Votre clan</td>
					 <td>
					    <ul>
			               <li><?php echo $donnees_combat['infanterie']; ?> membres d'infanterie</li>
				           <li><?php echo $donnees_combat['assassins']; ?> assassins</li>
				           <li><?php echo $donnees_combat['archers']; ?> archers</li>
			            </ul>
				     </td>
				  </tr>
				  <tr>
				     <td>Plus grands dégâts infligés</td>
					 <td><?php echo $degats_max; ?></td>
				  </tr>
				  <tr>
				     <td>Nombre de tours avant la fin de la bataille</td>
					 <td><?php echo $tours; ?></td>
				  </tr>
				  <?php
				  if($id_gagnant == $_SESSION['id_joueur']){
				  ?>
				  <tr>
				     <td>Pièces d'or volés</td>
					 <td><?php echo $or_vole; ?></td>
				  </tr>
				  <?php
				  }
				  ?>
				  <tr>
				     <td>Points d'expérience gagnés</td>
					 <td><?php echo $xp_gagne; ?></td>
				  </tr>
			   </table>
			<?php
			if($id_gagnant == $_GET['id']){
			   $message = 'Notre clan a été attaqué par celui de ' . htmlspecialchars($_SESSION['pseudo']) . '!
			   
			   Fort heureusement, nous avons été capable de repousser l\'attaque!
			   Il n\'a ainsi pas pu accéder à nos réserves d\'or.';
			}
			else{
			   $message = 'Notre clan a été attaqué par celui de ' . htmlspecialchars($_SESSION['pseudo']) . '!
			   
			   Malheureusement, nous n\'avons su se défendre jusqu\'au bout...
			   Ainsi, ' . $or_vole . ' pièces d\'or ont été volées.';
			   //On ajoute l'argent au joueur
			   $req = $bdd->prepare('UPDATE joueurs SET argent=?, or_vole_total=? WHERE id=? ');
			   $req->execute(array(($joueur['argent'] + $or_vole), ($joueur['or_vole_total'] + $or_vole), $_SESSION['id_joueur']));
			   //On enlève l'argent du perdant
			   $req = $bdd->prepare('UPDATE joueurs SET argent=? WHERE id=? ');
			   $req->execute(array(($cible['argent'] - $or_vole), $_GET['id']));
			}
			//Envoie du message à la cible
			$req = $bdd->prepare('INSERT INTO messagerie (titre, message, id_destinataire, destinateur, date, type)
			   VALUES(:titre, :message, :id_destinataire, :destinateur, :date, \'1\')');
			   $req->execute(array(
			   'titre' => 'Défense contre ' . $_SESSION['pseudo'],
			   'message' => $message,
			   'id_destinataire' => $_GET['id'],
			   'destinateur' => 'Votre messager',
			   'date' => date('Y-m-d H:i:s')));
			   
			//Ajout des victoires et défaites
			if($id_gagnant == $_SESSION['id_joueur']){
			   $req = $bdd->prepare('UPDATE joueurs SET victoires=? WHERE id=? ');
			   $req->execute(array(($joueur['victoires'] + 1), $_SESSION['id_joueur']));
			   $req = $bdd->prepare('UPDATE joueurs SET defaites=? WHERE id=? ');
			   $req->execute(array(($cible['defaites'] + 1), $_GET['id']));
			}
			else{
			   $req = $bdd->prepare('UPDATE joueurs SET victoires=? WHERE id=? ');
			   $req->execute(array(($cible['victoires'] + 1), $_GET['id']));
			   $req = $bdd->prepare('UPDATE joueurs SET defaites=? WHERE id=? ');
			   $req->execute(array(($joueur['defaites'] + 1), $_SESSION['id_joueur']));
			}
			//On marque le combat comme terminé
			$req = $bdd->prepare('UPDATE attaques SET combat_termine=\'1\' WHERE id=? ');
			$req->execute(array($_GET['id_combat']));
			
			//On redonne les points et membres de clan au joueur
			$req = $bdd->prepare('UPDATE joueurs SET infanterie=?, assassin=?, archer=?, points_attaque=?, points_defense=?
			WHERE id=? ');
			$req->execute(array(
			($donnees_combat['infanterie'] + $joueur['infanterie']), 
			($donnees_combat['assassins'] + $joueur['assassin']),
			($donnees_combat['archers'] + $joueur['archer']),
			($donnees_combat['points_attaque'] + $joueur['points_attaque']),
			($donnees_combat['points_defense'] + $joueur['points_defense']),
			$_SESSION['id_joueur']));
			
			//On redonne les armes au joueur
			$req = $bdd->prepare('UPDATE joueurs SET armes_infanterie=?, armes_assassin=?, armes_archer=? WHERE id=? ');
			$req->execute(array(($donnees_combat['armes_infanterie'] + $joueur['armes_infanterie']), 
			($donnees_combat['armes_assassin'] + $joueur['armes_assassin']),
			($donnees_combat['armes_archer'] + $joueur['armes_archer']),
			$_SESSION['id_joueur']));
			
			//On ajoute les points d'expérience du joueur
			$req = $bdd->prepare('SELECT experience FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			
			$req = $bdd->prepare('UPDATE joueurs SET experience=? WHERE id=? ');
			$req->execute(array(($xp_gagne + $joueur['experience']), $_SESSION['id_joueur']));
			
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
			$req->closeCursor();
			break;
			
			case 'annuler':
			$req = $bdd->prepare('SELECT * FROM attaques WHERE id=? ');
			$req->execute(array($_GET['id_combat']));
			$donnees_combat = $req->fetch();
			$req = $bdd->prepare('SELECT facteur_combat, argent, or_vole_total, victoires, defaites, infanterie, assassin, archer, points_defense, points_attaque, armes_infanterie, armes_assassin, armes_archer FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$joueur = $req->fetch();
			if($donnees_combat['combat_termine'] == 1){//Si le combat a déjà été effectué
			   ?>
			   <p class="erreur">
			      Ce combat a déjà été effectué!<br />
			   </p>
			   <?php
			}
			else if(!isset($_GET['id_combat'])){header('Location: index.php');}
			else{
			//On marque le combat comme terminé
			$req = $bdd->prepare('UPDATE attaques SET combat_termine=\'1\' WHERE id=? ');
			$req->execute(array($_GET['id_combat']));
			
			//On redonne les points et membres de clan au joueur
			$req = $bdd->prepare('UPDATE joueurs SET infanterie=?, assassin=?, archer=?, points_attaque=?, points_defense=?
			WHERE id=? ');
			$req->execute(array(
			($donnees_combat['infanterie'] + $joueur['infanterie']), 
			($donnees_combat['assassins'] + $joueur['assassin']),
			($donnees_combat['archers'] + $joueur['archer']),
			($donnees_combat['points_attaque'] + $joueur['points_attaque']),
			($donnees_combat['points_defense'] + $joueur['points_defense']),
			$_SESSION['id_joueur']));
			
			//On redonne les armes au joueur
			$req = $bdd->prepare('UPDATE joueurs SET armes_infanterie=?, armes_assassin=?, armes_archer=? WHERE id=? ');
			$req->execute(array(($donnees_combat['armes_infanterie'] + $joueur['armes_infanterie']), 
			($donnees_combat['armes_assassin'] + $joueur['armes_assassin']),
			($donnees_combat['armes_archer'] + $joueur['armes_archer']),
			$_SESSION['id_joueur']));
			
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
			?>
			<p class="succes">
			   Vos troupes sont immédiatement revenues comme vous le souhaitiez<br />
			</p>
			<?php
			}
			break;
		   }
		 }
	  ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>