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
				<!-- ADD CITIES.JSON ? -->
				<label for="city">Zone de recherche </label>
                <input name="city" id="city" type="text" placeholder="Ville,Arrondissement" 
                        aria-labelledby="name" value="" >
			</div>
			<div>
				<!-- ADD TYPES_P.JSON ? -->
				<label for="p">Type de produit</label>
                <input name="p" id="p" type="text" placeholder="Oeufs,Laiterie" 
                        aria-labelledby="name" value="" >
			</div>

			<div>
				<!-- ADD PRODS.JSON ? -->
				<label for="p2">Produit</label>
                <input name="p2" id="p2" type="text" placeholder="Pomme Golden" 
                        aria-labelledby="name" value="" >
			</div>

			<div>
				<label id="choice">Enseigne à comparer</label>
				<div id="checks">
					<label for="c1">Carrefour</label>
					<input name="c1" id="c1" type="checkbox" 
							aria-labelledby="name" value="Carrefour"
							style="margin-left:22px;"><br>
					<label for="c2">Leclerc</label>
					<input name="c2" id="c2" type="checkbox"
							aria-labelledby="name" value="Leclerc"
							style="margin-left:42.5px;"><br>
					<label for="c3">Monoprix</label>
					<input name="c3" id="c3" type="checkbox"
							aria-labelledby="name" value="Monoprix"
							style="margin-left:24px;"><br>
					<label for="c4">Auchan</label>
					<input name="c4" id="c4" type="checkbox"
							aria-labelledby="name" value="Auchan"
							style="margin-left:43px;"><br>
					<label for="c5">Intermarché</label>
					<input name="c5" id="c5" type="checkbox"
							aria-labelledby="name" value="Intermarche"
							style="margin-left:2px;"><br>
					<label for="c6">Systeme U</label>
					<input name="c6" id="c6" type="checkbox"
							aria-labelledby="name" value="SystemeU"
							style="margin-left:17px;"><br>
				</div>
			</div>
			<button id="btnsubmit" name="submitproduct" 
					value="Comparer">Comparer</button>
		</fieldset>
	</form>

	<div>
		<?php echo $GLOBALS['zone'];?><br>
		<?php echo $GLOBALS['type'];?><br>
		<?php echo $GLOBALS['product'];?><br>
		<?php echo $GLOBALS['choices'];?><br>
		<?php echo $GLOBALS['dis'];?>
	</div>

	<script src="../common.js"></script>
	<script src="P.js"></script>
</body>
</html>