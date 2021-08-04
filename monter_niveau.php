<?php include("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <div id="centre" >
      <h2>Monter d'un niveau</h2>
	  <?php
	     $req = $bdd->prepare('SELECT argent, victoires, niveau, experience, infanterie, assassin, archer FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $joueur = $req->fetch();
		 $joueur['membres_clan_total'] = $joueur['infanterie'] + $joueur['assassin'] + $joueur['archer'];
		 $req = $bdd->prepare('SELECT * FROM niveaux WHERE id=? ');
		 $req->execute(array($joueur['niveau']));
		 $niveau = $req->fetch();
		 
		 if($joueur['membres_clan_total'] >= $niveau['membres_clan_requis'] AND
		 $joueur['experience'] >= $niveau['exp_requis'] AND
		 $joueur['victoires'] >= $niveau['victoires_requises']){ //On peut monter d'un niveau
		    $req = $bdd->prepare('UPDATE joueurs SET experience=\'0\', niveau=?, argent=?, infanterie=?, 
			assassin=?, archer=? WHERE id=? ');
			$req->execute(array(
			($joueur['niveau'] + 1),
			($joueur['argent'] + $niveau['lvlUp_argent_gagne']),
			($joueur['infanterie'] + $niveau['lvlUp_infanterie_gagne']),
			($joueur['assassin'] + $niveau['lvlUp_assassin_gagne']),
			($joueur['archer'] + $niveau['lvlUp_archer_gagne']),
            $_SESSION['id_joueur'] ));
			$_SESSION['niveau'] ++;
	        ?>
			<p class="succes" >
			   Félicitations!<br />
			   Votre clan et vous venez d'augmenter de niveau!<br />
			   Pour l'occasion, vous recevez plusieurs choses gratuitement:<br />
			   <ul>
			      <li>+<?php echo $niveau['lvlUp_argent_gagne']; ?> pièces d'or</li>
				  <li>+<?php echo $niveau['lvlUp_infanterie_gagne']; ?> membres d'infanterie</li>
				  <li>+<?php echo $niveau['lvlUp_assassin_gagne']; ?> assassins</li>
				  <li>+<?php echo $niveau['lvlUp_archer_gagne']; ?> archers</li>
				  <li>+1 niveau</li>
			   </ul>
			</p>
			<?php
		 }
		 else{
		    ?>
			<p class="erreur" >
			Vous n'avez pas encore remplis tous les objectifs!<br />
			Pour monter de niveau, vous devez absolument remplir les objectifs au minimum<br />
			</p>
			<?php
		 }
	  ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>
<?php $req->closeCursor();?>