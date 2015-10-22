<?php
// OZY's CAPTIVE PORTAL FOR RADIUS/MySQL authentication 2015102102
// Config file for captive portal

/************************************* TEST ENV */
/*
DEFINE("DEBUG", true);
DEFINE("DBHOST", "localhost");
DEFINE("DBUSER", "root");
DEFINE("DBPASS", "");
DEFINE("DBNAME", "radius");
*/

/************************************* PROD ENV */
DEFINE("DEBUG", false);
DEFINE("DBHOST", "localhost");
DEFINE("DBUSER", "radius");
DEFINE("DBPASS", "radpass");
DEFINE("DBNAME", "radius");

// Users logins are all logged or only the last one depending on this setting
$UPDATE = true;

// Hotel identification

$brand = "HOTEL WIFI";

$hotelName = "MyHotel";					// Name of the hotel property to show in login screen
$hotelSite = "www.example.com";			// Internet site of hotel
$identificator = "HOTEL_ID";

?>