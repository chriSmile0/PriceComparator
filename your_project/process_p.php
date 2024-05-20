
<?php 
/**
 * [BRIEF] HERE WE USE THE RETURN OF PROCESSES
 * 		(beause pcntl_fork()) is not support on the webserver
 * 		Results : All informations we write by the user is transmit in string (JSON_STR)
 * 					On the exec of the file usage.php
 * 				  - Parser and creater is necessary  
*/


$scrappers_usages_example = [
	"Carrefour" => ["lardons","Paris"],
	"Leclerc" => ["lardons","Paris"],
	"Auchan" => ["lardons","Paris"],
	"SystemeU" => ["lardons","Paris"],
	"Intermarche" => ["lardons","Paris"],
	"Monoprix" => ["lardons"]
];

$names_products = [ // NAME PRODUCT
	"Carrefour" => "title",
	"Leclerc" => "sLibelleLigne1|sLibelleLigne2",
	"Auchan" => "label",
	"SystemeU" => "brand",
	"Intermarche" => "informations",
	"Monoprix" => "name"
];


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
				,"Oeufs","Fruits","Legumes","Lardons","Saumon"	
];

$rayons = ["Boulangerie","Fruits"]; // FOR NEXT UPDATE

$choice_ens = array();
$possible_ens = ["Carrefour","Leclerc","Monoprix","Auchan","Intermarche"];//ADD SU LATER
$active_ens =  ["Carrefour"=>false,"Leclerc"=>false,"Monoprix"=>false,"Auchan"=>false,"Intermarche"=>false];

//---------------------------------UTILS--------------------------------------//
function cmp_label(string $label1, string $label2) {
	$split_l1 = explode(" ",strtolower($label1)." ");
	$split_l2 = explode(" ",strtolower($label2)." ");
	$s1 = sizeof($split_l1);
	$s2 = sizeof($split_l2);
	$i1 = 0;
	$i2 = 0;
	$commons1 = "";
	foreach($split_l1 as $sl1) {
		foreach($split_l2 as $sl2) {
			if($sl1 == $sl2) {
				$commons1 .= " $sl1";
				$i1++;
				break;
			}
		}
	}
	$commons2 = "";
	foreach($split_l2 as $sl2) {
		foreach($split_l1 as $sl1) {
			if($sl2 == $sl1) {
				$commons2 .= " $sl2";
				$i2++;
				break;
			}
		}
	}
	if(($s1 === $i1)) 
		return substr($commons1,1);
	return "";
}


function sort_list(array $products) {
	$l = $products;
	$sort = array();
	$index = 0;
	foreach($l as $p) {
		$found = false;
		$index = 0;
		foreach($sort as $s) {
			if(($c = cmp_label($p['name'],$s['name'])) !== "") {
				$found = TRUE;
				break;
			}
			$index++;
		}
		if($found === TRUE) {
			if(stristr($sort[$index]['ens'],$p['ens'])!==FALSE)
				continue;
			$sort[$index]['ens'] .= "|".$p['ens'];
			$sort[$index]['prix'] .= "|".$p['prix'];
		}
		else 
			$sort[] = $p;

	}
	return $sort;
}

//-----------------------------END UTILS--------------------------------------//


function main_(array $elements) {
	$str = my_json_encoding($elements);
	exec("php usage.php $str > out.txt");
	$rtn = file_get_contents("out.txt");
	$parsing = parse_exec_usage($rtn);
	return $parsing;
}

$arr = array("Auchan"=>["Lardons","Paris"]);
$arr2 = array("Leclerc"=>["lardons","Voglans"]);


function my_json_encoding(array $to_encode) { // FOR MAIN PARAMETER
	$str = json_encode($to_encode);
	$res = "";
	$size = strlen($str);
	for($i = 0; $i < $size ;$i++) 
		$res .= ($str[$i] === '"') ? '\"' : (($str[$i] === "'") ? "\'" : $str[$i]);
	
	return $res;
}

function parse_exec_usage(string $rtn) {
	return json_decode(substr($rtn,strpos($rtn,"{\"")),true);
}

function create_cmp_product(array $all_products, string $compare_product) { // easy with 1.2 Scrapper Version
	$save_products = array();
	foreach($all_products as $k => $v) {
		if($v !== NULL) {
			if($k === "Carrefour") {
				foreach($v as $val) {
					$save_products[] = ["brand"=>$val['brand'],"name"=>$val['title'],"ens"=>$k,"prix"=>$val['price']['price']];
				}
			}
			if($k === "Leclerc") {
				foreach($v as $val) {
					$save_products[] = ["brand"=>$val['brand'],"name"=>$val['sLibelleLigne1']." ".$val['sLibelleLigne2'],"ens"=>$k,"prix"=>$val['nrPVUnitaireTTC']];
				}
			}
			if($k === "Monoprix") {
				foreach($v as $val) {
					$save_products[] = ["brand"=>$val['brand'],"name"=>$val['name'],"ens"=>$k,"prix"=>$val['price']['current']['amount']];
				}
			}
			if($k === "Auchan") {
				foreach($v as $val) {
					$save_products[] = ["brand"=>$val['brand'],"name"=>$val['label']." ".$val['quantity'],"ens"=>$k,"prix"=>$val['price']];
				}
			}
			if($k === "Intermarche") {
				foreach($v as $val) {
					$infos = $val["informations"];
					$save_products[] = ["brand"=>$infos['brand'],"name"=>$infos['title']." ".$infos["packaging"]." ".$infos["brand"],"ens"=>$k,"prix"=>$val['prices']['value']];
				}
			}
			if($k === "SystemeU") {
				foreach($v as $val) {
					$save_products[] = ["brand"=>$val['brand'],"name"=>$val['name'],"ens"=>$k,"prix"=>$val['price']];
				}
			}
		}

	}
	$all_brands = array();
	$products_by_brand = array();
	foreach($save_products as $elem) {
		if(!in_array($lower=strtolower($elem['brand']),$all_brands)) {
			$all_brands[] = $lower;
			$products_by_brand = array_merge($products_by_brand,[$lower=>[$elem['ens'],[$elem]]]);
		}
		else {
			$products_by_brand[$lower][1][] = $elem;
			if(stristr($products_by_brand[$lower][0],$elem['ens'])===FALSE) // REPLACE BY STR_CONTAINS IN PHP8.0
				$products_by_brand[$lower][0] .= "|".$elem['ens'];
		}

	}
	$comparable_ens = array();
	foreach($products_by_brand as $k=>$pb) {
		if((substr_count($pb[0],"|"))>0)
			$comparable_ens = array_merge($comparable_ens,[$k=>$pb]);
	}

	$sort_list_p = array();
	foreach($comparable_ens as $k=>$pb) {
		$sort_list_p = array_merge($sort_list_p,[$k=>sort_list($pb[1])]);
	}
	return $sort_list_p;

}

