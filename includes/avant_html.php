<?php
//Le code par défaut d'avant HTML.
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
//Si il n'y a eu aucune action depuis 10 minutes
//{
$reponse = $bdd->prepare('SELECT timestamp_derniere_action FROM joueurs WHERE id=?');
$reponse->execute(array($_SESSION['id_joueur']));
$joueur = $reponse->fetch();
$timestamp_min = time() - 600;//600 secondes, soit 10 minutes
if($joueur['timestamp_derniere_action'] < $timestamp_min){
   //Signifie qu'on a rien fait depuis 10 minutes
   $req = $bdd->prepare('UPDATE joueurs SET timestamp_derniere_action=? WHERE id=? ');
   $req->execute(array(0, $_SESSION['id_joueur']));
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

if($_GET['co'] == 'o' AND $_SESSION['connecte'] != NULL){
}
else{
header('Location: index.php');
}
?>