# PRODUCT 
- [x] Compare differents products of subcategories -> products of categories (example : 
  - Salmon, corn flakes, bacon, ham, jam, orange juice)
- [] Compare precise sub product of subcategories -> smoked Salmon, bio corn flakes, chicken ham, ...

## Files 
- `process_p.php` -> main program 
- `usage.php`-> use by **process_p.php** for each research in different process
- `proofs/` -> the main_program operate in the past (explanation in root `README.md`) 

## Usage 
### SERVER
- PHP -> `php -S localhost:{PORT}` 
- APACHE2 : 
  - `mkdir /var/www/.local && mkdir /var/www/.cache && mkdir /var/www/.mozilla`
  - `chown www-data:www-data .l/c/m{..} chmod 755 html && chmod 755 PRICECOMPARATOR_FOLDER_NAME` 
- LAMPP/XAMPP -> (NEXT)

### WEB 
- LAMPP/XAMPP -> Launch geckodriver on a terminal window && `localhost/path/to/PRICECOMPARATOR`

### LOCAL_USAGE
- UNCOMMENT `//main(implode(" ",$argv));`
- `$e` = `"Monoprix", "Carrefour" , "Auchan"`
- `"Leclerc` -> DATADOME ADD -> DISABLE LECLERC OPTION
- `"Intermarche" ` -> new version DATADOME (like LECLERC) -> DISABLE INTEMRARCHE OPTION
- `"SystemeU"` -> DISABLE SYSTEMEU OPTION
- `php process_p.php --ens=$e[0],$e[1],$e[2] --cities="cities$e[0][0]"+"cities$e[0][1]","cities$e[1][0]" --product="prod" --label_product="sub_product"`