function display_each_brand(array $comparable_brand) {
	$rtn = "";
	$all_brands = array_keys($comparable_brand);
	foreach($all_brands as $b) {
		foreach($comparable_brand[$b] as $product) { // [1] for comparable ens
			$rtn .= display_each_product($product);
		}
	}
	return $rtn;
}

function display_each_product(array $product) {
	$rtn = "<span>";
	foreach($product as $k=>$elem) 
		$rtn .= "<b>$k:$elem</b>";
	
	$rtn .= "</span>\n";
	return $rtn;
}

function display_compare(array $ens, string $product, string $label, array $cities) {
	$elements = array();
	$i = 0;
	foreach($ens as $e) {
		$elements = array_merge($elements,[$e=>[$product,$cities[$i]]]);
		$i++;
	}
	$to_display = main_($elements); // OK
	$full_rtn = display_each_brand(create_cmp_product($to_display,$label));
	return $full_rtn;
}

function display_ens(array $checkbox_ens) : string {
	$rtn = "";
	foreach($checkbox_ens as $k => $v) 
		$rtn .= ($v) ? $k.";" : "";
	
	return $rtn;
}

function display_cities(array $cities) : string {
	return implode("|",$cities);
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

function parse_command_line(string $command_line) {
	// EXAMPLE : php7.2 process_p.php --ens=Auchan,Leclerc --cities="Paris","Voglans" --products="Lardons" --label_products="Lardons fumes"
	$arr = explode(" ",$command_line,2);
	if(sizeof($arr) == 1) {
		echo "ERROR arguments : --ens= --cities= --products= --label_product=[OPTION]\n";
	}
	else {
		$options = array_slice(explode(" --"," ".$arr[1]),1);
		if(($s = sizeof($options))>=3 && $s < 5) {
			$elements = [
					"ens"=>explode(",",substr($options[0],strpos($options[0],"=")+1)),
					"cities"=>explode(",",substr($options[1],strpos($options[1],"=")+1)),
					"products"=>explode(",",substr($options[2],strpos($options[2],"=")+1))
			];
			if($s == 4) 
				$elements = array_merge($elements,["labels"=>explode(",",substr($options[3],strpos($options[3],"=")+1))]);

			return $elements;
		}
		else {
			echo "ERROR arguments : --ens= --cities= --products= \n";
		}
	}
	return array();
}

function main(string $command_line, bool $web) {
	if(!$web) {
		$fields = parse_command_line($command_line);
		$ens = $fields["ens"];
		$GLOBALS['active_ens'] = $ens;
		$cities = $fields["cities"]; 		// for the moment is one city 
		$products = $fields["products"][0]; // ""
		$labels = $fields["labels"][0]; 	// ""
		$GLOBALS['zone'] = "Zone de recherche : ".($c=display_cities($cities));
		$GLOBALS['type'] = "Type de produit : ".($p=display_type($products));
		$GLOBALS['product'] = "Produit : ".($p2=display_product($labels)); // update now 
		$GLOBALS['choices'] = "Comparaison des enseignes : ".display_ens($ens);
		$GLOBALS['dis'] = display_compare($ens,$p,$p2,$cities);
		echo $GLOBALS['dis'];
	}
	else { // TO UPDATE IN WEB VERSION
		if(isset($_POST['submitproduct'])) {
			$keys = array_keys($GLOBALS['active_ens']);
			$len_ens2 = sizeof($keys);
			for($i = 1; $i < $len_ens2+1; $i++) 
				$GLOBALS['active_ens'][$keys[$i-1]] = (isset($_POST["c".$i]));
			//tab of $cities
			$cities = array();
			$GLOBALS['zone'] = "Zone de recherche : ".($c=display_cities(array($_POST['city'])));
			$GLOBALS['type'] = "Type de produit : ".($p=display_type($_POST['p']));
			$GLOBALS['product'] = "Produit : ".($p2=display_product($_POST['p2']));
			$GLOBALS['choices'] = "Comparaison des enseignes : ".display_ens($GLOBALS['active_ens']);
			$GLOBALS['dis'] = display_compare($GLOBALS['active_ens'],$p,$p2,[$c]); //$cities
		}
	}

}
main(implode(" ",$argv),false);
?>