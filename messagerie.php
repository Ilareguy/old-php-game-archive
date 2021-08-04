<?php include ("includes/avant_html.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Messagerie</title>
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
	  <h2>Messagerie</h2>
	  <?php 
   //Action à faire
      if(isset($_GET['action']) AND isset($_GET['id'])){
	     $reponse = $bdd->prepare('SELECT * FROM messagerie WHERE id=?');
		 $reponse->execute(array($_GET['id']));
         $donnees_message = $reponse->fetch();
   switch($_GET['action']){
      case 'sup':
	  if($_SESSION['id_joueur'] == $donnees_message['id_destinataire']){
	  $bdd->exec('DELETE FROM messagerie WHERE id=' . $_GET['id']);?>
	  <div class="succes"><p>Message supprimé</p></div>
	  <?php 
	  }
	     else{
	        ?>
			<p class="erreur">
			   Impossible de supprimer ce message<br />
			</p>
			<?php
	     }
   break;

      case 'env':
	  $req = $bdd->prepare('SELECT id FROM joueurs WHERE pseudo_min=? ');
	  $req->execute(array(strtolower($_POST['destinataire'])));
	  $id_destinataire = $req->fetch();
	  if(!empty($_POST['titre']) AND($_POST['message_textarea']) AND($_POST['destinataire'])){
	  echo '<p class="succes" " >
	  Vous pouvez voir votre messager disparaître au dos de son cheval.<br />' . htmlspecialchars($_POST['destinataire']) . ' devrait reçevoir 
	  votre lettre d\'ici peu.</p>';
	  $req = $bdd->prepare('INSERT INTO `messagerie` (`titre`, `destinateur`, `id_destinataire`, `message`, `message_lu`, `date`) 
	  VALUES (:titre, :destinateur, :id_destinataire, :message, 0, :date)');
	  $req->execute(array(
	  'titre' => stripslashes($_POST['titre']),
	  'destinateur' => $_SESSION['pseudo'],
	  'id_destinataire' => $id_destinataire['id'],
	  'message' => stripslashes($_POST['message_textarea']),
	  'date' => date('Y-m-d H:i:s')));
	  }
	  else{echo '<p class="erreur" " >Par manque d\'information, 
	  il vous est impossible d\'envoyer votre lettre...</p>';}
   break;
   }
}
?>
	  <?php
	     //On regarde les nouveaux messages de chaque sorte
		 $reponse = $bdd->prepare('SELECT message_lu, type FROM messagerie WHERE id_destinataire=? ');
         $reponse->execute(array($_SESSION['id_joueur']));
		 $nouveau_message = 0;
		 $messages_total = 0;
		 $nouveau_rapport_combat = 0;
		 $rapports_combat_total = 0;
		 $nouveau_rapport_espion = 0;
		 $rapports_espion_total = 0;
         while($donnees = $reponse->fetch()){
            if($donnees['message_lu'] == 0){
			switch($donnees['type']){
			   case 0:
			      $nouveau_message ++;
			   break;
			   case 1:
			      $nouveau_rapport_combat ++;
			   break;
			   case 2:
			      $nouveau_rapport_espion ++;
			   break;
			}
         }
		 else{
		    switch($donnees['type']){
			   case 0:
			      $messages_total ++;
			   break;
			   case 1:
			      $rapports_combat_total ++;
			   break;
			   case 2:
			      $rapports_espion_total ++;
			   break;
			}
		 }
         }
		 $messages_total += $nouveau_message;
		 $rapports_combat_total += $nouveau_rapport_combat;
		 $rapports_espion_total += $nouveau_rapport_espion;
	  ?>
	  <ul style="text-align:left" >
	     <li><a href="messagerie.php?co=o&amp;type=0" >Messages normaux (<?php echo $nouveau_message;?>)</a></li>
		 <li><a href="messagerie.php?co=o&amp;type=1" >Rapports défensifs (<?php echo $nouveau_rapport_combat;?>)</a></li>
		 <li><a href="messagerie.php?co=o&amp;type=2" >Rapports d'infiltrations (<?php echo $nouveau_rapport_espion;?>)</a></li>
	  </ul>
	  <!-- Messages normaux -->
	  <?php if($_GET['type'] == 0){ ?>
		 <p><a href="message.php?co=o&amp;action=ecrire&amp;id=0"<!-- l'id n'importe pas ici -->
		 <img src="images/Templates/design_defaut/Boutons/nouvelle_lettre.png" 
               onmouseover="this.src='images/Templates/design_defaut/Boutons/nouvelle_lettre_mouseover.png';" 
               onmouseout="this.src='images/Templates/design_defaut/Boutons/nouvelle_lettre.png';" /></a></p>
		 <table id="table_messagerie">
		 <caption>Vos messages</caption>
		 <thead>
		 <tr>
		    <th>Actions</th>
		    <th style="width:50%">Titre</th>
			<th style="width:15%">Destinateur</th>
			<th>Date</th>
		 </tr>
		 <tr>
		    <td colspan="4" >
			   <select onchange="golinks(this.options[this.selectedIndex].value)" >
			      <option selected="selected" value="" >Sauter vers la page...</option>
				  <optgroup label="Pages" >
			      <?php
				  $messages_total /= 15;
				  $messages_total = ceil($messages_total);
				  for($i=1;$i!=($messages_total + 1);$i++){
				     ?>
					 <option value="messagerie.php?co=o&amp;type=0&amp;page=<?php echo $i; ?>" >
					 <?php
					 echo $i;
					 ?>
					 </option>
					 <?php
				  }
				  ?>
				  </optgroup>
			   </select>
			</td>
		 </tr>
		 </thead>
		 	     <tfoot>
		 <tr>
		    <th>Actions</th>
		    <th style="width:50%">Titre</th>
			<th style="width:15%">Destinateur</th>
			<th>Date</th>
		 </tr>
		 </tfoot>
		 <tbody>
		 <?php
		 $numero_max = 15;
		 $numero_min = 0;
		 $numero = 0;
		 $reponse = $bdd->prepare('SELECT * FROM messagerie WHERE type=\'0\' AND id_destinataire=? ORDER BY id DESC');
		 if(isset($_GET['page'])){
		    $numero_min = ($_GET['page'] * 15) - 15;
		    $numero_max = ($_GET['page'] * 15);
		 }
		 $reponse->execute(array($_SESSION['id_joueur']));
		 $nb_messages = 0;
         while($donnees = $reponse->fetch() AND $numero != $numero_max){
		 if($numero < $numero_min){
	        $numero++;
	     }
		 else{
		 $nb_messages++;
		 ?>
		 <tr>
		 <?php if($donnees['message_lu'] == 1){ ?>
		 <td><br />
			<form>
			      <select onchange="golinks(this.options[this.selectedIndex].value)" >
				     <optgroup label="Actions">
				        <option value=<?php echo '"message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '"';?> >Ouvrir</option>
					    <option value=<?php echo '"message.php?co=o&amp;action=rep&amp;id=' . $donnees['id'] . '"';?> >Répondre</option>
					 </optgroup>
					 <optgroup label="Action irréversible" >
					    <option value=<?php echo '"messagerie.php?co=o&amp;action=sup&amp;id=' . $donnees['id'] . '&amp;type=0"';?> >Supprimer</option>
					 </optgroup>
				  </select>
			</form>
			<br /></td>
			   <?php
		       echo '<td><a href="message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '" >' . htmlspecialchars($donnees['titre']) . '</a></td>';
			   if($donnees['destinateur'] == 'Votre messager'){
			      echo '<td>Votre messager</td>';
			   }
			   else{
			      echo '<td><a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($donnees['destinateur']) . '" >' . htmlspecialchars($donnees['destinateur']) . '</a></td>';
			   }
			   echo '<td>' . $donnees['date'] . '</td>'; 
			   }
			else{ ?>
			<td class="nouveau_message_liste "><br />
			<form>
			      <select onchange="golinks(this.options[this.selectedIndex].value)" >
				  <option selected="selected" ></option>
				     <optgroup label="Actions">
				        <option value=<?php echo '"message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '"';?> >Ouvrir</option>
					    <option value=<?php echo '"message.php?co=o&amp;action=rep&amp;id=' . $donnees['id'] . '"';?> >Répondre</option>
					 </optgroup>
					 <optgroup label="Action irréversible" >
					    <option value=<?php echo '"messagerie.php?co=o&amp;action=sup&amp;id=' . $donnees['id'] . '&amp;type=0"';?> >Supprimer</option>
					 </optgroup>
				  </select>
			</form>
			<br /></td>
			   <?php
			   echo '<td class="nouveau_message_liste" ><a href="message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '" >' . htmlspecialchars($donnees['titre']) . '</a></td>';
			   if($donnees['destinateur'] == 'Votre messager'){
			      echo '<td class="nouveau_message_liste" >Votre messager</td>';
			   }
			   else{
			      echo '<td class="nouveau_message_liste" ><a href="fiche_joueur.php?co=o&amp;pseudo=' . htmlspecialchars($donnees['destinateur']) . '" >' . htmlspecialchars($donnees['destinateur']) . '</a></td>';
			   }
			   echo '<td class="nouveau_message_liste" >' . $donnees['date'] . '</td>';
			} 
			?>
		 </tr>
		 <?php
		 $numero++;
		 }
		 }
		 if($nb_messages == 0){?>
		 <td colspan="4">Vous n'avez aucun message!</td>
		 <?php }
      ?>
	  </tbody>
	  </table>
	  <?php 
	  } 
	  else if($_GET['type'] == 1){
	  ?>
	  <!-- Rapports de combat -->
	     <table id="table_messagerie">
		 <caption>Vos rapports défensifs</caption>
		 <thead>
		 <tr>
		    <th>Actions</th>
		    <th style="width:70%">Titre</th>
			<th style="width:15%">Date</th>
		 </tr>
		 <tr>
		    <td colspan="4" >
			   <select onchange="golinks(this.options[this.selectedIndex].value)" >
			      <option selected="selected" value="" >Sauter vers la page...</option>
				  <optgroup label="Pages" >
			      <?php
				  $rapports_combat_total /= 15;
				  $rapports_combat_total = ceil($rapports_combat_total);
				  for($i=1;$i!=($rapports_combat_total + 1);$i++){
				     ?>
					 <option value="messagerie.php?co=o&amp;type=1&amp;page=<?php echo $i; ?>" >
					 <?php
					 echo $i;
					 ?>
					 </option>
					 <?php
				  }
				  ?>
				  </optgroup>
			   </select>
			</td>
		 </tr>
		 </thead>
		 	     <tfoot>
		 <tr>
		    <th>Actions</th>
		    <th>Titre</th>
			<th>Date</th>
		 </tr>
		 </tfoot>
		 <tbody>
		 <?php 
		 $numero_max = 15;
		 $numero_min = 0;
		 $numero = 0;
		 $reponse = $bdd->prepare('SELECT * FROM messagerie WHERE type=\'1\' AND id_destinataire=? ORDER BY id DESC');
		 if(isset($_GET['page'])){
		    $numero_min = ($_GET['page'] * 15) - 15;
	        $numero_max = ($_GET['page'] * 15);
		 }
		 $reponse->execute(array($_SESSION['id_joueur']));
		 $nb_messages = 0;
         while($donnees = $reponse->fetch() AND $numero != $numero_max){
		 if($numero < $numero_min){
	        $numero++;
	     }
		 else{
		 $nb_messages++;
		 ?>
		 <tr>
		 <?php if($donnees['message_lu'] == 1){ ?>
		 <td><br />
			<form>
			      <select onchange="golinks(this.options[this.selectedIndex].value)" >
				     <optgroup label="Action">
				        <option value=<?php echo '"message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '"';?> >Ouvrir</option>
					 </optgroup>
					 <optgroup label="Action irréversible" >
					    <option value=<?php echo '"messagerie.php?co=o&amp;action=sup&amp;id=' . $donnees['id'] . '&amp;type=0"';?> >Supprimer</option>
					 </optgroup>
				  </select>
			</form>
			<br /></td>
			   <?php
		       echo '<td><a href="message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '" >' . htmlspecialchars($donnees['titre']) . '</a></td>';
			   echo '<td>' . $donnees['date'] . '</td>'; 
			   }
			else{ ?>
			<td class="nouveau_message_liste "><br />
			<form>
			      <select onchange="golinks(this.options[this.selectedIndex].value)" >
				  <option selected="selected" ></option>
				     <optgroup label="Action">
				        <option value=<?php echo '"message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '"';?> >Ouvrir</option>
					 </optgroup>
					 <optgroup label="Action irréversible" >
					    <option value=<?php echo '"messagerie.php?co=o&amp;action=sup&amp;id=' . $donnees['id'] . '&amp;type=0"';?> >Supprimer</option>
					 </optgroup>
				  </select>
			</form>
			<br /></td>
			   <?php
			   echo '<td class="nouveau_message_liste" ><a href="message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '" >' . htmlspecialchars($donnees['titre']) . '</a></td>';
			   echo '<td class="nouveau_message_liste" >' . $donnees['date'] . '</td>';
			} 
			?>
		 </tr>
		 <?php
		 $numero++;
		 }
		 }
		 if($nb_messages == 0){?>
		 <td colspan="4">Vous n'avez aucun rapport d'affrontement!</td>
		 <?php }
      ?>
	  </tbody>
	  </table>
	  <?php 
	  }
	  
	  else if($_GET['type'] == 2){ ?>
	  <!-- Rapports d'espionnage -->
	     <table id="table_messagerie">
		 <caption>Vos rapports d'espions</caption>
		 <thead>
		 <tr>
		    <th>Actions</th>
		    <th>Titre</th>
			<th>Date</th>
		 </tr>
		 <tr>
		    <td colspan="4" >
			   <select onchange="golinks(this.options[this.selectedIndex].value)" >
			      <option selected="selected" value="" >Sauter vers la page...</option>
				  <optgroup label="Pages" >
			      <?php
				  $rapports_espion_total /= 15;
				  $rapports_espion_total = ceil($rapports_espion_total);
				  for($i=1;$i!=($rapports_espion_total + 1);$i++){
				     ?>
					 <option value="messagerie.php?co=o&amp;type=2&amp;page=<?php echo $i; ?>" >
					 <?php
					 echo $i;
					 ?>
					 </option>
					 <?php
				  }
				  ?>
				  </optgroup>
			   </select>
			</td>
		 </tr>
		 </thead>
		 	     <tfoot>
		 <tr>
		    <th>Actions</th>
		    <th>Titre</th>
			<th>Date</th>
		 </tr>
		 </tfoot>
		 <tbody>
		 <?php 
		 $numero_max = 15;
		 $numero_min = 0;
		 $numero = 0;
		 $reponse = $bdd->prepare('SELECT * FROM messagerie WHERE type=\'2\' AND id_destinataire=? ORDER BY id DESC');
		 if(isset($_GET['page'])){
		    $numero_min = ($_GET['page'] * 15) - 15;
	        $numero_max = ($_GET['page'] * 15);
		 }
		 $reponse->execute(array($_SESSION['id_joueur']));
		 $nb_messages = 0;
         while($donnees = $reponse->fetch() AND $numero != $numero_max){
		 if($numero < $numero_min){
	        $numero++;
	     }
		 else{
		 $nb_messages++;
		 ?>
		 <tr>
		 <?php if($donnees['message_lu'] == 1){ ?>
		 <td><br />
			<form>
			      <select onchange="golinks(this.options[this.selectedIndex].value)" >
				     <optgroup label="Action">
				        <option value=<?php echo '"message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '"';?> >Ouvrir</option>
					 </optgroup>
					 <optgroup label="Action irréversible" >
					    <option value=<?php echo '"messagerie.php?co=o&amp;action=sup&amp;id=' . $donnees['id'] . '&amp;type=0"';?> >Supprimer</option>
					 </optgroup>
				  </select>
			</form>
			<br /></td>
			   <?php
		       echo '<td><a href="message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '" >' . htmlspecialchars($donnees['titre']) . '</a></td>';
			   echo '<td>' . $donnees['date'] . '</td>'; 
			   }
			else{ ?>
			<td class="nouveau_message_liste "><br />
			<form>
			      <select onchange="golinks(this.options[this.selectedIndex].value)" >
				  <option selected="selected" ></option>
				     <optgroup label="Action">
				        <option value=<?php echo '"message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '"';?> >Ouvrir</option>
					 </optgroup>
					 <optgroup label="Action irréversible" >
					    <option value=<?php echo '"messagerie.php?co=o&amp;action=sup&amp;id=' . $donnees['id'] . '&amp;type=0"';?> >Supprimer</option>
					 </optgroup>
				  </select>
			</form>
			<br /></td>
			   <?php
			   echo '<td class="nouveau_message_liste" ><a href="message.php?co=o&amp;action=lire&amp;id=' . $donnees['id'] . '" >' . htmlspecialchars($donnees['titre']) . '</a></td>';
			   echo '<td class="nouveau_message_liste" >' . $donnees['date'] . '</td>';
			} 
			?>
		 </tr>
		 <?php
		 $numero++;
		 }
		 }
		 if($nb_messages == 0){?>
		 <td colspan="4">Vous n'avez aucun rapport d'infiltration!</td>
		 <?php }
      ?>
	  </tbody>
	  </table>
	  <?php } 
	  else{
	     header('Location: index.php');
	  }?>
	  </div>
	  <?php include("includes/menu_jeu.php"); ?>
	  <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>