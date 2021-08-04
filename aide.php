<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Berceau de Guerres - Aide</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	   <link rel="shortcut icon" href="images/ico.ico" />
	   <script type="Text/JavaScript" src="scripts/global.js" ></script>
   </head>
   <body>
      <?php include("includes/banniere.php"); ?>
      <div id="centre">
	     <?php
         if(!isset($_GET['aide'])){
		 ?>
	     <h2>Aide</h2>
		 <p style="text-align:left">
		    Cette section a été mise en place pour vous aider à bien<br />
			démarrer dans <strong>Berceau de Guerres</strong>.<br />
		 </p>
		 <h5>Liste des sections</h5>
		 <table>
		    <tr>
			   <th colspan="2">Sections</th>
			</tr>
			<tr>
			   <td style="width:80%">Les bases</td>
			   <td><a href="aide.php?aide=bases">Voir</a></td>
			</tr>
			<tr>
			   <td>Mon clan</td>
			   <td><a href="aide.php?aide=clan">Voir</a></td>
			</tr>
			<tr>
			   <td>Les professions</td>
			   <td><a href="aide.php?aide=professions">Voir</a></td>
			</tr>
			<tr>
			   <td>Les combats</td>
			   <td><a href="aide.php?aide=combats">Voir</a></td>
			</tr>
			<tr>
			   <td>Les facteurs</td>
			   <td><a href="aide.php?aide=facteurs">Voir</a></td>
			</tr>
			<tr>
			   <td>Les espionnages</td>
			   <td><a href="aide.php?aide=espionnages">Voir</a></td>
			</tr>
			<tr>
			   <td>La messagerie</td>
			   <td><a href="aide.php?aide=messagerie">Voir</a></td>
			</tr>
			<tr>
			   <td>Les alliances</td>
			   <td><a href="aide.php?aide=alliances">Voir</a></td>
			</tr>
			<tr>
			   <td>Mon inventaire</td>
			   <td><a href="aide.php?aide=inventaire">Voir</a></td>
			</tr>
			<tr>
			   <td>Les magasins</td>
			   <td><a href="aide.php?aide=magasins">Voir</a></td>
			</tr>
			<tr>
			   <td>La carte et les déplacements</td>
			   <td><a href="aide.php?aide=carte">Voir</a></td>
			</tr>
			<tr>
			   <td>Les objectifs</td>
			   <td><a href="aide.php?aide=objectifs">Voir</a></td>
			</tr>
			<tr>
			   <td>Les missions</td>
			   <td><a href="aide.php?aide=missions">Voir</a></td>
			</tr>
			<tr>
			   <td>Les améliorations</td>
			   <td><a href="aide.php?aide=ameliorations">Voir</a></td>
			</tr>
			<tr>
			   <td>L'atelier du forgeron</td>
			   <td><a href="aide.php?aide=atelier">Voir</a></td>
			</tr>
		 </table>
		 <?php
		 }
		 else{
		    switch($_GET['aide']){
			   case 'bases':
			      ?>
				  <h2>Les bases</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>La navigation</h3>
					 <p>
					    Avant toute chose, il est fortement recommandé d'utiliser un navigateur qui accepte les cookies
						et le JavaScript. <a href="http://www.mozilla-europe.org/fr/firefox/">Mozilla Firefox</a>
						en est un bon exemple.<br />
						Pour une écriture plus belle, nous vous recommandons aussi de télécharger<br />
						<a href="downloads/Belwe_Cn_BT.ttf">cette écriture</a>.<br />
						<ins>(Si le téléchargement ne se lance pas par lui-même lorsque vous appuyez, faites le Clic droit, et
						Enregistrer la cible du lien sous...)</ins><br />
						Ce fichier (Belwe_Cn_BT.ttf), doit être placé dans le répertoire de votre ordinateur qui contient tous<br />
						les fichiers de ce type. Sous Windows, placez-le dans "C:\Windows\Fonts"
					 </p>
					 <hr />
					 <h3>Jouer à Berceau de Guerres - Inscription</h3>
					 <p>
					    Pour pouvoir vous connecter au jeu et ainsi jouer, vous devez d'abord vous créer un compte. 
						Pour ce ffaire, remplissez le petit formulaire d'inscription <a href="inscription.php">ici</a>. 
						Une fois ce formulaire terminé et envoyé (s'il n'y a pas de message d'erreur), un courrier 
						électronique vous sera envoyé à l'adresse que vous avez entré dans le formulaire. 
						Rendu à cette étape, vous ne pourrez toujours pas vous connecter. Il vous faudra d'abord valider 
						votre inscription. Pour y parvenir, rien de plus simple. Lisez bien le message qui vous a été 
						envoyé via votre adresse courriel et vous y trouverez un lien de validation. 
						Appuyez dessus et vous serez redirigé vers une page de Berceau de Guerres. 
						Un message s'affichera pour vous informer que votre inscription est bien terminée. 
						Une fois cela fait, vous pouvez vous connecter au jeu comme les autres et commencer à jouer ! 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'clan':
			      ?>
				  <h2>Mon clan</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>De quoi est constitué mon clan ?</h3>
					 <p>
					    Votre clan est constitué de trois types de membres (appelés «Membres de clan»). 
						Les membres d'infanterie, les assassins et les archers. 
						Votre clan <ins>n'est pas</ins> constitué d'autres joueurs du jeu. 
					 </p>
					 <hr />
					 <h3>Les membres d'infanterie</h3>
					 <p>
					    Les membres d'infanterie sont l'équilibre en l'attaque et la défense. 
						Ils sont aussi moins dispendieux que les autres types de membres de clan. 
						Une grande partie des armes réservées aux membres d'infanterie n'ont pas 
						de grande différence entre les points d'attaque et les points de défense.
					 </p>
					 <hr />
					 <h3>Les assassins</h3>
					 <p>
					    Les assassins, quant à eux, sont bien plus concentrés sur l'attaque que la défense. 
						Ils sont également plus dispnedieux. 
					 </p>
					 <hr />
					 <h3>Les archers</h3>
					 <p>
					    Les archers permettent de bénificier de plus de points de défense. 
						Ce sont eux qui vous protègeront le plus contre d'éventuelles attaques ! 
					 </p>
					 <hr />
					 <h3>Les points d'attaque et de défense</h3>
					 <p>
					    Chaque fois que vous équiperez un de vos membres de clan d'une arme, des 
						points d'attaque et de défense vous seront rapportés. Pour en connaître le nombre, 
						regardez la description de l'objet lors de son achat. 
						Ce sont ces points qui vous feront gagner en puissance lors des combats. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'professions':
			      ?>
				  <h2>Les professions</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>Les bases</h3>
					 <p>
					    Tous les joueurs de Berceau de Guerres ont une profession bien distincte. 
						Certes, plus d'un joueur peut avoir la même profession. 
						Votre profession, vous la choisissez lors de votre inscription. 
						Chaque profession possède ses propres facteurs (Voir aussi la <a href="aide.php?aide=facteurs">page d'aide 
						sur les facteurs</a>). 
						Lors de l'inscription, vous avez le choix entre quatre (4) professions, soit 
						Guerrier, Marchand, Forgeron ou Explorateur. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'combats':
			      ?>
				  <h2>Les combats</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>Combattre un autre clan</h3>
					 <p>
					    Pour combattre un autre clan, vous devez vous rendre sur la carte 
						et appuyer sur la case de votre choix. Sur cette nouvelle page, vous 
						pourrez voir une liste de tous les joueurs se trouvant sur cette position. 
						À côté de ce joueur se trouve une liste déroulante. Si vous pouvez attaquer ce joueur, 
						vous verrez l'option «Combattre». 
						Lors de l'envoie, vous pourrez choisir de n'envoyer qu'une partie de vos troupes. 
						Ceci dit, si vous ne les envoyez pas toutes, vos points d'attaque et de défense seront 
						divisés ! Si par exemple vous n'envoyez que la moitié de vos troupes, alors ce combat ne comptera 
						que la moitié de vos points d'attaque et de défense. Cette techinque vous permet de lancer plus 
						d,une attaque à la fois !<br />
						<br />
						Une fois l'assaut officiellement lancé, il vous faudra attendre quelques minutes, 
						peu-être même des heures pour que vous troupes arrivent à destination voulue, c'est-à-dire 
						chez votre cible. En revanche, le retour de vos troupes est immédiat.<br />
						<br />
						Il se peut que vos troupent arrivent chez votre cible et que celle-ci ne soit plus sur 
						cette case de la carte ! Dans ce cas, vos troupes reviendront sans avoir à se battre. <br />
						<br />
						Pour lancer l'assaut contre un autre clan, vous devez disposer d'au moins 15 points d'attaque ou de 
						défense ! 
					 </p>
					 <hr />
					 <h3>Les calculs</h3>
					 <p>
					    Bien nombreux sont les joueurs qui s'intéressent aux calculs des combats ! 
						Voici comment les combats fonctionnent...<br />
						<br />
					 </p>
						<table>
						   <tr>
						      <th class="gauche" style="width:50%">L'attaquant</th>
							  <th class="droite">Le défenseur</th>
						   </tr>
						   <tr>
						      <td>
							     Ses points de vie = ses points de défense * 3;<br />
						         Ses dégâts maximums = ses points d'attaque + 10% * son facteur combat;<br />
						         Ses dégâts minimums = la moitié de ses points d'attaque<br />
							  </td>
							  <td class="droite">
							     Ses points de vie = ses points de défense * 3 * son facteur combat;<br />
								 Ses dégâts maximums = ses points de défense + 10%;<br />
								 Ses dégâts minimums = la moitié de ses points de défense;<br />
							  </td>
						   </tr>
						</table>
				  </div>
				  <?php
			   break;
			   case 'facteurs':
			      ?>
				  <h2>Les facteurs</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Les facteurs</h3>
					 <p>
					    Les facteurs doivent être vus comme un pourcentage. 
						Plus ce pourcentage est élevé, plus il vous sera utile et bénéfique. 
					 </p>
					 <hr />
				     <h3>Facteur combat</h3>
					 <p>
					    Le facteur combat vous aidera lors des batailles contre d'autres clans. 
						Il peut très bien vous aider autant pour vos points de vie que pour 
						votre puissance dans une attaque. (Voyez aussi la 
						<a href="aide.php?aide=combats">page d'aide sur les combats</a>)
					 </p>
					 <hr />
					 <h3>Facteur économie</h3>
					 <p>
					    Ce facteur vous aidera à gagner, entre autre, plus d'or par jour. 
						Si votre gain d'or par jour est de douze (12) pièces d'or, il sera 
						multiplié par votre facteur économie.<br />
						Dans le cas présent, si votre facteur économie est de 1.15:<br />
						12 pièces d'or * 1.15 = 13.8 pièces d'or;<br />
						Arrondis à 14 pièces d'or.<br />
					 </p>
					 <hr />
					 <h3>Facteur vitesse</h3>
					 <p>
					    Ce facteur vous permettra de vous déplacer de façon plus rapide et vous 
						permettra aussi de lancer des attaques et des espionnages sur de plus grandes 
						distances.
					 </p>
					 <hr />
					 <h3>Facteur furtif</h3>
					 <p>
					    Ce facteur vous sera avant tout utile lors des espionnages. 
						Un clan qui possède un bon facteur furtif pourrait être utilisé comme 
						espion au sein d'une alliance. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'espionnages':
			      ?>
				  <h2>Les espionnages</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Espionner un autre clan</h3>
					 <p>
					    Pour espionner un autre clan, il vous faudra disposer d'au moins 
						un espion. Vous aurez la possibilité d'envoyer plusieurs espions 
						en mission à la même place ou à des endroits différents sur la carte. 
						Plus vous envoyez d'espions, plus vous aurez des chances de succès ! 
						Vous pouvez donner l'ordre d'espionner plus d'une chose chez votre cible. 
						Cependant, plus vous en donnez, plus la mission sera difficile ! 
						Pour obtenir des espions, il est nécessaire d'aller dans un des deux 
						magasins à objets illimités.
					 </p>
				  </div>
				  <?php
			   break;
			   case 'messagerie':
			      ?>
				  <h2>La messagerie</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Les messages</h3>
					 <p>
					    Il y a trois types de message:<br />
						*Messages normaux;<br />
						*Rapports défensifs;<br />
						*Rapports d'espionnage<br />
						<br />
						Lorsque vous envoyez un message à un autre joueur, il la recevra et pourra 
						aussitôt la lire et vous répondre. 
					 </p>
					 <hr />
					 <h3>Envoyer, supprimer ou répondre à un message</h3>
					 <p>
					    Vous pouvez envoyer un message à n'importe quel joueur de Berceau de Guerres 
						via la messagerie du jeu. Pour y parvenir, allez sur la page messagerie que vous pouvez 
						trouver sur le menu du jeu (seulement lorsque vous êtes connecté). Dans le haut de 
						la page, il y a un bouton «Voudriez-vous écrire une nouvelle lettre ?» qui, 
						une fois que vous aurez appuyé dessus, vous dirigera vers la page de rédaction 
						de votre lettre. Si vous avez appuyé sur le bouton Répondre depuis l'un des messages 
						de votre messagerie, le titre et le destinataire de la lettre seront entrés 
						automatiquement. Il ne vous restera plus qu'à écrire votre lettre et appuyer le bouton 
						«Envoyer le messager» !<br />
<br />						
						Dans votre messagerie, vous pouvez voir la liste de tous vos messages. À côté de 
						chacuns d'entre eux, vous trouverez une petite liste déroulante qui vous 
						permettra d'ouvrir, répondre ou spprimer un message. Appuyez sur l'action désirée. 
						<span class="attention">Attention!</span>: La suppression d'un message n'a pas besoin 
						d'une confirmation. Dès que vous appuyez sur Supprimer, le message est effacé. 
						Cette action est irréversible !
					 </p>
				  </div>
				  <?php
			   break;
			   case 'alliances':
			      ?>
				  <h2>Les alliances</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Joindre une alliance</h3>
					 <p>
					    <span class="information">Note: Maximum trente (30) joueurs par alliance</span><br />
						<br />
					    Si vous ne faites partie d'aucune alliance, vous pouvez en rejoindre une sans 
						problème. Pour y parvenir, vous devez vous rendre sur la page Alliances. 
						Sur cette page se trouve une liste de toutes les alliances en ordre croissant du nombre de 
						joueurs dans chaque. Vous pouvez demander à faire partie d'une d'entre elles en appuyant sur 
						le lien «Postuler dans cette alliance». 
						Vous serez amené à inscrire un message pour convaincre le dirigeant de l'alliance 
						de vous accepter dans son équipe. 
						Pour éviter le harcelement, les dirigeants ne peuvent pas envoyer de demande ! 
						Ils peuvent par contre envoyer un message privé pour lui demander s'il veut joindre son 
						alliance. Notez qu'il n'est pas obligatoire d'entrer un message pour le convaincre. 
						Seulement, votre acceptation n'est pas garantie ! 
					 </p>
					 <hr />
					 <h3>Créer un alliance</h3>
					 <p>
					    Pour créer une alliance, vous devez faire partie d'aucune alliance. Vous pouvez 
						vous rendre sur la page Alliances et appuyer sur le lien «Je veux créer ma propre alliance». 
						Vous devrez remplir un petit formulaire pour indiquer quel sera le nom de votre alliance et 
						sa description. Il n'est pas obligatoire d'entrer de description immédiatement. Vous pourez 
						changer la description de votre alliance tant que vous le voudrez sur la page Administration 
						de votre alliance. Le nom, par contre, ne <ins>peut pas</ins> être changé.
					 </p>
					 <hr />
					 <h3>Administration de votre alliance</h3>
					 <p>
					    Si vous êtes dirigeant de votre alliance, vous avez accès à la page d'Administration 
						de vorte alliance. Sur cette page, vous pourrez accepter ou refuser un joueur qui a demandé 
						à faire partie de votre alliance, changer la description de votre alliance, renvoyer un membre 
						de votre alliance, donner des statuts spéciaux à des membres de votre alliance ou dissoudre 
						votre alliance.
					 </p>
				  </div>
				  <?php
			   break;
			   case 'inventaire':
			      ?>
				  <h2>Votre inventaire</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Les bases</h3>
					 <p>
					    Votre inventaire peut contenir dix (10) objets. Sur la page de votre inventaire 
						s'affiche un tableau avec tous les objets que vous possédez. <br />
						<span class="information">Vu le petit nombre de places disponibles, il serait 
						judicieux d'utiliser rapidement vos objets !</span><br />
					 </p>
					 <hr />
					 <h3>Vendre un objet</h3>
					 <p>
					    Si vous avez un magasin, il vous est possible de mettre votre objet en vente dans 
						celui-ci. Tous les objets qui peuvent êtres vendus ont un prix minimum et maximum. 
						Vous devez le vendre selon ces prix. <br />
						<br />
						Il se peut aussi que vous n'ayez pas de magasin, ou que vous ayez simplement envie 
						de vendre votre objet rapidement. Il est possible de le faire en sélectionnant 
						Vendre au prix minimum dans le petit menu déroulant à côté de votre objet. Cette 
						action est irréversible. 
					 </p>
					 <hr />
					 <h3>Jeter un objet</h3>
					 <p>
					    Dans le menu déroulant à côté de votre objet que vous voulez jeter se trouve l'action 
						Jeter. Appuyez et vous devrez confirmer la suppression de l'objet. Appuyez sur le lien 
						«Oui, je veux supprimer cet objet» pour confirmer, ou sur l'autre pour annuler la sippression. 
						Cette action est irréversible. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'magasins':
			      ?>
				  <h2>Les magasins</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Magasins</h3>
					 <p>
					    Vous pouvez acheter des objets à n'importe quel moment avec les magasins. 
						Rendez-vous sur la page Magasin pour avoir une liste de tous les magasins. 
						Une fois votre magasin sélectionné, vous y trouverez tous les objets qu'il 
						contient. Si vous en avez les moyens, vous pouvez acheter des objets. Les pièces 
						d'or seront automatiquement transférés vers le vendeur et un message lui sera envoyé 
						pour  l'informer qu'il a vendu un objet. 
					 </p>
					 <hr />
					 <h3>Votre magasin</h3>
					 <p>
					    Si vous avez une profession qui permet d'avoir un magasin, tel qu'un marchand, 
						vous aurez accès à la page Gestion de mon magasin, depuis la page Magasin. 
						Sur cette page, vous pourrez changer le nom et la devise de votre magasin, mais 
						aussi voir vos objets en vente et ainsi annuler des ventes. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'carte':
			      ?>
				  <h2>La carte</h2>
				  <p><a href="aide.php">Revenir à la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Voir la carte</h3>
					 <p>
					    Vous pouvez observer la carte à tout moment depuis la page Carte. 
						Tous les joueurs sont placés aléatoirement selon des emplacements de départ 
						sur la carte. 
					 </p>
					 <hr />
					 <h3>Se déplacer</h3>
					 <p>
					    Pour vous déplacer sur la carte, vous devez appuyer sur la case de la carte 
						que vous voulez aller. Plus cette case est éloignée, plus il vous faudra du temps 
						pour arriver. (Le facteur vitesse entre en jeu. Voyez aussi la 
						<a href="aide.php?aide=facteurs">page d'aide sur les facteurs</a>) 
						Une fois en déplacement, vous ne pourrez pas attaquer ou vous faire attaquer, 
						espionner ou vous faire espionner
					 </p>
				  </div>
				  <?php
			   break;
			}
		 }
		 ?>
      </div>
	  <?php include("includes/pied_de_page.php"); ?>
   </body>
</html>