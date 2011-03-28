<?php
require_once ('genericException/genericException.class.php');
require_once ('formHandler/formRule.class.php');
require_once ('formHandler/formHandler.class.php');

$oFh = new formHandler ();

$oLogin = new formRule ('login');
$oLogin->setIsRequired ('Veuillez indiquer un pseudo !');
$oLogin->setMaxLength (25, 'Votre pseudo ne doit pas faire plus de 25 caractères !');

$oPass = new formRule ('passwd');
$oPass->setIsRequired ('Veuillez indiquer un mot de passe');
$oPass->setMinLength (6, 'Votre mot de passe doit avoir plus de 6 caractères');

$oConf = new formRule ('confpass');
$oConf->setIsRequired ('Veuillez indiquer une Confirmation de mot de passe');
$oConf->setMinLength (6, 'Votre Confirmation de mot de passe doit avoir plus de 6 caractères');
$oConf->setIsEqual ($oPass, 'Votre mot de passe doit être identique a sa confirmation');

$oMail = new formRule ('email');
$oMail->setIsRequired ('Votre email est obligatoire !');
$oMail->setIsEmail ('Votre email est invalide !');

$oWeb = new formRule ('web');
$oWeb->setIsRequired ('Votre site Web est requis !');
$oWeb->setIsUrl ('l\'Url de votre site est invalide !');

$oGenre = new formRule ('genre');
$oGenre->setIsRequired ('Genre requis !');
$oGenre->setIsNotEqual('---------', 'Veuillez choisir un genre !');

$oAnimaux = new formRule ('animaux');
$oAnimaux->setIsRequired ('Animaux requis !');

$oFh->addRule($oLogin, $oPass, $oConf, $oMail, $oWeb, $oGenre, $oAnimaux);

if ($oFh->validate ()) {
	echo "Le formulaire à parfaitement été validé ! :)";
}
else {
	?>
	<form method="post" action=".">
	Login : <input type="text" name="login" value="<?php echo $oLogin; ?>" /><br />
	Password : <input type="text" name="passwd" value="<?php echo $oPass; ?>" /><br />
	Confirmation : <input type="text" name="confpass" value="<?php echo $oConf; ?>" /><br />
	Email : <input type="text" name="email" value="<?php echo $oMail; ?>" /><br />
	Site Web : <input type="text" name="web" value="<?php echo $oWeb; ?>" /><br />
	<select name="genre[]">
		<option>---------</option
		<option value="H">Homme</option>
		<option value="F">Femme</option>
	</select>
	<br />
	<select size="9"  MULTIPLE="MULTIPLE" name="animaux[]">
		<option>Chat</option>
		<option>Chien</option>
		<option>Hamster</option>
		<option>Lapin</option>
		<option>Autre</option>
	</select><br />
	<?php echo $oFh->getFirstError (); ?><br />
	<input type="submit" name="Envoyer !" />
	</form>
	<?php
}
?>
