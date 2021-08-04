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
      <div id="centre">
	     <?php
		 switch($_GET['code']){
		    case 0:
			   //Pas le statut de testeur (pendant les tests)
			   ?>
			   <h2>Tests en cours</h2>
			   <p>
			      <br />
				  Le jeu est en train d'être testé.<br />
				  Puisque vous n'êtes pas testeur, vous devez attendre la fin des tests. 
				  Visitez la page d'accueil régulièrement pour rester au courant de l'avancement !
			   </p>
			   <div style="border: 2px solid black;background-color: #9fa040;">
			      <p>
				     Appuyez <a href="index.php">ici</a> pour revenir vers la page d'accueil.<br />
				  </p>
			   </div>
			   <?php
			break;
			case 1:
			   //Compte pas activé
			   ?>
			   <h2>Confirmation du compte</h2>
			   <p>
			      <br />
			      Pour vous connecter à Berceau de Guerres, vous devez avoir activé votre compte. 
				  Lors de votre inscription, vous avez reçu un email de confirmation d'inscription 
				  dans lequel se trouvait un lien de confirmation. Pour terminer votre inscription, vous 
				  devez appuyer sur ce lien !<br />
			   </p>
			   <div style="border: 2px solid black;background-color: #9fa040;">
			      <p>
				     Appuyez <a href="index.php">ici</a> pour revenir vers la page de connexion !<br />
				  </p>
			   </div>
			   <?php
			break;
			case 2:
			   //Mot de passe incorrect
			   ?>
			   <h2>Identifiants incorrects</h2>
			   <p>
			      <br />
				  Nous n'avons pu faire correspondre le mot de passe que vous avez entré avec 
				  le mot de passe du compte. Il est également possible que vous aillez fait 
				  une faute de frappe.<br />
			   </p>
			   <div style="border: 2px solid black;background-color: #9fa040;">
			      <p>
				     Appuyez <a href="index.php">ici</a> pour revenir vers la page de connexion !<br />
				  </p>
			   </div>
			   <?php
			break;
		 }
		 ?>
	  </div>
	  <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>