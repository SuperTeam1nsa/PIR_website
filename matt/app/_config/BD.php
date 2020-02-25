<?php
function connexion() {
	// connexion avec la BD
	$con = new mysqli('127.0.0.1', 'root', '', 'dbname');
    if ($con->connect_error) {
    die('Connect Error (' . $con->connect_errno . ') '
            . $con->connect_error);
} 
	return $con;
}

function fermeture($con) {
	// fermeture de la connexion avec la BD
	mysqli_close($con);
}
?>
