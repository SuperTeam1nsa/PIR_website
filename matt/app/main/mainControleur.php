<?php
/*
session_start(); //deja dans app

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;e");
}
*/

class mainControleur {
	private $modele;

	public function __construct(){
		$this->modele = new mainModele;
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
