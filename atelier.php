<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Atelier</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/atelier.js" ></script>
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   
   <body>
      <?php include("includes/banniere.php"); ?>
   <div id="centre" >  
      <h2>Atelier</h2>
	  <p>
	  Fabriquez ici des objets grâce aux matériaux que vous avez dans votre inventaire.<br />
	  </p>
	  
	  <?php
	  //On détermine tous les matériaux du joueur
		 $buches = 0;
		 $bronze = 0;
		 $fer = 0;
		 $burin = 0;
		 $marteau = 0;
		 $cisailles = 0;
		 //4, les bûches
		 $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'4\' ');
		 $req->execute(array($_SESSION['id_joueur']));
		 while($donnees = $req->fetch()){
		    $buches ++;
		 }
		 
		 //5, les lingots de bronze
		 $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'5\' ');
		 $req->execute(array($_SESSION['id_joueur']));
		 while($donnees = $req->fetch()){
		    $bronze ++;
		 }
		 
		 //6, les lingots de fer
		 $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'6\' ');
		 $req->execute(array($_SESSION['id_joueur']));
		 while($donnees = $req->fetch()){
		    $fer ++;
		 }
		 
		 //7, le burin
		 $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'7\' ');
		 $req->execute(array($_SESSION['id_joueur']));
		 while($donnees = $req->fetch()){
		    $burin ++;
		 }
		 
		 //8, le marteau
		 $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'8\' ');
		 $req->execute(array($_SESSION['id_joueur']));
		 while($donnees = $req->fetch()){
		    $marteau ++;
		 }
		 
		 //9, les cisailles
		 $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'9\' ');
		 $req->execute(array($_SESSION['id_joueur']));
		 while($donnees = $req->fetch()){
		    $cisailles ++;
		 }
	  ?>
	  
	  <?php
	  if(isset($_GET['a'])){
	     switch($_GET['a']){
		    case 'fabriquer':
			   $req = $bdd->prepare('SELECT * FROM atelier_constructions WHERE id=? ');
			   $req->execute(array($_GET['id']));
			   $donnees = $req->fetch();
			   //Vérification des matériaux
			   $erreur = false;
			   if($buches >= $donnees['bois_requis'] AND $bronze >= $donnees['bronze_requis'] 
			   AND $fer >= $donnees['fer_requis']){
			      //Vérification des outils
			      switch($donnees['id_outil_requis']){
				     case 7:
					    if($burin <= 0){
						   $erreur = true;
						}
					 break;
					 
					 case 8:
					    if($marteau <= 0){
						   $erreur = true;
						}
					 break;
					 
					 case 9:
					    if($cisailles <= 0){
						   $erreur = true;
						}
					 break;
				  }
			   }
			   
			   else{
			      $erreur = true;
			   }
			   
			   if($_SESSION['niveau'] < $donnees['niveau_requis']){
			      $erreur = true;
			   }
			   
			   if($erreur){
			      ?>
				  <p class="erreur" >
				     Vous ne pouvez pas fabriquer cet objet car il vous manque soit des matériaux ou un outil
					 nécessaire à sa fabrication!<br />
					 Il est possible aussi que vous n'aillez pas un niveau assez élevé<br />
				  </p>
				  <?php
			   }
			   else{
			      //Nul besoin de regarder l'inventaire.
				  //Lors de la fabrication, au moins un matériaux sera retiré, donc automatiquement assez
				  //de place pour le nouvel objet
				  
				  //Suppression des matériaux
				  $req = $bdd->prepare('DELETE FROM inventaire WHERE id_joueur=? AND id_objet=\'4\' 
				  ORDER BY id LIMIT ' . $donnees['bois_requis'] . '');//Buches
				  $req->execute(array($_SESSION['id_joueur']));
				  
				  $req = $bdd->prepare('DELETE FROM inventaire WHERE id_joueur=? AND id_objet=\'5\' 
				  ORDER BY id LIMIT ' . $donnees['bronze_requis'] . '');//Bronze
				  $req->execute(array($_SESSION['id_joueur']));
				  
				  $req = $bdd->prepare('DELETE FROM inventaire WHERE id_joueur=? AND id_objet=\'6\' 
				  ORDER BY id LIMIT ' . $donnees['fer_requis'] . '');//Fer
				  $req->execute(array($_SESSION['id_joueur']));
				  
				  //Ajout du nouvel objet dans l'inventaire
				  $req = $bdd->prepare('INSERT INTO inventaire (id_joueur, id_objet)
				  VALUES (:id_joueur, :id_objet) ');
				  $req->execute(array(
				  'id_joueur' => $_SESSION['id_joueur'],
				  'id_objet' => $donnees['id_objet_cree']));
				  
				  ?>
				  <p class="succes" >
				     Vous avez bien fabriqué cet objet!<br />
					 Il a été placé dans votre inventaire<br />
				  </p>
				  <?php
			   }
			break;
		 }
	  }
	  ?>
	  <p>
	     <hr />
	  </p>
	  <?php
	  if($_SESSION['profession'] == 'Forgeron'){
	  ?>
	  <table class="table_invisible" >
	     <tr>
		    <td style="width:50%" >
			   <table class="table_visible" >
			      <tr>
				     <th>Liste des matériaux de votre inventaire</th>
				  </tr>
					    <?php
						//Bûches de bois
						$req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'4\' ');
						$req->execute(array($_SESSION['id_joueur']));
						while($objet = $req->fetch()){
						   $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
						   $req_objet->execute(array($objet['id_objet']));
						   $donnees = $req_objet->fetch();
						   ?>
						   <tr>
						      <td>
						         <?php
						         echo $donnees['nom'];
						         ?>
						      </td>
						   </tr>
						   <?php
						}
						
						//Lingots de bronze
						$req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'5\' ');
						$req->execute(array($_SESSION['id_joueur']));
						while($objet = $req->fetch()){
						   $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
						   $req_objet->execute(array($objet['id_objet']));
						   $donnees = $req_objet->fetch();
						   ?>
						   <tr>
						      <td>
						         <?php
						         echo $donnees['nom'];
						         ?>
						      </td>
						   </tr>
						   <?php
						}
						
						//Lingots de fer
						$req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'6\' ');
						$req->execute(array($_SESSION['id_joueur']));
						while($objet = $req->fetch()){
						   $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
						   $req_objet->execute(array($objet['id_objet']));
						   $donnees = $req_objet->fetch();
						   ?>
						   <tr>
						      <td>
						         <?php
						         echo $donnees['nom'];
						         ?>
						      </td>
						   </tr>
						   <?php
						}
						$req->closeCursor();
						?>
			   </table>
			</td>
			
			<td>
			   <table class="table_visible" >
			      <tr>
				     <th>Liste des outils que vous avez dans votre inventaire</th>
				  </tr>
				  
				  <?php
				  //Burin
				  $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'7\' ');
				  $req->execute(array($_SESSION['id_joueur']));
				  while($objet = $req->fetch()){
				     $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
					 $req_objet->execute(array($objet['id_objet']));
					 $donnees = $req_objet->fetch();
					 ?>
					 <tr>
					    <td>
						   <?php
						   echo $donnees['nom'];
						   ?>
						</td>
					 </tr>
					 <?php
				  }
				  
				  //Marteau
				  $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'8\' ');
				  $req->execute(array($_SESSION['id_joueur']));
				  while($objet = $req->fetch()){
				     $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
					 $req_objet->execute(array($objet['id_objet']));
					 $donnees = $req_objet->fetch();
					 ?>
					 <tr>
					    <td>
						   <?php
						   echo $donnees['nom'];
						   ?>
						</td>
					 </tr>
					 <?php
				  }
				  
				  //Cisailles
				  $req = $bdd->prepare('SELECT id_objet FROM inventaire WHERE id_joueur=? AND id_objet=\'9\' ');
				  $req->execute(array($_SESSION['id_joueur']));
				  while($objet = $req->fetch()){
				     $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
					 $req_objet->execute(array($objet['id_objet']));
					 $donnees = $req_objet->fetch();
					 ?>
					 <tr>
					    <td>
						   <?php
						   echo $donnees['nom'];
						   ?>
						</td>
					 </tr>
					 <?php
				  }
				  $req->closeCursor();
				  ?>
			   </table>
			</td>
		 </tr>
	  </table>
	  
	  <p>
	     <hr />
	  </p>
	  
	  <table>
		    <caption>Liste des objets que vous pouvez fabriquer</caption>

		 <tr>
		    <th>Objet</th>
			<th>Bûches</th>
			<th>Lingots de bronze</th>
			<th>Lingots de fer</th>
			<th>Outil</th>
			<th>Action</th>
		 </tr>
		 
		 <?php
		 $req = $bdd->prepare('SELECT * FROM atelier_constructions WHERE bois_requis <=? AND bronze_requis <=? AND fer_requis <=? AND niveau_requis <=?');
		 $req->execute(array($buches, $bronze, $fer, $_SESSION['niveau']));
		 while($donnees = $req->fetch()){
		    $req_objet = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
			$req_objet->execute(array($donnees['id_objet_cree']));
			$objet = $req_objet->fetch();
			$req_outil = $bdd->prepare('SELECT nom FROM objets WHERE id=? ');
			$req_outil->execute(array($donnees['id_outil_requis']));
			$outil = $req_outil->fetch();
		    ?>
			<tr>
			   <td><?php echo $objet['nom']; ?></td>
			   <td><?php echo $donnees['bois_requis']; ?></td>
			   <td><?php echo $donnees['bronze_requis']; ?></td>
			   <td><?php echo $donnees['fer_requis']; ?></td>
			   <td><?php echo $outil['nom']; ?></td>
			   <td><a href="atelier.php?co=o&amp;a=fabriquer&amp;id=<?php echo $donnees['id']; ?>" >Fabriquer</a></td>
			</tr>
			<?php
		 }
		 ?>
	  </table>
	  <?php
	  }
	  else{
	     ?>
		 <p class="erreur" >
		    Vous n'êtes pas forgeron et donc ne possedez pas d'atelier.<br />
		 </p>
		 <?php
	  }
	  ?>
   </div>
   <?php include("includes/menu_jeu.php"); ?>
   <?php include("includes/pied_de_page.php");?>
   </body>
</html>