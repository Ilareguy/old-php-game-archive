<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Classements</title>
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
      <div id="centre">
	     <h2>Classements</h2>
		 <?php
		 if(!isset($_GET['classement'])){
		    ?>
			<p>
			   Si vous n'êtes pas le plus riche ni le plus fort, visitez
			   les autres classements.<br />
			   Seule la puissance ne peut pas faire qu'un clan soit à la tête de tous les classements.<br />
			   Mais votre clan, lui, se situe où ?<br />
			</p>
			<table>
			   <tr>
			      <th colspan="2">Classements de joueurs</th>
			   </tr>
			   <tr>
			      <td style="width:75%">Classement global (Niveaux et victoires)</td>
			      <td><a href="classements.php?co=o&amp;classement=1&amp;page=1">Voir</a></td>
			   </tr>
			   <tr>
			      <td>Par pièces d'or volées au total</td>
				  <td><a href="classements.php?co=o&amp;classement=2&amp;page=1">Voir</a></td>
			   </tr>
			   <tr>
			      <td>Par messages au forum</td>
				  <td><a href="classements.php?co=o&amp;classement=3&amp;page=1">Voir</a></td>
			   </tr>
			</table>
			<hr />
			<table>
			   <tr>
			      <th colspan="2">Classements des alliances</td>
			   </tr>
			   <tr>
			      <td>Par nombre de clans</td>
				  <td><a href="classements.php?co=o&amp;classement=4&amp;page=1">Voir</a></td>
			   </tr>
			   <tr>
			      <td>Par ancienneté</td>
				  <td><a href="classements.php?co=o&amp;classement=5&amp;page=1">Voir</a></td>
			   </tr>
			</table>
			<?php
		 }
		 else{
		 $req = $bdd->query('SELECT COUNT(*) AS nb_joueurs FROM joueurs WHERE pseudo!=\'Les hauts\' ');
		 $nb_joueurs = $req->fetch();
		 $nb_pages_joueurs = ($nb_joueurs['nb_joueurs'] / 15);
		 $nb_pages_joueurs = ceil($nb_pages_joueurs);
		 
		 $req = $bdd->query('SELECT COUNT(*) AS nb_alliances FROM alliances ');
		 $nb_alliances = $req->fetch();
		 $nb_pages_alliances = ($nb_alliances['nb_alliances'] / 15);
		 $nb_pages_alliances = ceil($nb_pages_alliances);
		 ?>
		 <h6>
		    Il y a <?php echo $nb_joueurs['nb_joueurs']; ?> clans 
			et <?php echo $nb_alliances['nb_alliances']; ?> alliances sur ces terres <br />
		 </h6>
		 <?php
		 switch($_GET['classement']){
		    case 1:
			   /*
			   Classement de joueurs par niveau
			   Si deux joueurs ont le même niveau, le meilleur
			   sera celui qui aura le plus de victoires
			   */
		       ?>
		       <table name="classement">
		          <tr>
			         <th style="width:10%">Place</th>
			         <th style="width:25%">Pseudo</th>
			         <th style="width:30%">Alliance</th>
			         <th style="width:20%">Victoires</th>
			         <th>Niveau</th>
			      </tr>
				  <tr>
				     <td colspan="5">
					    <select name="page" onchange="golinks(this.options[this.selectedIndex].value)">
						   <option selected="selected" value="">Sauter vers la page...</option>
						   <optgroup label="Pages">
						      <?php
							  for($i=1;$i!=($nb_pages_joueurs+1);$i++){
							     ?>
								 <option value="classements.php?co=o&amp;classement=1&amp;page=<?php echo $i; ?>"><?php echo $i; ?></option>
								 <?php
							  }
							  ?>
						   </optgroup>
						</select>
					 </td>
				  </tr>
				  
				  <?php
				     $req = $bdd->prepare('SELECT pseudo, victoires, id_alliance, niveau FROM joueurs WHERE pseudo!=\'Les hauts\' ORDER BY niveau DESC, victoires DESC');
					 $req->execute(array());
					 $place = 0;
					 while($donnees = $req->fetch() AND $place!=(($_GET['page'] * 15))){
					    if($place < ($_GET['page'] * 15) - 15){
						   $place++;
						}
						else{
					    ?>
						<tr>
						   <td><?php echo $place + 1; ?></td>
						   <td><a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a></td>
						   <td>
						      <?php
							  $req2 = $bdd->prepare('SELECT nom FROM alliances WHERE id=? ');
							  $req2->execute(array($donnees['id_alliance']));
							  $alliance = $req2->fetch();
							  echo $alliance['nom'];
							  ?>
						   </td>
						   <td><?php echo $donnees['victoires']; ?></td>
						   <td><?php echo $donnees['niveau']; ?></td>
						</tr>
						<?php
						$place++;
						}
					 }
				  ?>
		       </table>
		       <?php
		    break;
			case 2:
			/*
			Classement des joueurs par pièces d'or volés
			*/
			?>
		       <table name="classement">
		          <tr>
			         <th style="width:15%">Place</th>
			         <th style="width:35%">Pseudo</th>
					 <th style="width:35%">Pièces d'or volés</th>
			         <th>Niveau</th>
			      </tr>
				  <tr>
				     <td colspan="5">
					    <select name="page" onchange="golinks(this.options[this.selectedIndex].value)">
						   <option selected="selected" value="">Sauter vers la page...</option>
						   <optgroup label="Pages">
						      <?php
							  for($i=1;$i!=($nb_pages_joueurs+1);$i++){
							     ?>
								 <option value="classements.php?co=o&amp;classement=2&amp;page=<?php echo $i; ?>"><?php echo $i; ?></option>
								 <?php
							  }
							  ?>
						   </optgroup>
						</select>
					 </td>
				  </tr>
				  
				  <?php
				     $req = $bdd->prepare('SELECT pseudo, victoires, or_vole_total, niveau FROM joueurs WHERE pseudo!=\'Les hauts\' ORDER BY or_vole_total DESC, niveau DESC, victoires DESC');
					 $req->execute(array());
					 $place = 0;
					 while($donnees = $req->fetch() AND $place!=(($_GET['page'] * 15))){
					    if($place < ($_GET['page'] * 15) - 15){
						   $place++;
						}
						else{
					    ?>
						<tr>
						   <td><?php echo $place + 1; ?></td>
						   <td><a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a></td>
						   <td><?php echo $donnees['or_vole_total']; ?></td>
						   <td><?php echo $donnees['niveau']; ?></td>
						</tr>
						<?php
						$place++;
						}
					 }
				  ?>
		       </table>
		       <?php
			break;
			case 3:
			   /*
			   Classement par le nombre de messages au forum
			   */
			   ?>
		       <table name="classement">
		          <tr>
			         <th style="width:15%">Place</th>
			         <th style="width:60%">Pseudo</th>
					 <th>Messages au forum</th>
			      </tr>
				  <tr>
				     <td colspan="5">
					    <select name="page" onchange="golinks(this.options[this.selectedIndex].value)">
						   <option selected="selected" value="">Sauter vers la page...</option>
						   <optgroup label="Pages">
						      <?php
							  for($i=1;$i!=($nb_pages_joueurs+1);$i++){
							     ?>
								 <option value="classements.php?co=o&amp;classement=3&amp;page=<?php echo $i; ?>"><?php echo $i; ?></option>
								 <?php
							  }
							  ?>
						   </optgroup>
						</select>
					 </td>
				  </tr>
				  
				  <?php
				     $req = $bdd->prepare('SELECT pseudo, nb_connexions, nb_messages_forum FROM joueurs WHERE pseudo!=\'Les hauts\' ORDER BY nb_messages_forum DESC, nb_connexions DESC ');
					 $req->execute(array());
					 $place = 0;
					 while($donnees = $req->fetch() AND $place!=(($_GET['page'] * 15))){
					    if($place < ($_GET['page'] * 15) - 15){
						   $place++;
						}
						else{
					    ?>
						<tr>
						   <td><?php echo $place + 1; ?></td>
						   <td><a href="fiche_joueur.php?co=o&amp;pseudo=<?php echo $donnees['pseudo']; ?>"><?php echo $donnees['pseudo']; ?></a></td>
						   <td><?php echo $donnees['nb_messages_forum']; ?></td>
						</tr>
						<?php
						$place++;
						}
					 }
				  ?>
		       </table>
		       <?php
			break;
			case 4:
			   /*
			   Classement par clans dans une alliance
			   */
			   ?>
		       <table name="classement">
		          <tr>
			         <th style="width:15%">Place</th>
			         <th style="width:60%">Nom de l'alliance</th>
					 <th>Nombre de clans</th>
			      </tr>
				  <tr>
				     <td colspan="5">
					    <select name="page" onchange="golinks(this.options[this.selectedIndex].value)">
						   <option selected="selected" value="">Sauter vers la page...</option>
						   <optgroup label="Pages">
						      <?php
							  for($i=1;$i!=($nb_pages_alliances+1);$i++){
							     ?>
								 <option value="classements.php?co=o&amp;classement=4&amp;page=<?php echo $i; ?>"><?php echo $i; ?></option>
								 <?php
							  }
							  ?>
						   </optgroup>
						</select>
					 </td>
				  </tr>
				  
				  <?php
				     // $alliance[id_de_l'alliance]['nb'] = nombre de joueurs
					 // $alliance[id_de_l'alliance]['nom'] = nom de l'alliance
					 $alliance = array();
					 $num_alliance;
					 $req = $bdd->prepare('SELECT id, nom, nb_joueurs FROM alliances ORDER BY nb_joueurs DESC, id DESC');
					 $req->execute(array());
					 $place = 0;
					 while($donnees = $req->fetch() AND $place!=(($_GET['page'] * 15))){
					    if($place < ($_GET['page'] * 15) - 15){
						   $place++;
						}
						else{
					       ?>
						   <tr>
						      <td><?php echo $place + 1; ?></td>
						      <td><a href="fiche_alliance.php?co=o&amp;alliance=<?php echo $donnees['nom']; ?>"><?php echo $donnees['nom']; ?></a></td>
						      <td><?php echo $donnees['nb_joueurs']; ?></td>
						   </tr>
						<?php
						$place++;
						}
					 }
				  ?>
		       </table>
		       <?php
			break;
			case 5:
			   /*
			   Classement par clans dans une alliance
			   */
			   ?>
		       <table name="classement">
		          <tr>
			         <th style="width:15%">Place</th>
			         <th style="width:60%">Nom de l'alliance</th>
			      </tr>
				  <tr>
				     <td colspan="5">
					    <select name="page" onchange="golinks(this.options[this.selectedIndex].value)">
						   <option selected="selected" value="">Sauter vers la page...</option>
						   <optgroup label="Pages">
						      <?php
							  for($i=1;$i!=($nb_pages_alliances+1);$i++){
							     ?>
								 <option value="classements.php?co=o&amp;classement=5&amp;page=<?php echo $i; ?>"><?php echo $i; ?></option>
								 <?php
							  }
							  ?>
						   </optgroup>
						</select>
					 </td>
				  </tr>
				  
				  <?php
				     // $alliance[id_de_l'alliance]['nb'] = nombre de joueurs
					 // $alliance[id_de_l'alliance]['nom'] = nom de l'alliance
					 $alliance = array();
					 $num_alliance;
					 $req = $bdd->prepare('SELECT id, nom FROM alliances ORDER BY id');
					 $req->execute(array());
					 $place = 0;
					 while($donnees = $req->fetch() AND $place!=(($_GET['page'] * 15))){
					    if($place < ($_GET['page'] * 15) - 15){
						   $place++;
						}
						else{
					       ?>
						   <tr>
						      <td><?php echo $place + 1; ?></td>
						      <td><a href="fiche_alliance.php?co=o&amp;alliance=<?php echo $donnees['nom']; ?>"><?php echo $donnees['nom']; ?></a></td>
						   </tr>
						<?php
						$place++;
						}
					 }
				  ?>
		       </table>
		       <?php
			break;
		 }
		 }
		 ?>
	  </div>
	  <?php include("includes/pied_de_page.php"); ?>
	  <?php include("includes/menu_jeu.php"); ?>
   </body>
</html>