<?php
/*
class TableRows extends RecursiveIteratorIterator {
	function __construct($it) {
	  parent::__construct($it, self::LEAVES_ONLY);
	}
  
	function current() {
	  return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
	}
  
	function beginChildren() {
	  echo "<tr>";
	}
  
	function endChildren() {
	  echo "</tr>" . "\n";
	}
}

try {
	$bdd = new PDO('sqlite:/home/chris/.mozilla/firefox/ob87e3st.default-release-1694253438802/places.sqlite');
	//$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	//$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
	//$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
	var_dump($e->getMessage());
}

try {
	$sql = "SELECT distinct(url) FROM moz_places WHERE url LIKE 'https://www.google.fr/maps/place/%'";
  	foreach($bdd->query($sql) as $row) {
    	echo $row[0];
	}

}
catch(PDOEXCEPTION $e){
	var_dump($e->getMessage());
}
$bdd = null;*/


//try : 
function extract_coordinates($file) {
	$file_w = fopen($file,"w");
	$dir = 'sqlite:/home/chris/.mozilla/firefox/ob87e3st.default-release-1694253438802/places.sqlite';
	$dbh  = new PDO($dir) or die("cannot open the database");
	$query =  "SELECT distinct(url) FROM moz_places where url LIKE 'https://www.google.fr/maps/place/%'";
	foreach ($dbh->query($query) as $row)
	{
		$index_place_and_coordinates = stripos($row[0],"place/");
		$search = substr($row[0],$index_place_and_coordinates+6);
		$end = stripos($search,"/data");
		$finally = substr($search,0,$end);
		echo $finally . "\n";
		fwrite($file_w,$finally);
	}
	fclose($file_w);
}


extract_coordinates("target_coordinates.txt");


//Fonctionne 
?>