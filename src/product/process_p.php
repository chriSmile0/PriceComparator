<?php 
namespace ChriSmile0\Scrapper;

use Exception;

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

$scrappers_usages_example2 = [
	"Carrefour" => ["Paris"],
	"Auchan" => ["Annecy"],
	"Monoprix" => ["Paris"]
];


$dis = $zone = $type = $product = $choices = "";

$all_types = ["Poisson","Viande","EpicerieSa","EpicerieSu","Pains","Patisserie"
				,"Charcuterie","Surgele","Boisson","Bebe","Bio","Hygiene","Beaute"
				,"Entretien","Nettoyage","Animalerie","Loisirs","Maison","Laiterie"
				,"Oeufs","Fruits","Legumes","Lardons","Saumon"	
];

$rayons = ["Boulangerie","Fruits"]; // FOR ANOTHER UPDATE OF SCRAPPER (2.0? NOW=1.5.2)

$choice_ens = array();
$possible_ens = ["Carrefour","Leclerc","Monoprix","Auchan","Intermarche","SystemeU"];//ADD SU LATER
$active_ens =  ["Carrefour"=>false,"Leclerc"=>false,"Monoprix"=>false,"Auchan"=>false,"Intermarche"=>false,"SystemeU"=>false];

$labelErr = $typeErr = "";

//---------------------------------UTILS--------------------------------------//
function test_input2($data) {
    return (is_string($data)) ? htmlspecialchars(stripslashes(trim($data))) : FALSE;
}

function validate_product(string $product, string $type) {
	$nom = test_input2($product);
	if(!preg_match("/^[a-zA-Z- éè]{2,30}$/",$nom)) {
		switch($type) {
			case "type":
				$GLOBALS['typeErr'] = "Espace et tiret autorisés ainsi que les majuscules";  
				break;
			case "label":
				$GLOBALS['labelErr'] = "Espace et tiret autorisés ainsi que les majuscules";  
				break;
			default:
				break;
			}
		return FALSE;
	} 
	return $nom;
}
 
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

function init_in_ens(string $init) : string {
	switch($init) {
		case "c":
			return "Carrefour";
		case "l":
			return "Leclerc";
		case "m":
			return "Monoprix";
		case "a":
			return "Auchan";
		case "i":
			return "Intermarche";
		case "s":
			return "SystemeU";
		default:
			return "X";
			break;
	}
}

//-----------------------------END UTILS--------------------------------------//
function main_(array $elements) {
	$str = my_json_encoding($elements);
	$cmd = PHP_BINDIR."/php " .__DIR__."/usage.php $str > out.txt";
	exec($cmd);
	$rtn = file_get_contents("out.txt");
	$parsing = parse_exec_usage($rtn);
	return $parsing;
}

