
<?php 

use function ChriSmile0\Scrapper\content_scrap_auchan;
use function ChriSmile0\Scrapper\content_scrap_carrefour;
use function ChriSmile0\Scrapper\content_scrap_intermarche;
use function ChriSmile0\Scrapper\content_scrap_leclerc;
use function ChriSmile0\Scrapper\content_scrap_monoprix;
require_once('../../vendor/autoload.php');

$finalLabels = [
	"name",
	"price"
];

$Carrefour = [ // replace by get_items
	"ean",
	"title", 
	"brand", 
	"slug",
	"offerServiceId",
	"offers" => [
		"ean" => [
			"offerServiceId" => [
				"attributes" => [
					"price",
					"promotion",
					"promotions"
				],
			],
		],
	],
	"packaging",
	"nutriscore"
];
$Leclerc = [ // replace by get items 
	"sLibelleLigne1",
	"sLibelleLigne2",
	"sPrixUnitaire",
	"nrPVUnitaireTTC",
	"sPrixPromo",
	"sPrixParUniteDeMesure",
	"nrPVParUniteDeMesureTTC",
	"sUrlPageProduit"
];



$dis = $zone = $type = $product = $choices = "";

$all_types = ["Poisson","Viande","EpicerieSa","EpicerieSu","Pains","Patisserie"
				,"Charcuterie","Surgele","Boisson","Bebe","Bio","Hygiene","Beaute"
				,"Entretien","Nettoyage","Animalerie","Loisirs","Maison","Laiterie"
				,"Oeufs","Fruits","Legumes","Lardons"	
]; // add Lardons for test

$choice_ens = array();
$possible_ens = ["Carrefour","Leclerc","Monoprix","Auchan","Intermarche"];//ADD SU LATER
$active_ens =  ["Carrefour"=>false,"Leclerc"=>false,"Monoprix"=>false,"Auchan"=>false,"Intermarche"=>false];

function sleep_test() {
}

