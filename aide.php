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
		    Cette section a �t� mise en place pour vous aider � bien<br />
			d�marrer dans <strong>Berceau de Guerres</strong>.<br />
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
			   <td>La carte et les d�placements</td>
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
			   <td>Les am�liorations</td>
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
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>La navigation</h3>
					 <p>
					    Avant toute chose, il est fortement recommand� d'utiliser un navigateur qui accepte les cookies
						et le JavaScript. <a href="http://www.mozilla-europe.org/fr/firefox/">Mozilla Firefox</a>
						en est un bon exemple.<br />
						Pour une �criture plus belle, nous vous recommandons aussi de t�l�charger<br />
						<a href="downloads/Belwe_Cn_BT.ttf">cette �criture</a>.<br />
						<ins>(Si le t�l�chargement ne se lance pas par lui-m�me lorsque vous appuyez, faites le Clic droit, et
						Enregistrer la cible du lien sous...)</ins><br />
						Ce fichier (Belwe_Cn_BT.ttf), doit �tre plac� dans le r�pertoire de votre ordinateur qui contient tous<br />
						les fichiers de ce type. Sous Windows, placez-le dans "C:\Windows\Fonts"
					 </p>
					 <hr />
					 <h3>Jouer � Berceau de Guerres - Inscription</h3>
					 <p>
					    Pour pouvoir vous connecter au jeu et ainsi jouer, vous devez d'abord vous cr�er un compte. 
						Pour ce ffaire, remplissez le petit formulaire d'inscription <a href="inscription.php">ici</a>. 
						Une fois ce formulaire termin� et envoy� (s'il n'y a pas de message d'erreur), un courrier 
						�lectronique vous sera envoy� � l'adresse que vous avez entr� dans le formulaire. 
						Rendu � cette �tape, vous ne pourrez toujours pas vous connecter. Il vous faudra d'abord valider 
						votre inscription. Pour y parvenir, rien de plus simple. Lisez bien le message qui vous a �t� 
						envoy� via votre adresse courriel et vous y trouverez un lien de validation. 
						Appuyez dessus et vous serez redirig� vers une page de Berceau de Guerres. 
						Un message s'affichera pour vous informer que votre inscription est bien termin�e. 
						Une fois cela fait, vous pouvez vous connecter au jeu comme les autres et commencer � jouer ! 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'clan':
			      ?>
				  <h2>Mon clan</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>De quoi est constitu� mon clan ?</h3>
					 <p>
					    Votre clan est constitu� de trois types de membres (appel�s �Membres de clan�). 
						Les membres d'infanterie, les assassins et les archers. 
						Votre clan <ins>n'est pas</ins> constitu� d'autres joueurs du jeu. 
					 </p>
					 <hr />
					 <h3>Les membres d'infanterie</h3>
					 <p>
					    Les membres d'infanterie sont l'�quilibre en l'attaque et la d�fense. 
						Ils sont aussi moins dispendieux que les autres types de membres de clan. 
						Une grande partie des armes r�serv�es aux membres d'infanterie n'ont pas 
						de grande diff�rence entre les points d'attaque et les points de d�fense.
					 </p>
					 <hr />
					 <h3>Les assassins</h3>
					 <p>
					    Les assassins, quant � eux, sont bien plus concentr�s sur l'attaque que la d�fense. 
						Ils sont �galement plus dispnedieux. 
					 </p>
					 <hr />
					 <h3>Les archers</h3>
					 <p>
					    Les archers permettent de b�nificier de plus de points de d�fense. 
						Ce sont eux qui vous prot�geront le plus contre d'�ventuelles attaques ! 
					 </p>
					 <hr />
					 <h3>Les points d'attaque et de d�fense</h3>
					 <p>
					    Chaque fois que vous �quiperez un de vos membres de clan d'une arme, des 
						points d'attaque et de d�fense vous seront rapport�s. Pour en conna�tre le nombre, 
						regardez la description de l'objet lors de son achat. 
						Ce sont ces points qui vous feront gagner en puissance lors des combats. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'professions':
			      ?>
				  <h2>Les professions</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>Les bases</h3>
					 <p>
					    Tous les joueurs de Berceau de Guerres ont une profession bien distincte. 
						Certes, plus d'un joueur peut avoir la m�me profession. 
						Votre profession, vous la choisissez lors de votre inscription. 
						Chaque profession poss�de ses propres facteurs (Voir aussi la <a href="aide.php?aide=facteurs">page d'aide 
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
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
					 <h3>Combattre un autre clan</h3>
					 <p>
					    Pour combattre un autre clan, vous devez vous rendre sur la carte 
						et appuyer sur la case de votre choix. Sur cette nouvelle page, vous 
						pourrez voir une liste de tous les joueurs se trouvant sur cette position. 
						� c�t� de ce joueur se trouve une liste d�roulante. Si vous pouvez attaquer ce joueur, 
						vous verrez l'option �Combattre�. 
						Lors de l'envoie, vous pourrez choisir de n'envoyer qu'une partie de vos troupes. 
						Ceci dit, si vous ne les envoyez pas toutes, vos points d'attaque et de d�fense seront 
						divis�s ! Si par exemple vous n'envoyez que la moiti� de vos troupes, alors ce combat ne comptera 
						que la moiti� de vos points d'attaque et de d�fense. Cette techinque vous permet de lancer plus 
						d,une attaque � la fois !<br />
						<br />
						Une fois l'assaut officiellement lanc�, il vous faudra attendre quelques minutes, 
						peu-�tre m�me des heures pour que vous troupes arrivent � destination voulue, c'est-�-dire 
						chez votre cible. En revanche, le retour de vos troupes est imm�diat.<br />
						<br />
						Il se peut que vos troupent arrivent chez votre cible et que celle-ci ne soit plus sur 
						cette case de la carte ! Dans ce cas, vos troupes reviendront sans avoir � se battre. <br />
						<br />
						Pour lancer l'assaut contre un autre clan, vous devez disposer d'au moins 15 points d'attaque ou de 
						d�fense ! 
					 </p>
					 <hr />
					 <h3>Les calculs</h3>
					 <p>
					    Bien nombreux sont les joueurs qui s'int�ressent aux calculs des combats ! 
						Voici comment les combats fonctionnent...<br />
						<br />
					 </p>
						<table>
						   <tr>
						      <th class="gauche" style="width:50%">L'attaquant</th>
							  <th class="droite">Le d�fenseur</th>
						   </tr>
						   <tr>
						      <td>
							     Ses points de vie = ses points de d�fense * 3;<br />
						         Ses d�g�ts maximums = ses points d'attaque + 10% * son facteur combat;<br />
						         Ses d�g�ts minimums = la moiti� de ses points d'attaque<br />
							  </td>
							  <td class="droite">
							     Ses points de vie = ses points de d�fense * 3 * son facteur combat;<br />
								 Ses d�g�ts maximums = ses points de d�fense + 10%;<br />
								 Ses d�g�ts minimums = la moiti� de ses points de d�fense;<br />
							  </td>
						   </tr>
						</table>
				  </div>
				  <?php
			   break;
			   case 'facteurs':
			      ?>
				  <h2>Les facteurs</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Les facteurs</h3>
					 <p>
					    Les facteurs doivent �tre vus comme un pourcentage. 
						Plus ce pourcentage est �lev�, plus il vous sera utile et b�n�fique. 
					 </p>
					 <hr />
				     <h3>Facteur combat</h3>
					 <p>
					    Le facteur combat vous aidera lors des batailles contre d'autres clans. 
						Il peut tr�s bien vous aider autant pour vos points de vie que pour 
						votre puissance dans une attaque. (Voyez aussi la 
						<a href="aide.php?aide=combats">page d'aide sur les combats</a>)
					 </p>
					 <hr />
					 <h3>Facteur �conomie</h3>
					 <p>
					    Ce facteur vous aidera � gagner, entre autre, plus d'or par jour. 
						Si votre gain d'or par jour est de douze (12) pi�ces d'or, il sera 
						multipli� par votre facteur �conomie.<br />
						Dans le cas pr�sent, si votre facteur �conomie est de 1.15:<br />
						12 pi�ces d'or * 1.15 = 13.8 pi�ces d'or;<br />
						Arrondis � 14 pi�ces d'or.<br />
					 </p>
					 <hr />
					 <h3>Facteur vitesse</h3>
					 <p>
					    Ce facteur vous permettra de vous d�placer de fa�on plus rapide et vous 
						permettra aussi de lancer des attaques et des espionnages sur de plus grandes 
						distances.
					 </p>
					 <hr />
					 <h3>Facteur furtif</h3>
					 <p>
					    Ce facteur vous sera avant tout utile lors des espionnages. 
						Un clan qui poss�de un bon facteur furtif pourrait �tre utilis� comme 
						espion au sein d'une alliance. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'espionnages':
			      ?>
				  <h2>Les espionnages</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Espionner un autre clan</h3>
					 <p>
					    Pour espionner un autre clan, il vous faudra disposer d'au moins 
						un espion. Vous aurez la possibilit� d'envoyer plusieurs espions 
						en mission � la m�me place ou � des endroits diff�rents sur la carte. 
						Plus vous envoyez d'espions, plus vous aurez des chances de succ�s ! 
						Vous pouvez donner l'ordre d'espionner plus d'une chose chez votre cible. 
						Cependant, plus vous en donnez, plus la mission sera difficile ! 
						Pour obtenir des espions, il est n�cessaire d'aller dans un des deux 
						magasins � objets illimit�s.
					 </p>
				  </div>
				  <?php
			   break;
			   case 'messagerie':
			      ?>
				  <h2>La messagerie</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Les messages</h3>
					 <p>
					    Il y a trois types de message:<br />
						*Messages normaux;<br />
						*Rapports d�fensifs;<br />
						*Rapports d'espionnage<br />
						<br />
						Lorsque vous envoyez un message � un autre joueur, il la recevra et pourra 
						aussit�t la lire et vous r�pondre. 
					 </p>
					 <hr />
					 <h3>Envoyer, supprimer ou r�pondre � un message</h3>
					 <p>
					    Vous pouvez envoyer un message � n'importe quel joueur de Berceau de Guerres 
						via la messagerie du jeu. Pour y parvenir, allez sur la page messagerie que vous pouvez 
						trouver sur le menu du jeu (seulement lorsque vous �tes connect�). Dans le haut de 
						la page, il y a un bouton �Voudriez-vous �crire une nouvelle lettre ?� qui, 
						une fois que vous aurez appuy� dessus, vous dirigera vers la page de r�daction 
						de votre lettre. Si vous avez appuy� sur le bouton R�pondre depuis l'un des messages 
						de votre messagerie, le titre et le destinataire de la lettre seront entr�s 
						automatiquement. Il ne vous restera plus qu'� �crire votre lettre et appuyer le bouton 
						�Envoyer le messager� !<br />
<br />						
						Dans votre messagerie, vous pouvez voir la liste de tous vos messages. � c�t� de 
						chacuns d'entre eux, vous trouverez une petite liste d�roulante qui vous 
						permettra d'ouvrir, r�pondre ou spprimer un message. Appuyez sur l'action d�sir�e. 
						<span class="attention">Attention!</span>: La suppression d'un message n'a pas besoin 
						d'une confirmation. D�s que vous appuyez sur Supprimer, le message est effac�. 
						Cette action est irr�versible !
					 </p>
				  </div>
				  <?php
			   break;
			   case 'alliances':
			      ?>
				  <h2>Les alliances</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Joindre une alliance</h3>
					 <p>
					    <span class="information">Note: Maximum trente (30) joueurs par alliance</span><br />
						<br />
					    Si vous ne faites partie d'aucune alliance, vous pouvez en rejoindre une sans 
						probl�me. Pour y parvenir, vous devez vous rendre sur la page Alliances. 
						Sur cette page se trouve une liste de toutes les alliances en ordre croissant du nombre de 
						joueurs dans chaque. Vous pouvez demander � faire partie d'une d'entre elles en appuyant sur 
						le lien �Postuler dans cette alliance�. 
						Vous serez amen� � inscrire un message pour convaincre le dirigeant de l'alliance 
						de vous accepter dans son �quipe. 
						Pour �viter le harcelement, les dirigeants ne peuvent pas envoyer de demande ! 
						Ils peuvent par contre envoyer un message priv� pour lui demander s'il veut joindre son 
						alliance. Notez qu'il n'est pas obligatoire d'entrer un message pour le convaincre. 
						Seulement, votre acceptation n'est pas garantie ! 
					 </p>
					 <hr />
					 <h3>Cr�er un alliance</h3>
					 <p>
					    Pour cr�er une alliance, vous devez faire partie d'aucune alliance. Vous pouvez 
						vous rendre sur la page Alliances et appuyer sur le lien �Je veux cr�er ma propre alliance�. 
						Vous devrez remplir un petit formulaire pour indiquer quel sera le nom de votre alliance et 
						sa description. Il n'est pas obligatoire d'entrer de description imm�diatement. Vous pourez 
						changer la description de votre alliance tant que vous le voudrez sur la page Administration 
						de votre alliance. Le nom, par contre, ne <ins>peut pas</ins> �tre chang�.
					 </p>
					 <hr />
					 <h3>Administration de votre alliance</h3>
					 <p>
					    Si vous �tes dirigeant de votre alliance, vous avez acc�s � la page d'Administration 
						de vorte alliance. Sur cette page, vous pourrez accepter ou refuser un joueur qui a demand� 
						� faire partie de votre alliance, changer la description de votre alliance, renvoyer un membre 
						de votre alliance, donner des statuts sp�ciaux � des membres de votre alliance ou dissoudre 
						votre alliance.
					 </p>
				  </div>
				  <?php
			   break;
			   case 'inventaire':
			      ?>
				  <h2>Votre inventaire</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Les bases</h3>
					 <p>
					    Votre inventaire peut contenir dix (10) objets. Sur la page de votre inventaire 
						s'affiche un tableau avec tous les objets que vous poss�dez. <br />
						<span class="information">Vu le petit nombre de places disponibles, il serait 
						judicieux d'utiliser rapidement vos objets !</span><br />
					 </p>
					 <hr />
					 <h3>Vendre un objet</h3>
					 <p>
					    Si vous avez un magasin, il vous est possible de mettre votre objet en vente dans 
						celui-ci. Tous les objets qui peuvent �tres vendus ont un prix minimum et maximum. 
						Vous devez le vendre selon ces prix. <br />
						<br />
						Il se peut aussi que vous n'ayez pas de magasin, ou que vous ayez simplement envie 
						de vendre votre objet rapidement. Il est possible de le faire en s�lectionnant 
						Vendre au prix minimum dans le petit menu d�roulant � c�t� de votre objet. Cette 
						action est irr�versible. 
					 </p>
					 <hr />
					 <h3>Jeter un objet</h3>
					 <p>
					    Dans le menu d�roulant � c�t� de votre objet que vous voulez jeter se trouve l'action 
						Jeter. Appuyez et vous devrez confirmer la suppression de l'objet. Appuyez sur le lien 
						�Oui, je veux supprimer cet objet� pour confirmer, ou sur l'autre pour annuler la sippression. 
						Cette action est irr�versible. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'magasins':
			      ?>
				  <h2>Les magasins</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Magasins</h3>
					 <p>
					    Vous pouvez acheter des objets � n'importe quel moment avec les magasins. 
						Rendez-vous sur la page Magasin pour avoir une liste de tous les magasins. 
						Une fois votre magasin s�lectionn�, vous y trouverez tous les objets qu'il 
						contient. Si vous en avez les moyens, vous pouvez acheter des objets. Les pi�ces 
						d'or seront automatiquement transf�r�s vers le vendeur et un message lui sera envoy� 
						pour  l'informer qu'il a vendu un objet. 
					 </p>
					 <hr />
					 <h3>Votre magasin</h3>
					 <p>
					    Si vous avez une profession qui permet d'avoir un magasin, tel qu'un marchand, 
						vous aurez acc�s � la page Gestion de mon magasin, depuis la page Magasin. 
						Sur cette page, vous pourrez changer le nom et la devise de votre magasin, mais 
						aussi voir vos objets en vente et ainsi annuler des ventes. 
					 </p>
				  </div>
				  <?php
			   break;
			   case 'carte':
			      ?>
				  <h2>La carte</h2>
				  <p><a href="aide.php">Revenir � la page d'aide</a></p>
				  <div style="font-family:Arial;font-size:0.8em" class="gauche">
				     <h3>Voir la carte</h3>
					 <p>
					    Vous pouvez observer la carte � tout moment depuis la page Carte. 
						Tous les joueurs sont plac�s al�atoirement selon des emplacements de d�part 
						sur la carte. 
					 </p>
					 <hr />
					 <h3>Se d�placer</h3>
					 <p>
					    Pour vous d�placer sur la carte, vous devez appuyer sur la case de la carte 
						que vous voulez aller. Plus cette case est �loign�e, plus il vous faudra du temps 
						pour arriver. (Le facteur vitesse entre en jeu. Voyez aussi la 
						<a href="aide.php?aide=facteurs">page d'aide sur les facteurs</a>) 
						Une fois en d�placement, vous ne pourrez pas attaquer ou vous faire attaquer, 
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