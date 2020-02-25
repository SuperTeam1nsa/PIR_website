<?php

         /*   $jour=date("j");
            $mois =date("n");
            $annee =date("Y"); */
 date_default_timezone_set("Europe/Paris");
            $heure=date("G");
            $minute=date("i");
            $seconde=date("s");
//echo $heure." ".$minute."  ".$seconde;
    //"jour":"'.$jour.'","mois":"'.$mois.'","annee":"'.$annne.'",
$jsonRetour='{"heure":"'.$heure.'","minute":"'.$minute.'","seconde":"'.$seconde.'"}';
echo $jsonRetour;


?>
