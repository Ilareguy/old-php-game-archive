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
   <?php include("includes/menu_accueil.php"); ?>
   <!-- Centre -->
   <div id="centre" >
   <p>
      Oyé, oyé!<br />
	  Soyez le bienvenue sur les terres de <strong>Berceau de Guerres</strong>!<br />
	  Que voulez-vous faire plus tard? Combattre l'ennemis jusqu'à votre dernier souffle?<br />
	  Ou simplement marchander pour devenir le plus riche!<br />
	  L'or ne vous intéresse-t-elle pas? Peu-être aimeriez-vous mieux devenir un grand explorateur...<br />
	  <br />
	  Toutes ces possibilités sont les bienvenues sur cette terre qui est vôtre.<br />
	  Faites-vous des alliés par le biais d'une alliance.<br />
	  Faites-vous des ennemis par la guerre!<br />
	  <br />
	  <ins><a name="inscription" title="S'inscrire!" href="inscription.php" >Joignez-vous</a> à ce monde qu'est le Berceau de Guerres!</ins><br />
	  <br />
   </p>
   
<form action="jeu_accueil.php?co=n" method="post">
<p id="case_connexion">
<strong>Entrez vos identifiants<br /></strong>
   <?php
   if(!isset($_COOKIE['BG_pseudo'])){
   ?>
   <label>Pseudo: <input type="text" name="pseudo" maxlength="25" /></label><br />
   <?php
   }
   else{
   ?>
   <label>Pseudo: <input type="text" name="pseudo" maxlength="25" value="<?php echo $_COOKIE['BG_pseudo']; ?>" /></label><br />
   <?php
   }
   
   if(!isset($_COOKIE['BG_mot_de_passe'])){
   ?>
   <label>Mot de passe: <input type="password" name="mdp" maxlength="20" /></label><br />
   <?php
   }
   else{
   ?>
   <label>Mot de passe: <input type="password" name="mdp" maxlength="20" value="<?php echo $_COOKIE['BG_mot_de_passe']; ?>" /></label><br />
   <?php
   }
   ?>
   <label>Se souvenir de mes identifiants:<input type="checkbox" name="retenir" /></label><br />
   <input type="submit" value="Se connecter" />
</p>
</form>
   </div>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>