# Price Comparator

## Définir la zone d'action 
- Prendre un point de départ cible (une ville par exemple)
- Prendre le rayon de la zone de recherche
- Calcul du cercle d'action en utilisant les coordonnées extrèmes de la zone
	(nord,est,sud,ouest)
- Laisser une marge pour les difficultés de trajets comme par exemple 
	pour suivre une route et ne pas se contenter de la distance à vol d'oiseau

## Afficher la liste de supermarché dans la zone de recherche 
- Utilisation d'une BDD (pour le moment FICHIER TXT -> C)
- Remplir ce fichier de manière automatique avec la recherche google maps
	afin d'obtenir les coordonnées exactes. (voir pour fichier bash)
	- Chercher pour chaque entreprise -> Leclerc,Super, etc | la liste de ces magasins
	- Procédure afin de récuperer les coordonnees de chaque magasin 
      - Aller sur google maps -> Faire la recherche du magasin -> document.location 
		parsing jusquà @x,y pour avoir les coordonnees exactes de la cible

- Trier le fichier en fonction des coordonnees (à la fin de la boucle d'insertion)
- Faire une recherche dans le fichier afin de d'afficher tout les magasins 
	qui sont dans la zone via un calcul simple pour savoir si il est dans la zone
	ou de recherche ou non 


### Script shell
- On va remplir un fichier de cibles (Paris,Leclerc,Super U, etc)
- On remplit notre historique avec beaucoup de lieu différents :
  - Pour toute cible :  
    - `firefox https://www.google.fr/maps/place/ + cible`
    - `CRTL^W`
  - Ou plus simplement : 
	- `firefox https://www.google.fr`
	- pour toute cible : 
      - Toutes les 8 onglets on kill la fenêtre : 
        - $NUM_PROC = `ps -e | grep -e firefox | grep -Eo '[0-9]{1,6}' | head -n 1`
			sans doute simplifiable avec une autre commande 
        - kill -s kill $NUM_PROC  
      - `firefox `
  
- On fouille dans notre historique avec la commande suivante :
  - `sqlite3 ~/.mozilla/firefox/ob87e3st.default-release-1694253438802/places.sqlite`
  - `PRAGMA table_info(moz_places)` qui donne : 
		0|id|INTEGER|0||1
		1|url|LONGVARCHAR|0||0
		2|title|LONGVARCHAR|0||0
		3|rev_host|LONGVARCHAR|0||0
		4|visit_count|INTEGER|0|0|0
		5|hidden|INTEGER|1|0|0
		6|typed|INTEGER|1|0|0
		7|frecency|INTEGER|1|-1|0
		8|last_visit_date|INTEGER|0||0
		9|guid|TEXT|0||0
		10|foreign_count|INTEGER|1|0|0
		11|url_hash|INTEGER|1|0|0
		12|description|TEXT|0||0
		13|preview_image_url|TEXT|0||0
		14|origin_id|INTEGER|0||0
		15|site_name|TEXT|0||0
		16|recalc_frecency|INTEGER|1|0|0
	- On peut donc trier par url et parser ensuite le retour de chaque entrer
	- Pour cela on utilise notre serveur apache avec php 
		afin d'extraire tout les urls et les mettre dans le fichier 
		avant d'inserer dans le fichier on parse l'url afin d'obtenir des informations
  		sous la forme cible/@x,y,z (on veut que x,y pour notre calcul de la distance par après) 
	- Commande d'extraction = `Select distinct(url) from moz_places where url LIKE 'https://www.google.fr/maps/place/%' ';` 
  		-> `fetch` puis `> file`
	- OPTION SUP -> On ajoute la comparaison avec le rayon à parcourir et le prix de l'essence afin de pousser la comparaison au maximum!!



## Selection des supermarchés dans la zone de recherche à mettre en concurrence


## Selection ou écriture des produits à rechercher dans le supermarché 





### Notes 
- Pour le moment on est sur du C pour les différentes formules que l'on doit 
	adapter et maitriser afin de faire la recherche.
- On passera sur du C++ par après pour ensuite faire un portage Web ou directement
	en PHP si ce n'est pas trop complexe à réaliser 
