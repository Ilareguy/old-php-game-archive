function timeout_message(message){
   message ++;
   if(message == 4){
      message = 1;
   }
   var t = setTimeout('afficher_message(' + message + ')', 7000);
}

function afficher_message(message){
   var msgs = new Array();
   msgs[1] = "Berceau de Guerres, un jeu en temps r�els des temps m�di�vaux. <strong>En construction!</strong><br />"
   + "Pour rester au courant de l'avancement, visitez cette page r�guli�rement.";
   msgs[2] = "Les tests pourront bient�t commencer!<br />"
   + "Il faut simplement ajouter les objets, mission, am�liorations (etc.) dans la base de donn�es.";
   msgs[3] = "Pour une question ou commentaire, vous pouvez me joindre � cette adresse:"
   + "<a href=\"mailto:webmasters@berceaudeguerres.com\" >webmasters@berceaudeguerres.com</a>";
   document.getElementById("message_chg").innerHTML = msgs[message];
   timeout_message(message);
}