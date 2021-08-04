   <div id="menu_actions_index">
      <p><a href="index.php" >Accueil</a><br />
	  <?php
	  if(!isset($_GET['maitre'])){
	  ?>
      <strong><a href="inscription.php">Inscription</a></strong><br />
	  <?php
	  }
	  else{
	  ?>
	  <strong><a href="inscription.php?maitre=<?php echo $_GET['maitre']; ?>">Inscription</a></strong><br />
	  <?php
	  }
	  ?>
      <a href="#" onclick="JavaScript:window.open('description.php', 'Description', 'width=500, height=600, resizable=no, statuts=no, menubar=no, scrollbars=yes')">Présentation du jeu</a>
	  <br />
	  </p>
	  <hr />
	  <h6 style="color:red">Nouveau</h6>
	     <p>
		    <a href="archives_forum.php">Aller aux archives du forum de jeu</a>
		 </p>
	  <hr />
	  <h6>Téléchargements</h6>
      <p><!-- Écritures -->
	     <span title="Placer ces fichiers dans votre dossier Fonts de votre ordinateur. Sur Windows: C:\Windows\Fonts">
			<span style="color:green">Clic-droit: Enregistrer la cible du lien sous...</span><br />
		    <a href="downloads/Belwe Cn BT.ttf">Belwe Cn BT (écriture)</a><br />
		    <a href="downloads/Monotype Corsiva.ttf">Monotype Corsiva (écriture)</a><br />
		 </span>
	  </p>
   </div>