<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Objectifs</title>
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
      <h2>Objectifs</h2>
	  <h5>Débloquez des objectifs en montant de niveau</h5>
	  <p>
	     Remplir des objectifs vous fera gagner des pièces d'or!<br />
	  </p>
	  <?php
	     $req = $bdd->prepare('SELECT experience, niveau, infanterie, assassin, archer, argent, victoires FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
	  ?>
	  <?php
	     if(isset($_GET['terminer'])){
		    $req = $bdd->prepare('SELECT * FROM objectifs WHERE id=? ');
			$req->execute(array($_GET['terminer']));
			$donnees = $req->fetch();
			if($donnees['niveau_requis'] <= $joueur['niveau'] AND
			$donnees['infanterie_requis'] <= $joueur['infanterie'] AND
			$donnees['assassins_requis'] <= $joueur['assassin'] AND
			$donnees['archers_requis'] <= $joueur['archer'] AND
			$donnees['argent_requis'] <= $joueur['argent'] AND
			$donnees['victoires_requises'] <= $joueur['victoires']){
			   $req = $bdd->prepare('SELECT id FROM objectifs_faits WHERE id_objectif=? AND id_joueur=? ');
			   $req->execute(array($_GET['terminer'], $_SESSION['id_joueur']));
			   $fait = $req->fetch();
			   if($fait['id'] != NULL){ //On regarde si l'objectif a déjà été fait
			      ?>
				     <p class="erreur" >
					    Vous avez déjà atteint cet objectif!<br />
					 </p>
				  <?php
			   }
			   else{
			   //On termine l'objectif
			   $experience_total = $joueur['experience'] + $donnees['xp_gagne'];
			   $req = $bdd->prepare('INSERT INTO objectifs_faits (id_joueur, id_objectif)
			   VALUES (:id_joueur, :id_objectif) ');
			   $req->execute(array(
			   'id_joueur' => $_SESSION['id_joueur'],
			   'id_objectif' => $_GET['terminer']));
			   $req = $bdd->prepare('UPDATE joueurs SET argent=?, experience=? WHERE id=? ');
			   $req->execute(array(($donnees['argent_gagne'] + $joueur['argent']),
			   $experience_total,  $_SESSION['id_joueur']));
			   ?>
			      <p class="succes" >
				     Vous avez correctement atteint cet objectif!<br />
					 Votre clan a gagné <?php echo $donnees['xp_gagne'];?> points d'expérience<br />
					 et <?php echo $donnees['argent_gagne']; ?> pièces d'or!<br />
				  </p>
			   <?php
			   }
			}
			else{//On a pas tout ce qu'il faut
			   ?>
			   <p class="erreur" >
			      Vous n'avez pas tout ce qu'il vous faut pour obtenir la récompense de cet objectif!<br />
			   </p>
			   <?php
			}
		 }
		 if(!isset($_GET['niveau'])){
		    if(!isset($_GET['page'])){
		       $req = $bdd->query('SELECT id FROM objectifs');
			}
			else{
			   $req = $bdd->query('SELECT id FROM objectifs ORDER BY id LIMIT ' . (($_GET['page'] * 15) - 15) . ', ' . ($_GET['page'] * 15) . '');
			}
		 }
		 else{
		    if(!isset($_GET['page'])){
		       $req = $bdd->prepare('SELECT id FROM objectifs WHERE niveau_requis=? ');
			   $req->execute(array($_GET['niveau']));
			}
			else{
			   $req = $bdd->prepare('SELECT id FROM objectifs WHERE niveau_requis=? ORDER BY id LIMIT ' . (($_GET['page'] * 15) - 15) . ', ' . ($_GET['page'] * 15) . '');
			   $req->execute(array($_GET['niveau']));
			}
		 }
		 $objectifs = 0;
		 while($compteur = $req->fetch()){
		    $objectifs++;
		 }
		 $pages = ceil(($objectifs / 15));
		 ?>
		 <table>
		    <tr>
			   <th colspan="2" >Objectifs</th>
			</tr>
			<tr>
			   <td>
			      <select name="niveau" onchange="golinks(this.options[this.selectedIndex].value)">
		             <option value="" selected="selected" >Filtrer les niveaux</option>
			         <optgroup label="Niveaux" >
			         <?php
					 if(!isset($_GET['page'])){
			         for($i=0;$i!=($joueur['niveau']+1);$i++){ //20 niveaux maximum incluant le 0
				        ?>
					    <option value="objectifs.php?co=o&amp;niveau=<?php echo $i; ?>" ><?php echo $i; ?></option>
					    <?php
				     }
					 }
					 else{
					    for($i=0;$i!=($joueur['niveau']+1);$i++){ //20 niveaux maximum incluant le 0
				        ?>
					    <option value="objectifs.php?co=o&amp;niveau=<?php echo $i; ?>" ><?php echo $i; ?></option>
					    <?php
				        }
					 }
			      ?>
			</optgroup>
		 </select>
			   </td>
			   <td>
			      <select name="page" onchange="golinks(this.options[this.selectedIndex].value)" >
		             <option value="" selected="selected" >Sauter vers la page...</option>
			      <optgroup label="Pages" >
				     <?php
					 if(!isset($_GET['niveau'])){
				     for($i=1;$i!=($pages + 1);$i++){ //20 niveaux maximum incluant le 0
				        ?>
					    <option value="objectifs.php?co=o&amp;page=<?php echo $i; ?>" ><?php echo $i; ?></option>
					    <?php
				     }
					 }
					 else{
					    for($i=1;$i!=($pages + 1);$i++){ //20 niveaux maximum incluant le 0
				        ?>
					    <option value="objectifs.php?co=o&amp;page=<?php echo $i; ?>&amp;niveau=<?php echo $_GET['niveau']; ?>" ><?php echo $i; ?></option>
					    <?php
				     }
					 }
					 ?>
			      </optgroup>
		          </select>
			   </td>
			</tr>
		 <?php
		 if(!isset($_GET['niveau'])){
		    if(!isset($_GET['page'])){
		       $req = $bdd->query('SELECT * FROM objectifs');
			}
			else{
			   $req = $bdd->query('SELECT * FROM objectifs ORDER BY id LIMIT ' . (($_GET['page'] * 15) - 15) . ', ' . ($_GET['page'] * 15) . '');
			}
		 }
		 else{
		    if(!isset($_GET['page'])){
		       $req = $bdd->prepare('SELECT * FROM objectifs WHERE niveau_requis=? ');
			   $req->execute(array($_GET['niveau']));
			}
			else{
			   $req = $bdd->prepare('SELECT * FROM objectifs WHERE niveau_requis=? ORDER BY id LIMIT ' . (($_GET['page'] * 15) - 15) . ', ' . ($_GET['page'] * 15) . '');
			   $req->execute(array($_GET['niveau']));
			}
		 }
		 while($donnees = $req->fetch()){
		    
		    $req2 = $bdd->prepare('SELECT id FROM objectifs_faits WHERE id_objectif=? AND id_joueur=? ');
			$req2->execute(array($donnees['id'], $_SESSION['id_joueur']));
			$donnees2 = $req2->fetch();
			$req2 = $bdd->prepare('SELECT niveau FROM joueurs WHERE id=? ');
			$req2->execute(array($_SESSION['id_joueur']));
			$niveau = $req2->fetch();
			
		    if($donnees2['id'] == NULL){ //Si NULL, l'objectif n'a pas été fait
			   ?>
			   <tr>
			      <td style="text-align:left" >
				  <div style="max-height:220px;overflow:auto;" >
				     <ins><strong><?php echo $donnees['nom'];?></strong></ins><br />
					 <a href="objectifs.php?co=o&amp;terminer=<?php echo $donnees['id'];?>&amp;niveau=<?php echo $niveau['niveau']; ?>" >Obtenir la recompense</a>
					 <ul>Voici ce qu'il vous faut
					    <li>Être niveau <?php echo $donnees['niveau_requis'];?></li>
						<li><?php echo $donnees['infanterie_requis'];?> membres d'infanterie</li>
						<li><?php echo $donnees['assassins_requis']; ?> assassins</li>
						<li><?php echo $donnees['archers_requis']; ?> archers</li>
						<li><?php echo $donnees['argent_requis']; ?> pièces d'or</li>
						<li><?php echo $donnees['victoires_requises']; ?> victoires</li>
					 </ul>
				  </div>
				  </td>
				  <td style="width:60%;text-align:left;white-space:pre-line;" >
				  <div style="max-height:220px;overflow:auto;" >
				     <?php
					    echo $donnees['description'];
					 ?>
				  </div>
				  </td>
			   </tr>
			   <?php
			}
			else{ //L'objectif a été fait
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