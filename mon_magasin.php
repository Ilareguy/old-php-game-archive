<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Mon magasin</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   
   <body>
   
   <?php if($_SESSION['profession'] != 'Forgeron' AND $_SESSION['profession'] != 'Marchand'){header('Location: index.php');}?>
      <?php include("includes/banniere.php"); ?>
   <div id="centre" >
   <h2>Gestion de votre magasin</h2>
   
   <?php if(isset($_GET['a'])){
      switch($_GET['a']){
	     case 'modifier':
		 $req = $bdd->prepare('SELECT * FROM magasins WHERE nom=? ');
		 $req->execute(array($_POST['nom']));
		 $donnees = $req->fetch();
		 $req = $bdd->prepare('SELECT nom FROM magasins WHERE id=? ');
		 $req->execute(array($_SESSION['id_magasin']));
		 $nom_magasin = $req->fetch();
		 
			if($donnees['nom'] != NULL AND $_POST['nom'] != $nom_magasin['nom'] AND $_POST['nom'] != NULL)
			{echo '<p class="erreur" >Ce nom de magasin existe déjà!</p>';}
			else{
			   if($_POST['nom'] != NULL){
			      echo '<p class="succes" >Votre magasin portera maintenant le nom de ' . stripslashes($_POST['nom']) . '</p>';
			      $req = $bdd->prepare('UPDATE magasins SET nom=?, devise=? WHERE id_joueur=? ');
			      $req->execute(array(stripslashes($_POST['nom']), stripslashes($_POST['devise']), $_SESSION['id_joueur']));
			   }
			   else{
			      echo '<p class="succes" >Le nom de votre magasin a bien été enlevé.<br />Il n\'apparaitra donc pas dans la liste</p>';
				  $req = $bdd->prepare('UPDATE magasins SET nom=NULL, devise=? WHERE id_joueur=? ');
			      $req->execute(array(stripslashes($_POST['devise']), $_SESSION['id_joueur']));
			   }
			   
			}
		 break;
		 
		 case 'enlever':
		    if(!isset($_GET['id'])){header('Location: index.php');}
			$req = $bdd->prepare('SELECT id_objet FROM magasins_objets WHERE id=? ');
			$req->execute(array($_GET['id']));
			$objet = $req->fetch();
			$req = $bdd->prepare('INSERT INTO inventaire (id_objet, id_joueur) VALUES (:id_objet, :id_joueur)');
			$req->execute(array(
			'id_objet' => $objet['id_objet'],
			'id_joueur' => $_SESSION['id_joueur']));
			$req = $bdd->prepare('DELETE FROM magasins_objets WHERE id=? ');
			$req->execute(array($_GET['id']));
			echo '<p class="succes" >Cette vente a étée enlevée</p>';
		 break;
	  }
   }?>
   
   <?php $req = $bdd->prepare('SELECT * FROM magasins WHERE id_joueur=? ');
         $req->execute(array($_SESSION['id_joueur']));
		 $donnees = $req->fetch();?>
		 
   <form action="mon_magasin.php?co=o&amp;a=modifier" method="post" >
      <label>Le nom de votre magasin<br />
	  Changer ce nom peut vous faire descendre en réputation!<br />
	  Supprimer le nom fera en sorte que votre magasin n'apparaitra pas dans la liste des magasins.<br />
	     <input type="text" maxlength="40" name="nom" value="<?php echo $donnees['nom'];?>" ></input><br /><br />
	  </label>
	  
	  <label>La devise de votre magasin<br />
	  Les magasins sans devise, ça existe!<br />
	     <input type="text" maxlength="50" name="devise" value="<?php echo $donnees['devise'];?>" ></input><br /><br />
	  </label>
	  
	  <input type="submit" value="Valider" ></input>
   </form>
   
   <table id="table_magasin" >
      <caption>Voici une liste de choses qui sont actuellement en vente dans votre magasin</caption>
	  
	  <tr>
	     <th>Objet</th>
		 <th>Prix</th>
		 <th>Action</th>
	  </tr>
	  
	  <?php $req = $bdd->prepare('SELECT * FROM magasins_objets WHERE id_magasin=? ');
            $req->execute(array($_SESSION['id_magasin']));
	  
	  while($donnees = $req->fetch()){
	     $req_objet = $bdd->prepare('SELECT nom, description FROM objets WHERE id=? ');
         $req_objet->execute(array($donnees['id_objet']));
		 $donnees_objet = $req_objet->fetch();
		 
	  echo '<tr><td>' . $donnees_objet['nom'] . 
	  '</td><td>' . $donnees['prix'] . 
	  '</td><td><a href="mon_magasin.php?co=o&amp;a=enlever&amp;id=' . $donnees['id'] . '" >Enlever</a>
	   </td></tr>';} ?>
   </table>
   
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
   
</html>