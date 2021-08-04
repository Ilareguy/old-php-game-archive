<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Connectés</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   
   <body>
      <?php include("includes/banniere.php"); ?>
   <div id="centre" >
      <?php
	  $timestamp_min = time() - 300;
	  ?>
      <h2>Liste des connectés</h2>
	  <p>
	     Voici une liste de tous les clans qui sont connectés en ce moment<br />
		 Si vous ne faites aucune action en 5 minutes, vous êtes automatiquement déconnecté.<br />
	  </p>
	  <table>
	     <tr>
		    <th>Pseudo</th>
			<th>Nom du clan</th>
			<th>Alliance</th>
		 </tr>
		 <?php
		 $req = $bdd->prepare('SELECT pseudo, nom_clan, id_alliance FROM joueurs WHERE timestamp_derniere_action > ? ');
		 $req->execute(array($timestamp_min));
		 while($donnees = $req->fetch()){
		    $req2 = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
			$req2->execute(array($donnees['id_alliance']));
			$alliance = $req2->fetch();
		    ?>
			<tr>
			   <td>
			      <a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a>
			   </td>
			   <td>
			      <?php echo $donnees['nom_clan']; ?>
			   </td>
			   <td>
			      <?php
				  if($donnees['id_alliance'] == 0){
				     echo 'Aucune alliance';
				  }
				  else{
				     ?>
			         <a href="fiche_alliance.php?co=o&amp;alliance=<?php echo $alliance['nom']; ?>"><?php echo $alliance['nom']; ?></a>
				     <?php
				  }
				  ?>
			   </td>
			</tr>
			<?php
		 }
		 ?>
	  </table>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>