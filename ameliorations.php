<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Améliorations</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
	   <script language="JavaScript" >
	   function golinks(where)
	   {self.location = where;}
	   </script>
   </head>
   <body>
	  <?php include("includes/banniere.php"); ?>
   <div id="centre" >
      <h2>Améliorations</h2>
	  <h5>Débloquez plus d'améliorations en montant de niveau</h5>
	  <?php
	     if(isset($_GET['ameliorer'])){
		    $reponse = $bdd->prepare('SELECT * FROM ameliorations WHERE id=? ');
			$reponse->execute(array($_GET['ameliorer']));
			$donnees = $reponse->fetch();
			$reponse = $bdd->prepare('SELECT argent, facteur_combat, facteur_economie, facteur_vitesse, facteur_furtif FROM joueurs WHERE id=? ');
			$reponse->execute(array($_SESSION['id_joueur']));
			$joueur = $reponse->fetch();
			if($donnees['niveau_requis'] > $_SESSION['niveau']){header('Location: index.php');}
			else if($donnees['id_profession_requise'] != $_SESSION['id_profession']){header('Location: index.php');}
			else if($joueur['argent'] < $donnees['prix']){echo '<p class="erreur" >Vous n\'avez pas les moyens de financer cette amélioration...</p>';}
			else{
			   $nouvelles_donnees['facteur_combat'] = $donnees['facteur_combat_add'] + $joueur['facteur_combat'];
			   $nouvelles_donnees['facteur_economie'] = $donnees['facteur_economie_add'] + $joueur['facteur_economie'];
			   $nouvelles_donnees['facteur_vitesse'] = $donnees['facteur_vitesse_add'] + $joueur['facteur_vitesse'];
			   $nouvelles_donnees['facteur_furtif'] = $donnees['facteur_furtif_add'] + $joueur['facteur_furtif'];
			   $nouvelles_donnees['argent'] = $joueur['argent'] - $donnees['prix'];
			   //On met l'argent et les stats du joueur à jour
			   $req = $bdd->prepare('UPDATE joueurs SET argent=?, facteur_combat=?, facteur_economie=?, facteur_vitesse=?, facteur_furtif=? WHERE id=? ');
			   $req->execute(array(
			      $nouvelles_donnees['argent'],
			      $nouvelles_donnees['facteur_combat'],
				  $nouvelles_donnees['facteur_economie'],
				  $nouvelles_donnees['facteur_vitesse'],
				  $nouvelles_donnees['facteur_furtif'],
				  $_SESSION['id_joueur']
			   ));
			   echo '<p class="succes" >L\'amélioration a été achevée avec succès!';
			   if($donnees['facteur_combat_add'] != 0){echo '<br />Votre facteur combat a augmenté de ' . $donnees['facteur_combat_add'];}
			   if($donnees['facteur_economie_add'] != 0){echo '<br />Votre facteur économie a augmenté de ' . $donnees['facteur_economie_add'];}
			   if($donnees['facteur_vitesse_add'] != 0){echo '<br />Votre facteur vitesse a augmenté de ' . $donnees['facteur_vitesse_add'];}
			   if($donnees['facteur_furtif_add'] != 0){echo '<br />Votre facteur furtif a augmenté de ' . $donnees['facteur_furtif_add'];}
			   echo '</p>';
			}
		 }
	  ?>
	        <table>
			   
			   <tr>
				   <th colspan="2" >
			          Liste des améliorations disponibles pour votre clan
				   </th>
			   </tr>
			   <tr>
				  <td colspan="2" >
				     <?php
					 //On détermine le nombre de page
					 $req = $bdd->prepare('SELECT id FROM ameliorations WHERE niveau_requis<=? AND id_profession_requise=?');
					 $req->execute(array($_SESSION['niveau'], $_SESSION['id_profession']));
					 $nb_ameliorations = 0;
					 while($nb = $req->fetch()){
					    $nb_ameliorations++;
					 }
					 $pages = $nb_ameliorations / 15;
					 $pages = ceil($nb_ameliorations);
					 ?>
					 <select name="page" onchange="golinks(this.options[this.selectedIndex].value)" >
					    <option value="" selected="selected" >Aller à la page</option>
					    <optgroup label="Pages" >
						   <?php
						   for($i=1;$i!=$pages;$i++){
						   ?>
						   <option value="ameliorations.php?co=o&amp;page=<?php echo $i; ?>" ><?php echo $i; ?></option>
						   <?php
						   }
						   ?>
						</optgroup>
					 </select>
				  </td>
			   </tr>
            <?php
			$numero_max = 15;
			$numero_min = 0;
			$numero = 0;
			if(isset($_GET['page'])){
               $numero_min = ($_GET['page'] * 15) - 15;
	           $numero_max = ($_GET['page'] * 15);
            }
            $reponse = $bdd->prepare('SELECT * FROM ameliorations WHERE id_profession_requise=? AND niveau_requis <= ? ');
			$reponse->execute(array($_SESSION['id_profession'], $_SESSION['niveau']));
	        while($donnees = $reponse->fetch() AND $numero != $numero_max){
			if($numero < $numero_min){
	           $numero++;
	        }
			else{
			   ?>
			   <tr>
			      <td style="text-align:left" >
				  <div style="max-height:170px;overflow:auto;" >
				     <ins><?php echo $donnees['nom']; ?>, niveau <?php echo $donnees['niveau_requis']; ?></ins><br />
					 <a href="ameliorations.php?co=o&amp;ameliorer=<?php echo $donnees['id']; ?>" >Payer cette amélioration</a><br />
					 Cette amélioration vous procure:
					 <ul>
					    <li>+<?php echo $donnees['facteur_combat_add']; ?> au facteur combat</li>
						<li>+<?php echo $donnees['facteur_economie_add']; ?> au facteur économie</li>
						<li>+<?php echo $donnees['facteur_vitesse_add']; ?> au facteur vitesse</li>
						<li>+<?php echo $donnees['facteur_furtif_add']; ?> au facteur furtif</li>
					 </ul>
			      </div>
				  </td>
				  <td>
				  <div style="max-height:170px;overflow:auto;" >
				     <strong>Cette amélioration coûte <?php echo $donnees['prix']; ?> pièces d'or</strong><br />
					 <br />
					 <?php echo $donnees['description']; ?>
				  </div>
				  </td>
			   </tr>
			   <?php
			   $numero++;
			   }
			   }
            ?>
			   <tfoot>
			      <tr>
				     <th colspan="2" >
					 </th>
			      </tr>
			   </tfoot>
			</table>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>
<?php
$req->closeCursor();
$reponse->closeCursor();
?>