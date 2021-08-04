<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Alliances</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
	  <?php
      include("includes/banniere.php");
      ?>
   <div id="centre" >
   <?php
   $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $donnees = $req->fetch();
      if($donnees['id_alliance'] == 0){ //Si le joueur n'a pas d'alliance
	     echo '<h2>Alliances</h2>'; ?>
		    <p>
			   Vous ne faites actuellement partie d'aucune alliance.<br />
			   Il est possible de rejoindre une alliance en postulant dans celle-ci.<br />
			   Vous pouvez également créer une alliance.<br />
			   <a href="options.php?co=o&amp;action=creer_alliance" >Je veux créer ma propre alliance</a>
			<br /><br /></p>
			<table id="table_alliances" >
			   <caption>Voici une liste des alliances</caption>
			   <tr>
			      <th style="width:50%" >Alliance</th>
				  <th style="width:20%" >Nombre de membres</th>
				  <th>Action</th>
			   </tr>
			   <?php
			      $numero_max = 15;
				  $numero_min = 0;
				  $numero = 0;
				  if(isset($_GET['page'])){
	                 $numero_min = ($_GET['page'] * 15) - 15;
		             $numero_max = ($_GET['page'] * 15);
	              }
			      $req = $bdd->query('SELECT * FROM alliances ORDER BY nb_joueurs DESC');
				  while($donnees = $req->fetch() AND $numero != $numero_max){
		             if($numero < $numero_min){
				        $numero++;
			         }
					 else{
				     echo '<tr>';
				     echo '<td><a href="fiche_alliance.php?co=o&amp;alliance=' . $donnees['nom'] . '" >' . htmlspecialchars($donnees['nom']) . '</a></td>';
					 
					 //On détermine le nombre de joueurs dans cette alliance
					 $req2 = $bdd->prepare('SELECT id FROM joueurs WHERE id_alliance=? ');
					 $req2->execute(array($donnees['id']));
					 echo '<td>' . $donnees['nb_joueurs'] . '</td>';
					 echo '<td><a href="options.php?co=o&amp;action=postuler_alliance&amp;id=' . $donnees['id'] . '" >Postuler dans cette alliance</a></td>';
					 echo '</tr>';
					 $numero++;
					 }
				  }
			   ?>
			</table>
		 <?php
      }
	  else{ //Si le joueur est dans une alliance
	     $req = $bdd->prepare('SELECT * FROM alliances WHERE id=? ');
		 $req->execute(array($donnees['id_alliance']));
		 $alliance = $req->fetch();
	     echo '<h2>' . htmlspecialchars($alliance['nom']) . '</h2>'; ?>
		 <div style="border: 2px solid black;background-color: #9fa040;">
		 <a href="forum_alliance.php?co=o">Aller au forum de mon alliance</a>
		 <?php
		 if($alliance['id_joueur_meneur'] == $_SESSION['id_joueur']){
		    echo '<p><a href="alliance_administration.php?co=o" >Administration de l\'alliance</a></p>';
	     }
		 else{
		    echo '<p><a href="options.php?co=o&amp;action=quitter_alliance" >Je veux quitter mon alliance</a></p>';
		 }
		 if($alliance['id_joueur_meneur'] == $_SESSION['id_joueur'] OR $alliance['id_joueur_maitre_de_guerre'] == $_SESSION['id_joueur']){
		    ?>
			   <p>
			      <a href="relations_alliances.php?co=o">Administrer les pactes avec votre alliance</a>
			   </p>
			<?php
		 }
		 ?>
		 </div>
		 <table id="table_alliances" >
		    <caption>Liste des seigneurs de votre alliance</caption>
			<tr>
			   <th style="width:45%" >Seigneur</th>
			   <th style="width:15%" >Niveau</th>
			   <th>Statut</th>
			</tr>
			<?php
			   $req = $bdd->prepare('SELECT pseudo, id, niveau FROM joueurs WHERE id_alliance=? ORDER BY niveau DESC, victoires DESC, defaites');
			   $req->execute(array($alliance['id']));
			   while($joueur = $req->fetch()){
			      $statut_special = 0;
			      echo '<tr>';
				  echo '<td><a href="fiche_joueur.php?co=o&amp;pseudo=' . $joueur['pseudo'] . '" >' . $joueur['pseudo'] . '</a></td>';
				  echo '<td>' . $joueur['niveau'] . '</td>';
				  
				  echo '<td>';
				  if($alliance['id_joueur_meneur'] == $joueur['id']){
				     echo 'Dirigeant de l\'alliance';
					 $statut_special ++;
				  }
				  if($alliance['id_joueur_porte_parole'] == $joueur['id']){
					 if($statut_special != 0){echo '<br />';}
					 echo 'Porte parole';
					 $statut_special ++;
				  }
				  if($alliance['id_joueur_maitre_de_guerre'] == $joueur['id']){
					 if($statut_special != 0){echo '<br />';}
					 echo 'Maitre de guerre';
					 $statut_special ++;
				  }
				  if($alliance['id_joueur_autre1'] == $joueur['id']){
					 if($statut_special != 0){echo '<br />';}
					 echo $alliance['statut_autre1'];
					 $statut_special ++;
				  }
				  if($alliance['id_joueur_autre2'] == $joueur['id']){
					 if($statut_special != 0){echo '<br />';}
					 echo $alliance['statut_autre2'];
					 $statut_special ++;
				  }
				  if($statut_special == 0){
				  echo 'Membre</td>';
				  }
				  else{echo '</td>';}
				  echo '</tr>';
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