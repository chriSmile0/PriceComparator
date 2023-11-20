#!/bin/bash

echo "coucou";

cmd_firefox="firefox https://www.google.fr/maps/place/"
cmd_num_proc="ps -e | grep -e firefox | grep -Eo '[0-9]{1,10}' | head -n 1"
num_proc=$(ps -e | grep -e firefox | grep -Eo '[0-9]{1,10}' | head -n 1)
cmd_access_history="sqlite3 /home/Bureau/.mozilla/firefox/ob87e3st.default-release-1694253438802/places.sqlite"
cmd_print_url="Select distinct(url) from moz_places where url LIKE 'https://www.google.fr/maps/place/%'" 
echo "numero de processus de la fenetre firefox active : $num_proc"
# Il s'agit ici du numéro à kill 


function space_in_plus()
{
	# Ici on attends une chaine de caracteres avec des espaces si possible
	str=$1
	res=""
	for carac in $str
		do
			res+='+'
			res+=$carac
	done 
 	# substr | ${str:offset:length}
	echo ${res:1}
	# remplacable par -> res="${str//$' '/'+'}"
} # OK 

function create_coordinates() 
{
	# Ici on attends en paramètre la cible à rechercher sur google maps
	#target=$(space_in_plus "$1") # inutile finalement voir fill_history
	# Si la cible contient des espaces on doit évidemment les remplacer
	# par des + 
	target=$1
	cmd=$cmd_firefox+$target
	$cmd
	# L'url est directement envoyé dans notre historique de navigation
	# Il reste qu'ensuite qu'en extraire les informations
} # OK 



function fill_history() 
{
	# On cherche maintenant notre fichier de cible que l'on adapte 
	all_targets=$(strings ../data/example_targets | tr -s ' ' '+')
	#echo $all_targets
	echo  $all_targets
	cpt=0
	found=6
	for target in $all_targets
		do 
			create_coordinates $target
			sleep 1
			echo $target+done
			cpt=$(($cpt + 1))
			echo $cpt
			if [ "$cpt" = "$found" ];
			then 
				echo reset;
				num_proc=$(ps -e | grep -e firefox | grep -Eo '[0-9]{1,10}' | head -n 1)
				kill -s kill "$num_proc"
			fi
	done 
}

function move_coordinates()
{
	# On est ici sur la ligne de commande qui va traiter les informations
	# de l'historique de recherche de firefox qui contient les coordonnées
	# via les URL de google 
	# pour move les coordonnées on va utiliser PHP qui rendra la tâche plus 
	# simple
	php move_coordinates.php
	return 0
}

function print_coordinates()
{
	# Réclame un paramètre qui est l'ensemble des cibles que l'on cherche 
	# à afficher
	cat target_coordinates.txt
}


#without_space=$(space_in_plus "coucou afterspace")
#echo $without_space

#create_coordinates "Paris" -> OK 
#fill_history -> OK 
#move_coordinates # -> OK 
#print_coordinatees # OK  