<?php 
   try
{
	$bdd = new PDO('mysql:host=localhost;dbname=totoila_berceaud', 'totoila_berceaud', 'n9DTlUgIoAzm');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>
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
   <div id="centre" >
      <?php
	     $req = $bdd->prepare('SELECT compte_active, cle_confirmation FROM joueurs WHERE pseudo=? ');
		 $req->execute(array($_GET['pseudo']));
		 $donnees = $req->fetch();
		 if($donnees['compte_active'] == 0){
		    if($donnees['cle_confirmation'] == urldecode($_GET['cle'])){
			   $req = $bdd->prepare('UPDATE joueurs SET compte_active=\'1\' WHERE pseudo=? ');
			   $req->execute(array($_GET['pseudo']));
			   ?>
			      <p class="succes" >
				     Votre compte a bien été activé!<br />
					 Vous pouvez désormais vous connecter depuis <a href="index.php" >cette page</a>
				  </p>
			   <?php
		    }
		    else{
			   ?>
			      <p class="erreur" >
				     La clé de confirmation que vous avez n'est pas valide.<br />
					 Contactez les webmasters pour plus d'aide!<br />
					 <a href="mailto:support@berceaudeguerres.com" >support@berceaudeguerres.com</a>
				  </p>
			   <?php
		    }
		 }
		 else{
		    ?>
			   <p class="erreur" >
			      Votre compte a déjà été activé!<br />
				  Vous pouvez vous connecter depuis <a href="index.php" >cette page</a>.
			   </p>
			<?php
		 }
	  ?>
   </div>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>