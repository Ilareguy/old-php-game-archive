<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Fiche personnage</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
	  <?php include("includes/banniere.php"); ?>
   <div id="centre" >
	  
   <form action="fiche_joueur.php?co=o&amp;a=rechercher" method="post" >
      <label>Trouver la fiche d'un autre seigneur:<br />
         <input type="text" maxlength="25" name="pseudo" title="Entrez le pseudo d'un autre seigneur" ></input><br />
      </label>
	  <label>
	     <input type="submit" name="Rechercher" value="Rechercher" ></input><br /><br />
	  </label>
   </form>
   
   <?php if(isset($_GET['a'])){
      if($_GET['a'] == 'rechercher'){
	        $reponse = $bdd->prepare('SELECT * FROM joueurs WHERE pseudo_min=?');
            $reponse->execute(array(strtolower($_POST['pseudo'])));
	        $donnees = $reponse->fetch();
	  }
	  }
	  
	  if(!isset($_GET['a'])){
         $reponse = $bdd->prepare('SELECT * FROM joueurs WHERE pseudo_min=?');
         $reponse->execute(array(strtolower($_GET['pseudo'])));
	     $donnees = $reponse->fetch();
	  }?>
	  
	  <?php if($donnees['pseudo'] == NULL){
	  echo '<p>Ce joueur n\'existe pas!</p>';}
	  
	  else{?>
			
   <h2><?php echo htmlspecialchars($donnees['pseudo']);?>,<br /> meneur de <?php echo htmlspecialchars($donnees['nom_clan']);?></h2>
   <div style="border: 2px solid black;background-color: #9fa040;max-height:400px;overflow:auto;">
      <p id="description_joueur" >
         <?php echo $donnees['description_rp'];?>
      </p>
   </div>
   <table id="table_stats_joueur" >

   <?php switch($donnees['profession']){
   case 'Guerrier':?>
      <td colspan="2" ><?php echo htmlspecialchars($donnees['pseudo']);?> est un guerrier</td>
   <?php break;
   
   case 'Marchand':?>
      <td colspan="2" ><?php echo htmlspecialchars($donnees['pseudo']);?> est un marchand</td>
   <?php break;
   
   case 'Forgeron':?>
      <td colspan="2" ><?php echo htmlspecialchars($donnees['pseudo']);?> est forgeron</td>
   <?php break;
   
   case 'Explorateur':?>
      <td colspan="2" ><?php echo htmlspecialchars($donnees['pseudo']);?> est un explorateur</td>
   <?php break;
   
   default:?>
      <td colspan="2" ><?php echo htmlspecialchars($donnees['pseudo']);?> n'a pas de profession pour le moment</td>
      <?php break;
   }?>
	  
	  <?php
	     //On obtien le nom de l'alliance
		 $req = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
		 $req->execute(array($donnees['id_alliance']));
		 $alliance = $req->fetch();
	  ?>
	  <tr>
	     <td style="text-align:left;" >Alliance: <?php if($alliance['nom'] == NULL){echo 'Aucune alliance';} 
		 else{ 
		 ?><a href="fiche_alliance.php?co=o&amp;alliance=<?php echo $alliance['nom']; ?>"><?php echo $alliance['nom']; ?></a>
		 <?php 
		 } ?></td>
	     <td style="text-align:right;" >Niveau: <?php echo $donnees['niveau'];?></td>
	  </tr>
	  
	  <tr>
	     <td style="text-align:left;" >Nombre de victoires: <?php echo $donnees['victoires'];?></td>
		 <td style="text-align:right;" >Nombre de défaites: <?php echo $donnees['defaites'];?></td>
	  </tr>
	  
	  <tr>
	     <td style="text-align:left;" >Pieces d'or volées: <?php echo $donnees['or_vole_total'];?></td>
		 <td style="text-align:right;" >Nombre de missions faites: <?php echo $donnees['missions_faites'];?></td>
	  </tr>
	  
	  <tr>
	     <td style="text-align:left;" >Statut spécial: <?php echo $donnees['statut_special'];?></td>
		 <td style="text-align:right;" >Nombre de messages au forum: <?php echo $donnees['nb_messages_forum'];?></td>
	  </tr>
	  
	  <tr>
	  <?php if(isset($_GET['pseudo'])){ 
	     echo '<td colspan="2" ><a href="message.php?co=o&amp;action=ecrire&amp;id=0&amp;destinataire=' . $_GET['pseudo'] . '" >Envoyer un messager</a></td>';
	  }
	  else{
	     echo '<td colspan="2" ><a href="message.php?co=o&amp;action=ecrire&amp;id=0&amp;destinataire=' . $_POST['pseudo'] . '" >Envoyer un messager</a></td>';
	  }?>
	  </tr>
   </table>
   <?php } ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>
<?php
   $req->closeCursor();
?>