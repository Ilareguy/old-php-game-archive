function timeout_message(message){
   message ++;
   if(message == 4){
      message = 1;
   }
   var t = setTimeout('afficher_message(' + message + ')', 7000);
}

function afficher_message(message){
   var msgs = new Array();
   msgs[1] = "Berceau de Guerres, un jeu en temps réels des temps médiévaux. <strong>En construction!</strong><br />"
   + "Pour rester au courant de l'avancement, visitez cette page régulièrement.";
   msgs[2] = "Les tests pourront bientôt commencer!<br />"
   + "Il faut simplement ajouter les objets, mission, améliorations (etc.) dans la base de données.";
   msgs[3] = "Pour une question ou commentaire, vous pouvez me joindre à cette adresse:"
   + "<a href=\"mailto:webmasters@berceaudeguerres.com\" >webmasters@berceaudeguerres.com</a>";
   document.getElementById("message_chg").innerHTML = msgs[message];
   timeout_message(message);
}