function test_input2($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function search_price(string $ens, array $item) {
	switch($ens) {
		case "Carrefour":
			return $item['offers']['ean']['offerServiceId']['attributes']['price'] . "€";
			break;
		case "Leclerc":
			return $item['nrPVUnitaireTTC'] . "€";
			break;
		default:
			break;
	}
}

function search_name(string $ens, array $item) {
	switch($ens) {
		case "Carrefour":
			return $item['title'] . "|" . $item['brand'];
			break;
		case "Leclerc":
			return $item['sLibelleLigne1'] . "|" . $item['sLibelleLigne2'];
			break;
		default:
			break;
	}
}

function parse_keys(string $ens, array $content) { // FOR LISTINGS MAYBE 
	$keys = $GLOBALS[$ens];
	$rtn = "";
	//$first_val = $content[0];
	foreach($content as $elem) {
		$rtn .= "<div>Prix : ".search_price($ens,$elem).", produit : ".search_name($ens,$elem)."</div>";
	}
	//$price_f_val = search_price($ens,$first_val);//$first_val[$keys['']]
	return $rtn;
}

function create_cmp_product(array $all_products) { // easy with 1.2 Scrapper Version
	$save_products = array();
	foreach($all_products as $k => $v) {
		if($v !== NULL) {
			if($k === "Carrefour") {
				foreach($v as $val) {
					$save_products[] = ["name"=>$val['title'],"ens"=>[$k],"prix"=>[$val['offers']['ean']['offerServiceId']['attributes']['price']]];
				}
			}
			if($k === "Leclerc") {
				foreach($v as $val) {
					$save_products[] = ["name"=>$val['sLibelleLigne1']." ".$val['sLibelleLigne2'],"ens"=>[$k],"prix"=>[$val['nrPVUnitaireTTC']]];
				}
			}
			if($k === "Monoprix") {
				foreach($v as $val) {
					$save_products[] = ["name"=>$val['name'],"ens"=>[$k],"prix"=>[$val['price']]];
				}
			}
			if($k === "Auchan") {
				foreach($v as $val) {
					$save_products[] = ["name"=>$val['label'],"ens"=>[$k],"prix"=>[$val['price']]];
				}
			}
			if($k === "Intermarche") {
				foreach($v as $val) {
					$save_products[] = ["name"=>$val['informations']['title'],"ens"=>[$k],"prix"=>[$val['prices']['productPrice']]];
				}
			}
		}

	}
	$unique_products = array();
	$compare_products = array();
	$indexs_cmp = array();
	//print_r($save_products);
	foreach($save_products as $elem) {
		$found = false;
		$index = 0;
		foreach($unique_products as $u_p) {
			if($elem['name'] === $u_p['name']) {
				echo $elem['name']."|".$u_p['name']."\n";
				//echo $elem['name'];
				$found = true;
				break;
			}
			$index++;
		}
		if($found == true) {
			array_push($unique_products[$index]['prix'],$elem['prix'][0]);
			array_push($unique_products[$index]['ens'],$elem['ens'][0]);
			if(!in_array($index,$indexs_cmp))
				$indexs_cmp += [$index];
		}
		else {
			$unique_products[] = $elem;
		}
	}
	//var_dump($unique_products);
	$comparable_product = array();
	foreach($indexs_cmp as $i) {
		$comparable_product[] = $unique_products[$i];
	}
	return $comparable_product;//$unique_products;

}

function display_each_product(array $create_cmp_pro) {
	$rtn = "";
	foreach($create_cmp_pro as $c_cmp) {
		$rtn .= "<div>";
		$rtn .= "<span>".$c_cmp['name']."</span>";
		$rtn .= "<ul>";
		$size = sizeof($c_cmp['ens']);
		echo "size : $size\n";
		for($i = 0 ; $i < $size ; $i++) {
			$rtn .= "<li>".$c_cmp['ens'][$i]."|".$c_cmp['prix'][$i]."</li>";
		}
		$rtn .= "</ul>";
		$rtn .= "</div>";
	}
	return $rtn;
}

function display_compare(string $product, string $city) {
	$rtn = "";
	$all_ens = $GLOBALS['active_ens'];
	echo "SLEEP \n";
	//sleep_test();
	$c = ($all_ens['Carrefour']) ? content_scrap_carrefour($product,$city,4444) : NULL; // HIDE URL NEXT UPDATE SCRAPPER


	/**************************************USE REMOTEWEBDRIVER**** */

	echo "end SLEEP \n";
	//$c = ($all_ens['Carrefour']) ? "TRUE" : "FALSE"; 
	/////********************Timed out waiting for http://localhost:9515/status***************///
	//$l = ($all_ens['Leclerc']) ? content_scrap_leclerc($product,$city) : NULL;
	/*$m = ($all_ens['Monoprix']) ? content_scrap_monoprix($product) : NULL;
	$a = ($all_ens['Auchan']) ? content_scrap_auchan(,$product,$city) : NULL; // HIDE URL NEXT UPDATE SCRAPPER
	$i = ($all_ens['Intermarche']) ? content_scrap_intermarche($product,$city) : NULL; */// HIDE URL NEXT UPDATE SCRAPPER */

	// TEST FOR DISPLAY LECLERC AND CARREFOUR 
	$l = array([
		"sLibelleLigne1" => "Lardons fumés Herta",
		"sLibelleLigne2" => "200g",
		"sPrixUnitaire" => "2",
		"nrPVUnitaireTTC" => "2.15",
		"sPrixPromo" => "0",
		"sPrixParUniteDeMesure" => "12",
		"nrPVParUniteDeMesureTTC" => "12.05",
		"sUrlPageProduit" => "https//fd7-courses.leclercdrive.fr/magasin-037301-037301-Voglans/fiche-produits-169342-Lardons-fumes-Herta.aspx"
	]);

	$c = array([
		"ean" =>"12345",
		"title" => "Lardons fumés Herta 200g", 
		"brand" => "HERTA", 
		"slug" => "slug",
		"offerServiceId" => "yes",
		"offers" => [
			"ean" => [
				"offerServiceId" => [
					"attributes" => [
						"price" => "1.2",
						"promotion" => "0",
						"promotions" => "NULL"
					],
				],
			],
		],
		"packaging" => "null",
		"nutriscore" => "null"
	]);
	$m = array([
		"productID" =>"12345",
		"retailerProductID" => "Lardons fumés Herta 200g", 
		"name" => "Lardons fumés Herta 200g", 
		"available" => "true",
		"alternatives" => "null",
		"price" => "2",
		"brand" => "HERTA"
	]);
	$a = array([
		"reviewCount" => "1",
		"label" => "Lardons fumés Herta 200g",
		"quantity" => "200",
		"pricePerKg" => "10",
		"promoOffer" => "null",
		"seeOffer" => "null",
		"dealMode" => "drive",
		"price" => "2.4"
	]);
	$i = array([
		"type" => "IDK",
		"offers" => "null",
		"promotions" => null,
		"prices" => [
			"productPrice" => "2.5"
		],
		"id" => "1",
		"ean" => "1234567",
		"informations" => [
			"title" => "Lardons fumés Herta 200g",
			"packaging" => "IDK",
			"brand" => "HERTA",
		],
		"url" => "url",
		"hasReduction" => "IDK"
	]);
	//$c = NULL;
	$compare = array("Carrefour"=>$c,"Leclerc"=>$l,"Monoprix"=>$m,"Auchan"=>$a,"Intermarche"=>$i);
	$full_rtn = "";
	$full_rtn = display_each_product(create_cmp_product($compare));
	/*foreach($compare as $k => $v) { 
		if($v !== NULL) { 
			echo "$k:\n";
			//var_dump(array_keys($v[0]));//implode(",",$v[0]); // first val
			$rtn = parse_keys($k,$v); // // v1 display immediatly 
			$full_rtn .= $rtn;
		} 
		//echo "<div style=\"border:solid;\">$echo</div>";
	*/
	/*$all_products = array();
	foreach($compare as $k => $v) { 
		if($v !== NULL) {
			$all_products[] = $v;
		}
	}*/
	//var_dump($all_products);

	

	//$rtn =//content_scrap_leclerc($product,$city);//$GLOBALS['universal_URL'];
	//$res = content_scrap_leclerc("Lardons","Voglans");
	//$rtn .= json_encode($res[0]);
	/*$full = array($c,$l);//,//$m,$a,$i);
	$rtn = $full; // for the moment */
	return $full_rtn;//"WAITED\n";//$rtn;
}

function display_ens(array $checkbox_ens) : string {
	$rtn = "";
	foreach($checkbox_ens as $k => $v) 
		$rtn .= ($v) ? $k.";" : "";
	
	return $rtn;
}

function display_city(string $zone) : string {
	$rtn = "";
	//check valid city -> cities.json ?
	$rtn .= $zone;
	return $rtn;
}

function display_type(string $type) : string {
	$rtn = "";
	if(in_array($type,$GLOBALS['all_types'])) {
		return $type;
	}
	return $rtn;
}

function display_product(string $product) : string {
	$rtn = "";
	$rtn .= $product;
	return $rtn;
}


if(isset($_POST['submitproduct'])) {
	$keys = array_keys($GLOBALS['active_ens']);
	$len_ens2 = sizeof($keys);
	for($i = 1; $i < $len_ens2+1; $i++) 
		$GLOBALS['active_ens'][$keys[$i-1]] = (isset($_POST["c".$i]));

	$GLOBALS['zone'] = "Zone de recherche : ".($c=display_city($_POST['city']));
	$GLOBALS['type'] = "Type de produit : ".($p=display_type($_POST['p']));
	$GLOBALS['product'] = "Produit : ".display_product($_POST['p2']);
	$GLOBALS['choices'] = "Comparaison des enseignes : ".display_ens($GLOBALS['active_ens']);
	$GLOBALS['dis'] = display_compare($p,$c);
}
/*
foreach($GLOBALS['active_ens'] as $g) {
	var_dump($g);
}
var_dump($GLOBALS['active_ens']);
$keys = array_keys($GLOBALS['active_ens']);
for($i = 1; $i < 6; $i++) 
	var_dump($GLOBALS['active_ens'][$keys[$i-1]]);*/

?>