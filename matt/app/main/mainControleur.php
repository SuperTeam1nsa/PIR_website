<?php
/*
session_start(); //deja dans app

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;e");
}
*/

class mainControleur {
	public function __construct(){
	}

	// fonctions
	public function show(){
		//$titre = "Liste de scores";
		// récupération des enregistrements 
		//$liste = $this->modele->recupereTous();
		// affichage de la vue
		//$data = array("liste"=>$liste, "titre"=>$titre); 
        $data="";
		$this->view("principale", $data,'main');
	}
	public function view($view, $data = [], $module){
		require(dirname(__FILE__).'/../'.$module.'/'.$view.'Vue.php');
	}
    

}
?>
