<?php
require "../app/_config/BD.php";

function recupereEnregistrementParMailEtPassword($donnees){
	// récupération d'un enregistrement
	$mail = $donnees['mail'];
	$pass = $donnees['password'];
   // echo $pass;
	$con = connexion();
	$query = "SELECT * FROM utilisateur WHERE mail = '$mail' AND password = '$pass';";
    //echo $query;
	$resultat=$con->query($query)or die($con->error);
    $r=$resultat->fetch_assoc();
    //  echo "<br> ".$r['id'];
    //$_SESSION['id']=$r['id'];
    fermeture($con);
    return $r;
}	
	
?>
