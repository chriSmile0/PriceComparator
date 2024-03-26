<?php 
// 1.2 OF SCRAPPER 
namespace Facebook\WebDriver;

use Facebook\WebDriver\Firefox\FirefoxOptions as FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities as DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver as RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy as WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition as WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys as WebDriverKeys;
use Facebook\WebDriver\Firefox\FirefoxDriver as FirefoxDriver;
require __DIR__ . '/../vendor/autoload.php';

putenv('WEBDRIVER_FIREFOX_DRIVER=/snap/bin/geckodriver');
$capabilities = DesiredCapabilities::firefox();
$firefoxOptions = new FirefoxOptions;
//$firefoxOptions->addArguments(['-headless']);
$capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);
$driver = FirefoxDriver::start($capabilities);
$driver->get("https://google.com");
$driver->quit();

?>