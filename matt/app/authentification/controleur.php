<?php

session_start();

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;e");
}

require "modele.php";

// récupération des données passées en GET
$action = $_GET['action'];

// traitement selon l'action
switch ($action) {
    case "login":
        login();
        break;
	case "logout":
	    logout();
	    break;
}

function login(){
	$mode = "";
    $utilisateur=array();
	// affichage du formulaire
	if ( !isset ($_POST['mail']) ) {
		// pas de données => affichage
		$donnees = null;
		$erreurs = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		// données => test d'existence de cet utilisateur
		$erreurs = testDonnees($_POST,$utilisateur);
		if (count($erreurs) == 0){
			// authentification réussie
			authentificationReussie($utilisateur);
			// redirection (sinon l'url demeurera action=creer)
			header ('Location: '.BASE_PATH.'/score/lister');
		} else {
			// authentification non réussie
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}
}

function afficherFormulaire($mode, $donnees, $erreurs){
	$titre = "Authentification";
	// création code HTML
	$mail = $donnees['mail'];
    $erreurAuth=null;
	$password = $donnees['password'];
	if(isset($erreurs['auth']))
	$erreurAuth = $erreurs['auth'];
	if(isset($erreurs['validation']))
	$erreurAuth =$erreurs['validation'];
    $actionForm =BASE_PATH."authentification/login";
	$corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$actionForm">
<label for="login"><dd>Login:<dd></label>
<input id="mail" type="email" name="mail" value="$mail" required aria-required="true" />
<p class="erreur"></p>
<label for="password">Password:<dd></label>
<input id="password" type="password" name="password" value="$password" required aria-required="true" />
<p class="erreur">$erreurAuth</p>
<br><br>
<button name='submit' type='submit' id='submit'>Valider</button>
</form>
EOT;
	// affichage de la vue
	require "vue.php";
}
//&$var => référence
function testDonnees($donnees,&$utilisateur){
	$erreurs = array();
	$utilisateur = recupereEnregistrementParMailEtPassword($donnees);
    //echo "<BR>test <br> ".$utilisateur['id']." ".$donnees['mail'];
    //die();
	if($utilisateur == null) {
		$erreurs['auth'] = "Pas d'utilisateur avec ces identifiants";
	}
	else if($utilisateur['valide']==0)
	$erreurs['validation']="Erreur: vous n'avez pas encore validé votre compte ! Cliquez sur le lien reçu par email";

	return $erreurs;
}

function authentificationReussie($utilisateur){
	$_SESSION['mail'] = $utilisateur['mail'];
    $_SESSION['id']=$utilisateur['id'];
    //echo "<BR>test <br> ".$utilisateur['id']." ".$utilisateur['mail'];die();
}

// ensuite...

function logout(){
	session_destroy();
	// redirection (sinon l'url demeurera action=creer)
	header ('Location:'.BASE_PATH.'score/lister');
}


?>
