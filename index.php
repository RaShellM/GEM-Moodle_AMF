<?php
//acces facilité aux methodes de la DB
/* @var $DB moodle_database */

//dirname(__FILE__)== __DIR__;
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__ . '/config.php');
//FONCTIONS
//**********comparaison de string
function str_compare($str_1, $str_2) {
    if ($str_1 == $str_2)
        return TRUE;
    else
        return FALSE;
}
//**********transforme un objet en array
function object_to_array($d) {
    if (is_object($d)) {
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        return array_map(__FUNCTION__, $d);
    } else {
        return $d;
    }
}

//lecture rapide : verifier que le fichier est clean
$csvMatchCat = fopen('amfcorrespondance.csv', 'rb');
  while(!feof($csvMatchCat)){
  $ligne=fgets($csvMatchCat, filesize('amfcorrespondance.csv'));
  echo $ligne.'<br>';
  }

//transforme le csv en array reformaté en clé (numero) => valeur (lettre de la catégorie) et affiche le nouvel array
$arrayfromcsv = array_map('str_getcsv', file('amfcorrespondance.csv'));
echo'<pre>'.print_r($arrayfromcsv).'</pre>';
$csv = array();
$a = '';
$b = '';
foreach ($arrayfromcsv as $csvkey => $csvvalue) {
    foreach ($csvvalue as $csvkey2 => $csvvalue2) {
        switch ($csvkey2) {
            case 0 :
                $a = $csvvalue2;
                echo $a;
                break;
            case 1 :
                $b = $csvvalue2;
                echo $b;
                $csv[$a] = $b;
                break;
        }
    }
}

//connection db pour requete de la table (TIPS : enlever mdl_ pour le nom de la table)
$dborigin = $DB->get_records('question_categories', ['parent' => '60'], 'id', 'id, name');
echo '<pre> la base de donnéee d origine est la suivante';
print_r($dborigin);
echo '</pre>';
//echo '<pre>'.print_r($dborigin, true).'</pre>';

object_to_array($dborigin);
// boucle sur le fichier db pour remplacer le nom
$dbnew = array();
$name='';
$id = '';
foreach ($dborigin as $dbkey => $dbvalue) {
    foreach ($dbvalue as $dbkey2 => &$dbvalue2) {
        //on supprime "THEME"
        if ($dbkey2 == 'name') {
            $dbvalue2 = trim(substr($dbvalue2, 5));
            //on boucle sur csv pour trouver les matches
            foreach ($csv as $keycsv => $valuecsv) {
                if (str_compare($keycsv, $dbvalue2)) {
                    //concaténer la catégorie au nom
                    $dbvalue2 = 'Theme '.$dbvalue2.' - Type '.$valuecsv;
                    //echo 'theme'.$dbvalue2.'<br>';
                    //a faire en sql (mais via objet) UPDATE 'question_categories' SET name = $dbvalue2 WHERE id = $dbkey;
                }
            }
        }
    }
    unset($value);
}
$dbarray =(object)$dborigin;
echo '<pre>';
print_r($dbarray);
echo '</pre>';
print(gettype($dbarray));
echo '<h1>nouvelle db </h1>';
for($i=62; $i<=114; $i++)
{
    //$DB->update_record('question_categories', $dbarray[$i]);
}

//faire un calcul sur le resultat avec les 80% de chaque catégorie

    ?>

