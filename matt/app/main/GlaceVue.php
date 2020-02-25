<?php

// fabrication du corps
/*
// liste
$titre = $data["titre"];
$liste = $data["liste"];
$corps = "<p><ul>";
while($r = $liste->fetch_assoc()) {
   	$corps .= "<li>";
	$corps .= $r['id'].", ".$r['valeur'];
	if( isset($_SESSION['id']) && $_SESSION['id'] == $r['idUtilisateur'] ){
		// liens 
		$corps .= " - <a href=\"".BASE_PATH."score/modifier/".$r['id']."\">Modifier</a>";
		$corps .= " | <a href=\"JavaScript:alertFunction(".$r['id'].")\">Supprimer</a>";
	}
	$corps .= "</li>";
}
$corps .= "</ul></p>";

// alerte
$message = "";
$corps .= "<script type='text/javascript'>function alertFunction(idE){";
$corps .= "var r=confirm('Voulez-vous vraiment supprimer cet enregistrement ?');";
$corps .= "if(r==true){var lien = '".BASE_PATH."score/supprimer/'+idE;location.replace(lien);}}</script>";
 
// lien pour création
if( isset($_SESSION['id']) ){
	$corps .= "<p><a href=\"".BASE_PATH."score/creer\">Cr&eacute;er</a></p>";
}*/

// insertion dans le gabarit
$corps =" <br>";
//gestion du highlight de la navbar (active)
$Endscript = <<<anl
<script>
$(document).ready(function(){
   $(".nav-item.active").removeClass("active");
   $("#glace").addClass("active");
});
</script>
anl;
require_once(dirname(__FILE__).'/../_config/gabarit.php');

?>