function main_web(array $elements) {
	$str = my_json_encoding($elements);
	$cmd = PHP_BINDIR."/php " .__DIR__."/usage.php $str";
	$rtn = exec($cmd);
	$parsing = parse_exec_usage($rtn);
	return $parsing;
}

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
	$no_return = array();
	foreach($all_products as $k => $v) {
		if($v !== NULL) {
			$old_k = $k;
			foreach($v as $k => $p) { // 
				if(empty($p)) 
					$no_return[$old_k][] = $k;
				else 
					$return[$old_k][] = $k;
				if($old_k === "Carrefour") {
					foreach($p as $val) { 
						$save_products[] = ["brand"=>$val['brand'],"name"=>$val['title']." ".$val["packaging"],"ens"=>$old_k.":".$k,"prix"=>$val['price']['price']];
					}
				}
				if($old_k === "Leclerc") {
					foreach($p as $val) {
						$save_products[] = ["brand"=>$val['brand'],"name"=>$val['sLibelleLigne1']." ".$val['sLibelleLigne2'],"ens"=>$old_k.":".$k,"prix"=>$val['nrPVUnitaireTTC']];
					}
				}
				if($old_k === "Monoprix") {
					foreach($p as $val) {
						$save_products[] = ["brand"=>$val['brand'],"name"=>$val['name'],"ens"=>$old_k.":".$k,"prix"=>$val['price']['current']['amount']];
					}
				}
				if($old_k === "Auchan") {
					foreach($p as $val) {
						$save_products[] = ["brand"=>$val['brand'],"name"=>$val['label']." ".$val['quantity'],"ens"=>$old_k.":".$k,"prix"=>$val['price']];
					}
				}
				if($old_k === "Intermarche") {
					foreach($p as $val) {
						$infos = $val["informations"];
						$save_products[] = ["brand"=>$infos['brand'],"name"=>$infos['title']." ".$infos["packaging"]." ".$infos["brand"],"ens"=>$old_k.":".$k,"prix"=>$val['prices']['value']];
					}
				}
				if($old_k === "SystemeU") {
					foreach($p as $val) {
						$save_products[] = ["brand"=>$val['brand'],"name"=>$val['name'],"ens"=>$old_k.":".$k,"prix"=>$val['price']];
					}
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
	$cmp = 0;
	if(sizeof($all_products)==1)
		$cmp = -1;

	$comparable_ens = array();
	$others = array();
	foreach($products_by_brand as $k=>$pb) {
		if((substr_count($pb[0],"|"))>$cmp)
			$comparable_ens = array_merge($comparable_ens,[$k=>$pb]);
		else 
			$others = array_merge($others,[$k=>$pb]);
	}
	
	$sort_list_p = array();
	foreach($comparable_ens as $k=>$pb) 
		$sort_list_p = array_merge($sort_list_p,[$k=>sort_list($pb[1])]);
	
	post($return);

	return [$no_return,[$sort_list_p,$others]];
}

function display_each_brand(array $products_brands) : array {
	$rtn = "";
	$all_common_brands = array_keys($products_brands[1][0]);
	$rdm_product = array_keys($products_brands[1][1]);
	$rtn .= "<div class=\"compare\">";
	foreach($all_common_brands as $b) {
		$br = (($b==="") ? "Inconnu" : $b);
		$rtn .= "<div class=\"brands\"><h5 style=\"text-align:center;margin-left:5%;\">$br</h5>";
		$rtn .= "<table><tr><th>Name</th><th>ENS:City</th><th>Price</th></tr>";
		foreach($products_brands[1][0][$b] as $product) {
			$rtn .= display_each_product($product);
		}
		$rtn .= "</table></div>";
	}
	foreach($rdm_product as $b) {
		$br = (($b==="") ? "Inconnu" : $b);
		$rtn .= "<div class=\"brands\"><h5 style=\"text-align:center;margin-left:5%;\">$br</h5>";
		$rtn .= "<table><tr><th>Name</th><th>ENS:City</th><th>Price</th></tr>";
		foreach($products_brands[1][1][$b][1] as $product) {
			$rtn .= display_each_product($product);
		}
		$rtn .= "</table></div>";
	}
	return [$products_brands[0],$rtn . "</div>"];
}

function display_each_product(array $product) {
	$rtn = "<tr>";
	foreach($product as $k=>$elem) {
		if($k=="brand")
			continue; 
		if(count(($tab_elem = explode("|",$elem)))>1) {
			$rtn .= "<td><div>";
			if($k=="prix") {
				$min = min($tab_elem);
				$spe = "style=\"border:solid blue;\"";
				foreach($tab_elem as $t) 
					$rtn .= "<b ".(($t==$min) ? $spe :"").">$t</b>";

			}
			else {
				foreach($tab_elem as $t) 
					$rtn .= "<b >$t</b>";
			}
			$rtn .= "</div></td>";
		}
		else {
			$rtn .= "<th>$elem</th> ";
		}
	}
	$rtn .= "</tr>\n";
	return $rtn;
}

function display_compare(array $globals, string $product, string $label, bool $web) {
	$elements = array();
	foreach($globals as $k => $e) 
		$elements = array_merge($elements,[$k=>[$product,$e]]);
	
	$to_display = ($web) ? main_web($elements) : main_($elements); // OK
	return display_each_brand(create_cmp_product($to_display,$label));
}

function display_globals(array $globals) : string {
	$rtn = "";
	foreach($globals as $k => $v) {
		$rtn .= "$k : ";
		foreach($globals[$k] as $v) 
			$rtn .=  "$v ";
		$rtn .= "\n";
	}
	return $rtn;
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

function onceens_multistores(array $post, array $ens) {
	// process to compare many stores of once ens (expect Monoprix)
	$all_cities = array();
	foreach($post as $k => $v) {
		$f_once = FALSE;
		if(strpos($k,"city_") === 0 && $v !== "") { // check $k and $v
			$all_cities[$t=init_in_ens(substr($k,5,1))][] = $v;
			$f_once = TRUE;
		}
		if(($f_once == FALSE) && (in_array($t=init_in_ens(substr($k,5,1)),$ens))) {
			$all_cities[$t][] = "Paris";
		}
	}
	return $all_cities;
}

function parse_command_line(string $command_line) {
	// EXAMPLE : php process_p.php --ens=Carrefour,Auchan --cities="Paris"+"Bordeaux","Annecy" --product="Lardons" --label_product="Lardons fumes"
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
					"product"=>explode(",",substr($options[2],strpos($options[2],"=")+1))
			];
			if($s == 4) 
				$elements = array_merge($elements,["label"=>explode(",",substr($options[3],strpos($options[3],"=")+1))]);
			foreach($elements["cities"] as $k => $v) 
				$elements["cities"][$k] = explode("+",$v);

			$elements["globals"] = array_combine($elements["ens"],$elements["cities"]);
			return $elements;
		}
		else {
			echo "ERROR arguments : --ens= --cities= --product= \n";
		}
	}
	return array();
}

function rtn_error(array $no_return, string $produit) {
	$rt_error = "***ERROR***\n";
	foreach($no_return as $k => $v) 
		foreach($v as $val)
			$rt_error .= "**$k:$val**\n";
	
	$rt_error .= "***END ERROR***\n";
	return $rt_error;
}

function main(string $command_line) {
	$fields = parse_command_line($command_line);
	$globals = $fields["globals"];
	$products = $fields["product"][0]; // ""
	$labels = $fields["label"][0]; 	// ""
	$dump = display_compare($globals,$products,$labels,false);
	echo $dump[1];
	echo rtn_error($dump[0],$products);
}

function main_array(array $ens_cities, string $product, string $sub_product, bool $web) {
	$dump = display_compare($ens_cities,$product,$sub_product,$web); // !!!!!!!!!!!!
	echo $dump[1];
	echo rtn_error($dump[0],$product);
}

//main(implode(" ",$argv));
//main_array($scrappers_usages_example2,"lardons","lardons",false);

if(isset($_POST['submitproduct'])) {
	$keys = array_keys($GLOBALS['active_ens']);
	$ens = $GLOBALS['active_ens'];
	$len_ens2 = sizeof($keys);
	for($i = 1; $i < $len_ens2+1; $i++) 
		$ens[$keys[$i-1]] = (isset($_POST["c".$i]));
	
	if(($p_ = validate_product($_POST['p'],"product")) === FALSE)
		return NULL;

	$p2 = $p_;
	//$GLOBALS['product'] = "Produit : ".($p2=display_product($_POST['p2']));
	$ens2 = array();
	foreach($ens as $k => $v) {
		if($v === true) {
			$ens2[] = $k;
		}
	}
	if($ens["Monoprix"]==true)
		$_POST["city_m"] = "Paris";
	$globals = onceens_multistores($_POST,$ens2);//  OK 
	$dump = display_compare($globals,$p_,$p2,true);	 // OK 
	$n_return = $dump[0];
	$rt_error = "<div class=\"div_error_cities\">";
	$rt_error .= "<h3 class=\"error\">!Relancer votre recherche!</h3>";
	$rt_error .= "<div><b>Produit : $p_</b> <br><span><b>Erreur sur les villes suivantes : </b></span>";
	foreach($n_return as $k => $v) {
		foreach($v as $val)
			$rt_error .= "<br><span><b>$k</b>:<b>$val</b></span>";
	}
	$rt_error .= "</div></div>";
	echo $dump[1];
	echo $rt_error;
}

function post_to_otherphp_file(string $url, array $key_elements) {
	$body = "";
	foreach($key_elements as $k => $v) { // $key_elements directly in $body is possible 
		$k_ = test_input2($k);
		$v_ = test_input2($v);
		if($k_ !== FALSE && $v_ !== FALSE)
			$body .= "&$k_=$v_";
	}
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_POSTFIELDS, http_build_query($key_elements));//["store"=>$body]);
	curl_setopt($c, CURLOPT_RETURNTRANSFER,true);
	$page = curl_exec ($c);
	curl_close ($c);
}

function post(array $fields) {
    $url = 'pricecomparator.co/product/cities.php';
	$fields_string = "__)(__=".count($fields)."&";
    foreach($fields as $key => $value) { 
		$key_ = test_input2($key);
		$fields_string .= $key_.'=';
		foreach($value as $city) 
			if($key_ !== FALSE && $city !== FALSE)
				$fields_string .= $city.'_'; 

		$fields_string .= "&";
	}
	 
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields)+1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	$page = curl_exec ($ch);
	return $page;
}

?>