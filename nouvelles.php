<?php include("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Nouvelles</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <div id="centre">
   <h2>Toutes les nouvelles<br /></h2>
      <?php
   $reponse = $bdd->query('SELECT * FROM news ORDER BY id DESC');
   while($donnees = $reponse->fetch()){
      ?>
	  <div class="news">
	     <fieldset>
	        <legend><?php echo $donnees['titre']; ?></legend>
   	        <?php echo $donnees['new'] ?>
            <p class="news_date">Date de publication: <?php echo $donnees['date_de_publication']; ?></p>
	     </fieldset>
	  </div>
      <?php
	}
	?>
	</div>
	<?php
$reponse->closeCursor();
include("includes/menu_jeu.php");
include ("includes/pied_de_page.php"); ?>
   </body>
</html>