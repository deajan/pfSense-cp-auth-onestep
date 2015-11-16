<?php
$build = "<!-- OZY's CAPTIVE PORTAL FOR RADIUS/MySQL authentication 2015111601 -->";
/*********************************************************************/
/* Workflow:                                                         */
/*                                                                   */
/* WelcomePage() --submit--> Create / Update RADIUS user --> Login() */
/*********************************************************************/

global $brand, $hotelName, $hotelSite, $identificator;
global $today, $build, $userName, $password;

// Config file
include "captiveportal-config.php";

// Language specific stuff
$monthList = Array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
$macAdressErrorMessage = "Votre matériel ne présente pas les caractéristiques nécessaires à la connection.";
$databaseConnectErrorMessage = "Impossible de se connecter au serveur de base de données: ";
$databaseRegisterErrorMessage = "Impossible de créer votre compte utilisateur";
$incorrectInput = "Les données que vous avez entrés ne semblent pas valides.";
$today = date('j')." ".$monthList[date('n')]." ".date('Y');
$ipAddress=$_SERVER['REMOTE_ADDR'];

#run the external command, break output into lines
$arp=`arp $ipAddress`;
$lines = explode(" ", $arp);
if (isset($line[3]))
	$macAddress = $lines[3]; // Works on FreeBSD
else
	$macAddress = "";
//$macAddress = "00:00:00:11:22:33"; // Fake MAC on dev station

// Clean input function
function cleanInput($input) {
	$search = array(
	'@<script[^>]*?>.*?</script>@si',   /* strip out javascript */
	'@<[\/\!]*?[^<>]*?>@si',            /* strip out HTML tags */
	'@<style[^>]*?>.*?</style>@siU',    /* strip style tags properly */
	'@<![\s\S]*?--[ \t\n\r]*>@'         /* strip multi-line comments */
	);

	$output = preg_replace($search, '', $input);
	return $output;
}

function slog($string) {
	echo "<p style=color:red>$string</p>";
}

if(isset($_POST["cgu"]))
{
	if (isset($_POST['familyName']))
		$familyName = cleanInput($_POST["familyName"]);
	else
		$familyName = false;
	if (strlen($familyName) < 2)
	{
		WelcomePage($incorrectInput);
		die();
	}
	if (isset($_POST['surName']))
		$surName = cleanInput($_POST["surName"]);
	else
		$surName = false;
	if (strlen($surName) < 2)
	{
		WelcomePage($incorrectInput);
		die();
	}
	if (isset($_POST['roomNumber']))
		$roomNumber = cleanInput($_POST["roomNumber"]);
	else
		$roomNumber = false;
	if (strlen($roomNumber) < 1)
	{
		WelcomePage($incorrectInput);
		die();
	}
	if (isset($_POST['emailAddress']))
		$emailAddress = cleanInput($_POST["emailAddress"]);
	else
		$emailAddress = false;
	if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))
	{
		WelcomePage($incorrectInput);
		die();
	}
	$regDate = date("Y-m-d H:i:s");
	if (isset($_POST['newsletter']))
		$newsletter = 1;
	else
		$newsletter = 0;
	if (isset($_POST['facebook']))
		$facebook = 1;
	else
		$facebook = 0;
	
	$con = @mysql_connect(DBHOST,DBUSER,DBPASS);
	if (!$con)
		WelcomePage($databaseConnectErrorMessage . utf8_encode(mysql_error()));
	else
	{
		@mysql_select_db(DBNAME, $con);
		if ($macAddress!=NULL) {
			$query = "INSERT INTO reg_users (familyName, surName, roomNumber, emailAddress, macAddress, ipAddress, regDate, identificator, newsletter, facebook) VALUES ('$familyName', '$surName', '$roomNumber', '$emailAddress', '$macAddress' , '$ipAddress', '$regDate', '$identificator', '$newsletter', '$facebook');";
			if ($UPDATE == true)
			{
				$check_query = "SELECT * FROM reg_users WHERE macAddress = '$macAddress' AND emailAddress = '$emailAddress';";
				if (DEBUG == true)
					slog($check_query);
				$result = @mysql_query($check_query);
				$numrows = @mysql_num_rows($result);
				if($numrows != 0)
					$query = "UPDATE reg_users SET familyName = '$familyName', surName = '$surName', roomNumber = '$roomNumber' , ipAddress = '$ipAddress', regDate = '$regDate', identificator = '$identificator', newsletter = '$newsletter', facebook = '$facebook' WHERE macAddress = '$macAddress' AND emailAddress = '$emailAddress';";
			}
			if (DEBUG == true)
					slog($query);
			if (!@mysql_query($query))
			{
				WelcomePage($databaseRegisterErrorMessage." (1) :" . utf8_encode(mysql_error()));
				die();
			}
			
			// User name and password for RADIUS
			$userName = $emailAddress.$roomNumber;
			$password = $familyName.$surName;
			
			$query = "INSERT INTO radcheck (username, attribute, value) VALUES ('$userName', 'Password', '$password');";
			if (DEBUG == true)
					slog($query);
			if (!@mysql_query($query))
			{
				WelcomePage($databaseRegisterErrorMessage." (2)");
				die();
			}
			
			$query = "INSERT INTO radusergroup (username, groupname) VALUES ('$userName', 'Free');";
			if (DEBUG == true)
					slog($query);
			if (!@mysql_query($query))
			{
				WelcomePage($databaseRegisterErrorMessage." (3)");
				die();
			}
			Login();
		}
		else
			WelcomePage($macAdressErrorMessage);
	@mysql_close($con);
	}
}
else
	WelcomePage();

