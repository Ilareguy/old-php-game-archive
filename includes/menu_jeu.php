   <?php
//Test de connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=totoila_berceaud', 'totoila_berceaud', 'n9DTlUgIoAzm');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>
   <div id="menu_actions_jeu">
   
   <div id="infos" style="overflow:auto">
   <h6>Informations</h6>
   <!-- Pièces d'or -->
   <?php
   $req = $bdd->prepare('SELECT argent, niveau FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $joueur = $req->fetch();   
   ?>
   <p><strong><?php echo $joueur['argent'];?> pièces d'or</strong></p>
   <hr />
      <!-- Message aléatoire -->
      <div>
	     <p name="message_aleatoire">
			<?php
			//Générer les messages aléatoires
			$type_message = rand(1, 2);
			switch($type_message){
			   case 1:
			      //Attaques
			      $req = $bdd->query('SELECT id FROM attaques WHERE combat_termine=\'0\' ORDER BY id DESC');//Nombre d'attaques en cours
			      $nb_attaques = 0;
			      $id_attaques = array();
			      while($donnees = $req->fetch()){
			         $id_attaques[$nb_attaques] = $donnees['id'];
			         $nb_attaques++;
			      }
				  if($nb_attaques != 0){
			         $req = $bdd->prepare('SELECT pos_x_cible, pos_y_cible FROM attaques WHERE id=? ');
			         $req->execute(array($id_attaques[array_rand($id_attaques)]));
			         $donnees = $req->fetch();
				     $message = 'Des troupes seraient en train de lancer une attaque 
				     vers la case ' . $donnees['pos_x_cible'] . ', ' . $donnees['pos_y_cible'] . ' !';
				  }
				  else{
				     $message = 'Aucune nouvelle importante';
				  }
				  $req->closeCursor();
			   break;
			   case 2:
			      //Déplacements
				  $req = $bdd->query('SELECT id FROM deplacements');//Nombre de déplacements
				  $nb_deplacements = 0;
				  $id_deplacements = array();
				  while($donnees = $req->fetch()){
				     $id_deplacements[$nb_deplacements] = $donnees['id'];
				     $nb_deplacements++;
				  }
				  if($nb_deplacements != 0){
				     $req = $bdd->prepare('SELECT x, y FROM deplacements WHERE id=? ');
			         $req->execute(array($id_deplacements[array_rand($id_deplacements)]));
			         $donnees = $req->fetch();
				     $message = 'Un clan serait en train de se déplacer 
				     vers la case ' . $donnees['x'] . ', ' . $donnees['y'] . ' !';
				  }
				  else{
				     $message = 'Aucune nouvelle importante';
				  }
				  $req->closeCursor();
			   break;
			}
			   echo $message; 
			?>
		 </p>
      </div>
   </div>
   
   <p><a href="jeu_accueil.php?co=o" >Général</a><br />
   <a href="mon_clan.php?co=o">Mon clan</a><br />
   </p>
   <hr />
   <p>
   <a href="forum.php?co=o" >Forum</a><br />
   <?php
   /***************************************La messagerie**********************************************/
   $reponse = $bdd->prepare('SELECT message_lu FROM messagerie WHERE id_destinataire=? ');
   $reponse->execute(array($_SESSION['id_joueur']));
   $nb_nouveau_message = 0;
   $nouveau_message = false;
   while($donnees = $reponse->fetch()){
      if($donnees['message_lu'] == 0){
         $nouveau_message = true;
         $nb_nouveau_message++;
      }
      else{
      }
   }
   if($nouveau_message == true){
      echo '<a href="messagerie.php?co=o&amp;type=0" id="nouveau_message" >Messagerie(' . $nb_nouveau_message . ')</a><br />'; 
   }
   else{
      ?> <a href="messagerie.php?co=o&amp;type=0" >Messagerie</a><br />
   <?php 
   }
   ?>
   <?php
   /************************************L'alliance (candidatures)*************************************/
   //On détermine l'id de l'alliance
      $req = $bdd->prepare('SELECT id_alliance FROM joueurs WHERE id=? ');
      $req->execute(array($_SESSION['id_joueur']));
      $id_alliance = $req->fetch();
   if($id_alliance['id_alliance'] != 0){
   //On regarde si le joueur est bien un dirigeant
	   $req = $bdd->prepare('SELECT id_joueur_meneur, nom, id_joueur_maitre_de_guerre FROM alliances WHERE id=? ');//+ le nom de l'alliance
	   $req->execute(array($id_alliance['id_alliance']));
	   $meneurs = $req->fetch();
	   if($meneurs['id_joueur_meneur'] == $_SESSION['id_joueur']){
	      //On regarde s'il y a une nouvelle candidature
		  $req = $bdd->prepare('SELECT id FROM alliances_postulations WHERE id_alliance=? ');
		  $req->execute(array($id_alliance['id_alliance']));
		  $nb_post_trouves = 0;
		  while($donnees = $req->fetch()){
		     $nb_post_trouves ++;
		  }
		  //On regarde s'il y a des demandes de pacte
		  $req = $bdd->prepare('SELECT id FROM pactes_postulations WHERE id_alliance_cible=? ');
		  $req->execute(array($id_alliance['id_alliance']));
		  while($donnees = $req->fetch()){
		     $nb_post_trouves++;
		  }
		  if($nb_post_trouves != 0){
		     echo '<a href="alliances.php?co=o" id="nouvelle_postulation" >Alliances(' . $nb_post_trouves . ')</a><br />';
		  }
		  else{
		     echo '<a href="alliances.php?co=o" >Alliances</a><br />';
		  }
	   }
	   
	   else if($meneurs['id_joueur_maitre_de_guerre'] == $_SESSION['id_joueur']){
	      //On regarde s'il y a des demandes de pacte
		  $req = $bdd->prepare('SELECT id FROM pactes_postulations WHERE id_alliance_cible=? ');
		  $req->execute(array($id_alliance['id_alliance']));
		  $nb_post_trouves = 0;
		  while($donnees = $req->fetch()){
		     $nb_post_trouves++;
		  }
		  if($nb_post_trouves != 0){
		     echo '<a href="alliances.php?co=o" id="nouvelle_postulation" >Alliances(' . $nb_post_trouves . ')</a><br />';
		  }
		  else{
		     echo '<a href="alliances.php?co=o" >Alliances</a><br />';
		  }
	   }
	   
	   else{
          echo '<a href="alliances.php?co=o" >Alliances</a><br />';
       }
	   }
	   else{
	      echo '<a href="alliances.php?co=o" >Alliances</a><br />';
	   }
   ?>
   <a href="magasin.php?co=o" >Magasin</a><br />
   <a href="carte.php?co=o&amp;cartex=0&amp;cartey=0" >Carte</a><br />
   </p>
   <hr />
   <p>
   <a href="inventaire.php?co=o" >Inventaire</a><br />
   <a href="objectifs.php?co=o&amp;page=1&amp;niveau=<?php echo $joueur['niveau'];?>" >Objectifs</a><br />
   <a href="missions.php?co=o&amp;page=1&amp;niveau=<?php echo $joueur['niveau'];?>" >Missions</a><br />
   <a href="ameliorations.php?co=o" >Améliorations</a><br />
   <?php
      if($_SESSION['profession'] == 'Forgeron'){
	     ?>
		 <a href="atelier.php?co=o" >Atelier de forgeron</a>
		 <?php
	  }
   ?>
   </p>
   <hr />
   <p>
   <a href="classements.php?co=o">Classements</a><br />
   <a href="connectes.php?co=o">Liste des connectés</a>
   <a href="jeu_accueil.php?co=d" >Déconnexion</a> <br /></p>
   </div>
   <?php $req->closeCursor(); ?>
