<?php
//Test de connexion à la base de données
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
       <title>Berceau de Guerres - Archives du forum</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <?php include("includes/menu_accueil.php"); ?>
   <div id="centre" >
   <h2>Archives du forum</h2>
   <p>
      Les messages de cette section du site sont archivés et personne ne peut y répondre<br />
   </p>
   <ul>
   <?php
      $reponse = $bdd->query('SELECT * FROM forums');
      while($donnees_forum = $reponse->fetch()){
	  ?>
	     <li>
		   <a href="archives_forum_p.php?fId=<?php echo $donnees_forum['id']; ?>"><?php echo $donnees_forum['forum']; ?></a>
		 </li>
	  <?php
	  }
	  ?>
   </ul>
   </div>
   <?php include("includes/pied_de_page.php");  ?>
   </body>
</html>