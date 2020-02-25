<?php

require "../app/_config/BD.php";
function existeMail($mail){
    //echo "test du mail :".$mail;
    $con = connexion();
	$query = "SELECT * FROM utilisateur WHERE mail='".$mail."';";
   // echo $query;
	$resultat=$con->query($query)or die($con->error);
    	fermeture($con);
    if($resultat->fetch_assoc())
        return true;
    return false;
}
function existePseudo($pseudo){
    $con = connexion();
	$query = "SELECT * FROM utilisateur WHERE pseudo='".$pseudo."';";
	$resultat=$con->query($query)or die($con->error);
    	fermeture($con);
    if($resultat->fetch_assoc())
        return true;
    return false;
}
function ajouteEnregistrement($donnees){
	$mail = $donnees['mail'];
	$password = $donnees['password'];
    $pseudo = $donnees['pseudo'];
	$valide = 0;
	$cle = $donnees['cle'];
	// ajout d'un enregistrement
	$con = connexion();
	$query = "INSERT INTO utilisateur (`id`, `mail`, `password`, `valide`, `cle`,`pseudo`)
		VALUES (NULL, '".$mail."', '".$password."', '".$valide."', '".$cle."','".$pseudo."');";
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);	
}

function recupereEnregistrementParCle($cle) {
	// récupération d'un enregistrement
	$con = connexion();
	$query = "SELECT * FROM utilisateur WHERE cle = '$cle'";
	$resultat=$con->query($query)or die($con->error);
    fermeture($con);
	return $resultat->fetch_assoc();
}
function validerUtilisateur($cle) {
	// validation d'un utilisateur
	$con = connexion();
	$query = "UPDATE utilisateur SET `valide` = 1 WHERE cle = '$cle';";
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);
}

function recupereTous(){
	// récupération de tous les enregistrements
	$con = connexion();
	$query = "SELECT * FROM utilisateur";
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);
	return $result;
}
	
	
?>