function Login()
{
	global $userName;
	global $password;
?>
<!DOCTYPE html>
<html>
	<body>
		Please click on Continue if your browser doesn't support JavaScript.
		<form name="loginForm" method="post" action="$PORTAL_ACTION$">
			<input name="auth_user" type="hidden" value="<?php echo $userName; ?>">
			<input name="auth_pass" type="hidden" value="<?php echo $password; ?>">
			<input name="redirurl" type="hidden" value="$PORTAL_REDIRURL$">
			<input id="submitbtn" name="accept" type="submit" value="Continue">
		</form>
		<script type="text/javascript">
			document.getElementById("submitbtn").click();
		</script>
	</body>
</html>
<?php
}

function WelcomePage($message = '')
{
	global $brand;
	global $hotelName;
	global $hotelSite;
	global $today;
	global $build;
?>
<!DOCTYPE html>
<?php echo $build; ?>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $brand; ?> - Accès WIFI</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php echo $brand; ?> - Accès WIFI">
		<meta name="author" content="<?php echo $brand; ?>">

		<link href="captiveportal-bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />

		<style type="text/css">
body {
  background:url('captiveportal-background.jpg') fixed center #FD6323;
  background-repeat: no-repeat;
  background-size: 100%;
}

body, html {
    height: 100%;
}

.btn {
    font-weight: 700;
    height: 36px;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    cursor: default;
}

.form-signin input[type=email],
.form-signin input[type=password],
.form-signin input[type=text],
.form-signin button {
    width: 100%;
    display: block;
    margin-bottom: 10px;
    z-index: 1;
    position: relative;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

.form-signin .form-control:focus {
    border-color: rgb(104, 145, 162);
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
}

.btn.btn-signin {
    background-color: #FB0E0E;
    padding: 5px;
    font-weight: 700;
    font-size: 14px;
    height: 36px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: 1px;
    -o-transition: all 0.218s;
    -moz-transition: all 0.218s;
    -webkit-transition: all 0.218s;
    transition: all 0.218s;
	color: #FFFFFF;
}

.btn.btn-signin:hover,
.btn.btn-signin:active,
.btn.btn-signin:focus {
    background-color: #FA6C1D;
}

.martop10p {
	margin-top: 10%;
}

.uppercase {
	text-transform: uppercase;
}

.messagebox {
	/*background-color: #EEEEEE;*/
	background: rgba(238,238,238,0.7);
	
}

.formulaire {
	color: #FFFFFF;
	background-color: #8BB6C8;
	direction: ltr;
    height: 44px;
    font-size: 16px;
}

.form-control::-webkit-input-placeholder { color: white; }
.form-control:-moz-placeholder { color: white; }
.form-control::-moz-placeholder { color: white; }
.form-control:-ms-input-placeholder { color: white; }
.form-control {
	margin-bottom: 3px;
}

.padding10 {
	padding: 10px;
}

.curpointer {
	cursor: pointer;
}

.logo {
	position: absolute;
	float: left;
	left: 10px;
	opacity: 0.7;
	height: 100%;
}

.right {
	float: right;
}

label {
	font-weight: normal;
}

/* Nice CSS checkbox */
input[type="checkbox"] {
	opacity: 0.9;
	position: absolute;
	left: -9999px;
}
input[type="checkbox"] + label span {
    display:inline-block;
	left: 14px;
    width:19px;
    height:19px;
    margin:-1px 4px 0 0;
    vertical-align:middle;
    background:url(captiveportal-check_radio_sheet.png) left top no-repeat;
    cursor:pointer;
}
input[type="checkbox"]:checked + label span {
    background:url(captiveportal-check_radio_sheet.png) -19px top no-repeat;
}

.modal .modal-body {
    max-height: 420px;
    overflow-y: auto;
	background-color: #EEEEEE;
}

.modal-content {
	background-color: #FA6C1D;
}

		</style>
		<script type="text/javascript" src="captiveportal-jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="captiveportal-bootstrap.min.js"></script>
	</head>
	<body>
		<!-- CGU Modal -->
		<div id="conditions" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Conditions d'utilisation du service</h4>
					</div>
						<div class="modal-body">
							<p>Ce service vous est offert par <?php echo $brand; ?>.</p>
							<div class="padding30 grey">	
								S'agissant d'un service gratuit, veuillez <strong>respecter</strong> les conditions normales d'utilisation
								et les <strong>autres usagers</strong> du service en évitant de télécharger ou de regarder des vidéos haute qualité svp.
								<br/>
								<br/>
								<?php include "captiveportal-termsofuse.html"; ?>
								<div class="clearfix"></div>
							</div>
						</div>
					<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">J'ai lu</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- CGU Error modal -->
		<div id="erreur" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Erreur</h4>
					</div>
						<div class="modal-body">
							<div class="padding30 grey">
								<?php echo $message; ?>
								<div class="clearfix"></div>
							</div>
						</div>
					<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<img class="logo" src="captiveportal-sidelogo.png">
			<div class="col-md-2"></div>
			<div class="col-md-8 martop10p">
				<div class="row messagebox padding10">
					<div class="col-md-6">
						<span class="uppercase">Le <?php echo $today; ?></span><br/><br/>
						<h2>Bienvenue !</h2>
						<br/>
						L'équipe de notre établissement <?php echo $hotelName; ?> est heureuse de vous offrir la connexion internet.<br/>
						Vous n'êtes plus qu'à 4 clics d'un accès web illimité toute la durée de votre séjour.<br/><br/><br/><br/><br/>
						<?php echo $hotelSite ?>
					</div>
					<div class="col-md-6">
						<form id="enregistrement" method='post' action='ozy-captive.php'>
							<fieldset>
								<div class="control-group">
									<div class="controls">
										<input type="text" class="form-control formulaire" id="roomNumber" name="roomNumber" placeholder="Numéro de chambre">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="email" class="form-control formulaire" id="emailAddress" name="emailAddress" placeholder="E-Mail">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="text" class="form-control formulaire" id="familyName" name="familyName" placeholder="Nom">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="text" class="form-control formulaire" id="surName" name="surName" placeholder="Prénom">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="checkbox" name="newsletter" id="newsletter" value="newsletter">
										<label for="newsletter">
											<span></span>Recevoir nos offres
										</label>	
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="checkbox" name="cgu" id="cgu" value="cgu">
										<label for="cgu">
											<span></span>J'accepte les 
											<a class="curpointer" data-toggle="modal" data-target="#conditions">conditions générales d'utilisation</a>
										</label>
									</div>
									<span id="cguval"></span>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="checkbox" name="facebook" id="facebook" value="facebook">
										<label for="facebook">
											<span></span>Voir nos évenements sur notre page facebook
										</label>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<input type="submit" class="btn btn-signin right" name="connecter" value="Se connecter">
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>

		<script type="text/javascript" src="captiveportal-jquery.validate.js"></script>
		<!-- Form validation -->
		<script type="text/javascript">
			$(document).ready(function(){
				$('input').hover(function(){	
					$(this).popover('show')
				});
				$("#enregistrement").validate({
					rules:{
						roomNumber:"required",
						emailAddress:{
								required:true,
								email: true
							},
						familyName:{
							required:true,
							minlength: 2
						},
						surName:{
							required:true,
							minlength: 2
						},
						cgu:{
							required:true
						}
					},
					messages:{
						roomNumber:"Entrez le numéro de votre chambre svp",
						emailAddress:{
							required:"Renseignez une adresse email valide svp",
							email:"Renseignez une adresse email valide svp"
						},
						familyName:{
							required:"Entrez votre nom svp",
							minlength:"Au minimum deux caractères"
						},
						surName:{
							required:"Entrez votre prénom svp",
							minlength:"Au minimum deux caractères"
						},
						cgu:"Merci de valider les CGU"
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight:function(element, errorClass, validClass) {
						$(element).parents('.control-group').addClass('error');
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).parents('.control-group').removeClass('error');
						$(element).parents('.control-group').addClass('success');
					},
					// Fix validate CSS checkboxes
					errorPlacement: function (error, element) {
						if (element.is(":checkbox")) {
							//element.closest('.span').append(error)
							$('#cguval').append(error);
						} else {
							error.insertAfter(element);
						}
					}
				});
				
			});
		</script>
		<?php
			// Shows error modal with $message if something didn't work
			if ($message != '')
			{
				echo "
				<script type=\"text/javascript\">
					$('#erreur').modal('show');
				</script>";
			}
		?>
	</body>
</html>
<?php
}
?>