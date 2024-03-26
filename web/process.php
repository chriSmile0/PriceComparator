<?php 
//SCRAPPER PROCESS HERE 

namespace ChriSmile0\Scrapper;

use ChriSmile0\Scrapper\scrapper;
use function ChriSmile0\Scrapper\content_scrap_auchan;
use function ChriSmile0\Scrapper\content_scrap_carrefour;
use function ChriSmile0\Scrapper\content_scrap_leclerc;
use function ChriSmile0\Scrapper\content_scrap_intermarche;
use function ChriSmile0\Scrapper\content_scrap_monoprix;
use function ChriSmile0\Scrapper\content_scrap_systemeu; // DONT-WORK
require_once('../vendor/autoload.php');

//var_dump(content_scrap_leclerc("Lardons","Voglans")); // OK 
//var_dump(content_scrap_carrefour("https://www.carrefour.fr/courses","lardons","Paris")); // OK 

$test_p = ["name"=>"Yes","price"=>"Life"];
$all_p = [
	[
		"name"=>"Yes",
		"price"=>"Life"
	],
	[
		"name"=>"No",
		"price"=>"Death"
	]
];

foreach($all_p as $o) {
	/*var_dump($o);
	print_r($o);
	if(in_array($test))*/
	var_dump($o['name']);
}
//var_dump(array_values($all_p));
/*if(in_array($test_p['name'],array_values($all_p)['name'])){
	echo "oui \n";
}*/
?>