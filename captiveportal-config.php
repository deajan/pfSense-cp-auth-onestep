<?php
DEFINE("CONF_BUILD", "OZY's CAPTIVE PORTAL FOR RADIUS/MySQL authentication conf 2016111701");
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

// When set to true, all successful user logins are written to database
// When set to false, only the last successful user login is written to database
$UPDATE = false;

//// Hotel identification

$brand = "HOTEL WIFI";				// Hotel Brandmark
$hotelName = "MyHotel";				// Name of the hotel property to show in login screen
$hotelSite = "www.example.com";			// Internet site of hotel
$identificator = "HOTEL_ID";			// Hotel identifcator string logged to database

//// Information to get
//Be aware that RADIUS username is generated from email and room number and password is generated from familyname and surname, so don't disable all of them at once.

$askForRoomNumber = false;
$askForEmailAddress = true;
$askForFamilyName = true;
$askForSurName = true;
$askForNewsletter = true;
$askForTermsOfUse = true;
$confirmationCode = "";				// Optional connection code asked for login with minimum three characters, leave this empty to disable optional code
//$confirmationCode = "0000";

//// Language function

$validLanguages = Array('en', 'fr', 'es');	// When adding languages, add a new entry here
$language = "en";				// May be superseeded by passing language parameter in URL

//TODO: function t approach of assigning all strings is not very effective (all strings assigned on every run!)
function t($string) {

global $language, $brand, $hotelName, $hotelSite;

if ($language == "en")
{
//// English strings

// Page title
$pageTitle_string = "Wifi Access";

// UI language strings
$termsOfUse_string = "Terms of use";
$termsOfUseRead_string = "I've read";
$termsOfUseAccept_string ="I accept the";
$wifiProvidedBy_string = "This service is provied by $brand";
$generalUseMessage_string = "Being a free service, please <strong>respect</strong> the terms of use and the <strong>other users</strong> by not using all available bandwidth.";
$error_string = "Error";
$datePrefix_string = "The";
$welcome_string = "Welcome";
$welcomeMessage_string = "$hotelName is happy to provide you free internet access.<br/> You're just a couple of clics away from unlimited web access for your whole stay.";

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
$newsletter_string = "Subscribe to our newsletter";

// Connect button string
$connect_string = "Connect";
$continue_string = "Continue";

// Month names
$monthList = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

// Error messages
$macAdressErrorMessage_string = "Your device doesn't provide all necessary data for connection.";
$databaseConnectErrorMessage_string = "Cannot connect to the database. ";
$databaseRegisterErrorMessage_string = "Cannot register your user account.";
$databaseCheckErrorMessage_string = "Cannot check database for user.";
$incorrectInput_string = "The input you provided is incorrect.";
$incorrectConfirmationCode_string = "The code is incorrect.";
$noScript_string = "Please click on Continue if your browser doesn't support JavaScript.";
}

if ($language == "fr")
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
$welcomeMessage_string = "L'équipe de notre établissement $hotelName est heureuse de vous offrir la connexion internet.<br/> Vous n'êtes plus qu'à quelques clics d'un accès web illimité pour toute la durée de votre séjour.";

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
$databaseCheckErrorMessage_string = "Impossible de vérifier la base de données pour l'utilisateur.";
$incorrectInput_string = "Les données que vous avez entrés ne semblent pas valides.";
$incorrectConfirmationCode_string = "Le code établissement ne semble pas correct.";
$noScript_string = "Veuillez cliquer sur Continuer si votre navigateur ne supporte pas le JavaScript.";
}

if ($language == "es")
{
//// Spanish strings

// Page title
$pageTitle_string = "Acces Wifi";

// UI language strings
$termsOfUse_string = "Políticas de uso";
$termsOfUseRead_string = "Leído";
$termsOfUseAccept_string ="Acepto los";
$wifiProviedBy_string = "Servicio provisto por $brand";
$generalUseMessage_string = "Al ser un servicio gratuito, por favor <strong>respetar</strong> las políticas de uso y a los <strong>demas usuarios</strong> al no utilizar todo el ancho de banda disponible.";
$error_string = "Error";
$datePrefix_string = "Hoy es";
$welcome_string = "Bienvenido";
$welcomeMessage_string = "$hotelName le acerca internet gratuitamente.<br/> Está a unos clicks de acceder al servicio.";

// UI field strings
$roomNumber_string = "Número de habitación";
$confirmationCode_string = "Código de cofirmación";
$emailAddress_string = "Email";
$familyName_string = "Nombre";
$surName_string = "Apellido";

// Validation strings
$roomNumberValidation_string = "Ingrese su nombre por favor";
$confirmationCodeValidation_string = "Ingrese su código de confirmación por favor";
$emailAddressValidation_string = "Ingrese una dirección de Email válida";
$familyNameValidation_string = "Ingrese su Nombre por favor";
$surNameValidation_string = "Ingrese su Apellido por favor";
$termsOfUseValidation_string = "Acepte la política de uso por favor";
$minTwoCharacters_string = "Mínimo de dos caracteres";
$minThreeCharacters_string = "Mínimo de tres caracteres";

// Checkbox strings
$newsletter_string = "Suscribirse a nuestro newsletter";

// Connect button string
$connect_string = "Conectar";
$continue_string = "Continuar";

// Month names
$monthList = Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

// Error messages
$macAdressErrorMessage_string = "Su dispositivo no provee toda la información necesaria para la conección.";
$databaseConnectErrorMessage_string = "No se puede conectar a la base de datos. ";
$databaseRegisterErrorMessage_string = "No se puede registrar su cuenta.";
$databaseCheckErrorMessage_string = "No se puede confirmar su usuario en la base de datos.";
$incorrectInput_string = "Ingreso incorrecto.";
$incorrectConfirmationCode_string = "Código incorrecto.";
$noScript_string = "Presione continuar si su navegador no soporta Javascript.";
}

// Today format
$today = date('j')." ".$monthList[(date('n') - 1)]." ".date('Y');

// Conf build

return $$string;

}

?>
