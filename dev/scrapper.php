<?php
require "../scrapper.php"
/*content_html("http://localhost/projet_pw2",false,"tag","lol");
echo protocol("https://localhost/projet_pw2");
echo "scrapping-> " . scrapping("https://localhost/projet_pw2") . "\n";*/
# SCRAPPING OF LECLERC : 
// - https://fd7-courses.leclercdrive.fr/magasin-037301-037301-voglans.aspx
// - https://fd7-courses.leclercdrive.fr/magasin-037301-037301-Voglans/recherche.aspx?TexteRecherche=lardons -> Voglans 
// - https://fd16-courses.leclercdrive.fr/magasin-099401-099401-Le-Kremlin-Bicetre/recherche.aspx?TexteRecherche=lardons -> Kremlin Bicêtre
// - xpath ->/html/body/form/div[4]/div[7]/div[3]/div/div[2]/section/div/div[2]/div/ul/li[23]/div
// - tag -> div 
// - class -> aWCRS310_Product, aWCSD313_Lien, divWCRS310_ProductsList

//***content_html("http://localhost/projet_pw2",false,"tag","lol");***//
# SCRAPPING OF MONOPRIX : 

# SCRAPPING OF CARREFOUR : 



?>