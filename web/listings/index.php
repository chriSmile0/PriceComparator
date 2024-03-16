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
    <div id="title">
        <h1>Listes</h1>
    </div>

	<form name="submitproduct" id="P" method="POST" enctype="multipart/form-data">
        <fieldset>
			<div>
				<!-- ADD CITIES.JSON ? -->
				<!-- OR  MAPS GOOGLE API -->
				<label for="city">Zone de recherche </label>
                <input name="city" id="city" type="text" placeholder="Ville,Arrondissement" 
                        aria-labelledby="name" value="" >
			</div>
			<div>
				<!-- ADD TYPES_P.JSON ? -->
			</div>
			<button id="btnsubmit" name="submitlistings" 
					value="Comparer" >Comparer</button>
		</fieldset>
	</form>
    
	<script src="../common.js"></script>
    <script src="l.js"></script>
</body>
</html>