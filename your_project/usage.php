<?php 

namespace ChriSmile0\Scrapper;

use function ChriSmile0\Scrapper\scrap_https;
use function ChriSmile0\Scrapper\content_scrap_auchan;
use function ChriSmile0\Scrapper\content_scrap_carrefour;
use function ChriSmile0\Scrapper\content_scrap_leclerc;
use function ChriSmile0\Scrapper\content_scrap_intermarche;
use function ChriSmile0\Scrapper\content_scrap_monoprix;
use function ChriSmile0\Scrapper\content_scrap_systemeu;
use function ChriSmile0\Scrapper\new_version;
require_once('../vendor/autoload.php');

function use_scrapper(string $url, bool $with_js) { // OK 
	return scrap_https($url,$with_js);
}

function use_content_scrapper_auchan(string $product, string $city, int $p) { // OK 
	return content_scrap_auchan($product,$city,$p);
}

function use_content_scrapper_carrefour(string $product, string $city, int $p) { // OK 
	return content_scrap_carrefour($product,$city,$p);
}

function use_content_scrapper_leclerc(string $product, string $city) { // OK 
	return content_scrap_leclerc($product,$city);
}

function use_content_scrapper_intermarche(string $product, string $city, int $p) { // OK 
	return content_scrap_intermarche($product,$city,$p);
}

function use_content_scrapper_monoprix(string $product, int $p) { // OK 
	return content_scrap_monoprix($product,$p);
}

function use_content_scrapper_systemeu(string $product, string $city) { // OK
	return content_scrap_systemeu($product,$city);
}


//var_dump(use_scrapper("https://Wikipedia.com",true)); 		// OK 
//var_dump(use_content_scrapper_leclerc("Lardons","Voglans")); 	// OK
//var_dump(use_content_scrapper_carrefour("lardons","Paris")); 	// OK 
//var_dump(use_content_scrapper_monoprix("Lardons")); 			// OK 
//var_dump(use_content_scrapper_auchan("lardons","Paris")); 	// OK
//var_dump(use_content_scrapper_intermarche("lardons","Paris"));// OK
//var_dump(use_content_scrapper_systemeu("Lardons","Voglans")); // OK


// WITH INTERNAL GECKO TEST : 
/*exec("geckodriver &");
var_dump(use_content_scrapper_carrefour("lardons","Paris")); */

$scrappers_usages_example = [
	"Carrefour" => ["lardons","Paris"],
	"Leclerc" => ["lardons","Paris"],
	"Auchan" => ["lardons","Paris"],
	"SystemeU" => ["lardons","Paris"],
	"Intermarche" => ["lardons","Paris"],
	"Monoprix" => ["lardons"]
];
$scrappers_usages_min = [
	"Carrefour" => ["lardons","Paris"]
];

$scrappers_usages_min2 = [
	"Carrefour" => ["lardons","Paris"],
	"Monoprix" => ["lardons"]
];

$scrappers_usages_min3 = [
	"Carrefour" => ["lardons","Paris"],
	"Monoprix" => ["lardons"],
	"Leclerc" => ["Lardons","Voglans"]
];

$scrappers_usages_min4 = [
	"Carrefour" => ["lardons","Paris"],
	"Monoprix" => ["lardons"],
	"Leclerc" => ["Lardons","Voglans"],
	"Auchan" => ["lardons","Paris"] // NOT STABLE 
];

$scrappers_usages_min5 = [
	"Carrefour" => ["lardons","Paris"],
	"Monoprix" => ["lardons"],
	"Leclerc" => ["Lardons","Voglans"],
	"Auchan" => ["lardons","Paris"],
	"Intermarche" => ["lardons","Paris"]
];

$scrappers_usages_min_a = [
	"Auchan" => ["lardons","Paris"]
];

$scrappers_usages_min_c = [
	"Carrefour" => ["lardons","Paris"]
];

$scrappers_usages_min_l = [
	"Leclerc" => ["lardons","Voglans"]
];

