<?php
require "../app/_config/BD.php";
require "../app/score/Score.php";

function recupereDixPremiers(){
	// récupération de tous les enregistrements
	$con = connexion();
    $listeScore=array();
	$query = "SELECT * FROM score ORDER BY valeur DESC ";
	$resultat=$con->query($query)or die($con->error);
   // print_r($resultat);
    $i=0;
    $r =array();
   while(($r = $resultat->fetch_assoc())&& $i <10) {
	   //	echo $r['valeur'];
       // print_r($r);
       //rq: pour diminuer le nombre de requetes on pourrait get tout le tableau et faire ce check d'id à la main
        $query = "SELECT * FROM utilisateur WHERE id=".$r['idUtilisateur'].";";
        //echo $query; 
	   $res=$con->query($query)or die($con->error);
       $temp = $res->fetch_assoc();
        if(isset($temp['pseudo']))
        $pseudo=$temp['pseudo'];
       else
           $pseudo="anonyme";
       $date=date_format(date_create($r['date']),"d/m/Y");
        $listeScore[$i]=new Score($r['valeur'],$pseudo,$r['id'],$r['idUtilisateur'],$date);
        $i++;
    }
    	fermeture($con);
	return $listeScore;
}
function getScoreFromId($id){
    $con = connexion();
    $Score=null;
	$query = "SELECT * FROM score WHERE id=".$id;
	$resultat=$con->query($query)or die($con->error);
    if($r = $resultat->fetch_assoc()){
    //print_r($r);
        $query = "SELECT * FROM utilisateur WHERE id=".$r['idUtilisateur'].";";
	   $res=$con->query($query)or die($con->error);
       $temp = $res->fetch_assoc();
        if(isset($temp['pseudo']))
        $pseudo=$temp['pseudo'];
       else
           $pseudo="anonyme";
       $date=date_format(date_create($r['date']),"d/m/Y");
        $Score=new Score($r['valeur'],$pseudo,$r['id'],$r['idUtilisateur'],$date);
    }
    else
    $Score=new Score('-1','-1','-1','-1','-1');
    	fermeture($con);
	return $Score;
}
function sqlAddScore($score){
    $con = connexion();
    //limite à 10 le nombre de score enregistrés
	$query = "INSERT INTO score (`valeur`,`idUtilisateur`) VALUES('".$score."','".$_SESSION['id']."');";
        //DELETE FROM `score` WHERE `id`=(SELECT `id` FROM (SELECT * FROM score) AS T ORDER BY `valeur` ASC LIMIT 1)LIMIT 1;";
    //date = timestamp auto géré par le serveur
    //rq:attention les echos ici sont considérés comme une réponse au ajax
    //echo $query;
	$con->query($query)or die($con->error);
    $query2="DELETE FROM `score` WHERE id NOT IN ( SELECT id FROM (SELECT * FROM score ORDER BY valeur DESC LIMIT 10) AS T);";
    $con->query($query2)or die($con->error);
	fermeture($con);	
	return "Well Done !";
}

/*
function ajouteEnregistrement($donnees){
	$valeur = $donnees['valeur'];
	// ajout d'un enregistrement
	$con = connexion();
	$query = "INSERT INTO score (`id`, `valeur`,`idUtilisateur`) VALUES(NULL,'".$valeur."','".$_SESSION['id']."');";
    //date = timestamp auto géré par le serveur
    //echo $query;
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);		
}
*/
function recupereEnregistrementParId($id){
	// récupération d'un enregistrement
	$con = connexion();
	$query = "SELECT * FROM score WHERE id = $id";
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);
    return $resultat->fetch_assoc();
}	
function modifieEnregistrement($id, $donnees){
	$valeur = $donnees['valeur'];
	// récupération d'un enregistrement
	$con = connexion();
	$query = "UPDATE score SET `valeur` = $valeur WHERE id = $id;";
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);
}
function supprimeEnregistrement($id){
	// suppression d'un enregistrement
	$con = connexion();
	$query = "DELETE FROM score WHERE id = $id";
	$resultat=$con->query($query)or die($con->error);
	fermeture($con);
}	
	
?>
