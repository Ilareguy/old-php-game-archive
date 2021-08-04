<?php
//Cookies
if(isset($_POST['retenir'])){
   setcookie('BG_pseudo', $_POST['pseudo'], (time() + 5259487));
   setcookie('BG_mot_de_passe', $_POST['mdp'], (time() + 5259487));
}
// On démarre la session AVANT d'écrire du code HTML
session_start();
//Test de connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=totoila_berceaud', 'totoila_berceaud', 'n9DTlUgIoAzm');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
 
if ($_GET['co'] == 'n' AND !empty($_POST['pseudo']) AND ($_POST['mdp'])){
$_SESSION['pseudo'] = $_POST['pseudo'];
//Vérification du mot de passe
$reponse = $bdd->prepare('SELECT mot_de_passe FROM joueurs WHERE pseudo=?');
$reponse->execute(array($_SESSION['pseudo']));
$mdp = $reponse->fetch();
if ($_POST['mdp'] == $mdp['mot_de_passe']){
$reponse = $bdd->prepare('SELECT compte_active, profession, id, places_inventaire,
pseudo_min, statut_special, niveau, nb_connexions, IP_inscription, IP_premiere_connexion,
 IP_derniere_connexion FROM joueurs WHERE pseudo=?');
$reponse->execute(array($_SESSION['pseudo']));
$donnees = $reponse->fetch();

if($donnees['compte_active'] != 1){
   header('Location: erreur.php?code=1');
}

$req = $bdd->prepare('SELECT id FROM magasins WHERE id_joueur=? ');
$req->execute(array($donnees['id']));
$donnees_magasin = $req->fetch();

//variables $_SESSION
//$_SESSION['pseudo'] = $_POST['pseudo']; (plus haut)
//$_SESSION['connecte'] = true; (plus bas)
$_SESSION['profession'] = $donnees['profession'];
$_SESSION['id_magasin'] = $donnees_magasin['id'];
$_SESSION['places_inventaire'] = $donnees['places_inventaire'];
$_SESSION['id_joueur'] = $donnees['id'];
$_SESSION['pseudo_min'] = $donnees['pseudo_min'];
$_SESSION['statut_special'] = $donnees['statut_special'];
//$_SESSION['id_profession'] = $profession['id']
$_SESSION['niveau'] = $donnees['niveau'];

$req_profession = $bdd->prepare('SELECT id FROM professions WHERE nom=? ');
$req_profession->execute(array($_SESSION['profession']));
$profession = $req_profession->fetch();
$_SESSION['id_profession'] = $profession['id'];

   //Mise à jour du timestamp_derniere_action
   $req = $bdd->prepare('UPDATE joueurs SET timestamp_derniere_action=? WHERE id=? ');
   $req->execute(array(time(), $_SESSION['id_joueur']));

//Si c'est la première connexion
if($donnees['nb_connexions'] == 0){
   //Position aléatoire
   $x = rand(0, 7);
   $y = rand(0, 2);
   $req = $bdd->prepare('UPDATE joueurs SET pos_x=?, pos_y=? WHERE id=? ');
   $req->execute(array($x, $y, $_SESSION['id_joueur']));
   $req = $bdd->prepare('UPDATE joueurs SET nb_connexions=\'1\' WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $req = $bdd->prepare('UPDATE joueurs SET timestamp_premiere_connexion=? WHERE id=? ');
   $req->execute(array(time(), $_SESSION['id_joueur']));
   if($donnees['IP_inscription'] == ""){
      $req = $bdd->prepare('UPDATE joueurs SET IP_inscription=? WHERE id=? ');
      $req->execute(array($_SERVER['REMOTE_ADDR'], $_SESSION['id_joueur']));
   }
   $req = $bdd->prepare('UPDATE joueurs SET IP_premiere_connexion=? WHERE id=? ');
   $req->execute(array($_SERVER['REMOTE_ADDR'], $_SESSION['id_joueur']));
}
else{
   //Mise à jour du nombre de connexions
   $nb_connexions = $donnees['nb_connexions'];
   $nb_connexions += 1;
   $reponse = $bdd->prepare('UPDATE joueurs SET nb_connexions=? WHERE id=?');
   $reponse->execute(array($nb_connexions, $_SESSION['id_joueur']));
   $reponse = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=?');
   $reponse->execute(array($_SESSION['id_joueur']));
   $donnees = $reponse->fetch();
}

//On ajoute l'IP de connexion dans la base de données
$req = $bdd->prepare('SELECT id, repetition FROM ip_connexions WHERE id_joueur=? AND ip=? ');
$req->execute(array($_SESSION['id_joueur'], $_SERVER['REMOTE_ADDR']));
$ip = $req->fetch();
if($ip['id'] == NULL){
   //S'il ne s'est jamais connecté sous cet IP
   $req = $bdd->prepare('INSERT INTO ip_connexions (id_joueur, ip, repetition)
   VALUES (:id_joueur, :ip, \'1\' )' );
   $req->execute(array(
   'id_joueur' => $_SESSION['id_joueur'],
   'ip' => $_SERVER['REMOTE_ADDR']));
}
else{
   $req = $bdd->prepare('UPDATE ip_connexions SET repetition=? WHERE id=? ');
   $req->execute(array(($ip['repetition']+1), $ip['id']));
}

$req = $bdd->prepare('UPDATE joueurs SET IP_derniere_connexion=? WHERE id=? ');
$req->execute(array($_SERVER['REMOTE_ADDR'], $_SESSION['id_joueur']));
//On reprend les informations du joueur pour qu'ils soient à jour (Important surtout lors de la première connexion)
$reponse = $bdd->prepare('SELECT argent, timestamp_premiere_connexion, gains_or_recus, gain_or_jour, facteur_economie
 FROM joueurs WHERE id=?');
$reponse->execute(array($_SESSION['id_joueur']));
$donnees = $reponse->fetch();
//Gain de l'argent depuis la dernière connexion
$argent = $donnees['argent'];
$timestamp_premiere_connexion = $donnees['timestamp_premiere_connexion'];
$nouveau_timestamp = time();
$difference_timestamp = $nouveau_timestamp - $timestamp_premiere_connexion;
$gains_or_recus = $donnees['gains_or_recus'];
$gain = $difference_timestamp / 86400; //86 400 secondes, soit une journée
$gain = round($gain);
$gain = $gain - $gains_or_recus;
$nouveau_gains_or_recus = $gain + $donnees['gains_or_recus'];
$reponse = $bdd->prepare('UPDATE joueurs SET gains_or_recus=? WHERE id=?'); //On met le nouveau nombre de gains d'or recus
$reponse->execute(array($nouveau_gains_or_recus, $_SESSION['id_joueur']));
$gain = $gain * $donnees['gain_or_jour']; //Nombre de gains * gain par jour
$argent += ($gain *= $donnees['facteur_economie']);
$reponse = $bdd->prepare('UPDATE joueurs SET argent=? WHERE id=?');
$reponse->execute(array($argent, $_SESSION['id_joueur']));

//Mise à jour de la date de la dernière connexion {
   $nouvelle_date_derniere_connexion = date('Y-m-d H:i');
   $reponse = $bdd->prepare('UPDATE joueurs SET date_derniere_connexion=? WHERE id=?');
   $reponse->execute(array($nouvelle_date_derniere_connexion, $_SESSION['id_joueur']));
//                                                }

$_SESSION['connecte'] = true;
}
else{
   header('Location: erreur.php?code=2');
}
}
else if($_GET['co'] == 'o' AND $_SESSION['connecte'] == true){
   //Si il n'y a eu aucune action depuis 10 minutes
//{
$req = $bdd->prepare('SELECT timestamp_derniere_action FROM joueurs WHERE id=?');
$req->execute(array($_SESSION['id_joueur']));
$joueur = $req->fetch();
$timestamp_min = time() - 600;//600 secondes, soit 10 minutes
if($joueur['timestamp_derniere_action'] < $timestamp_min){
   //Signifie qu'on a rien fait depuis 10 minutes
   session_destroy();
   ?>
   <script language="JavaScript">
      self.location="index.php";
   </script>
   <?php
}
else{
   $req = $bdd->prepare('UPDATE joueurs SET timestamp_derniere_action=? WHERE id=? ');
   $req->execute(array(time(), $_SESSION['id_joueur']));
}
//}
}
else if($_GET['co'] == 'd' AND $_SESSION['connecte'] == true){
   $req = $bdd->prepare('UPDATE joueurs SET timestamp_derniere_action=? WHERE id=? ');
   $req->execute(array(0, $_SESSION['id_joueur']));
   session_destroy();
   header('Location: index.php');
}
else{
   header('Location: index.php');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Général</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
   <?php include("includes/banniere.php"); ?>
   <?php include("includes/menu_jeu.php"); ?>
   <div id="centre" >
      <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['pseudo']);?></h2>
   <hr />
   <?php
   //*Publicités aléatoires
   switch(rand(0, 1)){
      case 0:
	     ?>
		 <h5>Le cap des 50 joueurs est presque atteint!</h5>
         <p style="font-size:85%;text-align:left">
	        Avec le nouveau système qui vous permet de toucher des pièces d'or lorsque vous montrez 
	        <strong>Berceau de Guerres</strong> à des proches, il y a moyen de devenir un peu plus riche facilement.<br />
	        <strong>Note:</strong> Les gains d'or touchés sont de plus en plus grands, selon nombre nombre de joueurs à qui vous avez montré le jeu.<br />
	        Plus vous avez d'élèves, plus vous obtiendrez d'or en montrant le jeu à des proches!<br />
	        Vous pouvez trouver votre propre lien de publicité sur votre page Mon Compte.
         </p>
		 <?php
	  break;
      case 1:
	     ?>
		 <h5>Un poste pour vous?</h5>
		 <p style="font-size:85%;text-align:left">
		    Vous voudriez avoir un poste parmi l'équipe?<br />
			Prochainement, j'aurai besoin de joueurs pour aider à quelques tâches
			pour le jeu. Entre autre, <strong>Berceau de Guerres</strong> aura besoin de modérateurs pour le forum, dès 
			qu'il sera plus animé! D'ailleurs, j'aurai également besoin d'une équipe d'animateurs.<br />
			En tant que membre de l'équipe, vous aurez accès à des sections du site tel qu'un forum bien à votre groupe!
		 </p>
		 <?php
	  break;
   }
   //*/
   //Message de l'alliance
      $reponse = $bdd->prepare('SELECT nb_connexions, id_alliance, id_derniere_nouvelle_lue FROM joueurs WHERE id=?');
      $reponse->execute(array($_SESSION['id_joueur']));
      $joueur = $reponse->fetch();
	  ?>
	  <hr />
	     <p><a href="nouvelles.php?co=o" >Voir toutes les nouvelles</a><br /></p>
	  <!-- Afficher la dernière nouvelle -->
   <?php
      $reponse = $bdd->query('SELECT * FROM news ORDER BY id DESC');
      $donnees = $reponse->fetch();
	  
	  if($joueur['id_derniere_nouvelle_lue'] < $donnees['id']){
	     $req = $bdd->prepare('UPDATE joueurs SET id_derniere_nouvelle_lue=? WHERE id=? ');
		 $req->execute(array($donnees['id'], $_SESSION['id_joueur']));
   ?>
   <div>
      <fieldset>
	     <legend style="color:green">La dernière nouvelle - <?php echo $donnees['titre']; ?></legend>
		 <div class="news">
	     <?php echo $donnees['new'] ?>
         <p class="news_date">Date de publication: <?php echo $donnees['date_de_publication']; ?></p>
		 </div>
	  </fieldset>
   </div>
   <?php
   }
   
      if($joueur['id_alliance'] != 0){
	  $req = $bdd->prepare('SELECT message FROM alliances WHERE id=? ');
      $req->execute(array($joueur['id_alliance']));
	  $message_alliance = $req->fetch(); 
	  if($message_alliance['message'] != ""){
	  ?>
	  <div>
	     <fieldset>
		 <legend>Message de votre alliance</legend>
		 <div id="message_alliance">
	     <?php
	        echo '<p>' . htmlspecialchars($message_alliance['message']) . '</p>'; ?>
			</div>
		 </fieldset>
	  </div>
	  <?php
	  }
	  }
      ?>
   
   <table class="table_invisible" >
      <tr>
	     <td style="max-height:250px" >
		    <fieldset>
			<legend>Ce que votre clan et vous faites</legend>
			<p>
		    <?php
			$req = $bdd->prepare('SELECT activite FROM joueurs WHERE id=? ');
			$req->execute(array($_SESSION['id_joueur']));
			$donnees = $req->fetch();
			switch($donnees['activite']){
			   case 0: //Rien
			      ?>
			      Votre clan n'est actuellement  pas occupé.<br />
				  Vous pouvez donc faire des <a href="missions.php?co=o" >missions</a>, vous <a href="carte.php?co=o&amp;cartex=0&amp;cartey=0" >déplacer</a>, ou <a href="carte.php?co=o&amp;cartex=0&amp;cartey=0" >lancer des assauts</a>!
				  <?php
			   break;
			   
			   case 1: //Mission
			      ?>
				  <strong>Votre clan est actuellement en pleine mission!</strong><br />
				  <?php
				  $req = $bdd->prepare('SELECT id_mission FROM missions_en_cours WHERE id_joueur=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $mission = $req->fetch();
				  $req = $bdd->prepare('SELECT id, nom, description FROM missions WHERE id=? ');
				  $req->execute(array($mission['id_mission']));
				  $mission = $req->fetch();
				  ?>
				  <strong>Mission:</strong> <?php echo $mission['nom']; ?><br />
				  <?php
				  $req = $bdd->prepare('SELECT activite_fin_timestamp FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $fin = $req->fetch();
				  if($fin['activite_fin_timestamp'] <= time()){
				     ?><br />
					 <input type="button" name="terminer_mission" value="Terminer la mission!" onclick=location.href="missions.php?co=o&amp;terminer=<?php echo $mission['id']; ?>" />
					 <?php
				  }
				  else{
				  ?><br />
				  <strong>Terminée dans <?php echo round((($fin['activite_fin_timestamp'] - time()) / 60)); ?> minutes</strong>
				  <?php
				  }
			   break;
			   
			   case 2: //Déplacement
			      $req = $bdd->prepare('SELECT activite_fin_timestamp FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $joueur = $req->fetch();
				  $req = $bdd->prepare('SELECT * FROM deplacements WHERE id_joueur=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $deplacement = $req->fetch();
				  $temps = $joueur['activite_fin_timestamp'] - time();
				  $temps /= 60; //En minutes
				  $temps = round($temps);
				  ?>
				  <strong>Votre clan est actuellement en train de se déplacer.</strong><br />
				  Pendant ce temps, vous ne pouvez faire de combat ou de mission.<br />
				  Votre destination: <?php echo $deplacement['x'] . ', ' . $deplacement['y'];?>
				  <br />
				  <br />
				  <?php
				  if($joueur['activite_fin_timestamp'] > time()){
				  ?>
				  <strong>Vous arrivez à destination dans <?php echo $temps; ?> minutes</strong>
				  <?php
				  }
				  else{
				     $req = $bdd->prepare('UPDATE joueurs SET activite=\'4\' WHERE id=? ');
					 $req->execute(array($_SESSION['id_joueur']));
					 ?>
					 <input type="button" name="terminer_deplacement" value="S'installer" onclick=location.href="options.php?co=o&amp;action=terminer_deplacement&amp;id=<?php echo $deplacement['id']; ?>"  />
					 <?php
				  }
			   break;
			   
			   case 3: //Attaque et espionnages
			      ?>
				  <strong>Votre clan est en pleine mission d'espionnage, de dérobage et/ou en combat!</strong><br />
				  Vous ne pouvez donc pas vous déplacer, mais vous pouvez tout de même faire des <a href="missions.php?co=o" >missions</a>.
				  <?php
			   break;
			   
			   case 4: //Déplacement terminé
			      $req = $bdd->prepare('SELECT activite_fin_timestamp FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $joueur = $req->fetch();
				  $req = $bdd->prepare('SELECT * FROM deplacements WHERE id_joueur=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $deplacement = $req->fetch();
				  $temps = $joueur['activite_fin_timestamp'] - time();
				  $temps /= 60; //En minutes
				  $temps = round($temps);
				  ?>
				  <strong>Votre clan est actuellement en train de se déplacer.</strong><br />
				  Pendant ce temps, vous ne pouvez faire de combat ou de mission.<br />
				  Votre destination: <?php echo $deplacement['x'] . ', ' . $deplacement['y'];?>
				  <br />
				  <br />
				  <?php
				     ?>
					 <input type="button" name="terminer_deplacement" value="S'installer" onclick=location.href="options.php?co=o&amp;action=terminer_deplacement&amp;id=<?php echo $deplacement['id']; ?>"  />
					 <?php
			   break;
			   
			   case -13:
			      ?>
				  <strong>Votre clan est en pleine mission d'espionnage, de dérobage et/ou en combat!</strong><br />
				  De plus, il est en train d'accomplir une mission.<br />
				  Vous ne pouvez donc pas vous déplacer pour le moment.<br />
				  <?php
				  $req = $bdd->prepare('SELECT id_mission FROM missions_en_cours WHERE id_joueur=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $mission = $req->fetch();
				  $req = $bdd->prepare('SELECT id, nom, description FROM missions WHERE id=? ');
				  $req->execute(array($mission['id_mission']));
				  $mission = $req->fetch();
				  ?>
				  <strong>Mission:</strong> <?php echo $mission['nom']; ?><br />
				  <?php
				  $req = $bdd->prepare('SELECT activite_fin_timestamp FROM joueurs WHERE id=? ');
				  $req->execute(array($_SESSION['id_joueur']));
				  $fin = $req->fetch();
				  if($fin['activite_fin_timestamp'] <= time()){
				     ?><br />
					 <input type="button" name="terminer_mission" value="Terminer la mission!" onclick=location.href="missions.php?co=o&amp;terminer=<?php echo $mission['id']; ?>" />
					 <?php
				  }
				  else{
				  ?><br />
				  <strong>Terminée dans <?php echo round((($fin['activite_fin_timestamp'] - time()) / 60)); ?> minutes</strong>
				  <?php
				  }
			   break;
			}
			?>
			</p>
			</fieldset>
		 </td>
	  </tr>
   </table>
   
   <table class="table_invisible" >
      <tr>
	     <td style="width:50%;max-height:400px;" >
		    <fieldset class="gauche" >
			   <legend>Informations sur les attaques</legend>
			   <p>
			      <?php
				  //Les attaques DE nous
				  $req = $bdd->prepare('SELECT id, id_cible, assassins, archers, infanterie, timestamp_arrivee FROM attaques WHERE id_joueur=? AND combat_termine=\'0\' ');
				  $req->execute(array($_SESSION['id_joueur']));
				  while($donnees = $req->fetch()){
				  ?>
				  <div title="<?php echo 'Infanterie: ' . $donnees['infanterie'] . ', Assassins: ' . $donnees['assassins'] .
				  ', Archers: ' . $donnees['archers'];?>" >
				  <?php
				     $req2 = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
					 $req2->execute(array($donnees['id_cible']));
					 $cible = $req2->fetch();
				     echo 'Vous lancez une attaque sur <a href="fiche_joueur.php?co=o&amp;pseudo=' . $cible['pseudo'] . '" >' . $cible['pseudo'] . '</a>!<br />';
					 $temps = $donnees['timestamp_arrivee'] - time();
					 $temps = $temps / 60;
					 $temps = round($temps);
					 if(time() >= $donnees['timestamp_arrivee']){
					    echo '<strong>Vos troupes sont arrivés à destination.</strong><br />';
						?>
					    <input type="button" name="attaquer" value="Attaquer!" onclick=location.href="<?php echo 'combat.php?co=o&amp;a=combat&amp;id=' . $donnees['id_cible'] . '&amp;id_combat=' . $donnees['id'] . '';?>" />
				        <?php
					 }
					 else{
					    echo 'Temps estimé avant l\'arrivée de nos troupes à destination: <strong>' . $temps . ' minutes</strong><br />';
					 }
					 ?>
					 <br /><input type="button" name="annuler_attaque" value="Ordonner aux troupes de revenir" onclick=location.href="<?php echo 'combat.php?co=o&amp;a=annuler&amp;id=' . $donnees['id_cible'] . '&amp;id_combat=' . $donnees['id'] . ''; ?>" />
					 <hr />
				  </div>
					 <?php
				  }
				  ?>
				  </p>
			</fieldset>
		 </td>
   
         <td>
		    <fieldset>
			   <legend>Informations sur les infiltrations</legend>
			   <p>
                  <div>
				     <?php
					 $req = $bdd->prepare('SELECT id, id_cible, timestamp_arrive, espions FROM espionnages WHERE id_joueur=? AND espionnage_termine=\'0\' ');
					 $req->execute(array($_SESSION['id_joueur']));
					 while($donnees = $req->fetch()){
					    ?>
				  <div title="<?php echo 'Espions envoyés: ' . $donnees['espions'];?>" >
				  <?php
				     $req2 = $bdd->prepare('SELECT pseudo FROM joueurs WHERE id=? ');
					 $req2->execute(array($donnees['id_cible']));
					 $cible = $req2->fetch();
					 $req2 = $bdd->prepare('SELECT facteur_vitesse FROM joueurs WHERE id=? ');
					 $req2->execute(array($_SESSION['id_joueur']));
					 $joueur = $req2->fetch();
				     echo 'Vous lancez une infiltration sur <a href="fiche_joueur.php?co=o&amp;pseudo=' . $cible['pseudo'] . '" >' . $cible['pseudo'] . '</a>!<br />';
					 $temps = 0;
					 $temps = $donnees['timestamp_arrive'] - time();
		             $temps = $temps / 60;
		             $temps = round($temps, 0);
					 if(time() >= $donnees['timestamp_arrive']){
					    echo '<strong>Vos espions sont arrivés à destination.</strong><br />';
						?>
					    <input type="button" name="espionner" value="Espionner!" onclick=location.href="<?php echo 'espion.php?co=o&amp;a=espionnage&amp;id=' . $donnees['id_cible'] . '&amp;confirm=2&amp;id_espionnage=' . $donnees['id'] . '';?>" />
				        <?php
					 }
					 else{
					    echo 'Temps estimé avant l\'arrivée de nos espions à destination: <strong>' . $temps . ' minutes</strong><br />';
					 }
					 ?>
					 <hr />
				  </div>
					 <?php
					 }
					 ?>
                  </div>
			   </p>
			</fieldset>
		 </td>
	  </tr>
   </table>
	
	<table class="table_invisible" >
	   <tr>
	      <td style="width: 50%" >
             <form action="fiche_joueur.php?co=o&amp;a=rechercher" method="post" >
                <label>Trouver la fiche d'un autre seigneur:<br />
                   <input type="text" maxlength="25" name="pseudo" title="Entrez le pseudo d'un autre seigneur" ></input><br />
                </label>
	            <label>
	               <input type="submit" name="Rechercher" value="Rechercher" ></input><br /><br />
	            </label>
             </form>
	      </td>
		  <td style="width:50%">
		     <form action="fiche_alliance.php?co=o&amp;a=rechercher" method="post" >
                <label>Trouver la fiche d'une alliance:<br />
                   <input type="text" maxlength="35" name="nom" title="Entrez le nom de l'alliance" ></input><br />
                </label>
	            <label>
	               <input type="submit" name="Rechercher" value="Rechercher" ></input><br /><br />
	            </label>
             </form>
		  </td>
	   </tr>
   </table>
   
   </div>
   <?php include ("includes/pied_de_page.php"); ?>
   </body>
</html>
<?php
   $req->closeCursor();
   $reponse->closeCursor();
?>