$scrappers_usages_min_ca = [
	"Auchan" => ["lardons","Paris"],
	"Carrefour" => ["lardons","Paris"]
];

/**
 * [BRIEF]	CHECK
 * @param	array	$scrap	the result of a scrapping function
 * @example	check_scrapper_return(content_scrap_leclerc("..",".."))
 * @author	chriSmile0
 * @return	-1 or content of $scrap
*/
function check_scrapper_return(array $scrap) {
	if(empty($scrap))
		return -1;
	return $scrap;
}

/**
 * [BRIEF]	If A/C/I/M then : 
 * 				- We launch 2 process, one for the geckodriver, and the other for the duplicate
 * 				- When we sure the geckodriver is launch we call the target scrapper by the `$key`
 * 				- We kill all sub processes and geckodriver processes
 * 			Else (no need process)
 * 			The return is the size of the return or just a ',' if we found nothing
 * 
 * @param	string	$key			the supermarket name
 * @param	array	$scrapper_usage	the research and the target city
 * @param	int		$port			port to connect geckodriver in case we use the port 
 * @example	parrallelize_scrapping_process("Monoprix",["lardons"],4444=default)
 * @author	chriSmile0
 * @return	string	content_scrap_... return or "," if not found
*/
function parrallelize_scrapping_process(string $key, array $scrapper_usage, 
										int $port) : string { // gecko -> auchan,monoprix,carrefour,intermarche
	$childs = array();
	$ens = $key[0];
	$rtn = "";
	$content = array();
	if($ens !== "S" && $ens !== "L") {
		for($x = 0; $x < 2; $x++) {
			if($x == 1)
				sleep(1);
			switch($pid = pcntl_fork()) {
				case -1:
					die('Fork failed');
					exit(0);
				case 0:
					if($x == 0) 
						exec("geckodriver -p $port");
					exit(); // IMPORTANT
				default:
					$childs[] = $pid;
					break;
			}
		}
		$exec_scrapper = 0;
		while(count($childs) > 0) {
			foreach($childs as $key => $pid) {
				$res = pcntl_waitpid($pid, $status, WNOHANG);
				if($res == -1 || $res > 0) {
					unset($childs[$key]);
				}
				if(($res == 0) && (count($childs)==1)) { // geckodriver is launch
					if(!$exec_scrapper) {
						$exec_scrapper = 1;
						switch($ens) {
							case "A":
								$content = content_scrap_auchan($scrapper_usage[0],$scrapper_usage[1],$port);
								break;
							case "C":
								$content = content_scrap_carrefour($scrapper_usage[0],$scrapper_usage[1],$port);
								break;
							case "I":
								$content = content_scrap_intermarche($scrapper_usage[0],$scrapper_usage[1],$port);
								break;
							case "M":
								$content = content_scrap_monoprix($scrapper_usage[0],$port);
								break;
							default:
								$exec_scrapper = 2; // ERROR
								break;
						}
					}
					system("kill -s kill $pid"); // kill geckodriver launcher
					exec("kill -s kill `lsof -i -P | grep -e :$port | grep -Eo '[0-9]{1,10}' | head -n 1`");
				}
			}
		}
	}
	else if($ens === "L") { // NO GECKODRIVER
		$content = content_scrap_leclerc($scrapper_usage[0],$scrapper_usage[1]);
	}
	else if($ens === "S") {	// PUPPETEER
		$content = content_scrap_systemeu($scrapper_usage[0],$scrapper_usage[1]);
	}
	else { // UNKNOWN 
		$content = "";
	}
	$rtn = (check_scrapper_return($content)==-1) ? "" : json_encode($content);
	if($rtn !== "")
		return "".strlen($rtn).",".$rtn;
	return ",";
}

