<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Mon clan</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <div id="centre">
   <?php
   $reponse = $bdd->prepare('SELECT * FROM joueurs WHERE pseudo=?');
   $reponse->execute(array($_SESSION['pseudo']));
   $donnees = $reponse->fetch();
   $reponse = $bdd->prepare('SELECT * FROM niveaux WHERE id=?');
   $reponse->execute(array($donnees['niveau']));
   $donnees_niveau = $reponse->fetch();
   ?>
   <h2>
   <strong><?php echo htmlspecialchars($donnees['pseudo']); ?></strong>,<br />
   meneur de <?php echo htmlspecialchars($donnees['nom_clan']); ?>
   </h2> <!-- Infos sur le niveau -->
   <hr />
   <table>
      <tr>
	     <th>Niveau</th>
	  </tr>
	  <tr>
	     <td>
		   Vous êtes actuellement <em style="color:brown" >niveau <?php echo $donnees['niveau']; ?>
           (Exp: <?php echo $donnees['experience']; ?> / 
           <?php echo $donnees_niveau['exp_requis']; ?>)</em><br />
           Pour monter d'un niveau, vous devez remplir tous les objectifs.<br />
           Seuls, les points d'expériences ne suffisent pas!<br />
           Description du niveau: <br />
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <?php
            $membres_clan_total = $donnees['infanterie'] + $donnees['assassin'] + $donnees['archer'];?>
            <em> <?php echo $donnees_niveau['description']; ?> </em><br />
			<br />
		 </td>
	  </tr>
	  <?php 
      if($donnees['experience'] >= $donnees_niveau['exp_requis'] AND
      $membres_clan_total >= $donnees_niveau['membres_clan_requis'] AND
      $donnees['victoires'] >= $donnees_niveau['victoires_requises']){
      ?>
	  <tr>
	     <td>
            <br />
            <input type="button" name="monter_niveau" onclick=location.href="monter_niveau.php?co=o" value="Monter d'un niveau!" />
	     </td>
	  </tr>
      <?php 
      }
      ?>
   </table>
	   <hr />
   <?php 
   //Actualisation des données
   $reponse = $bdd->prepare('SELECT * FROM joueurs WHERE id=? ');
   $reponse->execute(array($_SESSION['id_joueur']));
   $donnees = $reponse->fetch();
   if($membres_clan_total != 0){
   
   // % infanterie
   $pourcent_infanterie = $donnees['infanterie'];
   $pourcent_infanterie *= 100 / $membres_clan_total;
   $pourcent_infanterie = round($pourcent_infanterie);
   // % assassins
   $pourcent_assassin = $donnees['assassin'];
   $pourcent_assassin *= 100 / $membres_clan_total;
   $pourcent_assassin = round($pourcent_assassin);
   // % archers
   $pourcent_archer = $donnees['archer'];
   $pourcent_archer *= 100 / $membres_clan_total;
   $pourcent_archer = round($pourcent_archer);
   //Infanterie sans arme
   $infanterie_sans_arme = $donnees['infanterie'] - $donnees['armes_infanterie'];
   //Assassin sans arme
   $assassin_sans_arme = $donnees['assassin'] - $donnees['armes_assassin'];
   //Archer sans arme
   $archer_sans_arme = $donnees['archer'] - $donnees['armes_archer'];
   }
   else{
      $pourcent_infanterie = 0;
	  $pourcent_assassin = 0;
	  $pourcent_archer = 0;
   }
   ?>
   
   <table>
      <tr>
	     <th colspan="3">Votre clan - <?php echo htmlspecialchars($donnees['nom_clan']); ?> est constitué de</th>
	  </tr>
	  <tr>
         <td>
		    <?php
			echo $donnees['infanterie'];
		    if($donnees['infanterie'] > 1) {echo ' membres d\'infanterie';} 
	        else{echo ' membre d\'infanterie';}
	        ?>
		 </td>
		 <td>
		    <em>(<em style="color:brown" ><?php echo $pourcent_infanterie?>%</em> de vos troupes)</em>
		 </td>
		 <td>
		    Dont <?php echo $infanterie_sans_arme; ?> sans arme
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <?php
			echo $donnees['assassin'] . ' assassin'; 
            if($donnees['assassin'] > 1){echo 's';}
			?>
		 </td>
		 <td>
		    <em>(<em style="color:brown" ><?php echo $pourcent_assassin?>%</em> de vos troupes)</em>
		 </td>
		 <td>
		    Dont <?php echo $assassin_sans_arme; ?> sans arme
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <?php 
			echo $donnees['archer'] . ' archer'; 
            if($donnees['archer'] > 1){echo 's';}
			?>
		 </td>
		 <td>
		    <em>(<em style="color:brown" ><?php echo $pourcent_archer?>%</em> de vos troupes)</em>
		 </td>
		 <td>
		    Dont <?php echo $archer_sans_arme; ?> sans arme
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <?php echo $donnees['espions']; ?> espions
		 </td>
		 <td colspan="2">
		    <?php echo $donnees['espions_perdus']; ?> espions perdu en mission d'espionnage
		 </td>
	  </tr>
   </table>
   <hr />
   <table>
      <tr>
	     <th>Vos membres de clan vous rapportent</th>
	  </tr>
	  <tr>
	     <td>
		    <?php echo $donnees['points_attaque']; ?> points d'attaque
		 </td>
	  </tr>
      <tr>
	     <td>
		    <?php echo $donnees['points_defense']; ?> points de défense
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <?php echo $donnees['gain_or_jour'] *= $donnees['facteur_economie']; ?> pièces d'or par jour
		 </td>
	  </tr>
   </table>
   <hr />
   
   <?php 
   $combats_total = $donnees['victoires'] + $donnees['defaites'];
   if($combats_total != 0){
   $pourcent_victoires = $donnees['victoires'];
   $pourcent_victoires *= 100 / $combats_total;
   $pourcent_victoires = round($pourcent_victoires);
   }
   ?>
   <table>
      <tr>
	     <th>Combats</th>
	  </tr>
	  <tr>
	     <td>
		    Votre clan s'est incliné <em style="color:brown" ><?php echo $donnees['defaites']; ?></em> fois<br />
	        mais est revenu vainqueur de <em style="color:brown" ><?php echo $donnees['victoires']; ?></em> batailles,<br />
	        <em>soit <em style="color:brown" ><?php if($combats_total != 0){echo $pourcent_victoires;} else {echo '0';} ?>
	        %</em> des combats menés.</em>
		 </td>
	  </tr>
	  <tr>
	     <td>
		    Au total, votre clan a volé <em style="color:brown" >
			<?php echo $donnees['or_vole_total']; ?></em> pièces d'or.
		 </td>
	  </tr>
   </table>
	  <hr />
   <table>
      <tr>
	     <th colspan="2">Mon compte</th>
	  </tr>
	  <tr>
	     <td>
		    <h4>Votre signature</h4>
	        Vous pouvez la changer en tout temps.<br />
	        Vous pouvez mettre un maximum de 100 caractères.
		 </td>
		 <td>
		    <form action="options.php?co=o&amp;changer=signature" method="post" >
            <label>
	           <textarea maxlength="100" name="signature" rows="3" cols="30"><?php echo $donnees['signature']; ?></textarea><br />
	        </label>
	        <label>
	           <input type="submit" value="Changer la signature" ></input>
            </label>
   </form>
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <h4>Votre description RP</h4>
		 </td>
		 <td>
		    <form action="options.php?co=o&amp;changer=description_joueur" method="post" >
            <label>
	           <textarea name="description" rows="10" cols="32"><?php echo $donnees['description_rp']; ?></textarea><br />
	        </label>
	        <label>
	           <input type="submit" value="Changer la description" ></input>
            </label>
   </form>
		 </td>
	  </tr>
	  <tr>
	     <td>
		    <h4>Votre URL</h4>
			<p>
			   Présentez, postez, affichez cet URL n'importe où.<br />
			   Si un joueur s'inscrit à partir de cet URL, vous obtiendrez un élève!<br />
			   Les élèves peuvent vous rapporter des avantages...
			</p>
		 </td>
		 <td>
		    <p>
			   Copiez ce lien:<br />
			   http://www.berceaudeguerres.com?maitre=<?php echo $_SESSION['id_joueur']; ?><br />
			   <br />
			   Ou copiez ce code HTML:<br />
			   <textarea name="pub01"><p><a href="http://www.berceaudeguerres.com/index.php?maitre=<?php echo $_SESSION['id_joueur']; ?>"><img src="http://www.berceaudeguerres.com/images/pubs/pub01.gif" name="Bercea de Guerres" alt="Berceau de Guerres"/></a></p></textarea>
			</p>
		 </td>
	  </tr>
	  <tr>
	     <td colspan="2">
		    <p><a href="http://www.berceaudeguerres.com/index.php?maitre=<?php echo $_SESSION['id_joueur']; ?>"><img src="http://www.berceaudeguerres.com/images/pubs/pub01.gif" name="Bercea de Guerres" alt="Berceau de Guerres"/></a></p>
		 </td>
	  </tr>
	  
   </table>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>