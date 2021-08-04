<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Magasin</title>
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
   <h2>Magasin</h2>
   
      	   <?php
	   		 //On regarde s'il y a action à faire
		 if(isset($_GET['a'])){
		 switch($_GET['a']){
		    case 'a':
		    if(isset($_GET['id'])){
			   $reponse = $bdd->prepare('SELECT places_inventaire, argent, infanterie, assassin, archer, espions, points_attaque, points_defense FROM joueurs WHERE id=? ');//Tous les infos du joueur
               $reponse->execute(array($_SESSION['id_joueur']));
	           $donnees_joueur = $reponse->fetch();
			   
			   $reponse = $bdd->prepare('SELECT id_joueur FROM magasins WHERE id=? ');
               $reponse->execute(array($_GET['magasin']));
	           $donnees_id = $reponse->fetch();
			   
			   $reponse = $bdd->prepare('SELECT pseudo, pseudo_min, id FROM joueurs WHERE id=? ');//Pseudo, pseudo_min, id du vendeur
			   $reponse->execute(array($donnees_id['id_joueur']));
			   $donnees_magasin = $reponse->fetch();
			   
			   $reponse = $bdd->prepare('SELECT argent FROM joueurs WHERE id=? ');//Argent du vendeur
               $reponse->execute(array($donnees_id['id_joueur']));
	           $donnees_vendeur = $reponse->fetch();
			   
			   $reponse = $bdd->prepare('SELECT prix, id_objet FROM magasins_objets WHERE id=? ');//Prix, id_objet de l'objet
               $reponse->execute(array($_GET['id']));
	           $donnees_vente = $reponse->fetch();
			   
			   $reponse = $bdd->prepare('SELECT type, attaque, defense, nom FROM objets WHERE id=? ');//Type, attaque, defense, nom de l'objet
               $reponse->execute(array($donnees_vente['id_objet']));
	           $donnees_objet = $reponse->fetch();
			   
			   //On détermine combien d'objet on a dans l'inventaire
			   $reponse = $bdd->prepare('SELECT * FROM inventaire WHERE id_joueur=? ');
			   $reponse->execute(array($_SESSION['id_joueur']));
			   $nb_objets = 0;
			   while($_ = $reponse->fetch()){$nb_objets ++;}
			   
			   if($donnees_joueur['argent'] < $donnees_vente['prix'])
			   {echo '<p class="erreur" >Vous n\'avez pas les moyens de faire cet achat!</p>';}
			   
			   else if($donnees_magasin['pseudo_min'] == $_SESSION['pseudo_min'])
			   {echo '<p class="erreur" >Vous ne pouvez pas acheter vos propres ventes!</p>';}
			   
			   else if($donnees_joueur['places_inventaire'] < $nb_objets + 1)
			   {echo '<p class="erreur" >Votre inventaire est plein!<br />Libérez un espace pour acheter cet objet</p>';}
			   
			   else{
			      $donnees_joueur['argent'] -= $donnees_vente['prix']; //On enlève l'argent au joueur
				  $req = $bdd->prepare('UPDATE joueurs SET argent=? WHERE pseudo=? ');
				  $req->execute(array($donnees_joueur['argent'], $_SESSION['pseudo']));
				  
				  $donnees_vendeur['argent'] += $donnees_vente['prix']; //On ajoute l'argent au vendeur
				  $req = $bdd->prepare('UPDATE joueurs SET argent=? WHERE pseudo=? ');
				  $req->execute(array($donnees_vendeur['argent'], $donnees_magasin['pseudo']));
				  
				  if($donnees_magasin['id'] != 0){
				  $req = $bdd->prepare('DELETE FROM magasins_objets WHERE id=? '); //On supprime la vente
				  $req->execute(array($_GET['id']));
				  }
				  
				  if($donnees_objet['type'] != 'Infanterie' AND $donnees_objet['type'] != 'Assassin' AND $donnees_objet['type'] != 'Archer' AND $donnees_objet['type'] != 'Espion'){
				  $req = $bdd->prepare('INSERT INTO inventaire (id_objet, id_joueur) VALUES(:id_objet, :id_joueur) ');//Ajoute l'objet dans l'inventaire
				  $req->execute(array(
				  'id_objet' => $donnees_vente['id_objet'],
				  'id_joueur' => $_SESSION['id_joueur']
				  ));
				  }
				  
				  switch($donnees_objet['type']){
					 
					 case 'Infanterie':
					 $req = $bdd->prepare('UPDATE joueurs SET infanterie=? WHERE id=? ');
					 $req->execute(array($donnees_joueur['infanterie'] += 1, $_SESSION['id_joueur']));
					 $req = $bdd->prepare('UPDATE joueurs SET points_attaque=?, points_defense=? WHERE id=? ');
					 $req->execute(array(($donnees_joueur['points_attaque'] + $donnees_objet['attaque']), 
					 ($donnees_joueur['points_defense'] + $donnees_objet['defense']), 
					 $_SESSION['id_joueur']));
					 break;
					 
					 case 'Assassin':
					 $req = $bdd->prepare('UPDATE joueurs SET assassin=? WHERE id=? ');
					 $req->execute(array($donnees_joueur['assassin'] += 1, $_SESSION['id_joueur']));
					 $req = $bdd->prepare('UPDATE joueurs SET points_attaque=?, points_defense=? WHERE id=? ');
					 $req->execute(array(($donnees_joueur['points_attaque'] + $donnees_objet['attaque']), 
					 ($donnees_joueur['points_defense'] + $donnees_objet['defense']), 
					 $_SESSION['id_joueur']));
					 break;
					 
					 case 'Archer':
					 $req = $bdd->prepare('UPDATE joueurs SET archer=? WHERE id=? ');
					 $req->execute(array($donnees_joueur['archer'] += 1, $_SESSION['id_joueur']));
					 $req = $bdd->prepare('UPDATE joueurs SET points_attaque=?, points_defense=? WHERE id=? ');
					 $req->execute(array(($donnees_joueur['points_attaque'] + $donnees_objet['attaque']), 
					 ($donnees_joueur['points_defense'] + $donnees_objet['defense']), 
					 $_SESSION['id_joueur']));
					 break;
					 
					 case 'Espion':
					    $req = $bdd->prepare('UPDATE joueurs SET espions=? WHERE id=? ');
					    $req->execute(array($donnees_joueur['espions'] += 1, $_SESSION['id_joueur']));
					 break;
					 
					 default:
				  }
				  
				  //On envoie un message au vendeur si le vendeur n'est pas Les hauts
				  if($donnees_magasin['pseudo'] != 'Les hauts'){
				  $message = 'Mes salutations,
				  
				  
				  Ce présent message est pour vous annoncer que votre objet: ' . $donnees_objet['nom'] . 
				  ', vient d\'être vendu à ' . htmlspecialchars($_SESSION['pseudo']) . ' pour ' . $donnees_vente['prix'] . ' pièces d\'or!
				  Votre gain a bien été mis dans vos réserves.
				  
				  
				  Que gloire et victoires fassent partie du reste de votre journée!';
				  $req = $bdd->prepare('INSERT INTO messagerie (titre, destinateur, id_destinataire, message, message_lu, date) 
				  VALUES (:titre, :destinateur, :id_destinataire, :message, 0, :date)');
				  $req->execute(array(
				     'titre' => 'Vente',
				     'destinateur' => 'Votre messager',
				     'id_destinataire' => $donnees_magasin['id'],
				     'message' => $message,
				     'date' => date('Y-m-d H:i:s')));
				  }
				  
				  echo '<p class="succes" >' . htmlspecialchars($donnees_magasin['pseudo']) .
				  ' vous vend cet objet pour ' .$donnees_vente['prix'] . ' pièces d\'or</p>';
			   }
			   
			}
			break;
			}
			}
		 ?>
   
   <?php if(!isset($_GET['magasin'])){ ?>
   <p>
      Voici une liste des magasins qui sont proche de votre position<br />
   </p>
   <?php if($_SESSION['profession'] == 'Forgeron' OR $_SESSION['profession'] == 'Marchand')
   {echo '<p><a href="mon_magasin.php?co=o" >Gestion de mon magasin</a></p>';}?>
   
   <table id="table_magasin" >
   
      <tr>
	     <th style="width:230px" >Magasin</th>
         <th style="width:170px" >Type</th>
		 <th style="width:150px" >Possession de</th>
      </tr>
	  
	  <tr>
	     <td><a href="magasin.php?co=o&amp;magasin=1" >Marché des mercenaires</a></td>
		 <td>Mercenaires</td>
		 <td>Les hauts</td>
	  </tr>
	  
	  <tr style="border-bottom:3px solid black" >
	     <td><a href="magasin.php?co=o&amp;magasin=2" >Marché publique</a></td>
		 <td>Objets</td>
		 <td>Les hauts</td>
	  </tr>
	  
	  <?php 
	  $reponse = $bdd->prepare('SELECT * FROM magasins');
	  $reponse->execute(array());
	  $req = $bdd->prepare('SELECT pseudo, pos_y, pos_x FROM joueurs WHERE id=? ');
	  $numero_min = 2;
	  $numero_max = 17;
	  $numero = 0;
	  $req2 = $bdd->prepare('SELECT pos_x, pos_y, facteur_vitesse FROM joueurs WHERE id=? ');
	  $req2->execute(array($_SESSION['id_joueur']));
	  $joueur = $req2->fetch();
	  if(isset($_GET['page'])){
	     $numero_min = ($_GET['page'] * 15) - 15 + 2;
		 $numero_max = ($_GET['page'] * 15) + 2;
	  }
	     while($donnees = $reponse->fetch() AND $numero != $numero_max){
		    if($numero < $numero_min){
		       $numero++;
			}
			else{
		    if($donnees['nom'] != NULL OR $donnees['nom'] != ''){
		       $req->execute(array($donnees['id_joueur']));
		       $autre_joueur = $req->fetch();
			   if(distance_carte($joueur['pos_x'], $joueur['pos_y'], $autre_joueur['pos_x'], $autre_joueur['pos_y']) <= (2 * $joueur['facteur_vitesse'])){
			      echo '<tr><td><a href="magasin.php?co=o&amp;magasin=' . $donnees['id'] .
		       '" >' . htmlspecialchars($donnees['nom']) . '</a></td><td>Publique</td><td><a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($autre_joueur['pseudo']) . '" >' .
		       htmlspecialchars($autre_joueur['pseudo']) . '</a></td></tr>';
			   }
			   else{
			   }
		        }
				$numero++;
				}
	     }
	  ?>
   </table>
   <?php 
   $reponse->closeCursor();
   $req->closeCursor();
   }
   else{
	  $reponse = $bdd->prepare('SELECT * FROM magasins WHERE id=? ');
      $reponse->execute(array($_GET['magasin']));
	  $donnees = $reponse->fetch();
	  $req = $bdd->prepare('SELECT pseudo, pos_x, pos_y FROM joueurs WHERE id=? ');
	  $req->execute(array($donnees['id_joueur']));
	  $autre_joueur = $req->fetch();
	  $req = $bdd->prepare('SELECT pos_x, pos_y, facteur_vitesse FROM joueurs WHERE id=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  $joueur = $req->fetch();
	  if(distance_carte($joueur['pos_x'], $joueur['pos_y'], $autre_joueur['pos_x'], $autre_joueur['pos_y']) <= (2 * $joueur['facteur_vitesse']) OR $_GET['magasin'] == 1 OR $_GET['magasin'] == 2){
	  ?>
	  <h4><?php echo $donnees['nom'];?></h4>
	  <p><?php echo $donnees['devise'];?></p>
	  
	  <table id="table_magasin" >
	     <caption>Dans cette petite échoppe, <?php echo htmlspecialchars($autre_joueur['pseudo']); if($autre_joueur == NULL){echo 'Les hauts';}?> vous propose les objets de la liste qui suit</caption>
		 
		 <tr>
		    <th style="width:300px" >Objet</th>
			<th style="width:200px" >Prix</th>
			<th style="width:200px" >Action</th>
		 </tr>
		 
		 <?php

		    $reponse_magasin = $bdd->prepare('SELECT * FROM magasins_objets WHERE id_magasin=? ');
            $reponse_magasin->execute(array($_GET['magasin']));
			
		    while($donnees_magasin = $reponse_magasin->fetch()){
			
			   $reponse_objet = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
               $reponse_objet->execute(array($donnees_magasin['id_objet']));
			   $donnees_objet = $reponse_objet->fetch();
			
			   echo '<tr><td title="Attaque(' . $donnees_objet['attaque'] . '), Défense(' . $donnees_objet['defense'] . ') ' . $donnees_objet['description'] . '" >' . $donnees_objet['nom'] . '</td><td>' .
			   $donnees_magasin['prix'] . ' pièces d\'or' . 
			   '</td><td><a href="magasin.php?co=o&amp;magasin=' . $_GET['magasin'] . 
			   '&amp;a=a&amp;id=' . $donnees_magasin['id'] . '" >Acheter</a></td></tr>';
			   $reponse_objet->closeCursor();
			}
		 }
		 else{
	     ?>
		 <p class="erreur">
		    Votre position est trop éloignée par rapport à ce magasin<br />
		 </p>
		 <?php
	     }
		 }
		 ?>
	  </table>
   
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>
