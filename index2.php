<?php

//acces facilité aux methodes de la DB
/* @var $DB moodle_database */

//dirname(__FILE__)== __DIR__;
define('__ROOT__', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once(__ROOT__ . '/config.php');

//FONCTIONS comparaison de string
function str_compare($str_1, $str_2) {
    if ($str_1 == $str_2)
        return TRUE;
    else
        return FALSE;
}

//connection db pour requete de la table (TIPS : enlever mdl_ pour le nom de la table)
$dborigin = $DB->get_records('question_categories', ['parent' => '60'], 'id', 'id, name');

$arrayfromcsv = array_map('str_getcsv', file('amfcorrespondance.csv'));
//die('<pre>'.print_r($arrayfromcsv, true).'</pre>');
$csv1 = array();
$a1 = '';
$b1 = '';
foreach ($arrayfromcsv as $csvtableau) {
    $a1 = $csvtableau[0][1];
    $b1 = $csvtableau[1][1];
    $csv1[$a1] = $b1;
}
print_r($csv);

echo "<p>".$dborigin[62]->name."</p>";
$dborigin[62]->name="george";
echo "<p>".$dborigin[62]->name."</p>";

foreach ($dborigin as $dbkey => $dbvalue) {

    foreach ($dbvalue as $dbkey2 => $dbvalue2) {
        //on supprime "THEME"
        if ($dbkey2 == 'name') {
            $dbvalue2 = trim(substr($dbvalue2, 5));
            $dbvalue[$dbkey2] = $dbvalue2;
            //on boucle sur csv pour trouver les matches
            foreach ($csv as $keycsv => $valuecsv) {
                if (str_compare($keycsv, $dbvalue2)) {
                    //concaténer la catégorie au nom
                    $dbvalue[$dbkey2] = $dbvalue[$dbkey2].' '.$valuecsv;
                    echo 'theme'.$dbvalue[$dbkey2].'<br>';
                    //a faire en sql (mais via objet) UPDATE 'question_categories' SET name = $dbvalue2 WHERE id = $dbkey;
                }
            }
        }
    }
}