/**
 * [BRIEF]	
 * @param	array	$scrappers_usage	target supermarket and target_product and town
 * @example	globals_execs(["Monoprix"=>["lardons"])
 * @author	chriSmile0
 * @return	the scrapping content of each usage in the array in parameter
*/
function globals_execs(array $scrappers_usage) { // OK 
	$childs = array();
	$recv_content = array();
	$ports = 4444;
	$returns = array();
	$arrys = array();
	$i = 0;
	var_dump($childs);
	foreach($scrappers_usage as $key => $usages) {
		$port = $ports+$i;
		$recv_content[] = [$key,false,$port];
		$arrys[] = array();
		socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $arrys[$i]);
		switch($pid = pcntl_fork()) {
			case -1:
				die('Fork failed');
				exit(0);
			case 0:
				$rtn = parrallelize_scrapping_process($key,$usages,$port); // DUMP RESULT IN PIPE
				$offset = 0;
				$size = substr($rtn,0,$offset=strpos($rtn,","));
				$size = ($size===FALSE || $size==="") ? "0" : $size; // FALSE -> 7.2, "" -> 8.0 // FOR REST OF THE PACKAGE FOR COMPATIBILITY!!!
				socket_close($arrys[$i][1]);
				socket_write($arrys[$i][0], $size);
				if($offset > 0) {
					$rtn_t = substr($rtn,$offset+1);
					if(socket_read($arrys[$i][0],2)=="OK") 
						socket_write($arrys[$i][0], $rtn_t, strlen($rtn_t));

				}
				socket_close($arrys[$i][0]);
				exit(1); // IMPORTANT
			default:
				$childs[] = [$pid,$i,$key];
				break;
			
		}
		$i++;
	}
	while(count($childs) > 0) {
		foreach($childs as $key => $pid) {
			$res = pcntl_waitpid($pid[0], $status, WNOHANG);
			if($res == -1 || $res > 0) {
				unset($childs[$key]);
			}
			if(($res == 0) && (!$recv_content[$key][1])) {
				socket_close($arrys[$pid[1]][0]);
				$size = trim(socket_read($arrys[$pid[1]][1],10));
				if($size > 0)  // send content 
					if(socket_write($arrys[$pid[1]][1],"OK")) 
						if($rtn_tt = trim(socket_read($arrys[$pid[1]][1],$size))) {
							$returns = array_merge($returns,[$pid[2]=>json_decode($rtn_tt,true)]);
							$recv_content[$key][1] = true;
						}
				
				socket_close($arrys[$pid[1]][1]);
			}
		}
	}
	foreach($recv_content as $rc) { // check if we have all contents, if this is not the case we relaunch the forgot research
		if($rc[1]==false)
			echo "re-LAUNCH :".$rc[0].", on port : ".$rc[2]."\n";
	}
	return $returns;
}

function my_json_encoding_2($to_encode) { // FOR MAIN PARAMETER
	$str = json_encode($to_encode);
	$res = "";
	$size = strlen($str);
	for($i = 0; $i < $size ;$i++) 
		$res .= ($str[$i] === '"') ? '\"' : (($str[$i] === "'") ? "\'" : $str[$i]);
	
	return $res;
}


function main_u($argc, $argv) {
	$arr = implode(",",array_slice($argv,1));
	$elements = json_decode($arr,true);// OK
	echo json_encode(globals_execs($elements)); // recv this in process_p for print
}

//echo json_encode(globals_execs($scrappers_usages_min_ca));
main_u($argc,$argv);
//var_dump(globals_execs($scrappers_usages_min_l));
//var_dump(use_content_scrapper_auchan("Lardons","Paris",4444));
//var_dump(use_content_scrapper_leclerc("Saumon","Annecy"));
//var_dump(use_content_scrapper_carrefour("Lardons fume","Paris",4444));
//var_dump(use_content_scrapper_intermarche("Lardons","Paris",4444));
//var_dump(use_content_scrapper_monoprix("Saumon",4444));
//var_dump(use_content_scrapper_systemeu("Lardons","Paris"));
?>