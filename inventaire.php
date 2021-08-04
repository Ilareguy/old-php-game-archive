<?php include("includes/avant_html.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Inventaire</title>
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
   <h2>Mon inventaire</h2>
   
   <?php $req = $bdd->prepare('SELECT places_inventaire FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $donnees = $req->fetch();?>
   <p>Vous avez un total de <strong style="color:brown" ><?php echo $donnees['places_inventaire'];?></strong> places maximum dans votre inventaire</p>
   
      <?php if(isset($_GET['a'])){ //On regarde s'il y a action à faire
	  
	  $req = $bdd->prepare('SELECT id_joueur, id_objet FROM inventaire WHERE id=? ');//id de l'objet à vendre et du joueur
	  $req->execute(array($_GET['id']));
	  $id_inventaire = $req->fetch();
	  if($id_inventaire['id_joueur'] != $_SESSION['id_joueur']){//Protection
	  header('Location: index.php');
	  }
	  
      switch($_GET['a']){
	  case 'vendre':
	  $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id=? ');
	  $req->execute(array($_GET['id']));
	  $donnees = $req->fetch();
		 
	  $req_objet = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
	  $req_objet->execute(array($donnees['id_objet']));
      $donnees_objet = $req_objet->fetch();
	  ?>
	     <form action="inventaire.php?co=o&amp;a=confirmer_vente&amp;id=<?php echo $_GET['id'];?>" method="post" >
		    <label>Donnez un prix de vente<br />
			<?php echo 'Prix minimum: ' . $donnees_objet['prix_min'] . ' pièces d\'or<br />';
			      echo 'Prix maximum: ' . $donnees_objet['prix_max'] . ' pièces d\'or<br />';?>
		       <input type="number" name="prix" ></input><br />
			</label>
			
			<label>
			   <input type="submit" name="Bouton vendre" value="Mettre en vente" ></input><br /><br />
			</label>
		 </form>
	  <?php break;
	  
	  case 'confirmer_vente':
	  $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id=? ');
	  $req->execute(array($_GET['id']));
	  $donnees = $req->fetch();
		 
	  $req_objet = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
	  $req_objet->execute(array($donnees['id_objet']));
      $donnees_objet = $req_objet->fetch(); 
	  
	  if(!ctype_digit($_POST['prix'])){//Caractère non numérique
	     echo '<p class="erreur" >Vous devez entrer un nombre entier!</p>';
	  }
	  else if($_POST['prix'] < $donnees_objet['prix_min'] OR $_POST['prix'] > $donnees_objet['prix_max']){
	     echo '<p class="erreur" >Votre prix est hors des limites!<br />Changez-le avant de le vendre</p>';
	  }
	  else{
	     $req = $bdd->prepare('INSERT INTO magasins_objets (id_magasin, id_objet, prix) VALUES(:id_magasin, :id_objet, :prix)');
		 $req->execute(array(
		 'id_magasin' => $_SESSION['id_magasin'],
		 'id_objet' => $id_inventaire['id_objet'],
		 'prix' => $_POST['prix']));
		 
		 $req = $bdd->prepare('DELETE FROM inventaire WHERE id=? ');
		 $req->execute(array($_GET['id']));
		 
		 echo '<p class="succes" >Votre objet a été mis en vente au prix de ' . $_POST['prix'] . ' pièces d\'or</p>';
	  }
	  break;
	  
	  case 'equip':
	  $req = $bdd->prepare('SELECT * FROM inventaire WHERE id=? ');
	  $req->execute(array($_GET['id']));
	  $donnees_objet = $req->fetch();
	  if($donnees_objet['id_joueur'] != $_SESSION['id_joueur']){header('Location: index.php');}
	  $req = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
	  $req->execute(array($donnees_objet['id_objet']));
	  $donnees_objet = $req->fetch();
	  $req = $bdd->prepare('SELECT * FROM joueurs WHERE id=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  $donnees_joueur = $req->fetch();
	  
	  			if($donnees_objet['type'] == 'Arme d\'infanterie' AND $donnees_joueur['armes_infanterie'] + 1 > $donnees_joueur['infanterie'])
			   {echo '<p class="erreur" >Vous devez avoir plus de membres d\'infanterie pour équiper ce type d\'objet<br /></p>';}
			   
			   else if($donnees_objet['type'] == 'Arme d\'assassin' AND $donnees_joueur['armes_assassin'] + 1 > $donnees_joueur['assassin'])
			   {echo '<p class="erreur" >Vous devez avoir plus d\'assassin pour équiper ce type d\'objet<br /></p>';}
			   
			   else if($donnees_objet['type'] == 'Arme d\'archer' AND $donnees_joueur['armes_archer'] + 1 > $donnees_joueur['archer'])
			   {echo '<p class="erreur" >Vous devez avoir plus d\'archer pour équiper ce type d\'objet<br /></p>';}
			   
			   else if($donnees_objet['maitre'] == 1 AND ($donnees_joueur['armes_maitres'] + 1) < $donnees_joueur['niveau'])
			   {echo '<p class="erreur" >Vous ne pouvez équiper plus d\'un arme maitre par niveau<br /></p>'; }
			   
		else{
	  switch($donnees_objet['type']){
				     case 'Arme d\'infanterie':
					 $req = $bdd->prepare('UPDATE joueurs SET armes_infanterie=?, points_attaque=?, points_defense=? WHERE pseudo=? ');
					 $req->execute(array($donnees_joueur['armes_infanterie'] += 1, 
					 $donnees_joueur['points_attaque'] += $donnees_objet['attaque'],
					 $donnees_joueur['points_defense'] += $donnees_objet['defense'],
					 $_SESSION['pseudo']));
					 break;
					 
					 case 'Arme d\'assassin':
					 $req = $bdd->prepare('UPDATE joueurs SET armes_assassin=?, points_attaque=?, points_defense=? WHERE pseudo=? ');
					 $req->execute(array($donnees_joueur['armes_assassin'] += 1, 
					 $donnees_joueur['points_attaque'] += $donnees_objet['attaque'],
					 $donnees_joueur['points_defense'] += $donnees_objet['defense'],
					 $_SESSION['pseudo']));
					 break;
					 
					 case 'Arme d\'archer':
					 $req = $bdd->prepare('UPDATE joueurs SET armes_archer=?, points_attaque=?, points_defense=? WHERE pseudo=? ');
					 $req->execute(array($donnees_joueur['armes_archer'] += 1, 
					 $donnees_joueur['points_attaque'] += $donnees_objet['attaque'],
					 $donnees_joueur['points_defense'] += $donnees_objet['defense'],
					 $_SESSION['pseudo']));
					 break;
					 }
			$req = $bdd->prepare('DELETE FROM inventaire WHERE id=? ');
			$req->execute(array($_GET['id']));
			echo '<p class="succes" >Un de vos membres de clan s\'est équipé de cet objet, ajoutant<br />
			ainsi ' . $donnees_objet['attaque'] . ' points d\'attaque et ' . $donnees_objet['defense'] . ' points de défense à votre clan!</p>';
					 }
	  break;
	  
	  case 'jeter':
	  	 $req = $bdd->prepare('SELECT * FROM inventaire WHERE id=? ');
	     $req->execute(array($_GET['id']));
	     $donnees_objet = $req->fetch();
	     if($donnees_objet['id_joueur'] != $_SESSION['id_joueur']){header('Location: index.php');}
		 if(isset($_GET['confirm'])){
		 $req = $bdd->prepare('DELETE FROM inventaire WHERE id=? ');
		 $req->execute(array($_GET['id']));
		 echo '<p class="succes" >Cet objet a été supprimé de votre inventaire</p>';}
		 else{?>
		 <p>Cette action est irréversible!<br />
		 Voulez-vous vraiment jeter cet objet?<br /><br />
		 <?php echo '<a href="inventaire.php?co=o&amp;a=jeter&amp;id=' . $_GET['id'] . '&amp;confirm=oui" >Oui, je veux jeter cet objet</a><br /><br />';?>
		 <a href="inventaire.php?co=o" >Non, je veux garder cet objet</a>
		 <br />
		 </p>
		 <?php
		 }
	  break;
	  
	  case 'vendre_minimum':
	     $req = $bdd->prepare('SELECT * FROM inventaire WHERE id=? ');
	     $req->execute(array($_GET['id']));
	     $donnees_objet = $req->fetch();
		 if($donnees_objet['id_joueur'] != $_SESSION['id_joueur']){header('Location: index.php');}
		 $req_objet = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
	     $req_objet->execute(array($donnees_objet['id_objet']));
         $donnees_objet = $req_objet->fetch();
		 $req = $bdd->prepare('SELECT argent FROM joueurs WHERE id=? ');
		 $req->execute(array($_SESSION['id_joueur']));
		 $argent = $req->fetch();
		 $argent['argent'] += $donnees_objet['prix_min'];
		 $req = $bdd->prepare('UPDATE joueurs SET argent=? WHERE id=? ');
		 $req->execute(array($argent['argent'], $_SESSION['id_joueur']));
		 $req = $bdd->prepare('DELETE FROM inventaire WHERE id=? ');
		 $req->execute(array($_GET['id']));
		 echo '<p class="succes" >Vous obtenez ' . $donnees_objet['prix_min'] . ' pièces d\'or pour cet objet</p>';
	  break;
	  }
   }
   ?>
   
   <table id="table_inventaire" >
      <caption>Voici la liste de votre inventaire</caption>
	  
	  <tr>
	     <th>Objet</th>
		 <th>Action</th>
	  </tr>
	  
	  <?php 
	  $req = $bdd->prepare('SELECT id_objet, id FROM inventaire WHERE id_joueur=? ');
	  $req->execute(array($_SESSION['id_joueur']));
	  for($i = 0;$i != $_SESSION['places_inventaire']; $i++){
	     $donnees = $req->fetch();
		 
		 if($donnees['id_objet'] != NULL){
	  	 $req_objet = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
	     $req_objet->execute(array($donnees['id_objet']));
		 $donnees_objet = $req_objet->fetch(); 
	     echo '<tr><td title="' . $donnees_objet['description'] . '"><br />' . $donnees_objet['nom'] . '<br /><br /></td><td>';
		 ?>
		 <form>
		    <select onchange="golinks(this.options[this.selectedIndex].value)" >
			<option selected="selected" >Choisissez une action</option>
		 <?php
		 //Actions ici:
		 //Vendre
		 if(($_SESSION['profession'] == 'Forgeron' OR $_SESSION['profession'] == 'Marchand') AND $donnees_objet['vendable'] == 1)
		 {echo '<option value="inventaire.php?co=o&amp;a=vendre&amp;id=' . $donnees['id'] . '" >' . 'Vendre ' . '</option>';}
		 
		 //Équiper un membre de clan
		 if($donnees_objet['type'] == 'Arme d\'infanterie'
		 OR $donnees_objet['type'] == 'Arme d\'assassin'
		 OR $donnees_objet['type'] == 'Arme d\'archer'){
			echo '<option value="inventaire.php?co=o&amp;a=equip&amp;id=' . $donnees['id'] . '" 
			title="Action irréversible. Pour équiper un autre arme, il vous faudra acheter un autre membre de clan" >
			Équiper un membre de clan</option>';
		}
			
		//Jeter
		echo '<option value="inventaire.php?co=o&amp;a=jeter&amp;id=' . $donnees['id'] . '" title="Action irréversible!" >Jeter</option>';
		
		//Vendre au minimum
		$req_objet = $bdd->prepare('SELECT * FROM objets WHERE id=? ');
	    $req_objet->execute(array($donnees['id_objet']));
	    $donnees_objet = $req_objet->fetch(); 
		if($donnees_objet['vendable'] == 1){
		echo '<option value="inventaire.php?co=o&amp;a=vendre_minimum&amp;id=' . $donnees['id'] . '" >Vendre au prix minimum</option>';
		}
		
		echo '</td></tr>';
		?>
			</select>
		 </form>
		 <?php
			  }
		 else{
		 echo '<tr><td colspan="2" ><p>Espace disponible</p></td></tr>';
		 }
	  }?>
   </table>
   
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>