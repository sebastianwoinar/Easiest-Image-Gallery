<?php
/* file: gallery.php
*  date: 9th October 2011
*  author: sebastian@woinar.de
*
* Using this file it is possible to claim a customized Header, Subheader and a Description on top of the Galleries.
* Use as index the number of the folder.
* For example:
*   $texte['1'] = array();
*   $texte['1'][head] = "My fabulous Gallery";
*   $texte['1'][subhead] = "October 2011";
*   $texte['1'][description] = "Look how amazing was my safari through the Krueger National Park!";
*
*
* function getHead($id)

* function getSubhead($id)

* function getDescription($id)
    
*
*/

$texte[] = array();

$texte['1'] = array();
$texte['1'][head] = "Kr&uuml;ger National Park";
$texte['1'][subhead] = "October 2011";
//$texte['1'][description] = "blub";


function getHead($id){
    global $texte;
    return $texte[$id][head] != null ? "<h1>" . $texte[$id][head] . "</h1>" : "<h1>Gallery " . $id . "</h1>";
}

function getSubhead($id){
    global $texte;
    return $texte[$id][subhead] != null ? "<h2>" . $texte[$id][subhead] . "</h2>": '';
}

function getDescription($id){
    global $texte;
    return $texte[$id][description] != null ? 	'<div class="description">' .  $texte[$id][description] . '</div>': '';
}
?>