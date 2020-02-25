<?php

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;e");
}
require "modele.php";

// récupération des données passées en GET
$action = $_GET['action'];

// traitement selon l'action
switch ($action) {
    case "lister":
        lister();
        break;
    case "creer":
        creer();
        break;
	case "valider":
	    valider();
	    break;
	/*case "modifier":
	    modifier();
	    break;
	case "supprimer":
	    supprimer();
	    break;*/
}

// fonctions
function lister(){
	$titre = "Liste d'enregistrements";
	// récupération des enregistrements
	$result = recupereTous();
	// création code HTML
	$corps = "<ul>";
	while($r = $result->fetch_assoc()) {
	   	$corps .= "<li>";
		$corps .= $r['id'].", ".$r['mail'];
		// liens
		$corps .= " - <a href=\"".BASE_PATH."score/modifier/".$r['id']."\">Modifier</a>";
		$corps .= " | <a href=\"".BASE_PATH."score/supprimer/".$r['id']."\">Supprimer</a>";
		$corps .= "</li>";
	}
	$corps .= "</ul>";
	// lien pour s'enregistrer
	$corps .= "<a href=\"".BASE_PATH."utilisateur/creer\">S'enregistrer</a>";
	// affichage de la vue
	require "vue.php";
}

function creer(){
	// affichage du formulaire
	if ( !isset ($_POST['mail']) ) {
		// pas de données => affichage
		$donnees = null;
		$erreurs = null;
		afficherFormulaire( $donnees, $erreurs);
	} else {
		// données => test
		$erreurs = testDonnees($_POST);
		if ($erreurs == null){
			$donnees = $_POST;
			// génération aléatoire d'une clé
			$cle = md5(microtime(TRUE)*100000);
			$donnees['cle'] = $cle;
			// envoi du mail
			envoiMailConfirmation($donnees);
			// ajout de l'enregistrement
			ajouteEnregistrement($donnees);
			// message
			$titre = "Validation";
			$corps = "Votre compte à été créé. Un mail de confirmation
 vous a été envoyé à l'adresse ".$donnees['mail'].".";
			require "vue.php";
		} else {
			afficherFormulaire($_POST, $erreurs);
		}
	}
}



function valider(){
	// validation d'un compte
    //cle=id same pour tous cf app.php
	if ( !isset($_GET["id"]) ) {
		// pas de données
		die("requ&ecirc;te non autoris&eacute;e");
	}
	$cle = $_GET["id"];
	//echo $cle;
	// recherche de l'utilisateur
	$utilisateur = recupereEnregistrementParCle($cle);
	if ( !isset($utilisateur) ) {
		// aucun utilisateur
		die("cl&egrave; non valide");
	}
	if( $utilisateur['valide'] == 1 ){
		// affichage de la vue
		$titre = "Validation";
		$corps = "Votre compte à déjà été validé. <a href=\"".BASE_PATH."score/lister\">Revenir à la liste des scores</a>";
		require "vue.php";
	} else {
		// validation
		validerUtilisateur($cle);
		// affichage de la vue
		$titre = "Validation";
		$corps = "Votre compte à bien été validé. Vous pouvez désormais vous connecter. <a href=\"".BASE_PATH."score/lister\">Revenir à la liste des scores</a>";
		require "vue.php";
	}
}


function afficherFormulaire($donnees, $erreurs){
	$titre = "Création";
	$action = "creer";
	// création code HTML
	$id = $donnees['id'];
	$mail = $donnees['mail'];
    $pseudo = $donnees['pseudo'];
	$password = $donnees['password'];
    if(isset($erreurs['mail']))
	$erreurMail = $erreurs['mail'];
    else
     $erreurMail =null;   
    if(isset($erreurs['pseudo']))
    $erreurPseudo = $erreurs['pseudo'];
    else
    $erreurPseudo =null;
    if(isset($erreurs['password']))
	$erreurPassword = $erreurs['password'];
    else
    $erreurPassword =null;   
	$corps = "<form id=\"creation-form\" name=\"creation-form\" method=\"post\" action=\"$action\">";
$corps.=
<<<EOT
<label for="mail"><dd>Mail:<dd></label>
<input id="mail" type="email" name="mail" value="$mail" required aria-required="true" />
<p class="erreur">$erreurMail</p>
<br>
<label for="pseudo"><dd>Pseudo:<dd></label>
<input id="pseudo" type="text" name="pseudo" value="$pseudo" required aria-required="true" />
<p class="erreur">$erreurPseudo</p>
<br>
<label for="password"><dd>Password (6 min):<dd></label>
<input id="password" type="password" name="password" value="$password" required aria-required="true" />
<p class="erreur">$erreurPassword</p>
<br>
<button name='submit' type='submit' id='submit'>Valider</button>
<input type='hidden' name='id' value='$id'/>
</form>
EOT;
	// affichage de la vue
	require "vue.php";
}

function testDonnees($donnees){
	$erreurs = array();
	if(filter_var($donnees['mail'], FILTER_VALIDATE_EMAIL) === false) { //Validation d'une adresse de messagerie.
    $erreurs['mail']="L'adresse de messagerie fournie n'est pas valide !";}
    else if(existeMail($donnees['mail']))
    $erreurs['mail']="Cette adresse de messagerie est déjà utilisée !";
    if(strlen($donnees['password'])<6)
	$erreurs['password']="Le mot de passe doit contenir au moins 6 caractères";
    if(existePseudo($donnees['pseudo']))
    $erreurs['pseudo']="Ce pseudo est déjà utilisé !";
    
	return $erreurs;
}

function envoiMailConfirmation($donnees){
	$destinataire = $donnees['mail'];
	$cle = $donnees['cle'];
	$sujet = "Activer votre compte" ;
	$entete = "From: inscription@RF.com" ;
	// Le lien d'activation est composé de la clé(cle)
	$message = 'Bienvenue sur le site de RF,
Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.
'.BASE_PATH.'utilisateur/valider/'.urlencode($cle).'
---------------
Ceci est un mail automatique, Merci de ne pas y répondre.';
	//&cle='.urlencode($cle).'
    // envoi
	mail($destinataire, $sujet, $message, $entete) ;
}

?>
