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
   <?php include ("includes/banniere.php"); ?>
   <?php include("includes/menu_accueil.php"); ?>
   <?php 
   try
{
	$bdd = new PDO('mysql:host=localhost;dbname=totoila_berceaud', 'totoila_berceaud', 'n9DTlUgIoAzm');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
$aValid = array('-', '_', '.');
 $sUser = $_POST['pseudo'];
if(!empty($_POST['pseudo']) AND ($_POST['nom_clan']) AND ($_POST['mdp']) AND ($_POST['confirmer_mdp']) AND ($_POST['email'])){
//Regarder si le pseudo est déjà pris
$reponse = $bdd->prepare('SELECT pseudo FROM joueurs WHERE pseudo=?');
$reponse->execute(array($_POST['pseudo']));
$donnees = $reponse->fetch();
$reponse2 = $bdd->prepare('SELECT nom_clan FROM joueurs WHERE nom_clan=?');
$reponse->execute(array($_POST['nom_clan']));
$donnees2 = $reponse2->fetch();
if($donnees['pseudo'] != NULL){ ?>
<p id="centre">
   Le pseudo que vous avez choisi existe déjà.<br />
   <a href="JavaScript:window.history.go(-1)" >Revenez en arrière</a> et essayez un autre pseudo!
</p>
<?php
}

//Regarder si le nom du clan est déjà pris
else if($donnees2['nom_clan'] != NULL){ ?>
<p id="centre" >
   Le nom du clan que vous avez choisi existe déjà.<br />
   <a href="JavaScript:window.history.go(-1)" >Revenez en arrière</a> et essayez un autre nom de clan!
</p>
<?php
}

//Regarder la confirmation du mot de passe
else if($_POST['mdp'] != $_POST['confirmer_mdp']){?>
<p id="centre" >
   La confirmation du mot de passe n'est pas la même que le mot de passe que vous avez entré.<br />
   <a href="JavaScript:window.history.go(-1)" >Revenez en arrière</a> pour continuer votre inscription!<br />
</p>
<?php
}
else if($_POST['profession'] == NULL){ ?>
<p id="centre" >
   Vous devez choisir une profession!
   <a href="JavaScript:window.history.go(-1)" >Revenez en arrière</a> pour continuer votre inscription!<br />
</p>
<?php
}
else if(!ctype_alnum(str_replace($aValid, '', $sUser))) {
    ?>
	<p id="centre" >
	Le pseudo que vous avez entré contient des caractères non-autorisés.<br />
	<a href="JavaScript:window.history.go(-1)" >Revenez en arrière</a> pour pour compléter votre inscription!<br />
	</p>
	<?php
} 
else{ //Si on se rend ici, tout est OK: pseudo valide, champs complets, etc.
   $pseudo_min = strtolower($_POST['pseudo']);
   $pos['x'] = rand(0, 9);
   $pos['y'] = rand(0, 9);
   $cle = md5(microtime(TRUE)*100000);
   $req = $bdd->prepare('SELECT * FROM professions WHERE nom=? ');
   $req->execute(array($_POST['profession']));
   $profession = $req->fetch();
   $req = $bdd->prepare('INSERT INTO joueurs (pseudo, pseudo_min ,mot_de_passe, argent ,infanterie, nom_clan ,description_rp,
   statut_special, gain_or_jour, date_derniere_connexion, timestamp_inscription, profession, pos_x, pos_y, facteur_combat, facteur_economie,
   facteur_vitesse, facteur_furtif, email, cle_confirmation, compte_active, IP_inscription, id_maitre, eleves)
VALUES (:pseudo, :pseudo_min, :mot_de_passe, 80, 1, :nom_clan,
 \'Aucune description pour le moment\', \'Aucun statut spécial\', 12, \'0000-00-00 00:00\', :timestamp_inscription, 
 :profession, :pos_x, :pos_y, :facteur_combat, :facteur_economie, :facteur_vitesse, :facteur_furtif,
 :email, :cle_confirmation, \'0\', :IP_inscription, \'0\', \'0\' )'); 
    $req->execute(array(
	'pseudo' => $_POST['pseudo'],
	'pseudo_min' => $pseudo_min,
	'mot_de_passe' => $_POST['mdp'],
	'nom_clan' => $_POST['nom_clan'],
	'timestamp_inscription' => time(),
	'profession' => $_POST['profession'],
	'pos_x' => $pos['x'],
	'pos_y' => $pos['y'],
	'facteur_combat' => $profession['facteur_combat'],
	'facteur_economie' => $profession['facteur_economie'],
	'facteur_vitesse' => $profession['facteur_vitesse'],
	'facteur_furtif' => $profession['facteur_furtif'],
	'email' => $_POST['email'],
	'cle_confirmation' => $cle,
	'IP_inscription' => $_SERVER["REMOTE_ADDR"]
	));
	//On détermine l'id du nouveau joueur
	   $req = $bdd->prepare('SELECT id FROM joueurs WHERE pseudo=? ');
	   $req->execute(array($_POST['pseudo']));
	   $id = $req->fetch();
	   
	//On envoie le email de confirmation
	//*
	     $to = $_POST['email'];
		 $subject = "Confirmation d'inscription";
		 $message = '
		    Bonjour ' . $_POST['pseudo'] . '!
			
			Vous avez presque terminé votre inscription!
			Il reste tout de même un petit quelque chose à faire: confirmer votre inscription.
			Pour ce faire, vous n\'avez qu\'à appuyer sur le lien suivant:
			http://www.berceaudeguerres.com/activation.php?pseudo=' . urlencode($_POST['pseudo']) . '&cle=' . $cle . '.
			
			En espérant vous voir le plus tôt possible sur Berceau de Guerres!
			
			L\'équipe de Berceau de Guerres.
			webmasters@berceaudeguerres.com
			
			
			Si vous ne vous êtes pas inscrits sur Berceau de Guerres, il s\'agit d\'une erreur.
			Si tel est le cas, ignorez ce message!
		 ';
		 $headers = 'From: Berceau de Guerres <webmasters@berceaudeguerres.com>' . "\r\n";
		 mail($to, $subject, $message, $headers);
		 //*/
		 
	//S'il est marchand ou forgeron, on crée le magasin
	if($_POST['profession'] == 'Marchand' OR $_POST['profession'] == 'Forgeron'){
	   $req = $bdd->prepare('INSERT INTO magasins (id_joueur, nom, devise)
	   VALUES (:id_joueur, :nom, :devise)');
	   $req->execute(array(
	   'id_joueur' => $id['id'],
	   'nom' => 'Magasin de ' . $_POST['pseudo'],
	   'devise' => 'Aucune devise'));
	   
	}
	if($_POST['maitre'] != null){
      $req = $bdd->prepare('SELECT id, eleves, niveau, pseudo, argent FROM joueurs WHERE pseudo=? ');
	  $req->execute(array($_POST['maitre']));
	  $maitre = $req->fetch();
	  $req = $bdd->prepare('UPDATE joueurs SET id_maitre=? WHERE id=? ');
	  $req->execute(array($maitre['id'], $id['id']));
	  $argent_gagne = rand(4, ($maitre['niveau'] * 2 + ($maitre['eleves']+1) * 5)) + 4;
	  $req = $bdd->prepare('UPDATE joueurs SET eleves=?, argent=? WHERE id=? ');
	  $req->execute(array(($maitre['eleves']+1), ($argent_gagne + $maitre['argent']), $maitre['id']));
	  //On envoie un message au maitre
	  $message = "Bonsoir " . $maitre['pseudo'] . ",
	  
	  Ce présent message est pour vous annoncer que vous avez maintenant une nouvel élève!
	  Vous êtes maintenant le maître de " . $_POST['pseudo'] . ", ce 
	  qui vous a permis d'obtenir " . $argent_gagne . " pièces d'or. 
	  Elles ont tout de suite été ajoutés à vos réserves.
	  
	  Que gloire et victoires prennent part du reste de votre journée!";
	  
	  $req = $bdd->prepare('INSERT INTO messagerie (titre, destinateur, id_destinataire, message, message_lu, date, type)
	  VALUES (\'Nouvel élève\', \'Votre messager\', :id_destinataire, :message, \'0\', :date, \'0\')');
	  $req->execute(array(
	  'id_destinataire' => $maitre['id'],
	  'message' => $message,
	  'date' => date('Y-m-d H:i:s')));
   }
	?>
    <p id="centre">
   Vous avez terminé votre inscription au Berceau de Guerres!<br/>
   Pour revenir à l'accueil et vous connecter, appuyez <a href="index.php"> ici</a>.<br />
   </p>
   <?php
   }
   }
 else{
 ?>
 <p id="centre">
 Il manque des informations au formulaire d'inscription.<br />
 Pour terminer l'inscription, il est nécéssaire de remplir tous les champs.<br />
 Pour revenir au formulaire, appuyez <a href="inscription.php">ici</a>.<br />
 </p>
 <?php
 }
 ?>
   <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>