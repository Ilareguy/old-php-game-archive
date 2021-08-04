<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Forum</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <div id="centre" >
   <h2>Forum</h2>
      <table>
   <tr>
      <th style="width:70%" >Forum</th>
	  <th>Dernière réponse</th>
   </tr>
   <?php
      $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  $joueur = $req->fetch();
	  if($joueur['id_alliance'] != 0){
	     ?>
		 <div style="border: 2px solid black;background-color: #9fa040;">
		    <h5>Forum de votre alliance</h5>
			<hr />
		    <p>
			   <a href="forum_alliance.php?co=o">Aller au forum de votre alliance</a>
			</p>
		 </div>
		 <hr />
		 <?php
	  }
      $reponse = $bdd->query('SELECT * FROM forums');
      while($donnees_forum = $reponse->fetch()){
	  ?>
	  <tr>
	     <td style="text-align: left;" >
	        <a href="forum_p.php?co=o&amp;fId=<?php echo $donnees_forum['id']; ?>" ><?php echo $donnees_forum['forum']; ?></a>
	     </td>
		 <td>
		    <?php
			//On prend le dernier message
			$req = $bdd->prepare('SELECT id_emmeteur, date FROM message_forum WHERE forum_id=? AND archive=\'0\' ORDER BY date DESC ');
			$req->execute(array($donnees_forum['id']));
			$dernier = $req->fetch();
			$req = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
			$req->execute(array($dernier['id_emmeteur']));
			$emmeteur = $req->fetch();
			if($dernier['id_emmeteur'] != NULL){
			?>
			   Par <a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $emmeteur['pseudo']; ?>"><?php echo htmlspecialchars($emmeteur['pseudo']); ?></a><br />
			   Le <?php echo $dernier['date'];
			}
			else{
			   ?>
			   Aucun message dans cette section
			   <?php
			}
			?>
		 </td>
	  </tr>
	  <?php
	  }
	  ?>
	  </table>
	  <hr />
	  <h4>Archives du forum</h4>
	  <p>
	     Vous pouvez accéder aux archives du forum <a target="_BLANK" href="archives_forum.php">ici</a><br />
	  </p>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");  ?>
   </body>
</html>