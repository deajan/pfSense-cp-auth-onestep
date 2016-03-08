<?php
// OZY's CAPTIVE PORTAL FOR RADIUS/MySQL authentication 2016030803
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

// All users login attempts are all logged or only the last attempt depending on this setting
$UPDATE = true;

//// Hotel identification

$brand = "HOTEL WIFI";				// Hotel Brandmark
$hotelName = "MyHotel";				// Name of the hotel property to show in login screen
$hotelSite = "www.example.com";			// Internet site of hotel
$identificator = "HOTEL_ID";			// Hotel identifcator string logged to database

$confirmationCode = "0803";				// Optional connection code asked for login with minimum three characters, leave this empty to disable optional code
//$confirmationCode = "0000";

//// Language function

function t($string) {

$language = "ENGLISH";				// Preconfigured values are ENGLISH and FRENCH

if ($language == "ENGLISH")
{
//// English strings

// Page title
$pageTitle_string = "Wifi Access";

// UI language strings
$termsOfUse_string = "Terms of use";
$termsOfUseRead_string = "I've read";
$termsOfUseAccept_string ="I accept the";
$wifiProviedBy_string = "This service is provied by $brand";
$generalUseMessage_string = "Being a free service, please <strong>respect</strong> the terms of use and the <strong>other users</strong> by not using all available bandwidth.";
$error_string = "Error";
$datePrefix_string = "The";
$welcome_string = "Welcome";
$welcomeMessage_string = "Our staff at $hotelName is happy to provide you free internet access.<br/> You're just 4 clics away from unlimited web access for your whole stay.";

// UI field strings
$roomNumber_string = "Room Number";
$confirmationCode_string = "Confirmation code";
$emailAddress_string = "Email";
$familyName_string = "Familyname";
$surName_string = "Surname";

// Validation strings
$roomNumberValidation_string = "Please enter your room number";
$confirmationCodeValidation_string = "Please enter your confirmation code";
$emailAddressValidation_string = "Please enter a valid email address";
$familyNameValidation_string = "Enter your familyname please";
$surNameValidation_string = "Enter your surname please";
$termsOfUseValidation_string = "Please accept the terms of use";
$minTwoCharacters_string = "Minmum two characters";
$minThreeCharacters_string = "Minimum three characters";

// Checkbox strings
$newsletter_string = "Get our offers";

// Connect button string
$connect_string = "Connect";
$continue_string = "Continue";

// Month names
$monthList = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

// Error messages
$macAdressErrorMessage_string = "Your device doesn't provide all necessary data for connection.";
$databaseConnectErrorMessage_string = "Cannot connect to the database. ";
$databaseRegisterErrorMessage_string = "Cannot register your user account.";
$incorrectInput_string = "The input you provided is incorrect.";
$incorrectConfirmationCode_string = "The code is incorrect.";
$noScript_string = "Please click on Continue if your browser doesn't support JavaScript.";
}

if ($language == "FRENCH")
{
//// French strings

// Page title
$pageTitle_string = "Accès Wifi";

// UI language strings
$termsOfUse_string = "Conditions d'utilisation du service";
$termsOfUseRead_string = "J'ai lu";
$termsOfUseAccept_string ="J'accepte les";
$wifiProviedBy_string = "Ce service vous est offert par $brand";
$generalUseMessage_string = "S'agissant d'un service gratuit, veuillez <strong>respecter</strong> les conditions normales d'utilisation et les <strong>autres usagers</strong> du service en évitant de télécharger ou de regarder des vidéos haute définition svp.";
$error_string = "Erreur";
$datePrefix_string = "Le";
$welcome_string = "Bienvenue";
$welcomeMessage_string = "L'équipe de notre établissement $hotelName est heureuse de vous offrir la connexion internet.<br/> Vous n'êtes plus qu'à 4 clics d'un accès web illimité pour toute la durée de votre séjour.";

// UI field strings
$roomNumber_string = "Numéro de chambre";
$confirmationCode_string = "Code établissement";
$emailAddress_string = "E-mail";
$familyName_string = "Nom";
$surName_string = "Prénom";

// Validation strings
$roomNumberValidation_string = "Entrez le numéro de votre chambre svp";
$confirmationCodeValidation_string = "Merci de renseigner le code établissement";
$emailAddressValidation_string = "Renseignez une adresse email valide svp";
$familyNameValidation_string = "Entrez votre nom svp";
$surNameValidation_string = "Entrez votre prénom svp";
$termsOfUseValidation_string = "Merci de valider les CGU";
$minTwoCharacters_string = "Au minimum deux caractères";
$minThreeCharacters_string = "Au minimum trois caractères";

// Checkbox strings
$newsletter_string = "Recevoir nos offres";

// Connect button string
$connect_string = "Se connecter";
$continue_string = "Continuer";

// Month names
$monthList = Array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Decembre');

// Error messages
$macAdressErrorMessage_string = "Votre matériel ne présente pas les caractéristiques nécessaires à la connection.";
$databaseConnectErrorMessage_string = "Impossible de se connecter au serveur de base de données. ";
$databaseRegisterErrorMessage_string = "Impossible de créer votre compte utilisateur.";
$incorrectInput_string = "Les données que vous avez entrés ne semblent pas valides.";
$incorrectConfirmationCode_string = "Le code établissement ne semble pas correct.";
$noScript_string = "Veuillez cliquer sur Continuer si votre navigateur ne supporte pas le JavaScript.";

}

// Today format
$today = date('j')." ".$monthList[date('n')]." ".date('Y');

return $$string;

}

?>
