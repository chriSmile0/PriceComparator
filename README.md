# Price Comparator

## What's it 
- A tool to compare differents subcategories of product between differents supermarket compagny
- Compare the price difference of same product in differents city of same compagny (expect MONOPRIX)

## Why 
- In the entire world the rarity of the raw materials and the price increase of these 
	saw a fly away of all products in the supermarkets (PANDEMICs, WARs, Climate Change)
- Help the people who don't have time to compare in each website the differents 
	products price to possibility make this with my tool. 

## How 
- Use the 'Drive' Websites of the principal compagny for compare products.
- Scrapping information with the parameters in entry (compagny,town,product) 
	The prices of each product is different in the same compagny in different town (or the same town)

### Scrapping
- The main tools of this tool is differents scrapping tools : 
  - [php-webdriver](https://github.com/php-webdriver/php-webdriver) 
    - [**LAST UPDATE**] 2 months (very far in scrapping world) -> (END OF THIS TOOL ? (70k download per day))
  - [puppeteer](https://github.com/puppeteer/puppeteer)
    - [**LAST UPDATE**] each week (minor evolutions)
  - [puppeteer-extra](https://github.com/berstend/puppeteer-extra)
    - [**LAST UPDATE**] <1 month
  - [puppeteer-extra-plugin-stealth](https://github.com/berstend/puppeteer-extra/tree/master/packages/puppeteer-extra-plugin-stealth)
    - [**LAST UPDATE**] 1 year 

All these tools are inspire from **SELENIUM** and are differents languages adaptation of `python/java` **SELENIUM**
A tool miss me to try, it's [PlayWright](https://github.com/microsoft/playwright) -> Ubuntu (20.04) or newer -> (NOT EVOLVE MY DEV DEVICE FOR THE MOMENT) (tool= build by microsoft team, you know.. :\ )
Maybe is the solution to my problem 

## Problems
- **Scrapping** is legal but many compagny don't want to anybody search in the body of
	the datas of their websites (too late). And it's a war between these compagny and datas looter/viewer.
- My tool is not illegal and it's necessary for many people in the differents country for survive 
	because many people jump meal because we are not lot of money to eat their fill and it's a society problem.
**BUT TODAY** in **FRANCE** these compagny protect their websites with a extra tool -> **DATADOME**

### DATADOME 
- [DATADOME](https://datadome.co/) 
It's a French-American "Cybersecurity" compagny 
Add the horse of battle of this compagny is the BOT in internet. 

#### Disclaimer 
- It's not a problem for me if a block bots solution exist but when you 
	sell a product it's important to known what's the use of these product by the buyer. 
I understand the importance to use this in a website like [LEBONCOIN](https://www.leboncoin.fr/)
But why on a Drive Website -> Block price comparator ok but it's not a consumer side solution.

The particularity of this solution is the server-side monitoring and the "IA" use
to detect suspect behavior's and the analyze of the content of the "browser" who connect
in the target. But the differents scrapper tools have a big difference with classical 
browser (mozilla,etc) the a `puppeteer-extra-plugin-stealth` by pass the **pre-last** 
version of DATADOME but known with the new version the waiting is detect and it's a very
big problem to scrap the website protect with these solution. 

The mix of this solution and the application on a website who concern the money of people 
is a problem and force many people to continue to develop **Scrapping tools** for help
people (I'm not [ROBIN_HOOD](https://en.wikipedia.org/wiki/Robin_Hood) and not [ROBIN_WOOD](https://github.com/digininja)).


### Other Tools 
- If you are in **FRANCE** and if we want to compare basket of products of 
	these different compagnies it's possible with this link : [UFC_QUE_CHOISIR](https://www.quechoisir.org/carte-interactive-drives-n21243/)
	It's an association who protect consumers and give informations on the consumer society.
	Compare 3 types of "family" -> Alone, Couple, Family -> but not possible to compare product per product.
	I hope one day it's possible to us to create a tool like mine on their tool.
	Because is an important association, I think it's possible to us to have an AUTHORIZED_IP (good BOT) on the websites of these compagnies.


- Maybe anothers tools exists but this type of tool is out of mode in partly because these compagnies use block bots solutions.

## Use 
- Clone the repository and (install all dependencies) : 
  - `composer update` or `curl -sS https://getcomposer.org/installer | php_OLDVERSION && php_OLDVERSION composer.phar update`
- (@see `cat src/product/README.md`) for use main program 
  - `composer update`
> [!IMPORTANT]
> On apache server for init `pricecomparator.co` dont forget to `mkdir .cache .mozilla .local on your /var/www` and setup your htaccess
>
> If we use this on lampp/xampp just change the url in `post` function to 
	the path to `cities.php` file.


## Clone
- IF YOU USE THIS TOOL FREE JUST A MENTION IS APPRECIATE OR A STAR (NOT COFFEE OR TEA FOR ME FOR THE MOMENT, thanks)

## Contribution
- if we have ideas for bypass **DATADOME SOLUTION** FORK or just mail me 

## Adaptation 
- if we want to adapt this tool on any others countries and we want to took a look or a contribution
	I'm open on this idea and I appreciated to work with many people on this type of project.

___________<br>
|Xxx\xxx/xxX|<br>
|Xxx| by  |xxX|<br>
| chriSmile0y|<br>
|Xxx/xxx\xxX|<br>
TTTTTTTTTT
------------
:robot: