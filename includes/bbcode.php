<?php
   function bbcode($texte)
{
   //Smileys
   $texte = str_replace(':)', '<img src="images/Smileys/sourire.png" title="sourire" alt="sourire" />', $texte);
   $texte = str_replace(':(', '<img src="images/Smileys/baboune.png" title="baboune" alt="baboune" />', $texte);
   $texte = str_replace('^^', '<img src="images/Smileys/amical.png" title="amical" alt="amical" />', $texte);
   $texte = str_replace(':ange:', '<img src="images/Smileys/ange.png" title="ange" alt="ange" />', $texte);
   $texte = str_replace('oO', '<img src="images/Smileys/blink.gif" title="blink" alt="blink" />', $texte);
   $texte = str_replace(';)', '<img src="images/Smileys/clin.png" title="clin" alt="clin" />', $texte);
   $texte = str_replace(':doh:', '<img src="images/Smileys/doh.png" title="d\'oh" alt="d\'oh" />', $texte);
   $texte = str_replace(':@', '<img src="images/Smileys/frustre.gif" title="frustré" alt="frustré" />', $texte);
   $texte = str_replace(':D', '<img src="images/Smileys/heureux.png" title="heureux" alt="heureux" />', $texte);
   $texte = str_replace(':o', '<img src="images/Smileys/ho.png" title="ho" alt="ho" />', $texte);
   $texte = str_replace(':s', '<img src="images/Smileys/incertain.gif" title="incertain" alt="incertain" />', $texte);
   $texte = str_replace(':P', '<img src="images/Smileys/langue.png" title="langue" alt="langue" />', $texte);
   $texte = str_replace('è_é', '<img src="images/Smileys/mechant.png" title="mechant" alt="mechant" />', $texte);
   $texte = str_replace(':pirate:', '<img src="images/Smileys/pirate.png" title="pirate" alt="pirate" />', $texte);
   $texte = str_replace(':\'(', '<img src="images/Smileys/pleure.png" title="pleure" alt="pleure" />', $texte);
   $texte = str_replace(':rire:', '<img src="images/Smileys/rire.gif" title="rire" alt="rire" />', $texte);
   $texte = str_replace(':rouge:', '<img src="images/Smileys/rouge.png" title="rouge" alt="rouge" />', $texte);
   $texte = str_replace(':-*', '<img src="images/Smileys/siffle.png" title="siffle" alt="siffle" />', $texte);
   $texte = str_replace(':O', '<img src="images/Smileys/surpris.png" title="surpris" alt="surpris" />', $texte);
   $texte = str_replace(':zorro:', '<img src="images/Smileys/zorro.png" title="zorro" alt="zorro" />', $texte);
   $texte = str_replace(':magicien:', '<img src="images/Smileys/magicien.png" title="magicien" alt="magicien" />', $texte);
   $texte = str_replace(':ninja:', '<img src="images/Smileys/ninja.png" title="ninja" alt="ninja" />', $texte);
   $texte = str_replace(':diable:', '<img src="images/Smileys/diable.png" title="diable" alt="diable" />', $texte);
   $texte = str_replace(':enchaine:', '<img src="images/Smileys/enchaine.gif" title="enchainé" alt="enchainé" />', $texte);
   
   //Formattage
   $texte = preg_replace('`\[gras\](.+)\[/gras\]`isU', '<strong>$1</strong>', $texte);
   $texte = preg_replace('`\[italic\](.+)\[/italic\]`isU', '<em>$1</em>', $texte);
   $texte = preg_replace('`\[souligné\](.+)\[/souligné\]`isU', '<ins>$1</ins>', $texte);
   $texte = preg_replace('`\[exposant\](.+)\[/exposant\]`isU', '<sup>$1</sup>', $texte);
   $texte = preg_replace('`\[barré\](.+)\[/barré\]`isU', '<del>$1</del>', $texte);
   $texte = preg_replace('`\[rouge\](.+)\[/rouge\]`isU', '<span style="color:#9d0000" >$1</span>', $texte);
   $texte = preg_replace('`\[vert\](.+)\[/vert\]`isU', '<span style="color:#0b8b00" >$1</span>', $texte);
   $texte = preg_replace('`\[bleu\](.+)\[/bleu\]`isU', '<span style="color:#000ae3" >$1</span>', $texte);
   $texte = preg_replace('`\[orange\](.+)\[/orange\]`isU', '<span style="color:#fc8300" >$1</span>', $texte);
   $texte = preg_replace('`\[ciel\](.+)\[/ciel\]`isU', '<span style="color:#00cee9" >$1</span>', $texte);
   $texte = preg_replace('`\[mauve\](.+)\[/mauve\]`isU', '<span style="color:#4b008b" >$1</span>', $texte);
   $texte = preg_replace('`\[brun\](.+)\[/brun\]`isU', '<span style="color:#6b3a00" >$1</span>', $texte);
   $texte = preg_replace('`\[fond rouge\](.+)\[/fond rouge\]`isU', '<span style="background-color:#9d0000" >$1</span>', $texte);
   $texte = preg_replace('`\[fond vert\](.+)\[/fond vert\]`isU', '<span style="background-color:#0b8b00" >$1</span>', $texte);
   $texte = preg_replace('`\[fond bleu\](.+)\[/fond bleu\]`isU', '<span style="background-color:#000ae3" >$1</span>', $texte);
   $texte = preg_replace('`\[fond orange\](.+)\[/fond orange\]`isU', '<span style="background-color:#fc8300" >$1</span>', $texte);
   $texte = preg_replace('`\[fond ciel\](.+)\[/fond ciel\]`isU', '<span style="background-color:#00cee9" >$1</span>', $texte);
   $texte = preg_replace('`\[fond mauve\](.+)\[/fond mauve\]`isU', '<span style="background-color:#4b008b" >$1</span>', $texte);
   $texte = preg_replace('`\[fond brun\](.+)\[/fond brun\]`isU', '<span style="background-color:#6b3a00" >$1</span>', $texte);
   $texte = preg_replace('`\[petit\](.+)\[/petit\]`isU', '<span style="font-size:50%" >$1</span>', $texte);
   $texte = preg_replace('`\[grand\](.+)\[/grand\]`isU', '<span style="font-size:125%" >$1</span>', $texte);
   $texte = preg_replace('`\[géant\](.+)\[/géant\]`isU', '<span style="font-size:180%" >$1</span>', $texte);
   $texte = preg_replace('`\[arial\](.+)\[/arial\]`isU', '<span style="font-family: arial" >$1</span>', $texte);
   $texte = preg_replace('`\[Comic Sans MS\](.+)\[/Comic Sans MS\]`isU', '<span style="font-family: \"Comic Sans MS\"" >$1</span>', $texte);
   

   return $texte;
}

?>