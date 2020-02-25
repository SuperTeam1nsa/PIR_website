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
    case "lister":
        lister();
        break;
    case "jouer":
        jouer();
        break;
    case "modifier":
        modifier();
        break;
	case "supprimer":
	    supprimer();
	    break;
    case "enregistrer":
        enregistrer();
        break;
}
//ou: (mais moins sûr)
//$action();
// fonctions
function lister(){
	$titre = "Liste de scores";
	// récupération des enregistrements 
	$result = recupereDixPremiers();
	// création code HTML
$corps= <<<LONG
    <div id="score">
    <TABLE BORDER="1">
    <CAPTION> Panthéon des Maîtres Serpents </CAPTION>
    <TR>
    <TH> Rang </TH>
    <TH> Maître du Serpent </TH>
    <TH> Score</TH>
    <TH> Date</TH>
LONG;
    if(isset($_SESSION['id']))
    $corps.="<TH> Suppression</TH></TR>";

    $rang=1;
	//while($r = $result->fetch_assoc()) 
    $s;
    foreach ($result as $s)
    {
       // echo $s->getPseudo();
	$corps .="<TR><TD>".$rang."</TD>";
    $corps .="<TD>".$s->getPseudo()."</TD> ";
	$corps .='<TD>'.$s->getScore().'</TD> ';
    $corps .='<TD>'.$s->getDate().'</TD> ';    
		// liens 
        //echo $$_SESSION['id'];
        if(isset($_SESSION['id']) && $_SESSION['id']==$s->getIdUtilisateur()){
		//$corps .= " - <a href=\"".BASE_PATH."score/modifier/".$r['id']."\">Modifier</a>";
		//$corps .= " | <a href=\"controleur.php?action=supprimer&id=".$r['id']."\">Supprimer</a>";
        $corps .= " <TD> <a class='cross' href=\"Javascript:alertFunction(".$s->getId().")\">X</a> </TD>";
        }
        else {
            $corps .= " <TD></TD>";
        }
		$corps .= "</TR>";
        $rang++;
	}
    $_SESSION['lastScore']=$s->getScore();
    $_SESSION['nombreDeScore']=$rang;
    $corps.="</TABLE></div>";
	// lien pour création
    if(isset($_SESSION['id']))
	$corps .= "<div id='jouer' class='logged'><a href=\"".BASE_PATH."score/jouer\">Jouer</a></div>";
    else
    $corps .= "<div id='jouer'><a href=\"".BASE_PATH."score/jouer\">Jouer sans enregistrement de scores (login)</a></div>";
	
	// lien pour authentification
	if ( !isset( $_SESSION['mail'] ) ) {		
		$loginLogout = "<a href=\"".BASE_PATH."authentification/login\">Login</a>";
	} else {
		$loginLogout = $_SESSION['mail']." - <a href=\"".BASE_PATH."authentification/logout\">Logout</a>";
	}
    $link=BASE_PATH.'score/supprimer/';
//attention à coller la variable à gauche pour que ça marche
$corps.= <<<EOT
        <script type ='text/javascript'>
        function alertFunction(idE){
        var r=confirm('Voulez-vous vraiment supprimer cet enregistrement ?');
        if(r){
            var lien='$link'+idE;
            location.replace(lien);
        }}</script>
EOT;
	// affichage de la vue
	require "vue.php"; 
}
function enregistrer(){
   /* if(isset($_POST["s"]))
       echo '{"message":"HOURRA!"}';
    else
       echo '{"message":"bouuuuu"}'; */
    //le check a pour but d'éviter des requêtes mysql inutiles (ajout d'un score supprimer dans la foulée car 11 ème)
    if(isset($_SESSION['id']))
    if(isset($_POST['s']) && isset($_SESSION['lastScore']) && ($_POST['s']>$_SESSION['lastScore'] || $_SESSION['nombreDeScore']<=10)){
        echo '{"message":"'.sqlAddScore($_POST['s']).'"}';
    }
    else{
    echo '{"message":"Améliore ton score pour rentrer au Panthéon !"}';
    }
    else
    echo '{"message":"<br>Connecte toi pour enregistrer ton score !"}';  
}
function jouer(){
    //echo "c'est le fun !";
    header ('Location:'.BASE_PATH.'game/');
	/*$mode = "creation";
	// affichage du formulaire
	if ( !isset ($_POST['valeur']) ) {
		// pas de données => affichage
		$donnees = null;
		$erreurs = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		// données => test
		$erreurs = testDonnees($_POST);
		if ($erreurs == null){
			// ajout
			ajouteEnregistrement($_POST);
			// redirection (sinon l'url demeurera action=creer)
			header ('Location:'.BASE_PATH.'score/lister');
		} else {
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}*/
}
function scoreBelongsToUser($id){
    $s=getScoreFromId($id);  
    return $s->getIdUtilisateur()==$_SESSION['id'];

}
function supprimer(){
    //empêche la suppression d'un score autre que le notre en modifiant à la main l'URL
   // echo "coucou vous !";
   // echo $_SESSION['id'];
   // echo "    ".$_GET["id"];
	if ( !isset ($_GET["id"]) || !scoreBelongsToUser($_GET["id"])) {
		// pas de données 
		die("requête non autorisée");
	}
	supprimeEnregistrement($_GET["id"]);
	lister();
}
function modifier(){
	if ( !isset ($_GET["id"]) && !isset ($_POST["id"])) {
		// pas de données 
		die("requ&ecirc;te non autoris&eacute;e");
	}
	$mode = "modification";
	// affichage du formulaire
	if ( !isset ($_POST["valeur"]) ) {
		// pas de données en POST (mais en GET) => affichage avec les données de l'enregistrement
		$donnees = recupereEnregistrementParId($_GET["id"]);
		$donnees['id'] = $_GET["id"];
		$erreurs = null;
		afficherFormulaire($mode, $donnees, $erreurs);
	} else {
		// données en POST => test
		$erreurs = testDonnees($_POST);
		if ($erreurs == null){
			// ajout
			modifieEnregistrement($_POST["id"], $_POST);
			lister();
		} else {
			afficherFormulaire($mode, $_POST, $erreurs);
		}
	}
}
function afficherFormulaire($mode, $donnees, $erreurs){
	$loginLogout = "";
	if($mode == "creation"){
		$titre = "Création";
		$action = ROOT_PATH."score/jouer";//minisite/ex11/public/
	} else	if($mode == "modification"){
		$titre = "Modification";
		$action = ROOT_PATH."score/modifier";
	}
	// création code HTML
	$valeur = $donnees['valeur'];
	$id = $donnees['id'];
	$erreurValeur = $erreurs['valeur'];
        $loginLogout = ""; 
	$corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="/$action">
<label for="valeur">Score</label>
<input id="valeur" type="text" name="valeur" value="$valeur" required aria-required="true" />
<p class="erreur">$erreurValeur</p>
<br><br>
<button name='submit' type='submit' id='submit'>Valider</button>
<input type='hidden' name='id' value='$id'/>
</form>
EOT;
	// affichage de la vue
	require "vue.php"; 	
}

function testDonnees($donnees){
	$erreurs = [];
	// test si le score est une valeur numérique
	if (!is_numeric($donnees['valeur'])) {
		$erreurs['valeur'] = "la valeur entrée doit être un nombre";
	}
	return $erreurs;
}

?>
