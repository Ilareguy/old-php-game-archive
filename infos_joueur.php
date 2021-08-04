<?php
   $req = $bdd->prepare('SELECT * FROM joueurs WHERE id=? ');
   $req->execute(array($_SESSION['id_joueur']));
   $donnees = $req->fetch();   
?>
<div id="infos_joueur" >
   <h3><?php echo $donnees['pseudo'];?><br /></h3>
   <h4>Informations</h4>
   <p><img src="images/Puces/piece_or.gif" alt="or" /> <?php echo $donnees['argent'];?></p>
   <p>Membres de clan
      <ul style="text-align:left" >
         <li id="point_liste_infanterie" title="Membres d'infanterie" >: <?php echo $donnees['infanterie'];?></li>
	     <li id="point_liste_assassin" title="Assassins" >: <?php echo $donnees['assassin'];?></li>
	     <li id="point_liste_archer" title="Archers" >: <?php echo $donnees['archer'];?></li>
      </ul>
   </p>
</div>