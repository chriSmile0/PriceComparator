<?php require 'process_p.php';?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="chriSmile0">
    <meta name="description" content="PriceComparator">
    <meta charset="UTF-8">
    <link href="../styles/style.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" 
          rel="stylesheet">
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>PriceComparator</title>
</head>
<body>
	<noscript id="js-check-container">
        <meta http-equiv="refresh" content="0; url=../LOVE_JS.html" />
    </noscript>
    <header id="title">
        <h1>Produit</h1>
	</header>

	<form name="submitproduct" id="P" method="POST" 
		  autocomplete="off" enctype="multipart/form-data">
        <fieldset>
			<div>
				<!-- ADD TYPES_P.JSON ? -->
				<label for="p">Type de produit</label>
                <input name="p" id="p" type="text" placeholder="Oeufs,Laiterie" 
                        aria-labelledby="name" value="">
			</div>
			<!--<div>
				 ADD PRODS.JSON ?
				<label for="p2">Produit</label>
                <input name="p2" id="p2" type="text" placeholder="Pommes Golden" 
                        aria-labelledby="name" value="">
			</div>!-->
			<div>
				<h3 id="choice">Enseigne à comparer</h3>
				<div id="checks">
					<div class="labels">
						<div class="spe">
							<div class="lab_e">
								<label for="c1">Carrefour</label>
								<input name="c1" id="c1" type="checkbox" 
										aria-labelledby="name" value="Carrefour"
										style="margin-left:22px;">
							</div>
							<div class="lab_m">
								<label for="city_c">Zone de recherche</label>
								<input class="cities"name="city_c" id="city_c" type="select" placeholder="Ville,Arrondissement" 
										aria-labelledby="name" value="" list="citiesListc"><datalist id="citiesListc" class="citiesList"></datalist>
							</div>
							<button class="add_town" id="add_town_c" >+</button>
							<button class="del_town" id="del_town_c" >-</button>
						</div>
						<div class="spe add_spe"></div>
					</div>
					<div class="labels">
						<div class="spe">
							<div class="lab_e">
								<label for="c2">Leclerc</label>
								<input name="c2" id="c2" type="checkbox" 
										aria-labelledby="name" value="Leclerc"
										style="margin-left:22px;">
							</div>
							<div class="lab_m">
								<label for="city_l">Zone de recherche</label>
								<input  class="cities" name="city_l" id="city_l" type="select" placeholder="Ville,Arrondissement" 
										aria-labelledby="name" value="" list="citiesListl"><datalist id="citiesListl" class="citiesList"></datalist>
							</div>
							<button class="add_town" id="add_town_l" >+</button>
							<button class="del_town" id="del_town_l" >-</button>
						</div>
						<div class="spe add_spe"></div>
					</div>
					<div class="labels">
						<div class="spe">
							<div class="lab_e">
								<label for="c3">Monoprix</label>
								<input name="c3" id="c3" type="checkbox" 
										aria-labelledby="name" value="Monoprix"
										style="margin-left:22px;">
							</div>
							<!--<div class="lab_m">
								<label for="city_m">Zone de recherche</label>
								<input class="cities" name="city_m" id="city_m" type="select" placeholder="Ville,Arrondissement" 
										aria-labelledby="name" value="" list="citiesListm"><datalist id="citiesListm" class="citiesList"></datalist>
							</div>
							<button class="add_town" id="add_town_m" >+</button>
							<button class="del_town" id="del_town_m" >-</button>-->
						</div>
						<!--<div class="spe add_spe"></div>-->
					</div>
					<div class="labels">
						<div class="spe">
							<div class="lab_e">
								<label for="c4">Auchan</label>
								<input name="c4" id="c4" type="checkbox" 
										aria-labelledby="name" value="Auchan"
										style="margin-left:22px;">
							</div>
							<div class="lab_m">
								<label for="city_a">Zone de recherche</label>
								<input class="cities" name="city_a" id="city_a" type="select" placeholder="Ville,Arrondissement" 
										aria-labelledby="name" value="" list="citiesLista"><datalist id="citiesLista" class="citiesList"></datalist>
							</div>
							<button class="add_town" id="add_town_a" >+</button>
							<button class="del_town" id="del_town_a" >-</button>
						</div>
						<div class="spe add_spe"></div>
					</div>
					<div class="labels">
						<div class="spe">
							<div class="lab_e">
								<label for="c5">Intermarche</label>
								<input name="c5" id="c5" type="checkbox" 
										aria-labelledby="name" value="Intermarche"
										style="margin-left:22px;">
							</div>
							<div class="lab_m">
								<label for="city_i">Zone de recherche</label>
								<input class="cities" name="city_i" id="city_i" type="text" placeholder="Ville,Arrondissement" 
										aria-labelledby="name" value="" list="citiesListi"><datalist id="citiesListi" class="citiesList"></datalist>
							</div>
							<button class="add_town" id="add_town_i" >+</button>
							<button class="del_town" id="del_town_i" >-</button>
						</div>
						<div class="spe add_spe"></div>
					</div>
					<div class="labels">
						<div class="spe">
							<div class="lab_e">
								<label for="c6">SystemeU</label>
								<input name="c6" id="c6" type="checkbox" 
										aria-labelledby="name" value="SystemeU"
										style="margin-left:22px;">
							</div>
							<div class="lab_m">
								<label for="city_s">Zone de recherche</label>
								<input class="cities" name="city_s" id="city_s" type="text" placeholder="Ville,Arrondissement" 
										aria-labelledby="name" value="" list="citiesLists"><datalist id="citiesLists" class="citiesList"></datalist>
							</div>
							<button class="add_town" id="add_town_s" >+</button>
							<button class="del_town" id="del_town_s" >-</button>
						</div>
						<div class="spe add_spe"></div>
					</div>
				</div>
			</div>
			<button id="btnsubmit" name="submitproduct" 
					value="Comparer" >Comparer
			</button>
		</fieldset>
	</form>
	<script src="index.js"></script>
</body>
</html>