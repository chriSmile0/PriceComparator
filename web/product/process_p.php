
<?php 
//include '../scrapper_leclerc.php';
//echo content_scrap_leclerc("Lardons","Voglans");

// SHELL EXEC OR COMPOSER 

$dis = $zone = $type = $product = $choices = "";

$all_types = ["Poisson","Viande","EpicerieSa","EpicerieSu","Pains","Patisserie"
				,"Charcuterie","Surgele","Boisson","Bebe","Bio","Hygiene","Beaute"
				,"Entretien","Nettoyage","Animalerie","Loisirs","Maison","Laiterie"
				,"Oeufs","Fruits","Legumes"	
];


function test_input2($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function display_compare(string $product, string $city) {
	$rtn = "";
	//$rtn =//content_scrap_leclerc($product,$city);//$GLOBALS['universal_URL'];
	//$res = content_scrap_leclerc("Lardons","Voglans");
	//$rtn .= json_encode($res[0]);
	return "WAITED $rtn\n";
}

function display_ens(array $checkbox_ens) : string {
	$rtn = "";
	foreach($checkbox_ens as $e) {
		$rtn .= $e.";";
	}
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
	$ens = array();
	for($i = 1; $i < 7; $i++) 
		(isset($_POST["c".$i])) ? $ens[] = $_POST["c".$i] : NULL;

	$GLOBALS['zone'] = "Zone de recherche : ".($c=display_city($_POST['city']));
	$GLOBALS['type'] = "Type de produit : ".($p=display_type($_POST['p']));
	$GLOBALS['product'] = "Produit : ".display_product($_POST['p2']);
	$GLOBALS['choices'] = "Comparaison des enseignes : ".display_ens($ens);
	$GLOBALS['dis'] = display_compare($p,$c);
}

?>