<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Carte</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script style="Text/JavaScript" src="scripts/carte.js" ></script>
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
	   <?php
	   function inverser($valeur){ //Inverse le point y de la carte. Ex.: L'inverse de 10 est 0, l'inverse de 9 est 1 ...
	      switch($valeur){
		     case 0: return 9; break;
			 case 1: return 8; break;
			 case 2: return 7; break;
			 case 3: return 6; break;
			 case 4: return 5; break;
			 case 5: return 4; break;
			 case 6: return 3; break;
			 case 7: return 2; break;
			 case 8: return 1; break;
			 case 9: return 0; break;
	      }
	   }
	   ?>
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
	   <script language="JavaScript" >
	      function golinks(where)
	      {self.location = where;}
	   </script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <?php if(isset($_GET['x']) AND isset($_GET['y'])){ ?>
   <div id="centre" >
      <?php echo '<h2>La carte</h2>'; ?>
      <?php echo '<h3>' . $_GET['x'] . ', ' . $_GET['y'] . '</h3>'; ?>
	  <?php 
	     //On trouve la position du joueur
		 $req = $bdd->prepare('SELECT pos_x, pos_y FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $position = $req->fetch();
		 $distance = distance_carte($position['pos_x'], $position['pos_y'], $_GET['x'], $_GET['y']);
		 $req = $bdd->prepare('SELECT facteur_vitesse, niveau FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
		 $deplacement = ($distance * 4) / $joueur['facteur_vitesse']; //Soit 4 heures par case
		 $deplacement *= 60;
		 $deplacement = round($deplacement);
		 $req = $bdd->prepare('SELECT informations, passable FROM carte WHERE x=? AND y=?');
		 $req->execute(array($_GET['x'], $_GET['y']));
		 $carte = $req->fetch();
		 if($carte['passable'] != NULL){
		    if($carte['passable'] == 0){
		       ?>
			   <p class="attention" >
			      Il est impossible de s'installer sur cette case de la carte.<br />
			   </p>
			   <?php
			}
			else{
			   ?>
			   <p>
			      Vous pouvez vous déplacer vers cette case.<br />
				  <span class="attention" >Attention! Une fois en déplacement, vous ne pourrez attaquer ou être attaqué et faire des missions.</span><br />
	              <span class="information" >Vous déplacer vers cette case vous prendra <?php echo $deplacement; ?> minutes.</span><br />
	              <a href="carte.php?co=o&amp;deplacer=1&amp;x=<?php echo $_GET['x']; ?>&amp;y=<?php echo $_GET['y']; ?>" >Se déplacer vers cette case</a>
			   </p>
			   <?php
			}
		}
		else{
		   ?>
		      <p>
			     Vous pouvez vous déplacer vers cette case.<br />
				 <span class="attention" >Attention! Une fois en déplacement, vous ne pourrez attaquer ou être attaqué et faire des missions.</span><br />
	             <span class="information" >Vous déplacer vers cette case vous prendra <?php echo $deplacement; ?> minutes.</span><br />
	             <a href="carte.php?co=o&amp;deplacer=1&amp;x=<?php echo $_GET['x']; ?>&amp;y=<?php echo $_GET['y']; ?>" >Se déplacer vers cette case</a>
			  </p>
		   <?php
		}
			
			if($carte['informations'] != NULL){
			   ?>
			   <div style="border: 2px solid black;background-color: #9fa040;">
			   <p>
			      <ins>Un peu plus d'infos sur cette position:</ins><br />
			      <?php echo $carte['informations']; ?>
			   </p>
			   </div>
			   <?php
			}
		 if(!isset($_GET['deplacer'])){
	  ?>
	  <p>Distance entre votre clan et cette position: <?php echo $distance; ?><br />
	  <br />
	  <hr />
	  <?php 
	  }
	  else{ //On veut se déplacer
	     $req = $bdd->prepare('SELECT activite, pos_x, pos_y FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
		 if($joueur['activite'] != 0){
		    ?>
			<span class="erreur" >Vous ne pouvez pas vous déplacer car votre clan est déjà occupé en ce moment<br /></span>
			<?php
		 }
		 else if($joueur['pos_x'] == $_GET['x'] AND $joueur['pos_y'] == $_GET['y']){
		    ?>
			<span class="erreur">Vous ne pouvez pas vous déplacer ici car vous y êtes déjà !<br /></span>
			<?php
		 }
		 else{
		    //On met l'activité
			$distance = distance_carte($position['pos_x'], $position['pos_y'], $_GET['x'], $_GET['y']);
		    $req = $bdd->prepare('SELECT activite, facteur_vitesse FROM joueurs WHERE id=? ');
		    $req->execute(array($_SESSION['id_joueur']));
		    $joueur = $req->fetch();
		    $deplacement = ($distance * 4) / $joueur['facteur_vitesse']; //Soit 4 heures par case
		    $deplacement *= 60; //En minutes
			$temps = round($deplacement);
			$deplacement *= 60; //En secondes (timestamp)
		    $deplacement = round($deplacement);
			$deplacement = $deplacement + time();
			$req = $bdd->prepare('UPDATE joueurs SET activite_fin_timestamp=?, activite=\'2\' WHERE id=? ');
			$req->execute(array($deplacement, $_SESSION['id_joueur']));
			//Ajout du déplacement
			$req = $bdd->prepare('INSERT INTO deplacements (id_joueur, x, y)
			VALUES (:id_joueur, :x, :y)' );
			$req->execute(array(
			'id_joueur' => $_SESSION['id_joueur'],
			'x' => $_GET['x'],
			'y' => $_GET['y']));
			
			?>
			<span class="succes" >Votre clan et vous vous déplacez vers cette position.<br />
			Vous serez arrivés dans <?php echo $temps; ?> minutes<br /></span>
			<?php
		 }
	  }
	  ?>
	  <br />
	  Les clans situés sur cette position:</p>
	  <table> 
	     <tr>
		    <th>Clans</th>
			<th>Actions</th>
		 </tr><?php
	  $req = $bdd->prepare('SELECT pseudo, niveau, id_alliance, id, points_attaque, points_defense FROM joueurs WHERE pos_x=? AND pos_y=? AND pseudo!=\'Les hauts\' AND nb_connexions!=\'0\' AND compte_active!=\'0\' ');
	  $req->execute(array($_GET['x'], $_GET['y']));
	  while($donnees = $req->fetch()){
	     if($donnees['id'] != $_SESSION['id_joueur']){
	     echo '<tr>';
		 echo '<td><a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($donnees['pseudo']) . '" >' . htmlspecialchars($donnees['pseudo']) . '</a></td>';
		 ?>
		    <td>
			   <select onchange="golinks(this.options[this.selectedIndex].value)" >
			      <option value="" selected="selected" ></option>
				  <optgroup label="Actions diplomatiques" >
				     <?php
					 if($donnees['niveau'] == $joueur['niveau'] AND distance_carte($position['pos_x'], $position['pos_y'], $_GET['x'], $_GET['y']) <= (2 * $joueur['facteur_vitesse'])){
					 ?>
				     <option value="combat.php?co=o&amp;id=<?php echo $donnees['id'];?>" >Attaquer</option>
					 <?php
					 }
					 ?>
					 <option value="espion.php?co=o&amp;id=<?php echo $donnees['id'];?>" >Espionner</option>
				  </optgroup>
				  <optgroup label="Actions diverses" >
				     <option value="message.php?co=o&amp;action=ecrire&amp;id=0&amp;destinataire=<?php echo $donnees['pseudo']; ?>" >Envoyer un messager</option>
				  </optgroup>
			   </select>
			</td>
		 <?php
		 echo '</tr>';
		 }
		 }
		 $req -> closeCursor();
	  ?> </table>
   </div>
   <?php
   }
   else{?>
   <div id="centre">
   <h2>La carte</h2>
   <?php
      $req = $bdd->prepare('SELECT facteur_vitesse FROM joueurs WHERE id=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  $vitesse = $req->fetch();
	  $req = $bdd->prepare('SELECT pos_x, pos_y FROM joueurs WHERE id=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  $joueur = $req->fetch();
   ?>
      <p style="text-align:left" >
	  Vous ne pouvez lancer des attaques et des missions d'espionnage<br />
	  à des distances plus élevées que <strong><?php echo ($vitesse['facteur_vitesse'] * 2); ?></strong>
	  </p>
	  <div style="border: 2px solid black;background-color: #9fa040;">
	     <p>
		    <a href="carte.php?co=o&amp;x=<?php echo $joueur['pos_x']; ?>&amp;y=<?php echo $joueur['pos_y']; ?>">
			   Votre position: <?php echo $joueur['pos_x'] . ', ' . $joueur['pos_y']; ?>
			</a>
		 </p>
	  </div>
	  <p id="infos_position1" > , </p>
   <div style="overflow:auto;border: 1px solid black;" id="carte" >
      <img src="images/Carte/monde_<?php echo $_GET['cartex'];?>_<?php echo $_GET['cartey'];?>.png" width="650" height="650" alt="Carte" usemap ="#map_carte" />
	  <map name="map_carte" >
	  <?php for($i=0; $i!=10; $i++){
	           for($j=0; $j!=10; $j++){
			      $coords['x1'] = $j * 65;
		   	      $coords['y1'] = inverser($i) * 65;
			      $coords['x2'] = $coords['x1'] + 65;
			      $coords['y2'] = $coords['y1'] + 65;
				  $x = $j + ($_GET['cartex'] * 10);
				  $y = $i + ($_GET['cartey'] * 10);
		          echo '<area onmouseover="position_souris(this, ' . $x . ', ' . $y . ')" title="' . $x . ', ' . $y . '" shape="rect" coords="' . $coords['x1'] . ', ' . $coords['y1'] . ', ' . $coords['x2'] . ', ' . $coords['y2'] . '" href="carte.php?co=o&amp;x=' . $x . '&amp;y=' . $y . '" />';
		    }
		 }
		 ?>
	  </map>
   </div>
      <p id="infos_position2" > , </p>
	  <hr />
	  <div name="petites_cartes">
	     <h3>Toutes les cartes</h3>
	     <table class="table_invisible">
		    <tr>
			   <td><a href="carte.php?co=o&amp;cartex=0&amp;cartey=1"><img alt="carte_0_1_small" src="images/Carte/Smalls/monde_0_1_small.png"/></a></td>
			   <td><a href="carte.php?co=o&amp;cartex=1&amp;cartey=1"><img alt="carte_1_1_small" src="images/Carte/Smalls/monde_1_1_small.png"/></a></td>
			</tr>
			<tr>
			   <td><a href="carte.php?co=o&amp;cartex=0&amp;cartey=0"><img alt="carte_0_0_small" src="images/Carte/Smalls/monde_0_0_small.png"/></a></td>
			   <td><a href="carte.php?co=o&amp;cartex=1&amp;cartey=0"><img alt="carte_1_0_small" src="images/Carte/Smalls/monde_1_0_small.png"/></a></td>
			</tr>
		 </table>
	  </div>
   </div>
   <?php
   }
   ?>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>
<?php
   $req->closeCursor();
?>