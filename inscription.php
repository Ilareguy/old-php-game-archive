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
       <title>Berceau de Guerres - Inscription</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php
   $maitre = "";
   if(isset($_GET['maitre'])){
      $req = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
	  $req->execute(array($_GET['maitre']));
	  $joueur = $req->fetch();
	  $maitre = $joueur['pseudo'];
   }
   ?>
   <?php include ("includes/banniere.php"); ?>
   <?php include("includes/menu_accueil.php"); ?>
   <div id="centre">
   
   <table id="table_inscription" >
   <thead>
   <tr>
      <th>Formulaire</th>
	  <th>Informations</th>
   </tr>
   </thead>
   <tbody>
   <tr>
      <td style="width:63%;border:none;" >
      <form action="terminer_inscription.php" method="post" >
	  <fieldset>
	  <legend style="color:green" >Inscription</legend>
         <p id="formulaire_inscription">
         <label title="Caractères autorisés: A à Z, 0 à 9, underscore, tirait et le point" >Quel sera votre pseudo?<br />
            <input type="text" name="pseudo" maxlength="25" /><br />
         </label><br />
   
         <label>Votre clan doit avoir un nom bien à lui.<br />
		 Quel sera-t-il?<br />
            <input type="text" name="nom_clan" maxlength="20"/><br />
         </label><br />
   
         <label>Choisissez-vous une profession.<br />
            Celle-ci sera votre profesion définitive jusqu'à son amélioration!<br />
            <select name="profession" >
			<option value="" selected="selected"> </option>
			<optgroup label="Professions" >
            <?php 
	           $req = $bdd->query('SELECT nom FROM professions WHERE niveau_requis=0 AND id_profession_mere=0 ');
		       while($donnees = $req->fetch()){
		          echo '<option value="' . $donnees['nom'] . '">' . $donnees['nom'] . '</option>';
	   	    }
	        ?>
			</optgroup>
            </select>
         </label><br /><br />

         <label>Quel sera votre mot de passe?<br />
            <input type="password" name="mdp" maxlength="20" /><br /><br />
         </label>
         <label>Confirmez votre mot de passe...<br />
            <input type="password" name="confirmer_mdp" maxlength="20" /><br /><br />
         </label>
		 
		 <label>Votre adresse électronique<br />
		 Pour l'inscription<br />
		    <input type="text" name="email" /><br /><br />
		 </label>
   
         <label>Si c'est un autre joueur qui vous a montré le jeu, inscrivez son pseudo ici<br />
		    <input type="text" maxlength="20" value="<?php echo $maitre; ?>" name="maitre"/><br />
		 </label>
		 <hr />
         <label>En terminant votre inscription, vous confirmez avoir lu et être en accord avec les <a href="Conditions.txt">Conditions de Jeu</a><br />
            <input type="submit" name="boutonEnvoyer" value="Terminer mon inscription" /><br />
         </label>
	  </fieldset>
      </form>
	  </td>
	  <td style="background-color:#9fa040" >
	     <div style="overflow:auto;height:500px;">
	        <h3>Quelques informations...</h3>
	        <p>Choisir une profession est une décision définitive jusqu'au moment où il sera temps de 
	        choisir une profession évoluée.<br />
	        Une fois que vous serez arrivés au niveau 10, vous serez amenés à choisir une nouvelle profession
	        qui descend de celle du premier niveau que vous choisissez maintenant.<br />
	        Pour chaque profession du premier niveau viendra deux professions. Ces deux professions
	        sont les professions qui descendent du premier niveau.<br /><br />
	        Les facteurs sont vues comme des pourcentages. Le facteur économie, par exemple, représente
	        à quel points le clan peut se faire de l'argent.<br />
	        Si le clan a un gain d'or quotidien de 10 pièces d'or avec un facteur économie de 1.5, il gagnera ainsi 
	        15 pièces d'or par jour (10 * 1.5 = 15).<br />
	        Le facteur combat représente la puissance défensive et offensive du clan.</p>
	        <hr />
	        <p><em>Choisissez bien votre profession!</em></p>
	       <?php
		    $req = $bdd->query('SELECT * FROM professions WHERE niveau_requis=0 AND id_profession_mere=0 ');
			while($donnees = $req->fetch()){
			   echo '<h4>' . $donnees['nom'] . '</h4>';
			   echo '<p>' . $donnees['description'] . '<p><hr />';
			}
		 ?>
	     </div>
	  </td>
   </tr>
   </tbody>
   </table>
   
   </div>
   
   <?php include("includes/pied_de_page.php");?>
   </body>
   </html>
   <?php
   $req->closeCursor();
   ?>