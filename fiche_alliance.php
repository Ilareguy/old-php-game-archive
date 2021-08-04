<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Fiche alliance</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
	  <?php include("includes/banniere.php"); ?>
   <div id="centre" >
   
   <div id="fiche_alliance" >
	  
   <form action="fiche_alliance.php?co=o&amp;a=rechercher" method="post" >
      <label>Trouver la fiche d'une autre alliance:<br />
         <input type="text" maxlength="35" name="nom" title="Entrez le nom de l'alliance" ></input><br />
      </label>
	  <label>
	     <input type="submit" name="Rechercher" value="Rechercher" ></input><br /><br />
	  </label>
   </form>
   
   <?php if(isset($_GET['a'])){
      if($_GET['a'] == 'rechercher'){
	        $reponse = $bdd->prepare('SELECT * FROM alliances WHERE nom=? ');
            $reponse->execute(array(stripslashes($_POST['nom'])));
	        $donnees = $reponse->fetch();
	  }
	  }
	  
	  if(!isset($_GET['a'])){
         $reponse = $bdd->prepare('SELECT * FROM alliances WHERE nom=?');
         $reponse->execute(array(stripslashes($_GET['alliance'])));
	     $donnees = $reponse->fetch();
	  }?>
	  
	  <?php if($donnees['nom'] == NULL){
	     echo '<p>Cette alliance n\'existe pas!</p>';
	  }
	  else{
	  ?>	
   <h2><?php echo $donnees['nom'];?></h2>
   
   <?php
   $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $joueur = $req->fetch();
   $req = $bdd->prepare('SELECT id_joueur_meneur, id_joueur_maitre_de_guerre FROM alliances WHERE id=? ');
   $req->execute(array($joueur['id_alliance']));
   $alliance = $req->fetch();
   if(($alliance['id_joueur_meneur'] == $_SESSION['id_joueur'] 
   OR $alliance['id_joueur_maitre_de_guerre'] == $_SESSION['id_joueur']) 
   AND ($joueur['id_alliance'] != $donnees['id'])){
      ?>
      <p>
	     Puisque vous en avez le pouvoir, vous pouvez faire une demande de pacte à cette alliance<br />
	  </p>
	  <a href="relations_alliances.php?co=o&amp;a=demande_pacte&amp;id_alliance=<?php echo $donnees['id']; ?>">Faire une demande de pacte</a>
	  <hr />
	  <?php
   }
   ?>
   
   <div style="border: 2px solid black;background-color: #9fa040;">
   <p id="description_alliance" ><?php if($donnees['description'] != NULL){
      echo htmlspecialchars($donnees['description']);
   } 
   else{
      echo 'Cette alliance n\'a toujours pas de description';
   }?>
   </p>
   </div>
   <table id="table_stats_alliance" >
   </table>
   
   <table>
      <caption>Voici tous les joueurs qui sont présents dans l'alliance</caption>
	  <tr>
	     <th>Joueur</th>
		 <th>Niveau</th>
		 <th>Statut</th>
	  </tr>
	  <?php
	     //On prend les infos des joueurs de l'alliance
		 $req = $bdd->prepare('SELECT pseudo, niveau, id FROM joueurs WHERE id_alliance=? ORDER BY niveau DESC');
		 $req->execute(array($donnees['id']));
		 while($joueur = $req->fetch()){
		    echo '<tr>';
			echo '<td><a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($joueur['pseudo']) . '">' . htmlspecialchars($joueur['pseudo']) . '</a></td>';
			echo '<td>' . $joueur['niveau'] . '</td>';
			if($joueur['id'] == $donnees['id_joueur_meneur']){$statut = 'Dirigeant';}
			else if($joueur['id'] == $donnees['id_joueur_porte_parole']){$statut = 'Porte parole';}
			else if($joueur['id'] == $donnees['id_joueur_maitre_de_guerre']){$statut = 'Maitre de guerre';}
			else if($joueur['id'] == $donnees['id_joueur_autre1']){$statut = htmlspecialchars($donnees['statut_autre1']);}
			else if($joueur['id'] == $donnees['id_joueur_autre2']){$statut = htmlspecialchars($donnees['statut_autre2']);}
			else{$statut = 'Membre';}
			echo '<td>' . $statut . '</td>';
			echo '<tr>';
		 }
	  ?>
   </table>
   <hr />
   <p>
      Liste de pactes signés en rapport avec cette alliance
   </p>
   <table>
      <tr>
	     <th>Alliance</th>
		 <th>Type de pacte</th>
	  </tr>
      <?php
	  //Liste des pactes
	  $req = $bdd->prepare('SELECT * FROM pactes WHERE id_alliance_1=? OR id_alliance_2=? ');
	  $req->execute(array($donnees['id'], $donnees['id']));
	  while($pactes = $req->fetch()){
	  ?>
      <tr>
	     <?php
	     $req2 = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
		 if($pactes['id_alliance_1'] != $donnees['id']){
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
	  </tr>
	  <?php } ?>
   </table>
   <?php } ?>
   </div>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>