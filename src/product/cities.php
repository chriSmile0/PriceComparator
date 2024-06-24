<?php 
// store and share correct store address
// learn with user input 

function test_input22($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function create_cities_db_table() {
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE IF NOT EXISTS Cities (
				id INTEGER PRIMARY KEY,
				city VARCHAR(40) NOT NULL,
				ens VARCHAR(10) NOT NULL,
				CONSTRAINT UQ_city_ens UNIQUE(city, ens)
			)"; // CHK IF IT's possible
				// FOR THE MOMENT WITH ELEMENT IN EACH TEXT SEPARATE WITH specific character '\n' 
				// for example to parse and display each element in the inputs_display in account
		$bdd->exec($sql);
		$bdd = null;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
}

function insert_city_db_table(string $city, string $ens) {
	if(!$e=check_ens($ens))
		return FALSE;
	try {
		$city_ = test_input22($city);
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$sql = "INSERT INTO Cities (city,ens) 
				VALUES (:c,:e)";
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$stmt->execute([':c'=> $city_,':e'=> $e]);
		return TRUE;
	}
	catch (PDOException $e) {
		var_dump($e->getMessage());
	}
	return TRUE;
}

function select_city_db_table(string $city, string $ens) {
	if(!$e=check_ens($ens))
		return FALSE;
	try {
		$city_ = test_input22($city);
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$sql = "SELECT * from Cities WHERE city = :c AND ens = :e ";
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$stmt->execute([':c'=> $city_,'e'=> $e]);
		$res = $stmt->fetchAll();
		if(sizeof($res) == 1) 
			return $res[0];
		
	}
	catch (PDOException $e) {}
	return "";
}

function check_ens(string $ens) {
	return (in_array($ens,["Monoprix","Leclerc","Carrefour","Auchan","Intermarche","SystemeU"])) ? $ens : FALSE;
}

function store_of_ens(string $ens) {
	if(!$e=check_ens($ens))
		return FALSE;
	try {
		$bdd = new PDO('sqlite:' . dirname(__FILE__) . '/database.db');
		$sql = "SELECT city from Cities WHERE ens = :e ";
		$stmt = $bdd->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$stmt->execute(['e'=> $e]);
		$res = $stmt->fetchAll();
		$rtn = array();
		foreach($res as $row) 
			$rtn[] = $row['city'];
		
		return $rtn;
	}
	catch (PDOException $e) {}
	return "";
}

function post_in_desktop_mode(string $city, string $ens) {
	$city_ = $city;
	$ens_ = $ens;
	$ens_ = check_ens($ens);
	if($ens_ !== FALSE) {
		$all_cities_of_ens = store_of_ens($ens_);
		var_dump($all_cities_of_ens);
		if($all_cities_of_ens !== FALSE && $all_cities_of_ens !== "") {
			$rt = array();
			foreach($all_cities_of_ens as $value) 
				if((strpos($value,$city_)) !== FALSE) 
					$rt[] = $value;//"<option id=\"$id\" value=\"$value\">";
				
			
			echo json_encode($rt);
		}
	}
}

if(isset($_POST['listings'])) {
	$arr = array();
	$recv = $_POST['listings'];
	$ens = $_POST["ens"];
	$ens = check_ens($ens);
	if($ens !== FALSE) {
		$all_cities_of_ens = store_of_ens($ens);
		if($all_cities_of_ens !== FALSE && $all_cities_of_ens !== "") {
			$rt = array();
			foreach($all_cities_of_ens as $value) 
				if((strpos($value,$recv)) !== FALSE) 
					$rt[] = $value;//"<option id=\"$id\" value=\"$value\">";
			
			echo json_encode($rt);
		}
	}
}

if(isset($_POST["__)(__"])) { // store cities 
	$len = $_POST["__)(__"];
	$keys = array_keys($_POST);
	$res = array();
	for($i = 1 ; $i < $len+1 ; $i++) {
		$res_ = array(); 
		foreach(explode("_",$_POST[$keys[$i]],-1) as $city) 
			$res_ = array_merge($res_,[$city=>insert_city_db_table($city,$keys[$i])]);
		
		$res[$keys[$i]] = $res_;
	}
	var_dump($res);
	return $res;
}

//create_cities_db_table(); // ok 
//insert_city_db_table("Paris","Monoprix");//  ok
//var_dump(select_city_db_table("Annecy","Monoprix")); // ok 
//var_dump(store_of_ens("Monoprix")); // ok 
//post_in_desktop_mode("Ann","Monoprix"); ok
?>