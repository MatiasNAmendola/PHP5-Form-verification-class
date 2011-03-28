(**Script originally created in 2007/12/19** for a french website : [http://www.phpcs.com/codes/PHP5-CLASSE-VERIFICATION-FORMULAIRE_44864.aspx](http://www.phpcs.com/codes/PHP5-CLASSE-VERIFICATION-FORMULAIRE_44864.aspx "PHP CS"))

Bonjour à tous ! :)
Voila, je devais me faire une classe afin de vérifier les entrées d'un formulaire. J'ai fait trois classes et deux gestionnaires d'erreur que vous trouverez dans le zip.

Alors la classe de vérification (formCheck) s'appele en statique. Du coup, vous pouvez l'appeler pour n'importe quel usage tant qu'elle reste accessible là ou vous l'appelez :)

Alors voila comment cela se passe.
Vous instanciez la classe formHandler.
Ensuite vous instanciez autant de formRule que vous voulez pour vos éléments (une instance de formRule pour un élément).
Vous leur appliquer des regles définies dans formCheck, en utilisant : setNomFunctionDansFormCheck (mettre le set avant), avec en premier parametre (optionnel), une valeur à transmettre à la fonction de formCheck (int, string ou array), et en deuxieme parametre (ou premier s'il n'y a rien à passer à la fonction formCheck), le message d'erreur à afficher.

Ensuite vous pouvez mettre votre instance de formRule pour l'element dans la méthode addRule de formHandler.
(Vous pouvez mettre toutes les instances à la suite dans addRule, ou appeler addRule pour chaques éléments)

La fonction validate de formHandler va vérifier s'il n'y a pas eu d'erreurs retournée par les instances de formRule.
Vous pouvez afficher la valeur de l'élément dans l'instance de formRule juste en appelant l'instance (voyez l'exemple) (utilisation de __toString ())

Vous pouvez récuperer une erreur, toutes les erreurs pour un élément ($oFormRuleInstance->getError ($NumErreur) ou $oFRI->getAllErrors ()), ou juste récuperer la premiere erreur apparue : $oFormHandler->getFirstError () (voir exemple).
S'il n'y a pas eu d'erreur, getFirstError retourne null.

Wala :)

Voici le résultat :

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

Voila pour le petit exemple :)

Après modifications, cette classe permet une meilleur flexibilité pour la gestion des éléments.
Maintenant, pour la structure ou les avis, j'attends vos commentaires :)
